<?php
/**
 * Theme functions
 *
 * @author   <Author>
 * @version  1.0.0
 * @package  <Package>
 */

require_once 'functions/func-admin.php';
require_once 'functions/func-debug.php';
require_once 'functions/func-menu.php';
require_once 'functions/func-script.php';
require_once 'functions/func-timer.php';
require_once 'functions/func-style.php';
require_once 'functions/func-woo.php';
//require_once 'functions/func-ajax.php';

require_once get_template_directory() . '/functions/my-custom-checkout/my-custom-checkout.php';
require_once get_template_directory() . '/functions/export-to-google-sheets/export-to-google-sheets.php';





// ===================
// 1. Створення кастомних ролей (без змін)
// ===================
function create_custom_roles() {
	$customer_role = get_role('customer');
	if (!$customer_role) {
		return; // Роль customer має існувати
	}
	$capabilities = $customer_role->capabilities;

	$roles = [
		'all_inclusive' => __('All Inclusive', 'text-domain'),
		'individual'    => __('Individual', 'text-domain'),
		'lounge'        => __('Lounge', 'text-domain'),
		'premium'       => __('Premium', 'text-domain'),
	];

	foreach ($roles as $role_slug => $role_name) {
		if (!get_role($role_slug)) {
			add_role($role_slug, $role_name, $capabilities);
		}
	}
}
add_action('init', 'create_custom_roles');

// ===================
// 2. Примусове встановлення ключа 'afpvu_applied_products_role' у WC-сесію
// ===================
function force_init_afpvu_wc_session_key() {
	if (!class_exists('WooCommerce')) {
		return;
	}

	if (WC()->session === null) {
		WC()->session = new WC_Session_Handler();
		WC()->session->init();
	}

	$roles_in_session = WC()->session->get('afpvu_applied_products_role');
	if (!is_array($roles_in_session)) {
		$default_roles = ['all_inclusive'];
		WC()->session->set('afpvu_applied_products_role', $default_roles);
	}

	if (!session_id()) {
		session_start();
	}
	if (!isset($_SESSION['afpvu_applied_products_role'])) {
		$_SESSION['afpvu_applied_products_role'] = $roles_in_session;
	}
}
add_action('woocommerce_init', 'force_init_afpvu_wc_session_key', 0);

// ===================
// 3. Синхронізація ключа між WC()->session і $_SESSION
// ===================
function sync_afpvu_role_in_php_session() {
	if (!class_exists('WooCommerce')) {
		return;
	}

	if (WC()->session === null) {
		WC()->session = new WC_Session_Handler();
		WC()->session->init();
	}

	$roles_in_wc = WC()->session->get('afpvu_applied_products_role', []);

	if (!session_id()) {
		session_start();
	}

	if (!isset($_SESSION['afpvu_applied_products_role'])) {
		$_SESSION['afpvu_applied_products_role'] = $roles_in_wc;
	} else {
		$_SESSION['afpvu_applied_products_role'] = $roles_in_wc;
	}
}
add_action('woocommerce_init', 'sync_afpvu_role_in_php_session', 1);

// ===================
// 4. Логування (debug) поточної WC-сесії у файлі debug.log
// ===================
function debug_afpvu_wc_session_roles() {
	if (WC()->session && method_exists(WC()->session, 'get')) {
		$current_data = WC()->session->get('afpvu_applied_products_role');
		error_log('Current WC Session Data (afpvu_applied_products_role): ' . json_encode($current_data));
	} else {
		error_log('WC Session not initialized or method "get" not available');
	}
}
add_action('wp_footer', 'debug_afpvu_wc_session_roles');

// ===================
// 5. Логування видимості продуктів (woocommerce_product_is_visible)
// ===================

function log_afpvu_issues($is_visible, $product_id) {
	$user  = wp_get_current_user();
	$roles = implode(',', $user->roles);

	error_log(
		'Product ID: ' . $product_id .
		' - Visible: ' . ($is_visible ? 'Yes' : 'No') .
		' - User Roles: ' . $roles
	);
	return $is_visible;
}
add_filter('woocommerce_product_is_visible', 'log_afpvu_issues', 10, 2);

// ===================
// 6. Логування "Applied Roles" (зчитуємо з WC()->session)
// ===================
function fix_afpvu_undefined_array_key_warning() {
	add_filter('woocommerce_product_is_visible', function($is_visible, $product_id) {
		if (WC()->session && method_exists(WC()->session, 'get')) {
			$applied_roles = WC()->session->get('afpvu_applied_products_role', []);
			error_log('Product ID: ' . $product_id . ' - Applied Roles from WC Session: ' . json_encode($applied_roles));

			// Синхронізація із PHP-сесією
			if (!isset($_SESSION['afpvu_applied_products_role'])) {
				$_SESSION['afpvu_applied_products_role'] = $applied_roles;
			}
		}
		return $is_visible;
	}, 10, 2);
}
add_action('init', 'fix_afpvu_undefined_array_key_warning');

// ===================
// 7. Примусова видимість продуктів у корзині та на сторінці оформлення
// ===================
function always_visible_in_cart($visible, $product_id) {
	if (is_cart() || is_checkout()) {
		return true; // Завжди відображати продукт
	}
	return $visible;
}
add_filter('woocommerce_product_is_visible', 'always_visible_in_cart', 20, 2);

// ===================
// 8. Перевірка наявності класів плагіна Addify
// ===================
function check_addify_classes() {
	if (class_exists('AF_Product_Discount_Front')) {
		error_log('Class AF_Product_Discount_Front exists.');
	} else {
		error_log('Class AF_Product_Discount_Front does not exist.');
	}

	if (class_exists('AF_Cart_Discount_Front')) {
		error_log('Class AF_Cart_Discount_Front exists.');
	} else {
		error_log('Class AF_Cart_Discount_Front does not exist.');
	}
}
add_action('plugins_loaded', 'check_addify_classes');

// ===================
// 9. (ОПЦІЙНО) Вимкнення фільтрів плагіна на сторінках корзини та оформлення
// ===================
function disable_addify_filters_in_cart_and_checkout() {
	if (is_cart() || is_checkout() || (defined('DOING_AJAX') && DOING_AJAX)) {
		// Замініть 'afpvu_front_visibility' і 'afpvu_front_purchasable' на реальні назви функцій плагіна
		remove_filter('woocommerce_product_is_visible', 'afpvu_front_visibility', 10);
		remove_filter('woocommerce_is_purchasable', 'afpvu_front_purchasable', 10);
	}
}
add_action('wp', 'disable_addify_filters_in_cart_and_checkout');

