Нижче я окреслю основний концепт рішення, яке дасть змогу **в один клік** передавати всі «completed» замовлення у дві Google-таблиці (Customer Sheets / Supplier Summary). Опишу загальний підхід, а також **пункти, на які варто звернути увагу** щодо структури даних та реалізації (зокрема, з використанням кастомних полів ACF чи meta-записів).

---

## 1. Загальний алгоритм експорту

1. **Знайти всі «completed» замовлення**
    - Це можна зробити, наприклад, через WP_Query або через WooCommerce-методи:
      ```php
      $orders = wc_get_orders(array(
        'status' => 'completed',
        'limit'  => -1,
      ));
      ```
    - Для кожного замовлення (WP Post типу `shop_order`) отримаєте список товарів, дані клієнта, тощо.

2. **Зібрати дані з кожного замовлення**:
    - **ID**: Номер замовлення або Product ID (залежно від того, що саме потрібно в рядку).
    - **ITEM**: Назва товару (product name).
    - **AMOUNT**: Кількість товарів у цьому замовленні.
    - **PRICE**: Ціна, за якою продається (зазвичай `line_total / quantity`).
    - **COST**: Внутрішня собівартість (зберігається у кастомному полі / ACF або product meta).

   Також, якщо необхідно для **Supplier Summary**, слід зберігати/зчитувати:
    - **Supplier**: Якимось чином визначити «постачальника» (у meta-продукту, taxonomy чи кастомному полі ACF).

3. **Формування масивів даних**:
    - **Customer Sheets**: Потрібно, щоб **кожен клієнт** (замовник) мав свою вкладку.
        - Вкладаємо дані по **кожному товару** з конкретного замовлення цього клієнта.
        - Якщо раніше вкладка (таб) у Google Sheets для цього клієнта не існувала — створюємо чи додаємо.

    - **Supplier Summary**: Потрібно **групувати** дані за постачальником.
        - Для кожного постачальника `X` рахуємо сумарно: скільки разів товар (ITEM) був замовлений, сума AMOUNT, PRICE, COST і т.д.
        - Записуємо підсумковим рядком для кожного postачальника + продукт.

4. **Завантаження (push) у Google Sheets**:
    - За допомогою **Google Sheets API** (офіційна бібліотека, зокрема `google/apiclient` Composer-пакет, або бібліотека підключена іншими способами).
    - Авторизація:
        - Найпростіше (з погляду сервера) — **service account**. Ви у Google Cloud Console створюєте «Service Account», даєте їй доступ до конкретного Sheets, отримуєте JSON-креденшали.
        - Зберігаєте їх у файлах на сервері або в безпечному місці, читаєте під час натискання на кнопку.
    - Далі формуєте масиви даних і викликаєте методи на зразок `spreadsheets.values.update()` чи `spreadsheets.values.append()`.
    - Для Customer Sheets:
        - Для **кожного** унікального `customer_id` (або email) робите **окремий «лист» (tab)**.
        - Записуєте туди заголовок `ID | ITEM | AMOUNT | PRICE | COST` і всі рядки з даними.
    - Для **Supplier Summary**:
        - Один лист (наприклад, назва: `Supplier Summary`), де зведені дані по постачальниках.
        - Кожен рядок — це `SupplierName | ITEM | AMOUNT (загальна) | PRICE (можливо, загальна ціна) | COST (собівартість)`, тощо.

---

## 2. Куди додати кнопку «Export to Google Sheets»

### Варіант 1: Меню в «WooCommerce → Orders»
- Можна додати свій суб-меню-розділ, наприклад, «Export to Google Sheets».
- У цьому розділі розташувати кнопку «Export Now».

### Варіант 2: Кнопка на сторінці «Order» (в адмінці)
- Наприклад, на сторінці списку замовлень у WordPress, поруч із масовими діями, чи у верхньому меню.

### Варіант 3: Окремий Tools / Dashboard
- Створити кастомну сторінку в розділі «Інструменти», де буде кнопка «Експортувати».

**Технічно**, це може бути просто `admin_post`-екшен або `wp_ajax`, який запускає код для збору даних і відправки в Sheets.

---

