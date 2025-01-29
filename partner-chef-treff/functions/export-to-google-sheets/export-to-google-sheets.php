<?php
/**
 * Main file for the "Export to Google Sheets" module
 */

if (!defined('ABSPATH')) {
  exit;
}

// 1) Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

// 2) Виносимо глобальні змінні або константи
define('MY_GS_SERVICE_ACCOUNT_JSON', __DIR__ . '/test-wsp-9b8a7214aa0f.json');
define('MY_GS_SPREADSHEET_ID', '1tNb2E22eBu0EbKJYZdO6tjWDbE-KH2dubTggzTNEDUY');
define('MY_GS_SPREADSHEET_SUPPLIER_ID', '1QRzpLqVFsir4IsPrazeh6yvnbpIqSQCj-eXR-Akx1r8');

// 3) Підключаємо файл з класом
require_once __DIR__ . '/includes/class-gs-export-admin.php';
require_once __DIR__ . '/includes/class-gs-export-core.php';
require_once __DIR__ . '/includes/class-gs-export-supplier.php';

//require_once __DIR__ . '/includes/class-gs-export.php';

// 4) Ініціюємо клас (можна передати змінні в конструктор, або використовувати зсередини класу як константи)
add_action('init', function() {
  new GS_Export_Admin();
  new GS_Export_Core();
  new GS_Export_Supplier();
});
