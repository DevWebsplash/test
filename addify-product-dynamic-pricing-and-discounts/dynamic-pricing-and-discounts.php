<?php
/**
 * Plugin Name:       Product Dynamic Pricing and Discounts
 * Requires Plugins:  woocommerce
 * Plugin URI:        https://woocommerce.com/products/product-dynamic-pricing-and-discounts/
 * Description:       Offer free gifts, adjust product prices, offer cart level discounts based on order quantity, amount, user roles and more.
 * Version:           1.2.0
 * Author:            Addify
 * Developed By:      Addify
 * Author URI:        https://woocommerce.com/vendor/addify/
 * Support:           https://woocommerce.com/vendor/addify/
 * License:           GNU General Public License v3.0
 * License            URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path:       /languages
 * Text Domain:       woo-af-drpc
 * WC requires at least: 3.0.9
 * WC tested up to: 9.*.*
 * Woo: 18734000100719:1eb0f8ca634569f89f015fa229a0d0ba
 *
 * @package woo-af-drpc
 * Woo: 18734000100719:1eb0f8ca634569f89f015fa229a0d0ba

 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AF_Discount_Main {

	public function __construct() {
		
		$this->addify_dynamic_pricing_nonce_global_constents_vars();

		add_action( 'init', array( $this, 'addf_drpc_add_actions' ) );
		add_action('before_woocommerce_init', array( $this, 'af_cp_HOPS_Compatibility' ));
		add_action( 'plugins_loaded', array( $this, 'af_cp_plugin_check' ) );

		register_activation_hook(__FILE__, array( $this, 'af_cp_register_plugin_create_settings' ));
	}

	public function addf_drpc_add_actions() {
		if ( defined( 'WC_PLUGIN_FILE' ) ) {

			$this->addf_reg_price_calculator_post_type();

			add_action( 'wp_loaded', array( $this, 'addify_dynamic_pricing_nonce_load_text_domain' ) );
			add_action( 'woocommerce_remove_cart_item', array( $this, 'af_cp_remove_cart_item' ), 10, 2 );

			include_once ADD_DISC_RPC_DIR . '/front/class-af-front-ajax-controller.php';
			include_once ADD_DISC_RPC_DIR . '/admin/general_functions/addf-drpc-general-functions.php';     

			if (is_admin() ) {
				include_once ADD_DISC_RPC_DIR . '/admin/class-af-discount-admin.php';
			} else {
				include_once ADD_DISC_RPC_DIR . '/front/class-af-product-discount-front.php';
				include_once ADD_DISC_RPC_DIR . '/front/class-af-cart-discount-front.php';
			}
		}
	}

	public function af_cp_register_plugin_create_settings() {
		if (!get_option('addf_drpc_enable_table_save_column')) {
			update_option('addf_drpc_enable_table_save_column', 'yes');
		}
	
		if (!get_option('addf_drpc_template_type')) {
			update_option('addf_drpc_template_type', 'table');
		}
	
		if (!get_option('addf_drpc_template_heading_text') || '' == get_option('addf_drpc_template_heading_text')) {
			update_option('addf_drpc_template_heading_text', 'Select your Deal');
		} 
		
		if (!get_option('addf_drpc_template_heading_font_size') || '' == get_option('addf_drpc_template_heading_font_size')) {
			update_option('addf_drpc_template_heading_font_size', '28');
		} 
	
		if (!get_option('addf_drpc_table_header_background_color')) {
			update_option('addf_drpc_table_header_background_color', '#FFFFFF');
		}
	
		if (!get_option('addf_drpc_table_odd_rows_background_color')) {
			update_option('addf_drpc_table_odd_rows_background_color', '#FFFFFF');
		}
	
		if (!get_option('addf_drpc_table_even_rows_background_color')) {
			update_option('addf_drpc_table_even_rows_background_color', '#FFFFFF');
		} 
	
		if (!get_option('addf_drpc_table_header_text_color')) {
			update_option('addf_drpc_table_header_text_color', '#000000');
		}
	
		if (!get_option('addf_drpc_table_odd_rows_text_color')) {
			update_option('addf_drpc_table_odd_rows_text_color', '#000000');
		} 
	
		if (!get_option('addf_drpc_table_even_rows_text_color')) {
			update_option('addf_drpc_table_even_rows_text_color', '#000000');
		} 
	
		if (!get_option('addf_drpc_enable_table_border')) {
			update_option('addf_drpc_enable_table_border', 'yes');
		} 
	
		if (!get_option('addf_drpc_table_border_color')) {
			update_option('addf_drpc_table_border_color', '#CFCFCF');
		} 
	
		if (!get_option('addf_drpc_table_header_font_size') || '' == get_option('addf_drpc_table_header_font_size')) {
			update_option('addf_drpc_table_header_font_size', '18');
		} 
	
		if (!get_option('addf_drpc_table_row_font_size') || '' == get_option('addf_drpc_table_row_font_size')) {
			update_option('addf_drpc_table_row_font_size', '16');
		} 
	
		if (!get_option('addf_drpc_list_border_color')) {
			update_option('addf_drpc_list_border_color', '#95B0EE');
		} 
	
		if (!get_option('addf_drpc_list_background_color')) {
			update_option('addf_drpc_list_background_color', '#FFFFFF');
		}  
	
		if (!get_option('addf_drpc_list_text_color')) {
			update_option('addf_drpc_list_text_color', '#000000');
		}  
	
		if (!get_option('addf_drpc_selected_list_background_color')) {
			update_option('addf_drpc_selected_list_background_color', '#DFEBFF');
		}  
		
		if (!get_option('addf_drpc_selected_list_text_color')) {
			update_option('addf_drpc_selected_list_text_color', '#000000');
		} 
	
		if (!get_option('addf_drpc_card_border_color')) {
			update_option('addf_drpc_card_border_color', '#A3B39E');
		}  
	
		if (!get_option('addf_drpc_card_background_color')) {
			update_option('addf_drpc_card_background_color', '#FFFFFF');
		}  
	
		if (!get_option('addf_drpc_card_text_color')) {
			update_option('addf_drpc_card_text_color', '#000000');
		}  
	
		if (!get_option('addf_drpc_selected_card_border_color')) {
			update_option('addf_drpc_selected_card_border_color', '#27CA34');
		}  
	
		if (!get_option('addf_drpc_sale_tag_background_color')) {
			update_option('addf_drpc_sale_tag_background_color', '#FF0000');
		} 
		
		if (!get_option('addf_drpc_sale_tag_text_color')) {
			update_option('addf_drpc_sale_tag_text_color', '#FFFFFF');
		} 
	}

	public function af_cp_HOPS_Compatibility() {

		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}

	public function af_cp_plugin_check() {

		if ( ! is_multisite() && ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

			add_action( 'admin_notices', array( $this, 'afcp_wc_plugin_loaded' ) );

		}
	}
	public function afcp_wc_plugin_loaded() {
			// Deactivate the plugin.
			
			deactivate_plugins( __FILE__ );
			$afpvu_woo_check = '<div id="message" class="error">
				<p><strong>' . esc_html__( 'Product Dynamic Pricing and Discounts is inactive.', 'woo-af-drpc' ) . '</strong> The <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce plugin</a> ' . esc_html__( 'must be active for this plugin to work. Please install &amp; activate WooCommerce.', 'woo-af-drpc' ) . ' »</p></div>';
			echo wp_kses_post( $afpvu_woo_check );
	}


	/**
	 * Load Text domain.
	 */
	public function addify_dynamic_pricing_nonce_load_text_domain() {
		if ( function_exists( 'load_plugin_textdomain' ) ) {
			load_plugin_textdomain( 'woo-af-drpc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}
	}
	public function addify_dynamic_pricing_nonce_global_constents_vars() {
		if ( ! defined( 'ADDF_DISC_RPC_URL' ) ) {
			define( 'ADDF_DISC_RPC_URL', plugin_dir_url( __FILE__ ) );
		}

		if ( ! defined( 'ADD_DISC_RPC_BASENAME' ) ) {
			define( 'ADD_DISC_RPC_BASENAME', plugin_basename( __FILE__ ) );
		}
		if ( ! defined( 'ADD_DISC_RPC_DIR' ) ) {
			define( 'ADD_DISC_RPC_DIR', plugin_dir_path( __FILE__ ) );
		}
	}
	public function addf_reg_price_calculator_post_type() {
		$label_product = array(
			'name'                => __('Product Pricing Rules', 'woo-af-drpc'),
			'singular_name'       => __('Product Pricing Rules', 'woo-af-drpc'),
			'add_new'             => __('Add New Product Rule', 'woo-af-drpc'),
			'add_new_item'        => __('Add Product Rule', 'woo-af-drpc'),
			'edit_item'           => __('Edit Product Rule', 'woo-af-drpc'),
			'new_item'            => __('New Product Rule', 'woo-af-drpc'),
			'view_item'           => __('View Product Rule', 'woo-af-drpc'),
			'search_items'        => __('Search Product Rule', 'woo-af-drpc'),
			'exclude_from_search' => true,
			'not_found'           => __('No rule found', 'woo-af-drpc'),
			'not_found_in_trash'  => __('No rule found in trash', 'woo-af-drpc'),
			'parent_item_colon'   => '',
			'all_items'           => __('Product Pricing Rules', 'woo-af-drpc'),
			'menu_name'           => __('Dynamic Pricing & Discounts', 'woo-af-drpc'),
			'attributes'          => esc_html__( 'Rule order', 'woo-af-drpc' ),
		);
		$args_product  = array(
			'labels'             => $label_product,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=product',
			'query_var'          => true,
			'capability_type'    => 'post', 
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 30,
			'rewrite'            => array(
				'slug'       => 'woo-af-drpc-rule',
				'with_front' =>false,
			),
			'supports'           => array( 'title', 'page-attributes' ),
		);
		register_post_type( 'af_disc_p_rules', $args_product );
		$label_cart = array(
			'name'                => __('Cart Discount Rules', 'woo-af-drpc'),
			'singular_name'       => __('Cart Discount Rules', 'woo-af-drpc'),
			'add_new'             => __('Add New Cart Rule', 'woo-af-drpc'),
			'add_new_item'        => __('Add Cart Rule', 'woo-af-drpc'),
			'edit_item'           => __('Edit Cart Rule', 'woo-af-drpc'),
			'new_item'            => __('New Cart Rule', 'woo-af-drpc'),
			'view_item'           => __('View Cart Rule', 'woo-af-drpc'),
			'search_items'        => __('Search Cart Rule', 'woo-af-drpc'),
			'exclude_from_search' => true,
			'not_found'           => __('No rule found', 'woo-af-drpc'),
			'not_found_in_trash'  => __('No rule found in trash', 'woo-af-drpc'),
			'parent_item_colon'   => '',
			'all_items'           => __('Cart Discounts Rules', 'woo-af-drpc'),
			'menu_name'           => __('Dynamic Pricing & Discounts', 'woo-af-drpc'),
			'attributes'          => esc_html__( 'Rule order', 'woo-af-drpc' ),
		);
		$args_cart  = array(
			'labels'             => $label_cart,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => 'woocommerce',
			'query_var'          => true,
			'capability_type'    => 'post', 
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 30,
			'rewrite'            => array(
				'slug'       => 'woo-af-drpc-rule',
				'with_front' =>false,
			),
			'supports'           => array( 'title', 'page-attributes' ),
		);
		register_post_type( 'af_dis_cart_rule', $args_cart );
	}

	public function af_cp_remove_cart_item( $cart_item_key, $cart ) {
		$cart_current_qty = $cart->get_cart()[ $cart_item_key ]['quantity'];
		if ( $cart->get_cart_contents_count() == $cart_current_qty ) {
			WC()->session->set('af_disc_product_rule_remove_gift_list', array() );
			WC()->session->set('af_disc_cart_rule_remove_gift_list', array() );
			return;
		}
		$this->af_cp_remove_cart_item_cart_rules( $cart_item_key, $cart);
		if ( WC()->session->get('af_disc_product_rule_remove_gift_list') ) {
			$remove_gift_array = (array) WC()->session->get('af_disc_product_rule_remove_gift_list');
			if ( array_key_exists( $cart_item_key , $remove_gift_array ) ) {
				$remove_gift_array[ $cart_item_key ] = array();
				WC()->session->set('af_disc_product_rule_remove_gift_list', $remove_gift_array );
			}
		}
		foreach ($cart->get_cart() as $value) {
			if ( $cart_item_key != $value['key'] ) {
				continue;
			}
			if (array_key_exists('addf_disc_rpc_product_rule_gift_item', $value) ) {
				$gift_product_arr  = $value['addf_disc_rpc_product_rule_gift_item'];
				$parent_product    = $gift_product_arr['key_id'];
				$gift_prod_min_qty = $gift_product_arr['prod_min_qty'];
				if ( $this->check_if_parent_item_exists_in_cart( $parent_product , $gift_prod_min_qty ) ) {
					if ( WC()->session->get('af_disc_product_rule_remove_gift_list') ) {
						$remove_gift_array = (array) WC()->session->get('af_disc_product_rule_remove_gift_list');
					} else {
						$remove_gift_array = array();
					}
					$remove_gift_array[ $parent_product ][] = array(
						'rule_id'         => $gift_product_arr['rule_id'],
						'parent_id'       => $parent_product,
						'gift_product_id' => $value['data']->get_id(),
						'min_qty'         => $gift_product_arr['prod_min_qty'],
						'gift_qty'        => $gift_product_arr['gift_quantity'],
					);
					WC()->session->set('af_disc_product_rule_remove_gift_list', $remove_gift_array );
				}
			}
		}
	}

	public function check_if_parent_item_exists_in_cart( $key, $min_qty ) {
		$cart = wc()->cart->get_cart();
		foreach ($cart as $value) {
			if ( ( $key == $value['key'] ) && ( $min_qty <= $value['quantity'] ) ) {
				return true;
			}
		}
		return false;
	}

	// remove item from cart
	public function af_cp_remove_cart_item_cart_rules( $cart_item_key, $cart ) {
		$cart_gift_array = array();
		foreach ($cart->get_cart() as $value) {
			if ( array_key_exists('addf_disc_rpc_product_rule_gift_item', $value) || array_key_exists('addf_disc_rpc_cart_rule_gift_item', $value)) {
				continue;
			}
			$cart_gift_array[] = $value['data']->get_id();
		}
		if ( empty($cart_gift_array) ) {
			WC()->session->set('af_disc_cart_rule_remove_gift_list', array() );
			return;
		}
		if ( WC()->session->get('af_disc_cart_rule_remove_gift_list') ) {
			$remove_gift_array = (array) WC()->session->get('af_disc_cart_rule_remove_gift_list');
			foreach ( $remove_gift_array as $key => $value) {
				$all_products       = $this->addf_disc_rpc_merge_all_product_cats( $key );
				$reset_session_list = true;
				foreach ( $cart_gift_array as $cart_product_value ) {
					if ( in_array( $cart_product_value , $all_products ) ) {
						$reset_session_list = false;
					}
				}
				if ($reset_session_list) {
					$remove_gift_array[ $key ] = array();
				}
			}
			WC()->session->set('af_disc_cart_rule_remove_gift_list', $remove_gift_array );
		}

		if ( $cart->cart_contents[ $cart_item_key ]) {
			$data = $cart->cart_contents[ $cart_item_key ];
			if ( array_key_exists( 'addf_disc_rpc_cart_rule_gift_item' , $data ) ) {
				if ( $data['addf_disc_rpc_cart_rule_gift_item']) {
					$gift_product_arr = $data['addf_disc_rpc_cart_rule_gift_item'];
					$rule_id          = $gift_product_arr['rule_id'];
					$gift_product     = $gift_product_arr['gift_product'];
					$gift_key         = $gift_product_arr['gift_key'];
					$gift_id_array    = $gift_product_arr['gift_id_array'];
					$min_qty          = $gift_id_array['min_qty'];
					$max_qty          = $gift_id_array['max_qty'];
					if ( $this->check_if_item_have_more_than_required_qty( $cart , $rule_id , $min_qty , $max_qty ) ) {
						return;
					}
					if ( WC()->session->get('af_disc_cart_rule_remove_gift_list') ) {
						$session_array = (array) WC()->session->get('af_disc_cart_rule_remove_gift_list');
					} else {
						$session_array = array();
					}
					$session_array[ $rule_id ][] = array(
						'rule_id'      => $rule_id,
						'gift_product' => $gift_product,
						'gift_key'     => $gift_key,
					);
					WC()->session->set('af_disc_cart_rule_remove_gift_list', $session_array );
				}
			}
		}
	}

	public function check_if_item_have_more_than_required_qty( $cart, $rule_id, $min_qty, $max_qty ) {
		$all_products                       = (array) $this->addf_disc_rpc_merge_all_product_cats( $rule_id );
		$quantity                           = 0;
		$discount_choice                    = get_post_meta( $rule_id , 'addf_drpc_discount_type_choice' , true );
		$addf_disc_rpc_product_selection_op = get_post_meta( $rule_id, 'addf_disc_rpc_product_selection_op' , true );
		foreach ( $cart->get_cart() as $key => $value ) {
			if (array_key_exists('addf_disc_rpc_product_rule_gift_item', $value) || array_key_exists('addf_disc_rpc_cart_rule_gift_item', $value)) {
				continue;
			}
			$item_id    = $value['data']->get_id();
			$product_id = $value['data']->get_id();
			if ('specific' == $addf_disc_rpc_product_selection_op) {
				$all_products = $this->addf_disc_rpc_merge_all_product_cats($value);
				$cart_product = $value['data'];
				if ( !in_array( $item_id , $all_products ) ) {
					if ( $cart_product->is_type('variable') ) {
						if ( ( !in_array( $value['variation_id'] , $all_products ) ) && ( !in_array( $value['product_id'] , $all_products ) ) ) {
							continue;
						}
					}
				}
			}
			if ( 'gift_on_price' == $discount_choice ) {
				$quantity += $value['quantity'] * $value['data']->get_price();
			} else {
				$quantity += $value['quantity'];
			}
		}
		if ( ( '' != $min_qty ) && ( '' != $max_qty ) ) {
			if ( ( $quantity >= $min_qty ) && ( $quantity <= $max_qty ) ) {
				return false;
			}
		} elseif ( ( '' != $min_qty ) && ( '' == $max_qty ) ) {
			if ( ( $quantity >= $min_qty ) ) {
				return false;
			}
		} elseif ( ( '' == $min_qty ) && ( '' != $max_qty ) ) {
			if ( ( $quantity <= $max_qty ) ) {
				return false;
			}
		}
		return true;
	}

	// merging all products in rule
	public function addf_disc_rpc_merge_all_product_cats( $rule_id ) {
		$addf_disc_rpc_cat      = get_post_meta( $rule_id , 'addf_disc_rpc_categories' , true );
		$addf_disc_rpc_products = (array) get_post_meta( $rule_id , 'addf_disc_rpc_products' , true );
		if ( is_array($addf_disc_rpc_cat)) {
			$addf_cats              = array( 
				'numberposts' => -1,    
				'post_status' => array( 'publish' ),
				'post_type'   => array( 'product' ), 
				'fields'      => 'ids',
			);
			$addf_cats['tax_query'] = array( 
				array( 
					'taxonomy' => 'product_cat', 
					'field'    => 'id', 
					'terms'    => $addf_disc_rpc_cat, 
					'operator' => 'IN', 
				),
			);
			$all_product_cats       =  get_posts($addf_cats);
			$all_products           =  array_merge( (array) $all_product_cats, (array) $addf_disc_rpc_products );
			return $all_products;
		} else {
			return $addf_disc_rpc_products;
		}
	}
}
new AF_Discount_Main();
