<?php
if (!defined ('ABSPATH')) {
  exit;
}

/**
 * Приклад: Кожен клієнт отримує окремий лист у Google Sheets;
 * всі його замовлення (зі статусом `processing`) дописуються (append).
 *
 * Перед використанням:
 *  1) У головному файлі модуля (наприклад, export-to-google-sheets.php):
 *       require_once __DIR__ . '/vendor/autoload.php'; // Composer
 *       require_once __DIR__ . '/includes/class-gs-export.php';
 *       new GS_Exporter();
 *  2) Змінити шлях до вашого JSON (сервіс-акаунт):
 *       $client->setAuthConfig(__DIR__ . '/../test-wsp-9b8a7214aa0f.json');
 *  3) Вписати ваш $spreadsheetId (з URL Google Sheets).
 */
class GS_Exporter
{

  public function __construct ()
  {
    // Додаємо підменю "Export to GSheets" у WooCommerce
    add_action ('admin_menu', array($this, 'register_menu'));
    // Обробка кнопки "Export Processing Orders"
    add_action ('admin_post_export_gs_now', array($this, 'handle_export_gs_now'));
  }

  /**
   * Створює підменю в розділі WooCommerce
   */
  public function register_menu ()
  {
    add_submenu_page (
        'woocommerce',        // parent slug
        'Export to GSheets',  // Page title
        'Export to GSheets',  // Menu title
        'manage_woocommerce', // Required capability
        'export-gs-page',     // menu slug
        array($this, 'render_export_page') // callback
    );
  }

  /**
   * Відображає сторінку з кнопкою "Export Processing Orders"
   */
  public function render_export_page ()
  {
    echo '<div class="wrap"><h1>Export Orders to Google Sheets (Per Customer)</h1>';

    if (isset($_GET[ 'done' ]) && $_GET[ 'done' ] == 1) {
      echo '<div class="notice notice-success is-dismissible"><p>Export completed successfully!</p></div>';
    }
    if (isset($_GET[ 'error' ]) && $_GET[ 'error' ] == 1) {
      echo '<div class="notice notice-error is-dismissible"><p>Something went wrong while exporting. Check logs.</p></div>';
    }

    echo '<form method="post" action="' . esc_url (admin_url ('admin-post.php')) . '">';
    // action=export_gs_now - обробляється в handle_export_gs_now()
    echo '<input type="hidden" name="action" value="export_gs_now" />';
    submit_button ('Export Processing Orders');
    echo '</form>';
    echo '</div>';
  }

  /**
   * Клік "Export Processing Orders" -> збираємо замовлення, групуємо, записуємо в Sheets
   */
  public function handle_export_gs_now ()
  {
    if (!current_user_can ('manage_woocommerce')) {
      wp_die ('Not allowed');
    }

    try {
      // 1) Отримати замовлення зі статусом "processing"
      $orders = wc_get_orders (array(
          'status' => 'processing',
          'limit' => -1
      ));
      if (empty($orders)) {
        wp_redirect (admin_url ('admin.php?page=export-gs-page&done=1'));
        exit;
      }

      // 2) Ініціалізуємо Google Client
      $client = new Google_Client();
      // ВАШ JSON service-акаунта:
      $client->setAuthConfig (MY_GS_SERVICE_ACCOUNT_JSON );
      $client->addScope (\Google_Service_Sheets::SPREADSHEETS);

      $service = new \Google_Service_Sheets($client);

      // ID вашої Google-таблиці
      $spreadsheetId = MY_GS_SPREADSHEET_ID;

      // 3) Завантажити структуру Sheets, щоб дізнатися назви наявних листів
      $spreadsheet = $service->spreadsheets->get ($spreadsheetId);
      $sheets = $spreadsheet->getSheets ();
      $sheetTitles = array();
      foreach ($sheets as $sh) {
        $sheetTitles[] = $sh->getProperties ()->getTitle ();
      }

      // 4) Перебираємо кожне замовлення, визначаємо ім'я вкладки = ім'я клієнта (або email)
      foreach ($orders as $order) {
        /** @var WC_Order $order */
        $order_id = $order->get_id ();

        // Витягуємо дані для назви листа
        $first_name = $order->get_billing_first_name ();
        $last_name = $order->get_billing_last_name ();
        $customer_tab_name = trim ($first_name . ' ' . $last_name);

        if (empty($customer_tab_name)) {
          // Якщо guest або нема імені
          $customer_tab_name = 'Guest-' . $order->get_billing_email ();
        }

        // Ліміт ~100 символів
        if (strlen ($customer_tab_name) > 90) {
          $customer_tab_name = substr ($customer_tab_name, 0, 90);
        }

        // Якщо листа з такою назвою ще нема -> створюємо
        if (!in_array ($customer_tab_name, $sheetTitles)) {
          $this->createNewSheet ($service, $spreadsheetId, $customer_tab_name);
          $sheetTitles[] = $customer_tab_name;
        }

        // 5) Формуємо дані для цього замовлення
        $values = $this->buildOrderData ($order);

        // 6) Використовуємо append(...), щоб додати в кінець листа
        $range = $customer_tab_name . '!A1';
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
        $params = [
            'valueInputOption' => 'RAW',
            'insertDataOption' => 'INSERT_ROWS'
        ];

        $appendResult = $service->spreadsheets_values->append (
            $spreadsheetId,
            $range,
            $body,
            $params
        );
      }

      // Done
      wp_redirect (admin_url ('admin.php?page=export-gs-page&done=1'));
      exit;

    } catch (\Exception $e) {
      error_log ('Export to Sheets error: ' . $e->getMessage ());
      wp_redirect (admin_url ('admin.php?page=export-gs-page&error=1'));
      exit;
    }
  }

