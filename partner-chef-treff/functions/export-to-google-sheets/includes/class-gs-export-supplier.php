<?php
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Клас GS_Export_Supplier
 *
 * Реалізує 2 публічні методи:
 *  1) export_orders()    -> Видаляє всі листи, крім одного, і додає вкладки під кожен order (за ім'ям клієнта).
 *  2) export_suppliers() -> Повністю очищує документ (видаляє всі, крім одного, і очищує 1-й),
 *                           далі створює вкладки за supplier_name, викликаючи update().
 */
class GS_Export_Supplier
{
  public function __construct() {
    // можна лишити порожнім
  }

  /**
   * (1) "Export Orders":
   *    - Беремо замовлення "processing"
   *    - Видаляємо всі листи, крім першого
   *    - Кожне замовлення -> create sheet (if missing) -> append()
   */
  public function export_orders() {
    // 1) Збираємо "processing" замовлення
    $orders = wc_get_orders([
        'status' => 'processing',
        'limit'  => -1
    ]);
    if (empty($orders)) {
      throw new \Exception('No orders to export');
    }

    // 2) Ініціюємо Google Client
    $client = new \Google_Client();
    $client->setAuthConfig(MY_GS_SERVICE_ACCOUNT_JSON);
    $client->addScope(\Google_Service_Sheets::SPREADSHEETS);

    $service = new \Google_Service_Sheets($client);
    $spreadsheetId = MY_GS_SPREADSHEET_ID; // Основна таблиця (для orders)

    // 3) Видаляємо всі листи, крім одного
    $this->deleteAllSheetsExceptFirst($service, $spreadsheetId);

    // 4) Зчитуємо назви листів, щоб потім перевіряти, чи треба createNewSheet()
    $spreadsheet = $service->spreadsheets->get($spreadsheetId);
    $sheetTitles = [];
    foreach ($spreadsheet->getSheets() as $sh) {
      $sheetTitles[] = $sh->getProperties()->getTitle();
    }

    // 5) Для кожного замовлення => робимо append_order_to_customer_tab()
    foreach ($orders as $order) {
      $this->append_order_to_customer_tab($order, $service, $spreadsheetId, $sheetTitles);
    }
  }

  /**
   * (2) "Export Suppliers":
   *     - Збираємо "processing" замовлення
   *     - Повністю очищуємо документ: видаляємо всі листи, крім першого, а перший очищуємо
   *     - Групуємо товари за supplier_name
   *     - Кожен supplier -> create sheet -> update()
   */
  public function export_suppliers() {
    // 1) Збираємо
    $orders = wc_get_orders([
        'status' => 'processing',
        'limit'  => -1
    ]);
    if (empty($orders)) {
      throw new \Exception('No orders to export for suppliers');
    }

    // 2) Ініціюємо Google Client
    $client = new \Google_Client();
    $client->setAuthConfig(MY_GS_SERVICE_ACCOUNT_JSON);
    $client->addScope(\Google_Service_Sheets::SPREADSHEETS);

    $service = new \Google_Service_Sheets($client);
    $spreadsheetId = MY_GS_SPREADSHEET_SUPPLIER_ID; // Інша таблиця (для suppliers)

    // 3) Повне очищення: видаляємо всі листи, крім одного, а той 1-й "clear"
    $this->deleteAllSheetsButLeaveOneEmpty($service, $spreadsheetId);

    // 4) Групуємо товари за supplier_name
    $grouped = $this->buildSupplierItems($orders);

    // 5) Для кожного supplier створюємо лист + update()
    foreach ($grouped as $supplierName => $rows) {
      $this->createNewSheet($service, $spreadsheetId, $supplierName);

      $range = $supplierName . '!A1';
      $body  = new \Google_Service_Sheets_ValueRange(['values' => $rows]);
      $params= ['valueInputOption' => 'RAW'];

      $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
    }
  }

  /* ================================================================
     Допоміжні методи
  ================================================================ */