## 3. Як групувати дані для Supplier Summary

- Потрібно, щоб кожен продукт мав поле «supplier» (у ACF чи у product meta). Наприклад, `_supplier_name = "ABC"` або taxonomy `supplier`.
- При переборі товарів у completed-замовленнях робимо:
  ```php
  $supplier_name = get_post_meta( $product_id, '_supplier_name', true );
  // Якщо використовуємо taxonomy - тоді wp_get_post_terms($product_id, 'supplier') і беремо назву.
  ```
- Далі формуємо асоціативний масив на зразок:
  ```php
  $summary[ $supplier_name ][ $product_name ]['amount'] += $line_item->get_quantity();
  $summary[ $supplier_name ][ $product_name ]['cost']   = ...;
  // і т. д.
  ```
- Наприкінці отримаємо структуру на кшталт:
  ```
  [
    "ABC" => [
      "Chair" => ["amount" => 5, "price" => ..., "cost" => ...],
      "Table" => ["amount" => 3, ...],
      ...
    ],
    "XYZ" => [...],
    ...
  ]
  ```
- Цей масив потім використати для формування рядків у Supplier Summary Sheet.

---

## 4. Структура даних

### Кастомні поля

1. **Cost** (собівартість)
    - Можна створити через ACF Pro поле `cost_price` на сторінці товару (Product).
    - У базі зберігатиметься, наприклад, як `_cost_price`.
    - Для отримання: `get_post_meta( $product_id, '_cost_price', true )`.

2. **Supplier** (постачальник)
    - Теж ACF (Text / Select / Relationship).
    - Зберігається як `_supplier_name`.
    - Якщо хочете складніший функціонал (декілька постачальників, фільтри), можна завести окрему таксономію `supplier`.

3. **Кожен клієнт** (Customer) має свій таб у Google Sheets
    - Можете визначати вкладку за `user_id` або за `user_nicename / user_login`.
    - Якщо клієнт гість (guest checkout) — можливо, варто групувати за email або створювати вкладку `guest_email`.

---

## 5. Технічна реалізація (скелет коду)

Нижче **псевдокод**, аби зорієнтуватися:

