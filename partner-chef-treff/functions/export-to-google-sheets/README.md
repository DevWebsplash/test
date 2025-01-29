Нижче приклад **рекомендованої** структури каталогу для модуля "**Export to Google Sheets**", який зберігатиметься у вашій директорії `functions/` у темі. Він буде незалежним «міні-пакетом» коду: з власним *bootstrap*-файлом, папкою для *includes* (де розташуємо класи або додаткові файли), і, за потреби, папкою `assets/`.

Припустімо, папка матиме назву `export-to-google-sheets`. Тоді структура може виглядати так:

```
/functions/
└── export-to-google-sheets/
    ├── export-to-google-sheets.php     // Головний файл модуля
    ├── includes/
    │   ├── class-gs-export.php         // Основна логіка експорту (PHP-клас)
    │   ├── class-gs-api-client.php     // (Опційно) Логіка підключення/авторизації до Google API
    │   └── helpers.php                 // (Опційно) якісь допоміжні функції, якщо треба
    ├── assets/
    │   ├── css/
    │   │   └── admin.css               // (Опційно) стилі для адмінчастини (кнопка, сторінка тощо)
    │   └── js/
    │       └── admin.js                // (Опційно) JS для сторінки налаштувань або кнопки
    └── README.md                       // (Опційно) опис модуля, інструкції
```

### Пояснення

1. **`export-to-google-sheets.php`**
    - Головний «bootstrap» файл модуля.
    - Саме його ви підключите у `functions.php` теми, викликавши приблизно:
      ```php
      require_once get_template_directory() . '/functions/export-to-google-sheets/export-to-google-sheets.php';
      ```
    - Всередині ви:
        - Реєструєте ваші *admin_menu* чи *admin_post* екшени для кнопок експорту.
        - Підключаєте інші файли (класи) із `includes/`.
        - Ініціалізуєте потрібні вам об’єкти (наприклад, `new Gs_Export();`).

2. **Папка `includes/`**
    - Містить **класи** або додаткові файли для розбиття логіки.
    - Наприклад, **`class-gs-export.php`** — це ваш основний клас, де буде метод на кшталт `export_processing_orders()`, що:
        - Отримує замовлення (через `wc_get_orders(['status' => 'processing'])`).
        - Формує потрібний масив із даними.
        - Викликає метод для відправлення в Google Sheets.
    - **`class-gs-api-client.php`** (умовна назва) — клас, який відповідає за ініціалізацію **Google Client**, авторизацію через *service account* і т. д.
        - Якщо логіки буде небагато, це можна **об’єднати** в одному класі, але інколи вигідніше розділити.
    - **`helpers.php`** — файл, де можна скласти якісь утилітарні функції (форматування цін, логування, хелпери), якщо знадобиться.

3. **Папка `assets/`**
    - Залежно від того, чи потрібна вам **адмін-сторінка** із кнопками / налаштуваннями, ви можете додати стилі (CSS) або скрипти (JS).
    - Якщо ваша логіка дуже проста й ви просто додаєте кнопку в адмінку, можливо, цього взагалі не треба.
    - Але якщо ви хочете гарне оформлення для сторінки «Export Orders» (наприклад, свій дизайн, табличку з результатами), тут зберігатимуться ваші `admin.css`, `admin.js` тощо.

4. **`README.md`**
    - Опціонально, але **корисно** для документації. Написати, як саме працює модуль, як налаштувати Google Credentials, які класи/методи є.

### Як це підключити у `functions.php` теми

У вашому **`functions.php`** або іншому основному файлі теми (наприклад, `custom-functions.php`), додаєте:

```php
// functions.php
require_once get_template_directory() . '/functions/export-to-google-sheets/export-to-google-sheets.php';
```

> Після цього весь функціонал модуля «Export to Google Sheets» буде автоматично активований разом із темою.

### Подальше розширення

1. **Admin Menu**:
    - У `export-to-google-sheets.php` чи у `class-gs-export.php` можна додати:
      ```php
      add_action('admin_menu', function() {
          add_submenu_page(
              'woocommerce',                 // або 'edit.php?post_type=shop_order'
              'Export to Sheets', 
              'Export to Sheets',
              'manage_woocommerce',         // права доступу
              'export-gs-page',            // slug
              'render_gs_export_page'      // callback
          );
      });
      ```
    - У колбеку `render_gs_export_page()` — робите кнопку «Export processing orders».

2. **AJAX / admin-post**:
    - Для натискання кнопки — `admin-post.php` екшен (наприклад, `action="export_gs_now"`).
    - Код, що виконує `wc_get_orders()` + формує масив + шле до Google Sheets.

3. **Google API Client** (через Composer або вручну):
    - Можна покласти у папку `vendor/` всередині `export-to-google-sheets/` і автозавантажувати.
    - Або зберігати у wp-plugins і викликати через `require_once`.

### Приклад (мінімалістичний, без composer)

1. **`export-to-google-sheets.php`** — головний файл:
   ```php
   <?php
   /**
    * Export to Google Sheets Module
    */

   // Захист від прямого виклику
   if (!defined('ABSPATH')) {
       exit;
   }

   // Підключаємо класи
   require_once __DIR__ . '/includes/class-gs-export.php';
   // require_once __DIR__ . '/includes/class-gs-api-client.php'; // якщо треба

   // Ініціюємо
   add_action('plugins_loaded', function() {
       new Gs_Export();
   });
   ```

2. **`includes/class-gs-export.php`** (основна логіка, приклад):
   ```php
   <?php
   if (!defined('ABSPATH')) {
       exit;
   }

   class Gs_Export {

       public function __construct() {
           // Наприклад, зареєструвати меню
           add_action('admin_menu', array($this, 'register_menu'));
           // Можна зареєструвати admin-post екшен і т. д.
       }

       public function register_menu() {
           add_submenu_page(
               'woocommerce',
               'Export to Sheets',
               'Export to Sheets',
               'manage_woocommerce',
               'export-gs-page',
               array($this, 'render_export_page')
           );
       }

       public function render_export_page() {
           echo '<h1>Export to Google Sheets</h1>';
           echo '<form method="post" action="'. esc_url(admin_url('admin-post.php')) .'">';
           echo '<input type="hidden" name="action" value="export_gs_now">';
           // Можливо, ще nonce
           submit_button('Export Processing Orders');
           echo '</form>';
       }
   }
   ```

3. **Обробник кнопки** (все ще в `class-gs-export.php` або в окремому файлі):
   ```php
   add_action('admin_post_export_gs_now', 'gs_handle_export');
   function gs_handle_export() {
       // Тут отримуємо всі processing-замовлення
       $orders = wc_get_orders(array(
           'status' => 'processing',
           'limit'  => -1
       ));
       // ... формуємо й відправляємо в Google Sheets

       // Повертаємося з повідомленням про успішний експорт
       wp_redirect(admin_url('admin.php?page=export-gs-page&done=1'));
       exit;
   }
   ```

Це — базова логіка, але жодної магії: у вас є чітка папка, чіткі файли.

---

## Підсумок

Описана вище структура (папка `export-to-google-sheets/` із головним файлом і папкою `includes/`) **достатньо гнучка**, щоби розширити її в майбутньому:

- Додавати нові класи (API, логіка форматування).
- Зберігати стилі/JS для адмінки.
- Мати README для команди, щоби все було задокументовано.

Усе це **не заважає** вашій основній темі, зберігається окремо, і ви **просто** підключаєте (`require_once`) цей міні-модуль у `functions.php`.