  /**
   * deleteAllSheetsExceptFirst($service, $spreadsheetId):
   *   Видаляє всі листи, крім першого (для Export Orders)
   */
  private function deleteAllSheetsExceptFirst($service, $spreadsheetId) {
    $spreadsheet = $service->spreadsheets->get($spreadsheetId);
    $sheets = $spreadsheet->getSheets();

    $requests = [];
    $isFirst = true;
    foreach ($sheets as $sheet) {
      $sheetId = $sheet->getProperties()->getSheetId();
      if ($isFirst) {
        // Лишаємо перший лист
        $isFirst = false;
        continue;
      }
      $requests[] = new Google_Service_Sheets_Request([
          'deleteSheet' => [ 'sheetId' => $sheetId ]
      ]);
    }
    if (!empty($requests)) {
      $batch = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
          'requests' => $requests
      ]);
      $service->spreadsheets->batchUpdate($spreadsheetId, $batch);
    }
  }

  /**
   * deleteAllSheetsButLeaveOneEmpty($service, $spreadsheetId):
   *   Видаляє всі листи, крім одного, й очищує його.
   *   (Так документ не залишається взагалі без листів,
   *    уникаємо помилки "can't remove all sheets in a document".)
   */
  private function deleteAllSheetsButLeaveOneEmpty($service, $spreadsheetId) {
    $spreadsheet = $service->spreadsheets->get($spreadsheetId);
    $sheets = $spreadsheet->getSheets();

    // Якщо у документі 1 sheet => просто очищуємо його
    // Якщо більше => видаляємо всі, крім першого
    $requests = [];
    $isFirst = true;
    foreach ($sheets as $sheet) {
      $sheetId = $sheet->getProperties()->getSheetId();
      if ($isFirst) {
        // Перший лишаємо (потім очищимо)
        $isFirst = false;
        continue;
      }
      // решту видаляємо
      $requests[] = new Google_Service_Sheets_Request([
          'deleteSheet' => [ 'sheetId' => $sheetId ]
      ]);
    }

    // Виконуємо batchUpdate (видалимо другий, третій..)
    if (!empty($requests)) {
      $batch = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(['requests' => $requests]);
      $service->spreadsheets->batchUpdate($spreadsheetId, $batch);
    }

    // Тепер лишився перший sheet => очищуємо його дані
    // беремо оновлений список sheet'ів
    $spreadsheet2 = $service->spreadsheets->get($spreadsheetId);
    $sheets2 = $spreadsheet2->getSheets();
    if (!empty($sheets2)) {
      // беремо назву першого
      $sheetName = $sheets2[0]->getProperties()->getTitle();
      // clear
      $clearReq = new Google_Service_Sheets_ClearValuesRequest();
      $service->spreadsheets_values->clear($spreadsheetId, $sheetName, $clearReq);
    }
  }

  /**
   * append_order_to_customer_tab():
   *  Для "Export Orders": визначаємо sheetName = "FirstName LastName",
   *  створюємо sheet, якщо нема, і робимо append() із buildOrderData($order).
   */
  private function append_order_to_customer_tab($order, $service, $spreadsheetId, &$sheetTitles) {
    $firstName = $order->get_billing_first_name();
    $lastName  = $order->get_billing_last_name();
    $sheetName = trim($firstName . ' ' . $lastName);
    if (!$sheetName) {
      $sheetName = 'Guest-' . $order->get_billing_email();
    }
    if (strlen($sheetName) > 90) {
      $sheetName = substr($sheetName, 0, 90);
    }

    // Якщо листа немає => createNewSheet
    if (!in_array($sheetName, $sheetTitles)) {
      $this->createNewSheet($service, $spreadsheetId, $sheetName);
      $sheetTitles[] = $sheetName;
    }

    // Будуємо дані (Order)
    $values = $this->buildOrderData($order);

    // Append
    $range = $sheetName . '!A1';
    $body = new \Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);
    $params = [
        'valueInputOption' => 'RAW',
        'insertDataOption' => 'INSERT_ROWS'
    ];

    $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
  }

  /**
   * buildOrderData($order):
   *  Формує масив рядків (Order Number, порожній рядок, header, товари, ...)
   */
  private function buildOrderData($order) {
    $order_id = $order->get_id();
    $data = [];

    // 1) "Order Number" row
    $data[] = ['Order Number', $order_id];
    // 2) порожній рядок
    $data[] = [];

    // 3) заголовок
    $data[] = ['Article-ID','Item Name','Measurements','Amount','Price','Cost'];

    // 4) товари
    $items = $order->get_items();
    if (!empty($items)) {
      foreach ($items as $item) {
        $product = $item->get_product();
        if (!$product) continue;

        $sku   = $product->get_sku();
        $name  = $product->get_name();
        $meas  = $this->get_measurements_string($product);
        $qty   = $item->get_quantity();
        $price = ($qty > 0)?($item->get_total()/$qty):0;
        $cost  = get_post_meta($product->get_id(), 'cost_price', true);

        $data[] = [
            $sku,
            $name,
            $meas,
            $qty,
            number_format($price,2,',','.').' €',
            number_format((float)$cost,2,',','.').' €'
        ];
      }
    }

    // 5) порожній рядок
    $data[] = [];

    return $data;
  }

  /**
   * buildSupplierItems($orders):
   *  Групуємо товари за ACF 'supplier_name',
   *  додаємо на початку кілька рядків ("Supplier", "", "ID", "", header),
   *  потім рядки товарів
   */
  private function buildSupplierItems($orders) {
    $result = [];
    $header = ['Article-ID','Item Name','Measurements','Amount','Price','Cost','Ordered By','Order ID'];

    foreach ($orders as $order) {
      $order_id = $order->get_id();
      $orderedBy = $order->get_billing_company();
      if (!$orderedBy) {
        $orderedBy = $order->get_formatted_billing_full_name();
      }

      $items = $order->get_items();
      foreach ($items as $item) {
        $product = $item->get_product();
        if (!$product) continue;

        $supplier = get_post_meta($product->get_id(), 'supplier_name', true);
        if (!$supplier) {
          $supplier = 'NoSupplierName';
        }

        $qty   = $item->get_quantity();
        $price = ($qty>0)?($item->get_total()/$qty):0;
        $cost  = get_post_meta($product->get_id(), 'cost_price', true);

        $row = [
            $product->get_sku(),
            $product->get_name(),
            $this->get_measurements_string($product),
            $qty,
            number_format($price,2,',','.').' €',
            number_format((float)$cost,2,',','.').' €',
            $orderedBy,
            $order_id
        ];

        // Якщо вперше бачимо $supplier -> додаємо "Supplier", "", "ID", "", header
        if (!isset($result[$supplier])) {
          $result[$supplier] = [];
          $result[$supplier][] = ['Supplier', $supplier,'','ID','PARTY-xxx'];
          $result[$supplier][] = [];
          $result[$supplier][] = $header;
        }

        $result[$supplier][] = $row;
      }
    }

    return $result;
  }

  /**
   * Створює нову вкладку
   */
  private function createNewSheet($service, $spreadsheetId, $title) {
    if (strlen($title) > 90) {
      $title = substr($title, 0, 90);
    }
    $requests = [];
    $requests[] = new \Google_Service_Sheets_Request([
        'addSheet' => [
            'properties' => ['title' => $title]
        ]
    ]);
    $batch = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(['requests' => $requests]);
    $service->spreadsheets->batchUpdate($spreadsheetId, $batch);
  }

  /**
   * Створює вкладку, якщо її нема (для варіанта без тотального видалення)
   */
  private function createSheetIfMissing($service, $spreadsheetId, $sheetName) {
    $spreadsheet = $service->spreadsheets->get($spreadsheetId);
    $sheetTitles = [];
    foreach ($spreadsheet->getSheets() as $sh) {
      $sheetTitles[] = $sh->getProperties()->getTitle();
    }
    if (!in_array($sheetName, $sheetTitles)) {
      $this->createNewSheet($service, $spreadsheetId, $sheetName);
    }
  }

  /**
   * Збираємо "Measurements" одним рядком
   */
  private function get_measurements_string($product) {
    $attributes = $product->get_attributes();
    if (empty($attributes)) {
      return '';
    }
    $parts = [];
    foreach ($attributes as $attr) {
      $opts = $attr->get_options();
      $parts[] = implode('/', $opts);
    }
    return implode(' ', $parts);
  }
}