```php
// 0. Додаємо кнопку в адмінці (наприклад, admin_menu -> my_export_page)
add_action('admin_menu', function() {
    add_submenu_page(
        'woocommerce', // or 'edit.php?post_type=shop_order'
        'Export Orders',
        'Export Orders',
        'manage_woocommerce',
        'export-orders-page',
        'render_export_orders_page'
    );
});

function render_export_orders_page() {
    // Проста кнопка, яка викликає форму з action=...
    echo '<h1>Export All Completed Orders</h1>';
    echo '<form method="post" action="'. esc_url( admin_url('admin-post.php') ) .'">';
    echo '<input type="hidden" name="action" value="export_to_sheets" />';
    submit_button('Export Now');
    echo '</form>';
}

// 1. Обробник (admin_post)
add_action('admin_post_export_to_sheets', 'handle_export_to_sheets');
function handle_export_to_sheets() {
    // 2. Отримуємо всі completed замовлення
    $orders = wc_get_orders(array(
        'status' => 'completed',
        'limit'  => -1
    ));

    // Масиви для Customer Sheets і Supplier Summary
    $customerData = array();
    $supplierData = array();

    foreach($orders as $order){
        $order_id   = $order->get_id();
        $customer_id = $order->get_user_id(); // якщо користувач не гість
        $customer_email = $order->get_billing_email(); // якщо треба email
        $customer_tab_id = $customer_id ? $customer_id : $customer_email; // для вкладки

        // Ініціалізуємо підмасив (Customer) якщо не існує
        if(!isset($customerData[$customer_tab_id])) {
            $customerData[$customer_tab_id] = array();
        }

        // Отримуємо line items
        foreach($order->get_items() as $item_id => $item){
            $product_id = $item->get_product_id();
            $quantity   = $item->get_quantity();
            $total      = $item->get_total();       // total for line
            $price      = ($quantity > 0) ? $total / $quantity : 0;
            $product_name = $item->get_name();

            // Cost & Supplier
            $cost = get_post_meta($product_id, '_cost_price', true);
            $supplier_name = get_post_meta($product_id, '_supplier_name', true);

            // Записуємо в customerData
            // Customer Sheets: ID, ITEM, AMOUNT, PRICE, COST
            $customerData[$customer_tab_id][] = array(
                'ID'    => $order_id,
                'ITEM'  => $product_name,
                'AMOUNT'=> $quantity,
                'PRICE' => $price,
                'COST'  => $cost
            );

            // Групуємо для supplierData
            // Наприклад, supplier => [productName => [amount =>, price =>, cost =>]]
            if(!isset($supplierData[$supplier_name][$product_name])){
                $supplierData[$supplier_name][$product_name] = [
                    'AMOUNT' => 0,
                    'PRICE'  => 0,
                    'COST'   => 0
                ];
            }

            $supplierData[$supplier_name][$product_name]['AMOUNT'] += $quantity;
            // Ціну/собівартість можна підсумувати чи брати середню
            $supplierData[$supplier_name][$product_name]['PRICE']  += $quantity * $price;
            $supplierData[$supplier_name][$product_name]['COST']   += $quantity * $cost; 
        }
    }

    // 3. Формуємо дані для Google Sheets
    // Підключаємо client Google API
    $client = my_get_google_client(); // функція ініціалізації service account
    $service = new Google_Service_Sheets($client);

    // 3a. Customer Sheets
    foreach($customerData as $customer_tab_id => $rows){
        // Перевірити/створити вкладку "customer_tab_id" 
        // Очищаємо / додаємо заголовки 
        // Додаємо кожен рядок
        $values = array(
          array('ID','ITEM','AMOUNT','PRICE','COST')
        );
        foreach($rows as $row){
            $values[] = array(
                $row['ID'],
                $row['ITEM'],
                $row['AMOUNT'],
                $row['PRICE'],
                $row['COST']
            );
        }

        // Приклад append:
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
        $params = ['valueInputOption' => 'USER_ENTERED'];

        // $spreadsheetId – ID вашого документа в Google Sheets
        $range = $customer_tab_id.'!A1'; // назва листа = $customer_tab_id
        $result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
    }

    // 3b. Supplier Summary
    // Структура: SupplierName, ITEM, AMOUNT, PRICE, COST
    $summaryValues = array(
      array('SUPPLIER','ITEM','AMOUNT','PRICE','COST')
    );
    foreach($supplierData as $supplier => $items){
        foreach($items as $productName => $vals){
            $summaryValues[] = array(
                $supplier,
                $productName,
                $vals['AMOUNT'],
                $vals['PRICE'],
                $vals['COST']
            );
        }
    }
    $body = new Google_Service_Sheets_ValueRange(['values' => $summaryValues]);
    $params = ['valueInputOption' => 'USER_ENTERED'];
    // Наприклад, вкладка "Supplier Summary"
    $supplierRange = 'Supplier Summary!A1';

    $result = $service->spreadsheets_values->update($spreadsheetId, $supplierRange, $body, $params);

    // 4. Перенаправляємо назад із повідомленням
    wp_redirect(admin_url('admin.php?page=export-orders-page&exported=1'));
    exit;
}

// Приклад ініціалізації Google Client (service account)
function my_get_google_client() {
    $client = new Google_Client();
    // JSON-файл ключів service account
    $client->setAuthConfig( __DIR__ . '/google-service-account.json' );
    $client->addScope(Google_Service_Sheets::SPREADSHEETS);
    return $client;
}
```

Це **дуже** спрощений приклад. У реальному ж сценарії потрібно:
- Перевіряти, чи існує аркуш (лист) із назвою `$customer_tab_id`, і якщо ні — створювати через `spreadsheets.batchUpdate()`.
- Можливо, **очищати** лист перед записом (щоб дані не «нанизувалися»).
- Форматувати ціни ($200.00) у вигляді формату.

Але це дає основне уявлення.

---

## 6. Особливості реалізації

1. **ACF Pro**:
    - Для поля **COST** (собівартість) на рівні Product.
    - Для поля **Supplier** (наприклад, текст/ селект).
    - Під час експорту робите `get_post_meta($product_id, '_cost_price', true)`.