  /**
   * Створює новий лист (tab) у таблиці.
   */
  private function createNewSheet ($service, $spreadsheetId, $sheetTitle)
  {
    $requests = [];
    $requests[] = new Google_Service_Sheets_Request([
        'addSheet' => [
            'properties' => [
                'title' => $sheetTitle
            ]
        ]
    ]);

    $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
        'requests' => $requests
    ]);
    $service->spreadsheets->batchUpdate ($spreadsheetId, $batchUpdateRequest);
  }

  /**
   * buildOrderData($order) - формує масив рядків:
   *  1) [ "Order Number", order_id ]
   *  2) порожній рядок
   *  3) заголовки (Article-ID, Item Name, ...)
   *  4) Кожен товар -> свій рядок
   *  5) порожній рядок в кінці
   */
  private function buildOrderData ($order)
  {
    $order_id = $order->get_id ();
    $data = [];

    // 1) Рядок із "Order Number"
    $data[] = ['Order Number', $order_id];
    // 2) Порожній рядок
    $data[] = [];

    // 3) заголовки
    $data[] = [
        'Article-ID',
        'Item Name',
        'Measurements',
        'Amount',
        'Price',
        'Cost'
    ];

    // 4) товари
    $items = $order->get_items ();
    if (!empty($items)) {
      foreach ($items as $item) {
        $product = $item->get_product ();
        if (!$product) continue;

        $sku = $product->get_sku ();
        $name = $product->get_name ();
        $meas = $this->get_measurements_string ($product);
        $qty = $item->get_quantity ();
        $price_unit = ( $qty > 0 ) ? ( $item->get_total () / $qty ) : 0;

        // Cost (ACF під ключем 'cost_price')
        $cost = get_post_meta ($product->get_id (), 'cost_price', true);

        $price_unit_str = number_format ($price_unit, 2, ',', '.') . ' €';
        $cost_str = number_format ((float)$cost, 2, ',', '.') . ' €';

        $data[] = [
            $sku,
            $name,
            $meas,
            $qty,
            $price_unit_str,
            $cost_str
        ];
      }
    }

    // 5) Порожній рядок "на завершення"
    $data[] = [];

    return $data;
  }

  /**
   * Збираємо атрибути (Measurements) в один рядок
   */
  private function get_measurements_string ($product)
  {
    $attributes = $product->get_attributes ();
    if (empty($attributes)) {
      return '';
    }
    $parts = [];
    foreach ($attributes as $attr_obj) {
      $options = $attr_obj->get_options (); // масив
      $parts[] = implode ('/', $options);
    }
    return implode (' ', $parts);
  }
}
