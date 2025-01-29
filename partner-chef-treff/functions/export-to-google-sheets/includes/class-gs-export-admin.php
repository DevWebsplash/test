<?php
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Клас GS_Export_Admin
 * - Показує в адмінці дві кнопки: "Export Orders" і "Export Suppliers"
 * - Але саму логіку викликає із class-gs-export-supplier.php
 */
class GS_Export_Admin {

  public function __construct() {
    add_action('admin_menu', array($this, 'register_menu'));
        // Кнопка 1: Export Orders
    add_action('admin_post_export_gs_now', array($this, 'handle_export_orders'));
        // Кнопка 2: Export Suppliers
    add_action('admin_post_export_suppliers_now', array($this, 'handle_export_suppliers'));
  }

    /**
     * Реєструємо одну сторінку "Export to GSheets"
     */
  public function register_menu() {
    add_submenu_page(
        'woocommerce',
        'Export to GSheets',
        'Export to GSheets',
        'manage_woocommerce',
        'export-gs-page',
        array($this, 'render_export_page')
    );
  }

  /**
     * Відображає дві кнопки на одній сторінці
   */
  public function render_export_page() {
    echo '<div class="wrap"><h1>Export to Google Sheets</h1>';

    // Відображаємо повідомлення про успіх/помилку (однаково для обох кнопок):
    if (isset($_GET['done']) && $_GET['done'] == 1) {
      echo '<div class="notice notice-success"><p>Export completed successfully!</p></div>';
    }
    if (isset($_GET['error']) && $_GET['error'] == 1) {
      echo '<div class="notice notice-error"><p>Something went wrong while exporting. Check logs.</p></div>';
    }

    // Кнопка 1: Export Orders
    echo '<h2>Export Orders</h2>';
    echo '<p><strong>Warning:</strong> This will ERASE all existing data in the Google Sheet!</p>';
    echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
    echo '<input type="hidden" name="action" value="export_gs_now" />';
    submit_button('Export Orders');
    echo '</form>';
    echo '<p><em>Make sure you have a backup if needed.</em></p>';

    // Кнопка 2: Export Suppliers
    echo '<hr>';
    echo '<h2>Export Suppliers</h2>';
    echo '<p><strong>Warning:</strong> This will ERASE all existing data in the Google Sheet!</p>';
    echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
    echo '<input type="hidden" name="action" value="export_suppliers_now" />';
    submit_button('Export Supplier Items');
    echo '</form>';
    echo '<p><em>Make sure you have a backup if needed.</em></p>';
    echo '</div>';
  }

    /**
     * Клік "Export Orders"
     *  - Просто викликає метод у GS_Export_Supplier (або іншому "core"-класі)
     */
    public function handle_export_orders() {
    if (!current_user_can('manage_woocommerce')) {
      wp_die('Not allowed');
    }

    try {
            // Викликаємо логіку з іншого класу:
            $exporter = new GS_Export_Supplier();
            $exporter->export_orders();

      wp_redirect(admin_url('admin.php?page=export-gs-page&done=1'));
      exit;

        } catch (\Exception $e) {
            error_log('Export Orders error: ' . $e->getMessage());
      wp_redirect(admin_url('admin.php?page=export-gs-page&error=1'));
      exit;
    }
  }

  /**
     * Клік "Export Supplier Items"
   */
  public function handle_export_suppliers ()
  {
    if (!current_user_can ('manage_woocommerce')) {
      wp_die ('Not allowed');
    }

    try {
            // Викликаємо логіку:
            $exporter = new GS_Export_Supplier();
            $exporter->export_suppliers();

            wp_redirect(admin_url('admin.php?page=export-gs-page&done=1'));
      exit;

    } catch (\Exception $e) {
      error_log ('Export suppliers error: ' . $e->getMessage ());
            wp_redirect(admin_url('admin.php?page=export-gs-page&error=1'));
      exit;
    }
  }
}

