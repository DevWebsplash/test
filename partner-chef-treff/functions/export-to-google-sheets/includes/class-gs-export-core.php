<?php
if (!defined('ABSPATH')) {
  exit;
}

class GS_Export_Core {

  public function __construct() {
        // nothing for now
  }

  /**
     * Основна логіка:
     * 1) Ініціюємо Google Client
     * 2) Видаляємо старі листи
     * 3) Збираємо "processing" замовлення
     * 4) Для кожного клієнта створюємо вкладку (якщо немає) та додаємо дані
   */
  public function export_processing_orders() {
        // 1) Google Client
    $client = new Google_Client();
        $client->setAuthConfig(MY_GS_SERVICE_ACCOUNT_JSON);
    $client->addScope(\Google_Service_Sheets::SPREADSHEETS);

    $service = new \Google_Service_Sheets($client);
    $spreadsheetId = MY_GS_SPREADSHEET_ID;

        // 2) Видаляємо всі старі листи (крім першого)
        // або за бажанням видалити всі — змінити логіку
        $this->deleteAllSheetsExceptFirst($service, $spreadsheetId);

        // 3) Збираємо замовлення
        $orders = wc_get_orders([
            'status' => 'processing',
            'limit'  => -1
        ]);
        if (empty($orders)) {
            throw new \Exception('No processing orders found');
        }

        // 4) Оскільки ми видалили листи, заново дізнаємось поточну структуру (щоб sheetTitles був актуальний)
    $spreadsheet = $service->spreadsheets->get($spreadsheetId);
        $sheets = $spreadsheet->getSheets();
        $sheetTitles = [];
        foreach ($sheets as $sh) {
      $sheetTitles[] = $sh->getProperties()->getTitle();
    }

        // Тепер для кожного замовлення => створити / доповнити вкладку
    foreach ($orders as $order) {
      $this->append_order_to_customer_tab($order, $service, $spreadsheetId, $sheetTitles);
    }
  }

  /**
     * Видаляє всі листи, крім першого (або можна видалити всі — залежить від вашої задачі)
     */
    private function deleteAllSheetsExceptFirst($service, $spreadsheetId) {
        $spreadsheet = $service->spreadsheets->get($spreadsheetId);
        $sheets = $spreadsheet->getSheets();

        $requests = [];
        $isFirst = true;
        foreach ($sheets as $sheet) {
            $sheetId = $sheet->getProperties()->getSheetId();
            $title   = $sheet->getProperties()->getTitle();

            if ($isFirst) {
                // пропустимо перший
                $isFirst = false;
                continue;
            }
            // решту видаляємо
            $requests[] = new Google_Service_Sheets_Request([
                'deleteSheet' => ['sheetId' => $sheetId]
            ]);
        }

        if (!empty($requests)) {
            $batchReq = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(['requests' => $requests]);
            $service->spreadsheets->batchUpdate($spreadsheetId, $batchReq);
        }
    }

    /**
     * Для кожного замовлення створюємо/доповнюємо вкладку з ім’ям клієнта
   */
  private function append_order_to_customer_tab($order, $service, $spreadsheetId, &$sheetTitles) {
        // 1) Визначити назву вкладки
        $first = $order->get_billing_first_name();
        $last  = $order->get_billing_last_name();
        $tabName = trim("$first $last");
        if (!$tabName) {
            $tabName = 'Guest-'.$order->get_billing_email();
    }
        if (strlen($tabName) > 90) {
            $tabName = substr($tabName, 0, 90);
    }

        // 2) Якщо вкладки немає => створюємо
        if (!in_array($tabName, $sheetTitles)) {
            $this->createNewSheet($service, $spreadsheetId, $tabName);
            $sheetTitles[] = $tabName;
    }

        // 3) Будуємо дані
    $values = $this->buildOrderData($order);

        // 4) Append
        $range = $tabName.'!A1';
    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);
    $params = [
        'valueInputOption' => 'RAW',
        'insertDataOption' => 'INSERT_ROWS'
    ];

    $service->spreadsheets_values->append(
        $spreadsheetId,
        $range,
        $body,
        $params
    );
  }

  /**
     * Створює нову вкладку (лист)
   */
    private function createNewSheet($service, $spreadsheetId, $title) {
    $requests = [];
    $requests[] = new Google_Service_Sheets_Request([
        'addSheet' => [
                'properties' => ['title' => $title]
        ]
    ]);
        $batchReq = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
            'requests' => $requests
        ]);
        $service->spreadsheets->batchUpdate($spreadsheetId, $batchReq);
  }

  /**
     * Формує масив рядків для одного замовлення
   */
  private function buildOrderData($order) {
    $order_id = $order->get_id();
    $data = [];

    // 1) "Order Number"
    $data[] = ['Order Number', $order_id];
    // 2) порожній рядок
    $data[] = [];

    // 3) заголовки
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
                $price_unit = ($qty>0)?($item->get_total()/$qty):0;

                // ACF cost
        $cost  = get_post_meta($product->get_id(), 'cost_price', true);

        $data[] = [
            $sku,
            $name,
            $meas,
            $qty,
                    number_format($price_unit,2,',','.').' €',
            number_format((float)$cost,2,',','.').' €'
        ];
      }
    }

        // 5) порожній рядок
    $data[] = [];

    return $data;
  }

  /**
     * Збираємо атрибути (Measurements) одним рядком
   */
  private function get_measurements_string($product) {
    $attributes = $product->get_attributes();
    if (empty($attributes)) {
      return '';
    }
    $parts = [];
    foreach ($attributes as $attr) {
            $options = $attr->get_options(); // масив
      $parts[] = implode('/', $options);
    }
    return implode(' ', $parts);
  }
}
