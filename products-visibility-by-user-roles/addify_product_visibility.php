<?php
/*
 * Plugin Name:       Products Visibility by User Roles
 * Plugin URI:        https://woocommerce.com/products/products-visibility-by-user-roles
 * Description:       Products Visibility by User Roles plugin allows you to decide which products will be visible site-wide for each user role. PLEASE TAKE BACKUP BEFORE UPDATING THE PLUGIN.
 * Version:           1.5.1
 * Author:            Addify
 * Developed By:      Addify
 * Author URI:        https://woocommerce.com/vendor/addify/
 * Support:           https://woocommerce.com/vendor/addify/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:       /languages
 * Text Domain:       addify_products_visibility
 *
 * Woo: 5332170:5a368d4ffb25b43a674bc33620ff2af8
 * WC requires at least: 3.0.9
 * WC tested up to: 9.*.*
 *
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Addify_Products_Visibility')) {

	class Addify_Products_Visibility {
	


		public function __construct() {

			$this->afpvu_global_constents_vars();
			register_activation_hook(__FILE__, array( $this, 'afpvu_add_quote_page' ));
			add_action('wp_loaded', array( $this, 'afpvu_init' ));

			include_once AFPVU_PLUGIN_DIR . 'general-setting.php';

			if (is_admin()) {
				include_once AFPVU_PLUGIN_DIR . 'class_afpvu_admin.php';
			} else {
				include_once AFPVU_PLUGIN_DIR . 'class_afpvu_front.php';
			}

			//HOPS compatibility
			add_action('before_woocommerce_init', array( $this, 'afpvu_HOPS_Compatibility' ));

			add_action('plugins_loaded', array( $this, 'afpvu_woocomerce_check' ));
		}

		public function afpvu_woocomerce_check() {

			// Check the installation of WooCommerce module if it is not a multi site.
			if (!is_multisite() && !in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true)) {
				add_action('admin_notices', array( $this, 'afpvu_admin_notice' ));
			}
		}

		public function afpvu_admin_notice() {

			$afpvu_allowed_tags = array(
				'a'      => array(
					'class' => array(),
					'href'  => array(),
					'rel'   => array(),
					'title' => array(),
				),
				'b'      => array(),

				'div'    => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'p'      => array(
					'class' => array(),
				),
				'strong' => array(),

			);

			// Deactivate the plugin
			deactivate_plugins(__FILE__);

			$afpvu_woo_check = '<div id="message" class="error">
				<p><strong>WooCommerce Products Visibility by User Roles plugin is inactive.</strong> The <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce plugin</a> must be active for this plugin to work. Please install &amp; activate WooCommerce »</p></div>';
			echo wp_kses(__($afpvu_woo_check, 'addify_products_visibility'), $afpvu_allowed_tags);
		}

		public function afpvu_HOPS_Compatibility() {

			if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
			}
		}

		public function afpvu_global_constents_vars() {

			if (!defined('AFPVU_URL')) {
				define('AFPVU_URL', plugin_dir_url(__FILE__));
			}

			if (!defined('AFPVU_BASENAME')) {
				define('AFPVU_BASENAME', plugin_basename(__FILE__));
			}

			if (!defined('AFPVU_PLUGIN_DIR')) {
				define('AFPVU_PLUGIN_DIR', plugin_dir_path(__FILE__));
			}
		}

		public function afpvu_init() {

			if (function_exists('load_plugin_textdomain')) {
				load_plugin_textdomain('addify_products_visibility', false, dirname(plugin_basename(__FILE__)) . '/languages/');
			}
		}

		public function afpvu_add_quote_page() {

			if (null == get_page_by_path('af-product-visibility')) {

				$new_page = array(
					'post_status'    => 'publish',
					'post_type'      => 'page',
					'post_author'    => 1,
					'post_name'      => esc_html__('af-product-visibility', 'addify_products_visibility'),
					'post_title'     => esc_html__('Products Visibility', 'addify_products_visibility'),
					'post_content'   => '[addify-product-visibility-page]',
					'post_parent'    => 0,
					'comment_status' => 'closed',
				);

				$page_id = wp_insert_post($new_page);

				update_option('addify_pvu_page_id', $page_id);
			} else {
				$page_id = get_page_by_path('af-product-visibility');
				update_option('addify_pvu_page_id', $page_id->ID);
			}
		}
	}

	new Addify_Products_Visibility();

}