2. **Гості vs. зареєстровані користувачі**:
    - Якщо частина замовлень створюється без реєстрації, у них `get_user_id()` = 0. Тоді можна групувати вкладки за emailом.
    - Якщо у вас **чітко** кожен клієнт - це зареєстрований user, краще використовувати `$order->get_user_id()` (ID).

3. **Оновлення/перезапис даних** у Google Sheets:
    - Можете `append`, тоді ви постійно додаєте нові рядки.
    - Можете `update(A1:Z1000, ...)`, тоді перезаписуєте весь лист.
    - Можливо, ви захочете перед експортом «чистити» аркуш: тобто викликати `spreadsheets.values.clear()`.

4. **Безпека**:
    - Кнопка може бути доступна лише адміністраторам (`manage_woocommerce`).
    - Переконайтеся, що JSON-креденшали (service account) не доступні публічно.

5. **Performance**:
    - Якщо замовлень дуже багато, експорт може тривати довго. У таких випадках — або робити батчами, або запускати асинхронно/через cron.

---

## 7. Підсумкові рекомендації

1. **Зберігати** поля `cost`, `supplier` у продукті через ACF (або кастомні meta):
    - `_cost_price` (float / int).
    - `_supplier_name` (string).

2. **Структурувати** код так, щоб:
    - **Окремо** була логіка збору даних (з completed-замовлень).
    - **Окремо** — функції для **формування** масиву для Customer Sheets і **групування** для Supplier Summary.
    - **Окремо** — функції для **відправлення** в Google Sheets API (авторизація, створення таблиці, запис значень).

3. **Додати** кнопку «Export to Google Sheets» на зручну сторінку в адмініпанелі (наприклад, під WooCommerce → Orders).
4. **Тестувати** з невеликою кількістю замовлень. Перевірити, чи все заповнюється коректно, чи створюються потрібні вкладки, чи правильно відображаються ціни/дата.
5. **Оптимізувати** за потреби (якщо дуже багато замовлень).

---

### Якщо виникнуть питання

1. **Створення вкладок** (customer tabs) «на льоту»: можна скористатись `spreadsheets.batchUpdate` (метод `addSheet`) і перевіряти, чи такий лист існує.
2. **Форматування** (прикріплення доларового формату, вирівнювання) — через `spreadsheets.batchUpdate` теж робиться, якщо це потрібно для косметичного оформлення.
3. **Окрема таблиця чи один з багатьма вкладками?** — залежить від вашого сценарію. Ви можете вести **один** Google Spreadsheet, де для кожного замовника є свій таб, і ще окремо таб «Supplier Summary».
4. **Оновлення чи додавання**: якщо потрібен хронологічний запис, то `append` (з датою / timestamp). Якщо ви щоразу хочете мати «свіжу» вибірку (останній стан), то `update`, попередньо очистивши аркуші.

---

## Висновок

Описане вище дає **повну картину**, як реалізувати функціонал:

1. **Адмін-кнопка** →
2. **Збираємо** дані з `completed` замовлень (ID, Items, Amount, Price, Cost, Supplier...).
3. **Формуємо** дві структури: «By Customer» та «By Supplier».
4. **Використовуємо Google Sheets API** для відправлення:
    - Для **Customer Sheets**: кожен клієнт → вкладка.
    - Для **Supplier Summary**: один лист із агрегованою статистикою по постачальниках.

Список полів (ID, ITEM, AMOUNT, PRICE, COST) та логіку групування можна доповнювати, залежно від реальних вимог (валюти, податки, доставка, дата замовлення, тощо).

Якщо у вас ще **немає** полів `COST`, `SUPPLIER`, потрібно завести їх:
- **ACF Pro** (fields на сторінці товара).
- Або навіть кастомними meta (через `add_post_meta`).

Після цього в коді берете `get_post_meta($product_id, '_cost_price', true)`, `get_post_meta($product_id, '_supplier_name', true)` і відправляєте далі.

Таким чином, ви отримаєте кнопку, яка за одним кліком робить **повний експорт** у потрібну структуру Google Sheets, і власники сайту бачитимуть звіт одночасно **по клієнтах** та **по постачальниках**.
