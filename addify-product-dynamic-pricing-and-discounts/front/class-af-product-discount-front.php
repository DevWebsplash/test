<?php

defined('ABSPATH') || exit;

if (! class_exists('AF_Product_Discount_Front')) {

	class AF_Product_Discount_Front {

		public $addf_drpc_enable_template_heading;
		public $addf_drpc_template_heading_text;
		public $addf_drpc_template_heading_font_size;
		public $addf_drpc_enable_template_icon;
		public $addf_drpc_template_icon;
		public $addf_drpc_template_font_family;

		//table settings
		public $addf_drpc_enable_table_save_column;
		public $addf_drpc_table_header_background_color;
		public $addf_drpc_table_header_text_color;
		public $addf_drpc_table_odd_rows_background_color;
		public $addf_drpc_table_odd_rows_text_color;
		public $addf_drpc_table_even_rows_background_color;
		public $addf_drpc_table_even_rows_text_color;
		public $addf_drpc_enable_table_border;
		public $addf_drpc_table_border_color;
		public $addf_drpc_table_header_font_size;
		public $addf_drpc_table_row_font_size;

		//list settings
		public $addf_drpc_list_border_color;
		public $addf_drpc_list_background_color;
		public $addf_drpc_list_text_color;
		public $addf_drpc_selected_list_background_color;
		public $addf_drpc_selected_list_text_color;
	
		//card settings
		public $addf_drpc_card_border_color;
		public $addf_drpc_card_background_color;
		public $addf_drpc_card_text_color;
		public $addf_drpc_selected_card_border_color;
		public $addf_drpc_enable_sale_tag;
		public $addf_drpc_sale_tag_background_color;
		public $addf_drpc_sale_tag_text_color;


	
		public function __construct() {
			// All product rules
			$this->addf_disc_rpc_product_rules_obj = $this->addf_disc_rpc_product_all_rules_cb();

			// Adding css
			add_action('wp_enqueue_scripts', array( $this, 'add_scripts' ));

			// Calculate price before total
			add_action('woocommerce_before_calculate_totals', array( $this, 'apply_discounts_before_cart_totals' ), 90, 1);

			// show notices on product page
			add_action('woocommerce_before_single_product', array( $this, 'show_discount_notices' ) );

			// disable quantity for gift products
			add_filter('woocommerce_cart_item_quantity', array( $this, 'disable_gift_quantity_in_cart' ), 10, 2);

			// show gift product in cart
			add_filter('woocommerce_get_item_data', array( $this, 'gift_label_in_cart' ), 10, 2);

			// show gift product after checkout
			add_action('woocommerce_checkout_create_order_line_item', array( $this, 'gift_label_after_checkout' ), 10, 4);

			// change price on product page
			add_filter( 'woocommerce_get_price_html', array( $this, 'product_price_html' ) , 100 , 2 );

			// change price in cart basket
			add_filter('woocommerce_cart_item_price', array( $this, 'show_price_cart_basket' ), 100, 3);

			// table location for variable / simple produtc 
			if ('below_price' == get_option('addf_drpc_option_pricing_table_location')) {
				add_action('woocommerce_single_product_summary', array( $this, 'show_table_product_page' ), 12);
			} elseif ('below_cart' == get_option('addf_drpc_option_pricing_table_location')) {
				add_action('woocommerce_after_add_to_cart_button', array( $this, 'show_table_product_page' ));
			} else {
				add_action('woocommerce_before_add_to_cart_button', array( $this, 'show_table_product_page' ));
			}
			// Table for variation product
			add_filter('woocommerce_available_variation', array( $this, 'show_table_for_variation' ), 10, 3);

			//template general settings
			if (!empty(get_option('addf_drpc_enable_template_heading'))) {
				$this->addf_drpc_enable_template_heading = get_option( 'addf_drpc_enable_template_heading');    
			} else {
				$this->addf_drpc_enable_template_heading = '';
			}
			if (!empty(get_option('addf_drpc_template_heading_text'))) {
				$this->addf_drpc_template_heading_text = get_option( 'addf_drpc_template_heading_text');    
			} else {
				$this->addf_drpc_template_heading_text = 'Select your Deal';
			}
			if (!empty(get_option('addf_drpc_template_heading_font_size'))) {
				$this->addf_drpc_template_heading_font_size = get_option( 'addf_drpc_template_heading_font_size');    
			} else {
				$this->addf_drpc_template_heading_font_size = '28';
			}
			if (!empty(get_option('addf_drpc_enable_template_icon'))) {
				$this->addf_drpc_enable_template_icon = get_option( 'addf_drpc_enable_template_icon');    
			} else {
				$this->addf_drpc_enable_template_icon = '';
			}
			if (!empty(get_option('addf_drpc_template_icon'))) {
				$this->addf_drpc_template_icon = get_option( 'addf_drpc_template_icon');    
			} else {
				$this->addf_drpc_template_icon = ADDF_DISC_RPC_URL . '/includes/images/fire.png';
			}

			if (!empty(get_option('addf_drpc_template_font_family'))) {
				$this->addf_drpc_template_font_family = get_option( 'addf_drpc_template_font_family');    
			} else {
				$this->addf_drpc_template_font_family = '';
			}

			if (!empty(get_option('addf_drpc_enable_table_save_column'))) {
				$this->addf_drpc_enable_table_save_column = get_option( 'addf_drpc_enable_table_save_column');    
			} else {
				$this->addf_drpc_enable_table_save_column = '';
			}

			if (!empty(get_option('addf_drpc_table_header_background_color'))) {
				$this->addf_drpc_table_header_background_color = get_option( 'addf_drpc_table_header_background_color');    
			} else {
				$this->addf_drpc_table_header_background_color = '#ffffff';
			}
	
			if (!empty(get_option('addf_drpc_table_header_text_color'))) {
				$this->addf_drpc_table_header_text_color = get_option( 'addf_drpc_table_header_text_color');    
			} else {
				$this->addf_drpc_table_header_text_color = '#000000';
			}

			if (!empty(get_option('addf_drpc_table_odd_rows_background_color'))) {
				$this->addf_drpc_table_odd_rows_background_color = get_option( 'addf_drpc_table_odd_rows_background_color');    
			} else {
				$this->addf_drpc_table_odd_rows_background_color = '#ffffff';
			}
			
			if (!empty(get_option('addf_drpc_table_odd_rows_text_color'))) {
				$this->addf_drpc_table_odd_rows_text_color = get_option( 'addf_drpc_table_odd_rows_text_color');    
			} else {
				$this->addf_drpc_table_odd_rows_text_color = '#000000';
			}

			if (!empty(get_option('addf_drpc_table_even_rows_background_color'))) {
				$this->addf_drpc_table_even_rows_background_color = get_option( 'addf_drpc_table_even_rows_background_color');  
			} else {
				$this->addf_drpc_table_even_rows_background_color = '#ffffff';
			}

			if (!empty(get_option('addf_drpc_table_even_rows_text_color'))) {
				$this->addf_drpc_table_even_rows_text_color = get_option( 'addf_drpc_table_even_rows_text_color');  
			} else {
				$this->addf_drpc_table_even_rows_text_color = '#000000';
			}

			if (!empty(get_option('addf_drpc_enable_table_border'))) {
				$this->addf_drpc_enable_table_border = get_option( 'addf_drpc_enable_table_border');  
			} else {
				$this->addf_drpc_enable_table_border = 'yes';
			}

			if (!empty(get_option('addf_drpc_table_border_color'))) {
				$this->addf_drpc_table_border_color = get_option( 'addf_drpc_table_border_color');  
			} else {
				$this->addf_drpc_table_border_color = '#cfcfcf';
			}

			if (!empty(get_option('addf_drpc_table_header_font_size'))) {
				$this->addf_drpc_table_header_font_size = get_option( 'addf_drpc_table_header_font_size');    
			} else {
				$this->addf_drpc_table_header_font_size = '18';
			}

			if (!empty(get_option('addf_drpc_table_row_font_size'))) {
				$this->addf_drpc_table_row_font_size = get_option( 'addf_drpc_table_row_font_size');    
			} else {
				$this->addf_drpc_table_row_font_size = '16';
			}
			
			//list design
			if (!empty(get_option('addf_drpc_list_border_color'))) {
				$this->addf_drpc_list_border_color = get_option( 'addf_drpc_list_border_color');  
			} else {
				$this->addf_drpc_list_border_color = '#95b0ee';
			}

			if (!empty(get_option('addf_drpc_list_background_color'))) {
				$this->addf_drpc_list_background_color = get_option( 'addf_drpc_list_background_color');  
			} else {
				$this->addf_drpc_list_background_color = '#ffffff';
			}

			if (!empty(get_option('addf_drpc_list_text_color'))) {
				$this->addf_drpc_list_text_color = get_option( 'addf_drpc_list_text_color');  
			} else {
				$this->addf_drpc_list_text_color = '#000000';
			}

			if (!empty(get_option('addf_drpc_selected_list_background_color'))) {
				$this->addf_drpc_selected_list_background_color = get_option( 'addf_drpc_selected_list_background_color');  
			} else {
				$this->addf_drpc_selected_list_background_color = '#dfebff';
			}

			if (!empty(get_option('addf_drpc_selected_list_text_color'))) {
				$this->addf_drpc_selected_list_text_color = get_option( 'addf_drpc_selected_list_text_color');  
			} else {
				$this->addf_drpc_selected_list_text_color = '#000000';
			}

			//card design

			if (!empty(get_option('addf_drpc_card_border_color'))) {
				$this->addf_drpc_card_border_color = get_option( 'addf_drpc_card_border_color');  
			} else {
				$this->addf_drpc_card_border_color = '#a3b39e';
			}

			if (!empty(get_option('addf_drpc_card_background_color'))) {
				$this->addf_drpc_card_background_color = get_option( 'addf_drpc_card_background_color');  
			} else {
				$this->addf_drpc_card_background_color = '#ffffff';
			}

			if (!empty(get_option('addf_drpc_card_text_color'))) {
				$this->addf_drpc_card_text_color = get_option( 'addf_drpc_card_text_color');  
			} else {
				$this->addf_drpc_card_text_color = '#000000';
			}

			if (!empty(get_option('addf_drpc_selected_card_border_color'))) {
				$this->addf_drpc_selected_card_border_color = get_option( 'addf_drpc_selected_card_border_color');  
			} else {
				$this->addf_drpc_selected_card_border_color = '#27ca34';
			}
			
			if (!empty(get_option('addf_drpc_enable_sale_tag'))) {
				$this->addf_drpc_enable_sale_tag = get_option( 'addf_drpc_enable_sale_tag');  
			} else {
				$this->addf_drpc_enable_sale_tag = '';
			}

			if (!empty(get_option('addf_drpc_sale_tag_background_color'))) {
				$this->addf_drpc_sale_tag_background_color = get_option( 'addf_drpc_sale_tag_background_color');  
			} else {
				$this->addf_drpc_sale_tag_background_color = '#ff0000';
			}

			if (!empty(get_option('addf_drpc_sale_tag_text_color'))) {
				$this->addf_drpc_sale_tag_text_color = get_option( 'addf_drpc_sale_tag_text_color');  
			} else {
				$this->addf_drpc_sale_tag_text_color = '#ffffff';
			}
		}

		// include css
		public function add_scripts() {
			// css
			wp_enqueue_style('addf_drpc_css', plugins_url('../includes/css/addf-drpc-style.css', __FILE__), false, '1.0.0');
		}

		// Getting all eligible rules
		public function addf_disc_rpc_product_all_rules_cb() {
			$disc_all_rules = array(
				'post_type'        => 'af_disc_p_rules',
				'post_status'      => 'publish',
				'numberposts'      =>   '-1',
				'fields'           => 'ids',
				'orderby'          => 'menu_order',
				'order'            => 'ASC',
				'suppress_filters' => true,
				'meta_query'       => array(
					array(
						'key'     => 'addf_disc_rpc_start_time',
						'value'   => gmdate('Y-m-d'),
						'compare' => '<=',
					),
				),
			);
			$all_rule_posts = get_posts($disc_all_rules);
			$all_posts      = array();
			foreach ($all_rule_posts as $key => $value) {
				// check days in rule
				if ($this->addf_disc_rpc_verify_curr_cust_user_roles_date_of_rule($value)) {
					continue;
				}
				$end_date = get_post_meta($value, 'addf_disc_rpc_end_time', true);
				
				if (''!= $end_date) {
					if ( $end_date < gmdate('Y-m-d')) {
						continue;
					}
				}
				$all_posts[] = $value;
			}
			return $all_posts;
		}

		// Checking days to apply the rules
		public function addf_disc_rpc_verify_curr_cust_user_roles_date_of_rule( $value ) {
			$addf_disc_rpc_days_radio = get_post_meta($value, 'addf_disc_rpc_days_radio', true);
			$addf_disc_week_days_arr  = (array) get_post_meta($value, 'addf_disc_week_days_arr', true);
			$today                    = gmdate('l');
			if ( 'all' == $addf_disc_rpc_days_radio ) {
				return false;
			}
			if (( 'specific' == $addf_disc_rpc_days_radio )  && ( !in_array($today, $addf_disc_week_days_arr) ) && ( !empty($addf_disc_week_days_arr) )) {
				return true;
			}
			return false;
		}

		// checking minimum amount spent by user for single rule
		public function addf_disc_rpc_user_spent_amount_check( $value ) {
			$addf_drpc_disc_min_spent_amount = get_post_meta($value, 'addf_drpc_disc_min_spent_amount', true);
			$rule_min_spent_amount           = get_post_meta($value, 'addf_disc_rpc_min_spent_amount', true);
			if ( '' == $rule_min_spent_amount ) {
				return false;
			}
			$min_spent_amount = floatval($rule_min_spent_amount);
			if ('up_till_now' == $addf_drpc_disc_min_spent_amount) {
				$total_spent = wc_get_customer_total_spent(get_current_user_id());
				if ( ( $total_spent - $min_spent_amount ) < 0 ) {
					return true;
				}
			} elseif ('start_end_date' == $addf_drpc_disc_min_spent_amount) {
				$addf_disc_rpc_min_start_date = get_post_meta($value, 'addf_disc_rpc_min_start_date', true);
				$addf_disc_rpc_min_end_date   = get_post_meta($value, 'addf_disc_rpc_min_end_date', true);

				$customer_orders = wc_get_orders(array(
					'customer_id' => get_current_user_id(),
					'limit'       => -1, 
					'status'      => array( 'wc-completed', 'wc-processing' ),
					'orderby'     => 'date',
					'order'       => 'DESC',
					'return'      => 'ids', 
				));             
			
				$calc_amount_spent            = 0;
				foreach ($customer_orders as $customer_order) {
					$order                 = wc_get_order($customer_order);
					$spent_amount_in_order =  $order->get_date_created()->date_i18n('Y-m-d');
					if ( ( '' != $addf_disc_rpc_min_start_date ) && ( '' != $addf_disc_rpc_min_end_date ) ) {
						if ( ( $addf_disc_rpc_min_start_date <= $spent_amount_in_order ) && ( $addf_disc_rpc_min_end_date >= $spent_amount_in_order ) ) {
							$calc_amount_spent += $order->get_total();
						}
					}
					if ( ( '' != $addf_disc_rpc_min_start_date ) && ( '' == $addf_disc_rpc_min_end_date ) ) {
						if ( ( $addf_disc_rpc_min_start_date <= $spent_amount_in_order ) ) {
							$calc_amount_spent += $order->get_total();
						}
					}
					if ( ( '' == $addf_disc_rpc_min_start_date ) && ( '' != $addf_disc_rpc_min_end_date ) ) {
						if ( ( $addf_disc_rpc_min_end_date >= $spent_amount_in_order ) ) {
							$calc_amount_spent += $order->get_total();
						}
					}
					if ( ( '' == $addf_disc_rpc_min_start_date ) && ( '' == $addf_disc_rpc_min_end_date ) ) {
						$calc_amount_spent += $order->get_total();
					}
				}
				if (( $calc_amount_spent - $min_spent_amount ) < 0) {
					return true;
				}
			}
			return false;
		}

		// Merging categories and products
		public function addf_disc_rpc_merge_all_product_cats( $rule_id ) {
			$addf_disc_rpc_cat      = get_post_meta($rule_id, 'addf_disc_rpc_categories', true);
			$addf_disc_rpc_products = (array) get_post_meta($rule_id, 'addf_disc_rpc_products', true);
			if (is_array($addf_disc_rpc_cat)) {
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
				$all_products           =  array_merge((array) $all_product_cats, (array) $addf_disc_rpc_products);
				return $all_products;
			} else {
				return $addf_disc_rpc_products;
			}
		}

		// showing notices on product page
		public function show_discount_notices() {
			if ( !is_cart() ) {
				$this->apply_discounts_before_cart_totals( WC()->cart );
			}
		}

		public function af_free_gift_price_fn() {
			$cart = wc()->cart->get_cart();
			foreach ($cart as $key => $value) {
				if ( ( array_key_exists('addf_disc_rpc_product_rule_gift_item', $value) ) || ( array_key_exists( 'addf_disc_rpc_cart_rule_gift_item' , $value ) ) ) {
					$value['data']->set_price(0);
				}
			}
		}

		// Adding Discount and gift to cart
		public function apply_discounts_before_cart_totals( $cart_object ) {
			if ( is_user_logged_in() ) {
				$user           = wp_get_current_user();
				$curr_user_role = current( $user->roles );
			} else {
				$curr_user_role = 'guest';
			}
			$curr_user_id               = get_current_user_id();
			$product_setting_price_type = get_option('addf_drpc_option_rules_priority');
			$all_cart_object            = $cart_object->get_cart();

			$gift_notice_info_arr            = array();
			$before_gift_product_info        = array();
			$after_gift_product_info         = array();
			$addf_disc_rpc_cart_all_notices  = array();
			$addf_disc_rpc_cart_cong_notices = array();
			$addf_disc_rpc_cart_gift_notices = array();
		  
			// cart loop
			foreach ($all_cart_object as $key_cart => $value_cart) {

				$all_selected_rule  = array();
				$must_selected_rule = array();
				$cart_product_id    = $value_cart['product_id'];
				$cart_variation_id  = $value_cart['variation_id'];
				$cart_product       = wc_get_product($cart_product_id);
				$cart_prod_id       = $cart_product_id;
				if ($cart_product->is_type('variable')) {
					$cart_prod_id = $cart_variation_id;
				}
				// continue if the current product is a gift from cart rule
				$this->af_free_gift_price_fn();
				if (array_key_exists('addf_disc_rpc_cart_rule_gift_item', $value_cart) ) {
					continue;
				}
				// check if current product is a gift from product rule
				if (array_key_exists('addf_disc_rpc_product_rule_gift_item', $value_cart) ) {
					$gift_product_arr         = $value_cart['addf_disc_rpc_product_rule_gift_item'];
					$parent_product           = $gift_product_arr['key_id'];
					$gift_prod_min_qty        = $gift_product_arr['prod_min_qty'];
					$_post                    = get_post($gift_product_arr['rule_id']);
					$addf_remove_gift_product = false;

					// check if the total amount spent by user is not qualified for rule
					if ( $this->addf_disc_rpc_user_spent_amount_check( $gift_product_arr['rule_id'] ) ) {
						$addf_remove_gift_product = true;
					}

					// check if the rule is changed or have changes any setting in rule which can not apply now 
					if ( !in_array( $gift_product_arr['rule_id'] , $this->addf_disc_rpc_product_rules_obj ) ) {
						$addf_remove_gift_product = true;
					}
					
					// check if post not xists
					if (!$_post) {
						$addf_remove_gift_product = true;
					} else {
						$addf_disc_rpc_days_radio = get_post_meta( $gift_product_arr['rule_id'] , 'addf_disc_rpc_days_radio', true);
						$addf_disc_week_days_arr  = (array) get_post_meta( $gift_product_arr['rule_id'] , 'addf_disc_week_days_arr', true);
						$today                    = gmdate('l');

						// check if the day passed for free gift in rule
						if (( 'specific' == $addf_disc_rpc_days_radio )  && ( !in_array($today, $addf_disc_week_days_arr) ) && ( !empty($addf_disc_week_days_arr) ) ) {
							$addf_remove_gift_product = true;
						}

						// check if the main discount setting is changed for free gifted product
						$discount_choice = get_post_meta($gift_product_arr['rule_id'], 'addf_drpc_discount_type_choice', true);
						if ( 'conditional' != $discount_choice ) {
							$addf_remove_gift_product = true;
						}

					}

					// check if the product (from which the user got gift product ) is removed from cart
					if (array_key_exists($parent_product, $all_cart_object)) {
						$cart_data_product = $all_cart_object[ $parent_product ];
						if ($cart_data_product['quantity'] < $gift_prod_min_qty) {
							$addf_remove_gift_product = true;
						}
					} else {
						$addf_remove_gift_product = true;
					}
					if ( ( $this->addf_disc_rpc_check_product_in_cart($cart_object->get_cart(), $gift_product_arr['key_id'], $gift_product_arr['prod_min_qty']) ) ) {
						$addf_remove_gift_product = true;
					}

					// delete the gifted product if any of above condition got false
					if ($addf_remove_gift_product) {
						WC()->cart->remove_cart_item($value_cart['key']);
					}
					$after_disc_value          = $gift_product_arr['rule_id'];
					$gift_string               = $gift_product_arr['gift_product'];
					$after_gift_product_info[] = $after_disc_value;
					$cart_product_id_for_cong  = $value_cart['product_id'];
					if ( wc_get_product($value_cart['product_id'])->is_type('variable') ) {
						$cart_product_id_for_cong = $value_cart['variation_id'];
					}
					$min_qty                           = $gift_product_arr['prod_min_qty'];
					$addf_disc_rpc_cart_gift_notices[] = array(
						'rule_id'        => $after_disc_value,
						'qty'            => $gift_product_arr['prod_min_qty'],
						'product_id'     => $gift_product_arr['product_id'],
						'min_qty'        => $min_qty,
						'max_qty'        => $gift_product_arr['prod_min_qty'],
						'disc_value'     => '',
						'gifted_product' => $gift_product_arr['gift_product'],
					);
					continue;
				}
				
				$this->af_free_gift_price_fn();
				// Filter all eligible product rules 
				foreach ( $this->addf_disc_rpc_product_rules_obj as $key => $value) {
					$priority                           = get_post_meta($value, 'addf_disc_rpc_rule_priority', true);
					$all_products                       = $this->addf_disc_rpc_merge_all_product_cats($value);
					$addf_disc_rpc_product_selection_op = get_post_meta( $value, 'addf_disc_rpc_product_selection_op' , true );

					// check minimum amount spent by user
					if ( $this->addf_disc_rpc_user_spent_amount_check( $value ) ) {
						$addf_disc_rpc_cart_all_notices[] = array(
							'rule_id'        => $value,
							'qty'            => '',
							'product_id'     => $cart_prod_id,
							'min_qty'        => '',
							'max_qty'        => '',
							'disc_value'     => '',
							'gifted_product' => '',
						);
						continue;
					}
					
					// checking product
					if ( ( 'specific' == $addf_disc_rpc_product_selection_op ) && ( !empty($all_products) ) ) {
						if ($cart_product->is_type('variable')) {
							if (in_array($cart_variation_id, $all_products) || ( empty($all_products) ) || ( in_array($cart_product_id, $all_products) )) {
								if ('must_apply' == $priority) {
									$must_selected_rule[] = $value;
								}
								$all_selected_rule[] = $value;
							}
						} elseif (( in_array($cart_product_id, $all_products) ) ) {
							if ('must_apply' == $priority) {
								$must_selected_rule[] = $value;
							}
								$all_selected_rule[] = $value;
						}
					} else {
						if ('must_apply' == $priority) {
							$must_selected_rule[] = $value;
						}
						$all_selected_rule[] = $value;
					}
				}

				// quantity added in cart
				$qty_added = $value_cart['quantity'];

				// price applied for product (option in setting about sale price product)
				$cart_product_price          = wc_get_product($cart_prod_id)->get_price();
				$addf_drpc_option_sale_price = get_option( 'addf_drpc_option_sale_price' );
				if ('regular' == $addf_drpc_option_sale_price) {
					$cart_product_price = wc_get_product($cart_prod_id)->get_regular_price();
				} elseif ( 'ignore' == $addf_drpc_option_sale_price ) {
					continue;
				}
				
				$default_cart_product_price = wc_get_product($cart_prod_id)->get_price();
				$change_price_check         = false;


				// check if must apply rule not exists than apply the other rules
				$all_filtered_loop = array();
				if (!empty($must_selected_rule)) {
					$all_filtered_loop = $must_selected_rule;
				} else {
					$all_filtered_loop = $all_selected_rule;
				}
				
				// Applying Dynamic pricing and discounts
				$discount_price             = 0;
				$discount_min_price         = 0;
				$discount_max_price         = 0;
				$discount_type              = '';
				$min_qty_to_get_disc        = '';
				$product_price_in_cart      = $cart_product_price;
				$product_price_in_cart_calc = $cart_product_price;
				$gift_rules_implemented     = array();
				// apply 
				foreach ($all_filtered_loop as $key => $value) {
					
					$discount_choice = get_post_meta($value, 'addf_drpc_discount_type_choice', true);
					if ('dynamic_price_adj' == $discount_choice) {
						if ( $this->addf_drpc_product_have_gift( $value_cart['key'] , $value ) ) {
							continue;
						}
						$selected_cust        = (array) get_post_meta($value, 'addf_disc_rpc_select_customer', true);
						$cust_choice          = (array) get_post_meta($value, 'addf_drpc_cust_disc_choice', true);
						$disc_val_cust        = (array) get_post_meta($value, 'addf_disc_rpc_disc_val_tbl_cust', true);
						$min_qty_cust         = (array) get_post_meta($value, 'addf_disc_rpc_min_qty_tbl_cust', true);
						$max_qty_cust         = (array) get_post_meta($value, 'addf_disc_rpc_max_qty_tbl_cust', true);
						$disc_val_for_cust    = array();
						$disc_choice_for_cust = array();
						$min_qty_for_cust     = array();
						$max_qty_for_cust     = array();
						
						// getting rows from customer table
						if (!empty($selected_cust)) {
							foreach ($selected_cust as $disc_tbl_key => $disc_tbl_value) {
								if ($curr_user_id == $disc_tbl_value) {
									$disc_choice_for_cust[] = $cust_choice[ $disc_tbl_key ];
									$disc_val_for_cust[]    = $disc_val_cust[ $disc_tbl_key ];
									$min_qty_for_cust[]     = $min_qty_cust[ $disc_tbl_key ];
									$max_qty_for_cust[]     = $max_qty_cust[ $disc_tbl_key ];
								}
							}
						}
						// check for roles in roles table if no row found for current user in customer table 
						if ( ( empty($disc_val_for_cust) ) || ( ( 2 > count($disc_val_for_cust) ) && ( '' == current($disc_val_for_cust) ) ) ) {
							$selected_roles   = (array) get_post_meta($value, 'addf_disc_rpc_roles_select', true);
							$user_role_choice = (array) get_post_meta($value, 'addf_drpc_discount_amount_choice', true);
							$disc_val_role    = (array) get_post_meta($value, 'addf_disc_rpc_disc_val_tbl', true);
							$min_qty_role     = (array) get_post_meta($value, 'addf_disc_rpc_min_qty_tbl', true);
							$max_qty_role     = (array) get_post_meta($value, 'addf_disc_rpc_max_qty_tbl', true);
							if (!empty($selected_roles)) {
								foreach ($selected_roles as $disc_role_key => $disc_role_value) {
									if ( ( $curr_user_role != $disc_role_value ) && ( 'all' != $disc_role_value ) ) {
										continue;
									}
										$disc_choice_for_cust[] = $user_role_choice[ $disc_role_key ];
										$disc_val_for_cust[]    = $disc_val_role[ $disc_role_key ];
										$min_qty_for_cust[]     = $min_qty_role[ $disc_role_key ];
										$max_qty_for_cust[]     = $max_qty_role[ $disc_role_key ];
								}
							}
						}

						// check if any row exists in customer or roles table for current user to apply discount / fees
						if (!empty($disc_val_for_cust)) {
							$discount_type      = '';
							$discount_price     = 0;
							$discount_min_price = 0;
							$discount_max_price = 0;
							foreach ($disc_val_for_cust as $find_key => $find_value) {
								if ('' == $find_value) {
									continue;
								}
								$min_qty = $min_qty_for_cust[ $find_key ];
								$max_qty = $max_qty_for_cust[ $find_key ];
								if (0 == $discount_min_price) {
									$discount_min_price = $find_value;
								}
								$check_if_disc_applied = false;
								if (( '' != $min_qty ) && ( '' != $max_qty )) {
									if (( $min_qty <= $qty_added ) && ( $qty_added <= $max_qty )) {
										$return_array               = $this->addf_disc_rpc_calculate_discount_price_fn($disc_choice_for_cust[ $find_key ], $discount_type, $discount_min_price, $discount_max_price, $find_value, $discount_price, $cart_product_price , $product_price_in_cart_calc , $product_price_in_cart );
										$discount_type              = $return_array['discount_type'];
										$discount_price             = $return_array['discount_price'];
										$discount_min_price         = $return_array['discount_min_price'];
										$discount_max_price         = $return_array['discount_max_price'];
										$product_price_in_cart_calc = $return_array['calculated_product_price'];
									} else {
										$check_if_disc_applied = true;
									}
								} elseif (( '' != $min_qty ) && ( '' == $max_qty )) {
									if (( $min_qty <= $qty_added )) {
										$return_array               = $this->addf_disc_rpc_calculate_discount_price_fn($disc_choice_for_cust[ $find_key ], $discount_type, $discount_min_price, $discount_max_price, $find_value, $discount_price, $cart_product_price , $product_price_in_cart_calc , $product_price_in_cart );
										$discount_type              = $return_array['discount_type'];
										$discount_price             = $return_array['discount_price'];
										$discount_max_price         = $return_array['discount_max_price'];
										$discount_min_price         = $return_array['discount_min_price'];
										$product_price_in_cart_calc = $return_array['calculated_product_price'];
									} else {
										$check_if_disc_applied = true;
									}
								} elseif (( '' == $min_qty ) && ( '' != $max_qty )) {
									
									if (( $qty_added <= $max_qty )) {
										$return_array               = $this->addf_disc_rpc_calculate_discount_price_fn($disc_choice_for_cust[ $find_key ], $discount_type, $discount_min_price, $discount_max_price, $find_value, $discount_price, $cart_product_price , $product_price_in_cart_calc , $product_price_in_cart );
										$discount_type              = $return_array['discount_type'];
										$discount_price             = $return_array['discount_price'];
										$discount_max_price         = $return_array['discount_max_price'];
										$discount_min_price         = $return_array['discount_min_price'];
										$product_price_in_cart_calc = $return_array['calculated_product_price'];
									} else {
										$check_if_disc_applied = true;
									}
								}
								$product_id = $value_cart['product_id'];
								if ( wc_get_product($product_id)->is_type('variable') ) {
									$product_id = $value_cart['variation_id'];
								}
								// for discount not applied
								if ($check_if_disc_applied) {
									// Add congratulations notices to array
									$return_array_before_disc  = $this->addf_disc_rpc_calculate_discount_price_fn($disc_choice_for_cust[ $find_key ], $discount_type, $discount_min_price, $discount_max_price, $find_value, $discount_price, $cart_product_price  , $product_price_in_cart_calc , $product_price_in_cart );
									$discount_price_before     = $return_array_before_disc['discount_price'];
									$check_if_notice_not_added = true;
									foreach ( $addf_disc_rpc_cart_all_notices as $all_notice_key => $all_notice_value ) {
										if ( ( $value == $all_notice_value['rule_id'] ) && ( $product_id == $all_notice_value['product_id'] )  ) {
											$check_if_notice_not_added = false;
											if ( $min_qty < $all_notice_value['min_qty'] ) {
												$addf_disc_rpc_cart_all_notices[ $all_notice_key ] = array(
													'rule_id' => $value,
													'qty' => $qty_added,
													'product_id' => $product_id,
													'min_qty' => $min_qty,
													'max_qty' => $max_qty,
													'disc_value' => $discount_price_before,
													'gifted_product' => '',
												);
											}
										}
									}
									if ( $check_if_notice_not_added ) {
										$addf_disc_rpc_cart_all_notices[] = array(
											'rule_id'    => $value,
											'qty'        => $qty_added,
											'product_id' => $product_id,
											'min_qty'    => $min_qty,
											'max_qty'    => $max_qty,
											'disc_value' => $discount_price_before,
											'gifted_product' => '',
										);

									}
								} else {
									$change_price_check                = true;
									$min_qty_to_get_disc               = $min_qty;
									$addf_disc_rpc_cart_cong_notices[] = array(
										'rule_id'        => $value,
										'qty'            => $qty_added,
										'product_id'     => $product_id,
										'min_qty'        => $min_qty,
										'max_qty'        => $max_qty,
										'disc_value'     => $discount_price,
										'gifted_product' => '',
									);
									if ('follow_sequence' == $product_setting_price_type) {
										break;
									}
								}
							}
							
							// break if only 1 rule to be applied
							if ('follow_sequence' == $product_setting_price_type) {
								if ( $product_price_in_cart_calc != $cart_product_price ) {
									break;
								}
							} else {
								// continue if multiple rules are applied
								continue;
							}
						} else {
							continue;
						}
					} elseif ('conditional' == $discount_choice) {
						$check_if_table_not_empty = false;
						if ( !in_array( $value , $gift_rules_implemented ) ) {
							$gift_rules_implemented[] = $value;
						}
						$value_cart['data']->set_price($cart_product_price);
						if ( $product_price_in_cart_calc != $value_cart['data']->get_price() ) {
							continue;
						}
						$change_price_check    = true;
						$addf_check_gift_added = false;
						remove_action('woocommerce_before_calculate_totals', array( $this, 'apply_discounts_before_cart_totals' ), 90, 1);
						// Gift for customers
						$cust_gift_list         = (array) get_post_meta($value, 'addf_disc_rpc_select_customer_gift', true);
						$addf_drpc_gift_list    = (array) get_post_meta($value, 'addf_disc_choose_new_gift_list', true);
						$addf_drpc_gift_qty     = (array) get_post_meta($value, 'addf_disc_choose_new_gift_qty', true);
						$addf_drpc_gift_min_qty = (array) get_post_meta($value, 'addf_disc_choose_gift_min_qty', true);
						$all_gift_list          = array();
						$all_gift_qty_list      = array();
						$all_min_qty_list       = array();

						// get gift list from customer table for current user
						foreach ($cust_gift_list as $gift_cust_key => $gift_cust_value) {
							if ($curr_user_id == $gift_cust_value) {
								$all_gift_list[]     = $addf_drpc_gift_list[ $gift_cust_key ];
								$all_gift_qty_list[] = $addf_drpc_gift_qty[ $gift_cust_key ];
								$all_min_qty_list[]  = $addf_drpc_gift_min_qty[ $gift_cust_key ];
							}
						}
						$product_id = $value_cart['product_id'];
						$product    = wc_get_product($product_id);
						if ($product->is_type('variable')) {
							$product_id = $value_cart['variation_id'];
						}

						// get gift list from roles table if not found in customer table
						if ( empty( $this->af_remove_all_empty_spaces($all_gift_list) ) ) {
							$cust_gift_list_roles         = (array) get_post_meta($value, 'addf_disc_rpc_select_user_role_gift', true);
							$addf_drpc_gift_list_roles    = (array) get_post_meta($value, 'addf_disc_user_role_gift_list', true);
							$addf_drpc_gift_qty_roles     = (array) get_post_meta($value, 'addf_disc_user_role_gift_qty', true);
							$addf_drpc_gift_min_qty_roles = (array) get_post_meta($value, 'addf_disc_user_role_gift_min_qty', true);
							foreach ($cust_gift_list_roles as $gift_roles_key => $gift_roles_value) {
								if (( $curr_user_role == $gift_roles_value ) || ( 'all' == $gift_roles_value )) {
									$all_gift_list[]     = $addf_drpc_gift_list_roles[ $gift_roles_key ];
									$all_gift_qty_list[] = $addf_drpc_gift_qty_roles[ $gift_roles_key ];
									$all_min_qty_list[]  = $addf_drpc_gift_min_qty_roles[ $gift_roles_key ];
								}
							}
						}
						// remove the gift product from cart which is removed from gift list for current user
						foreach ($all_cart_object as $remove_gift_key => $remove_gift_value) {
							if (array_key_exists('addf_disc_rpc_product_rule_gift_item', $remove_gift_value)) {
								$remove_gift_value['data']->set_price(0);
								$remove_gift_array = $remove_gift_value['addf_disc_rpc_product_rule_gift_item'];
								if ( ( $value_cart['key'] == $remove_gift_array['key_id'] ) && ( $value == $remove_gift_array['rule_id'] ) ) {
									if ( !in_array( $remove_gift_value['data']->get_id() , $all_gift_list ) ) {
										WC()->cart->remove_cart_item($remove_gift_value['key']);
									}
								}
							}
						}
						
						// check if any gift found in gift list for current user
						if (!empty($all_gift_list)) {
							$check_if_table_not_empty = true;
							foreach ($all_gift_list as $gift_key => $gift_value) {
								$gift_string = $all_gift_qty_list[ $gift_key ] . ' x ' . get_the_title($gift_value);
								if ($qty_added >= $all_min_qty_list[ $gift_key ]) {
									foreach ($all_cart_object as $remove_gift_key => $remove_gift_value) {
										if (array_key_exists('addf_disc_rpc_product_rule_gift_item', $remove_gift_value)) {
											$remove_gift_array = $remove_gift_value['addf_disc_rpc_product_rule_gift_item'];
											if ( !in_array( $remove_gift_array['rule_id'] , $gift_rules_implemented ) ) {
												break;
											}
											if ( ( $value_cart['key'] == $remove_gift_array['key_id'] ) && ( $value == $remove_gift_array['rule_id'] ) && ( $remove_gift_value['data']->get_id() == $gift_value ) && ( $all_min_qty_list[ $gift_key ] == $remove_gift_array['prod_min_qty'] ) && ( $all_gift_qty_list[ $gift_key ] == $remove_gift_array['gift_quantity'] ) ) {
												$addf_check_gift_added = true;
												continue 2;
											}
										}
									}
									if ( $this->af_check_if_gift_already_added( $value , $value_cart['key'] , $all_min_qty_list[ $gift_key ] , $gift_value , $all_gift_qty_list[ $gift_key ] ) ) {
										$addf_check_gift_added = true;
										continue;
									}
									if ( WC()->session->get('af_disc_product_rule_remove_gift_list') ) {
										$session_array = WC()->session->get('af_disc_product_rule_remove_gift_list');
										foreach ( $session_array as $session_key => $session_value ) {
											foreach ( $session_value as $child_session_key => $child_session_value ) {
												if ( !array_key_exists( 'rule_id' , (array) $child_session_value ) ) {
													continue;
												}
												if ( ( $value_cart['key'] == $child_session_value['parent_id'] ) && ( $value == $child_session_value['rule_id'] ) && ( $gift_value == $child_session_value['gift_product_id'] ) && ( $all_min_qty_list[ $gift_key ] == $child_session_value['min_qty'] ) && ( $all_gift_qty_list[ $gift_key ] == $child_session_value['gift_qty'] ) ) {
													continue 3;
												}
											}
										}
									}
									if ('' == $all_gift_qty_list[ $gift_key ]) {
										$all_gift_qty_list[ $gift_key ] = 1;
									}
										$array_identity = array(
											'addf_disc_rpc_product_rule_gift_item' => array(
												'rule_id' => $value,
												'key_id'  => $value_cart['key'],
												'product_id' => $product_id,
												'prod_min_qty' => $all_min_qty_list[ $gift_key ],
												'gift_quantity' => $all_gift_qty_list[ $gift_key ],
												'gift_product' => $all_gift_qty_list[ $gift_key ] . ' x ' . get_the_title($gift_value),
											),
										);
										
										$addf_check_gift_added = true;
										WC()->cart->add_to_cart($gift_value, $all_gift_qty_list[ $gift_key ], $variation_id = 0, $variation_attr = array(), $array_identity);
										$this->af_free_gift_price_fn();
								} else {
									// remove product from cart if quantity decreases
									$addf_disc_rpc_cart_all_notices[] = array(
										'rule_id'        => $value,
										'qty'            => $qty_added,
										'product_id'     => $product_id,
										'min_qty'        => $all_min_qty_list[ $gift_key ],
										'max_qty'        => '',
										'disc_value'     => '',
										'gifted_product' => $gift_string,
									);
									$before_gift_product_info[]       = array(
										'type'         => 'before_discount',
										'product_id'   => $product_id,
										'qty'          => $qty_added,
										'rem_qty'      => $all_min_qty_list[ $gift_key ],
										'gift_product' => $gift_string,
										'rule_id'      => $value,
									);
									$gift_notice_info_arr[]           = $value;

								}
							}
						}
						add_action('woocommerce_before_calculate_totals', array( $this, 'apply_discounts_before_cart_totals' ), 90, 1);
						if (  $addf_check_gift_added ) {
							break;
						}
					}
					// end of filtered loop of product rules
				}
				foreach ($all_cart_object as $remove_gift_key => $remove_gift_value) {
					if (array_key_exists('addf_disc_rpc_product_rule_gift_item', $remove_gift_value)) {
						$remove_gift_array = $remove_gift_value['addf_disc_rpc_product_rule_gift_item'];
						if ( ( $value_cart['key'] == $remove_gift_array['key_id'] ) && ( !in_array( $remove_gift_array['rule_id'] , $gift_rules_implemented ) ) ) {
							WC()->cart->remove_cart_item($remove_gift_value['key']);
						}
					}
				}
				if ( $change_price_check ) {
					$value_cart['data']->set_price($product_price_in_cart_calc);
					if ( $product_price_in_cart_calc != $cart_product_price ) {
						foreach ($all_cart_object as $remove_gift_key => $remove_gift_value) {
							if (array_key_exists('addf_disc_rpc_product_rule_gift_item', $remove_gift_value)) {
								$remove_gift_value['data']->set_price(0);
								$remove_gift_array = $remove_gift_value['addf_disc_rpc_product_rule_gift_item'];
								if ( $value_cart['key'] == $remove_gift_array['key_id'] ) {
									WC()->cart->remove_cart_item($remove_gift_value['key']);
								}
							}
						}
					}
				}

				// apply finalized product price for single product
				
				if ('' != $discount_type) {
					if ('fixed_price' == $discount_type) {
						$addf_disc_rpc_cart_cong_notices[] = array(
							'rule_id'        => $value,
							'qty'            => $qty_added,
							'product_id'     => $cart_prod_id,
							'min_qty'        => $min_qty_to_get_disc,
							'max_qty'        => $max_qty,
							'disc_value'     => $discount_price,
							'gifted_product' => '',
						);
					} elseif ( 0 != $discount_price ) {
							$addf_disc_rpc_cart_cong_notices[] = array(
								'rule_id'        => $value,
								'qty'            => $qty_added,
								'product_id'     => $cart_prod_id,
								'min_qty'        => $min_qty_to_get_disc,
								'max_qty'        => $max_qty,
								'disc_value'     => $discount_price,
								'gifted_product' => '',
							);
					}
				}
				// end of cart loop
			}
			
			foreach ($all_cart_object as $value_cart) {
				if (array_key_exists('addf_disc_rpc_product_rule_gift_item', $value_cart) ) {
					$value_cart['data']->set_price(0);
					$gift_product_arr         = $value_cart['addf_disc_rpc_product_rule_gift_item'];
					$after_disc_value         = $gift_product_arr['rule_id'];
					$gift_string              = $gift_product_arr['gift_product'];
					$cart_product_id_for_cong = $value_cart['product_id'];
					if ( wc_get_product($value_cart['product_id'])->is_type('variable') ) {
						$cart_product_id_for_cong = $value_cart['variation_id'];
					}
					$min_qty                           = $gift_product_arr['prod_min_qty'];
					$addf_disc_rpc_cart_gift_notices[] = array(
						'rule_id'        => $after_disc_value,
						'qty'            => $gift_product_arr['prod_min_qty'],
						'product_id'     => $cart_product_id_for_cong,
						'min_qty'        => $min_qty,
						'max_qty'        => $gift_product_arr['prod_min_qty'],
						'disc_value'     => '',
						'gifted_product' => $gift_product_arr['gift_product'],
					);
				}
			}
			// show all calculated notices on cart and product pages
			$message_shown_single_product = true;
			wc_clear_notices();

			$addf_disc_rpc_gift_notices = array();
			$product_shown_notices      = array();
			foreach ( $addf_disc_rpc_cart_gift_notices as $key => $value ) {
				$rule_id = $value['rule_id'];
				if ( in_array( $rule_id , $addf_disc_rpc_gift_notices ) ) {
					continue;
				}
				$product_id = $value['product_id'];
				if ( in_array( $product_id , $product_shown_notices ) ) {
					continue;
				}
				$product_shown_notices[]      = $product_id;
				$notice_message               = get_post_meta( $rule_id, 'addf_disc_rpc_after_disc_msg', true);
				$addf_disc_rpc_gift_notices[] = $rule_id;
				if ( '' != $notice_message ) {
					$req_qty     = $value['qty'];
					$discount    = '';
					$gift_string = $value['gifted_product'];
					$this->addf_disc_rpc_show_cart_notices( $rule_id , $notice_message , $product_id , $req_qty , $discount , $gift_string );
				}
			}
			$addf_disc_rpc_cong_notices_shown = array();
			// notices for discount applied products
			foreach ( $addf_disc_rpc_cart_cong_notices as $key => $value ) {
				$rule_id                            = $value['rule_id'];
				$notice_message                     = get_post_meta( $rule_id, 'addf_disc_rpc_after_disc_msg', true);
				$product_id                         = $value['product_id'];
				$addf_disc_rpc_cong_notices_shown[] = array(
					'rule_id'    => $rule_id,
					'product_id' => $product_id,
				);
				
				if ( in_array( $product_id , $product_shown_notices ) ) {
					continue;
				}
				$product_shown_notices[] = $product_id;
				if ( '' != $notice_message ) {
					$req_qty     = $value['min_qty'];
					$discount    = $value['disc_value'];
					$gift_string = $value['gifted_product'];
					$this->addf_disc_rpc_show_cart_notices( $rule_id , $notice_message , $product_id , $req_qty , $discount , $gift_string );
				}
			}
			// notices for before discount and before gift products
			foreach ( $addf_disc_rpc_cart_all_notices as $key => $value ) {
				$rule_id    = $value['rule_id'];
				$product_id = $value['product_id'];
				if (  in_array( $rule_id , $addf_disc_rpc_gift_notices ) ) {
					continue;
				}
				
				if ( in_array( $product_id , $product_shown_notices ) ) {
					continue;
				}
				$product_shown_notices[]      = $product_id;
				$addf_disc_rpc_gift_notices[] = $value['rule_id'];
				foreach ( $addf_disc_rpc_cong_notices_shown as $child_key => $child_value ) {
					if ( ( $rule_id == $child_value['rule_id'] ) && ( $product_id == $child_value['product_id'] ) ) {
						continue 2;
					}
				}
				$notice_message = get_post_meta( $rule_id, 'addf_disc_rpc_before_disc_msg', true);
				if ( '' != $notice_message ) {
					$req_qty     = $value['min_qty'];
					$discount    = $value['disc_value'];
					$gift_string = $value['gifted_product'];
					$this->addf_disc_rpc_show_cart_notices( $rule_id , $notice_message , $product_id , $req_qty , $discount , $gift_string );
				}
			}
		}

		// check if gift already not exists 
		public function af_check_if_gift_already_added( $rule_id, $key, $min_qty, $gift_value, $gift_qty ) {
			$return = false;
			foreach ( wc()->cart->get_cart() as $cart_key => $value ) {
				if (array_key_exists('addf_disc_rpc_product_rule_gift_item', $value) ) {
					$gift           = $value['addf_disc_rpc_product_rule_gift_item'];
					$parent_product = $gift['key_id'];
					if ( ( $gift['rule_id'] == $rule_id ) && ( $value['data']->get_id() == $gift_value ) && ( $min_qty == $gift['prod_min_qty'] ) && ( $gift_qty == $gift['gift_quantity'] ) ) {
						$return = true;
					}
				}
			}
			return $return;
		}

		// continue rule if product already have gift
		public function addf_drpc_product_have_gift( $key, $value ) {
			foreach ( wc()->cart->get_cart() as $check_key_cart_gift => $check_value_cart_gift ) {
				if (array_key_exists('addf_disc_rpc_product_rule_gift_item', $check_value_cart_gift) ) {
					$gift_product_arr = $check_value_cart_gift['addf_disc_rpc_product_rule_gift_item'];
					$parent_product   = $gift_product_arr['key_id'];
					$check_value_cart_gift['data']->set_price(0);
					if ( ( $parent_product == $key ) && ( $gift_product_arr['rule_id'] == $value ) ) {
						return true;
					}
				}
			}
			return false;
		}

		// show all notices
		public function addf_disc_rpc_show_cart_notices( $rule_id, $disc_gift_msg, $product_id, $req_qty, $discount, $gift_string ) {
			$start_date                      = get_post_meta($rule_id, 'addf_disc_rpc_start_time', true);
			$end_date                        = get_post_meta($rule_id, 'addf_disc_rpc_end_time', true);
			$min_spent_amount                = get_post_meta($rule_id, 'addf_disc_rpc_min_spent_amount', true);
			$addf_drpc_disc_min_spent_amount = get_post_meta($rule_id, 'addf_drpc_disc_min_spent_amount', true);
			if ( 'ignore' == $addf_drpc_disc_min_spent_amount ) {
				$min_spent_amount = '';
			}
			$product_qty = $this->addf_disc_rpc_count_qty_of_product( $product_id );
			if ( '' != $req_qty ) {
				$rem_qty = $req_qty - $product_qty;
				if ( $rem_qty <= 0 ) {
					$rem_qty = '';
				}
			} else {
				$rem_qty = '';
			}
			$disc_gift_msg = str_replace('{product_name}', get_the_title($product_id), $disc_gift_msg);
			$disc_gift_msg = str_replace('{product_qty}', $product_qty , $disc_gift_msg);
			$disc_gift_msg = str_replace('{req_qty}', $req_qty , $disc_gift_msg);
			$disc_gift_msg = str_replace('{rem_qty}', $rem_qty , $disc_gift_msg);
			$disc_gift_msg = str_replace('{discount}', $discount , $disc_gift_msg);
			$disc_gift_msg = str_replace('{start_date}', $start_date, $disc_gift_msg);
			$disc_gift_msg = str_replace('{end_date}', $end_date, $disc_gift_msg);
			$disc_gift_msg = str_replace('{min_spent_amount}', $min_spent_amount, $disc_gift_msg);
			$disc_gift_msg = str_replace('{gift_product}', $gift_string, $disc_gift_msg);
			if ( is_cart() ) {
				// show notices on cart page
				wc_add_notice( esc_html__( $disc_gift_msg , 'woo-af-drpc' ), 'notice');
			} else {
				// show notices only for current product on product page and remove all other notices
				wc_clear_notices();
				if ( wc_get_product( $product_id )->is_type('variation') ) {
					$product_id = wc_get_product( $product_id )->get_parent_id();
				}
				if ( get_the_ID() == $product_id) {
					wc_print_notice( esc_html__( $disc_gift_msg , 'woo-af-drpc' ), 'notice' );
				}
			}
		}

		// calculate all quantities from cart for a single product
		public function addf_disc_rpc_count_qty_of_product( $product_id ) {
			$quantity = 0;
			foreach ( WC()->cart->get_cart() as $key => $value) {
				if (array_key_exists('addf_disc_rpc_product_rule_gift_item', $value) || array_key_exists('addf_disc_rpc_cart_rule_gift_item', $value)) {
					continue;
				}
				$cart_product_id = $value['product_id'];
				if ( wc_get_product($cart_product_id)->is_type('variable') ) {
					$cart_product_id = $value['variation_id'];
				}
				if ( $product_id == $cart_product_id ) {
					$quantity += $value['quantity'];
				}
			}
			return $quantity;
		}

		// check if product don't have quantity equal or more than to get free gift product
		public function addf_disc_rpc_check_product_in_cart( $cart, $key, $prod_min_qty ) {
			foreach ($cart as $value) {
				if (( $value['key'] == $key ) && ( $prod_min_qty > $value['quantity'] )) {
					return true;
				}
			}
			return false;
		}

		// apply discount from table and condition like fix price , increase price , decrease price , increase percentage and decrease  percentage
		public function addf_disc_rpc_calculate_discount_price_fn( $disc_choice_for_cust, $discount_type, $discount_min_price, $discount_max_price, $find_value, $discount_price, $cart_product_price, $product_price_in_cart_calc, $product_price_in_cart ) {
			$product_setting_price_type = get_option('addf_drpc_option_rules_priority');
			$percentage_price           = ( $product_price_in_cart/100 )*$find_value;
			if ('fixed_price' == $disc_choice_for_cust) {
				$discount_type = 'fixed_price';
				if ( $product_price_in_cart_calc == $product_price_in_cart ) {
					$product_price_in_cart_calc = $find_value;
				}
				$discount_price = $find_value;
				if ( 'follow_sequence' == $product_setting_price_type ) {
					$product_price_in_cart_calc = $find_value;
				}
				if ( ( 'smaller_price' == $product_setting_price_type ) && ( $find_value < $product_price_in_cart_calc )) {
					$product_price_in_cart_calc = $find_value;
				}
				if ( ( 'more_price' == $product_setting_price_type ) && ( $find_value > $product_price_in_cart_calc )) {
					$product_price_in_cart_calc = $find_value;
				}
			} elseif ('fixed_price_increase' == $disc_choice_for_cust) {
				$discount_type = 'fixed_price_increase';
				if ( $product_price_in_cart_calc == $product_price_in_cart ) {
					$product_price_in_cart_calc = $find_value + $product_price_in_cart;
				}
				$discount_price = $find_value;
				if ( 'follow_sequence' == $product_setting_price_type ) {
					$product_price_in_cart_calc = $find_value + $product_price_in_cart;
				}
				if ( ( 'smaller_price' == $product_setting_price_type ) && ( $product_price_in_cart_calc > ( $find_value + $product_price_in_cart ) ) ) {
					$product_price_in_cart_calc = $find_value + $product_price_in_cart;
				}
				if ( ( 'more_price' == $product_setting_price_type ) && ( $product_price_in_cart_calc < ( $find_value + $product_price_in_cart ) ) ) {
					$product_price_in_cart_calc = $find_value + $product_price_in_cart;
				}
			} elseif ('fixed_price_decrease' == $disc_choice_for_cust) {
				$discount_type = 'fixed_price_decrease';
				if ( $product_price_in_cart_calc == $product_price_in_cart ) {
					$product_price_in_cart_calc = $product_price_in_cart - $find_value;
				}
				$discount_price = $find_value;
				if ( 'follow_sequence' == $product_setting_price_type ) {
					$product_price_in_cart_calc = ( $product_price_in_cart - $find_value );
				}
				if ( ( 'smaller_price' == $product_setting_price_type ) && ( $product_price_in_cart_calc > ( $product_price_in_cart - $find_value ) )) {
					$product_price_in_cart_calc = ( $product_price_in_cart - $find_value );
				}
				if (( 'more_price' == $product_setting_price_type ) && ( $product_price_in_cart_calc < ( $product_price_in_cart - $find_value ) )) {
					$product_price_in_cart_calc = ( $product_price_in_cart - $find_value );
				}
			} elseif ('fixed_percent_increase' == $disc_choice_for_cust) {
				$discount_type  = 'fixed_percent_increase';
				$discount_price = $percentage_price;
				$compare_value  = $product_price_in_cart + $percentage_price;
				if ( $product_price_in_cart_calc == $product_price_in_cart ) {
					$product_price_in_cart_calc = $compare_value;
				}
				if ( 'follow_sequence' == $product_setting_price_type ) {
					$product_price_in_cart_calc = $compare_value;
				}
				if ( ( 'smaller_price' == $product_setting_price_type ) && ( $product_price_in_cart_calc > $compare_value )) {
					$product_price_in_cart_calc = $compare_value;
				}
				if (( 'more_price' == $product_setting_price_type ) && ( $product_price_in_cart_calc < $find_value )) {
					$product_price_in_cart_calc = $compare_value;
				}
			} elseif ('fixed_percent_decrease' == $disc_choice_for_cust) {
				$discount_type    = 'fixed_percent_decrease';
				$discount_price   =  $percentage_price;
				$compare_discount = $product_price_in_cart - $percentage_price;
				if ( $product_price_in_cart_calc == $product_price_in_cart ) {
					$product_price_in_cart_calc = $compare_discount;
				}
				if ( 'follow_sequence' == $product_setting_price_type ) {
					$product_price_in_cart_calc = $compare_discount;
				} 
				if ( ( 'smaller_price' == $product_setting_price_type ) && ( $product_price_in_cart_calc > $compare_discount ) ) {
					$product_price_in_cart_calc = $compare_discount;
				}
				if (( 'more_price' == $product_setting_price_type ) && ( $product_price_in_cart_calc < $compare_discount )) {
					$product_price_in_cart_calc = $compare_discount;
				}
			}
			if ( 0 > $product_price_in_cart_calc ) {
				$product_price_in_cart_calc = 0;
			}
			$return_array = array(
				'discount_type'            => $discount_type,
				'discount_max_price'       => $discount_max_price,
				'discount_price'           => $discount_price,
				'discount_min_price'       => $discount_min_price,
				'calculated_product_price' => $product_price_in_cart_calc,
			);
			return $return_array;
		}

		// disable product quantity in cart for gift product
		public function disable_gift_quantity_in_cart( $product_quantity, $cart_item_key ) {
			$cart_all = WC()->cart->get_cart();
			if (array_key_exists('addf_disc_rpc_product_rule_gift_item', $cart_all[ $cart_item_key ])) {
				return $cart_all[ $cart_item_key ]['quantity'];
			} else {
				return $product_quantity;
			}
		}

		// label on gift product in cart
		public function gift_label_in_cart( $item_data, $cart_item_data ) {
			if (isset($cart_item_data['addf_disc_rpc_product_rule_gift_item'])) {
				$gift_key    = $cart_item_data['addf_disc_rpc_product_rule_gift_item'];
				$item_data[] = array(
					'key'   =>esc_html__('Gift ', 'woo-af-drpc'),
					'value' => wc_clean('with ' . get_the_title($gift_key['product_id'])),
				);
			}
			return $item_data;
		}

		// label gift product on checkout page
		public function gift_label_after_checkout( $item, $cart_item_key, $values, $order ) {
			if (isset($values['addf_disc_rpc_product_rule_gift_item'])) {
				$gift_key = $values['addf_disc_rpc_product_rule_gift_item'];
				$item->add_meta_data(
					esc_html__('Gift ', 'woo-af-drpc'),
					esc_html__('with ' . get_the_title($gift_key['product_id']), 'woo-af-drpc'),
					true
				);
			}
		}

		// show table for variation
		public function show_table_for_variation( $variation_get_max_purchase_quantity, $instance, $variation ) {
			$this->addf_disc_rpc_apply_template_styling();
			$variation_get_max_purchase_quantity['price_html'] .= $this->addf_disc_rpc_price_table_on_product_page_create_table( $variation->get_id() );
			return $variation_get_max_purchase_quantity;
		}

		// creating table on product page for variable or simple product
		public function show_table_product_page() {
			$this->addf_disc_rpc_apply_template_styling();
			echo wp_kses_post($this->addf_disc_rpc_price_table_on_product_page_create_table( get_the_ID() ));
		}

		public function addf_disc_rpc_apply_template_styling() {

			if (!empty($this->addf_drpc_template_font_family)) {
				$this->addf_drpc_template_font_family($this->addf_drpc_template_font_family);
			}
			
			if ('yes' != $this->addf_drpc_enable_table_save_column) {
				$this->addf_drpc_disable_table_save_column();
			}

			if ('yes' != $this->addf_drpc_enable_template_heading) {
				$this->addf_drpc_disable_template_heading();
			}

			if ('yes' != $this->addf_drpc_enable_template_icon) {
				$this->addf_drpc_disable_template_icon();
			}

			if (!empty($this->addf_drpc_template_font_family)) {
				$this->addf_drpc_template_font_family($this->addf_drpc_template_font_family);
			}
			

			if (!empty($this->addf_drpc_table_header_background_color)) {
				$this->addf_drpc_apply_table_header_background_color($this->addf_drpc_table_header_background_color);
			}

			if (!empty($this->addf_drpc_table_header_text_color)) {
				$this->addf_drpc_apply_table_header_text_color($this->addf_drpc_table_header_text_color);
			}

			if (!empty($this->addf_drpc_table_header_font_size)) {
				$this->addf_drpc_apply_table_header_font_size($this->addf_drpc_table_header_font_size);
			}

			if (!empty($this->addf_drpc_table_row_font_size)) {
				$this->addf_drpc_apply_table_row_font_size($this->addf_drpc_table_row_font_size);
			}

			if ('yes' == $this->addf_drpc_enable_table_border) {
				$this->addf_drpc_table_border($this->addf_drpc_table_border_color);
			}

			if (!empty($this->addf_drpc_table_odd_rows_background_color)) {
				$this->addf_drpc_table_odd_rows_background_color($this->addf_drpc_table_odd_rows_background_color);
			}

			if (!empty($this->addf_drpc_table_odd_rows_text_color)) {
				$this->addf_drpc_odd_row_text_color($this->addf_drpc_table_odd_rows_text_color);
			}

			if (!empty($this->addf_drpc_table_even_rows_background_color)) {
				$this->addf_drpc_table_even_rows_background_color($this->addf_drpc_table_even_rows_background_color);
			}

			if (!empty($this->addf_drpc_table_even_rows_text_color)) {
				$this->addf_drpc_even_row_text_color($this->addf_drpc_table_even_rows_text_color);
			}

			if (!empty($this->addf_drpc_table_rows_font_size)) {
				$this->addf_drpc_table_row_font_size($this->addf_drpc_table_rows_font_size);
			}
			
			if (!empty($this->addf_drpc_list_border_color)) {
				$this->addf_drpc_list_border_color($this->addf_drpc_list_border_color);
			}

			if (!empty($this->addf_drpc_list_background_color)) {
				$this->addf_drpc_list_background_color($this->addf_drpc_list_background_color);
			}

			if (!empty($this->addf_drpc_list_text_color)) {
				$this->addf_drpc_list_text_color($this->addf_drpc_list_text_color);
			}

			if (!empty($this->addf_drpc_selected_list_background_color)) {
				$this->addf_drpc_selected_list_background_color($this->addf_drpc_selected_list_background_color);
			}

			if (!empty($this->addf_drpc_selected_list_text_color)) {
				$this->addf_drpc_selected_list_text_color($this->addf_drpc_selected_list_text_color);
			}

			if (!empty($this->addf_drpc_card_border_color)) {
				$this->addf_drpc_card_border_color($this->addf_drpc_card_border_color);
			}

			if (!empty($this->addf_drpc_card_text_color)) {
				$this->addf_drpc_card_text_color($this->addf_drpc_card_text_color);
			}

			if (!empty($this->addf_drpc_card_background_color)) {
				$this->addf_drpc_card_backgrorund_color($this->addf_drpc_card_background_color);
			}
			
			if (!empty($this->addf_drpc_selected_card_border_color)) {
				$this->addf_drpc_card_selected_border_color($this->addf_drpc_selected_card_border_color);
			}
			
			if ( 'yes'!=  $this->addf_drpc_enable_sale_tag ) {
				$this->addf_drpc_disable_sale_tag();
			}

			if (!empty($this->addf_drpc_sale_tag_background_color)) {
				$this->addf_drpc_sale_tag_background_color($this->addf_drpc_sale_tag_background_color);
			}

			if (!empty($this->addf_drpc_sale_tag_text_color)) {
				$this->addf_drpc_sale_tag_text_color($this->addf_drpc_sale_tag_text_color);
			}
		}

		public function addf_drpc_template_font_family( $font_family ) {
			?>
				<style>
					.addf_drpc_list_div,.addf_drpc_table_div,.addf_drpc_card_div{
						font-family: <?php echo esc_attr($font_family); ?>
					}
				</style>
			<?php
		}

		public function addf_drpc_disable_table_save_column() {
			?>
				<style>
					.addf_drpc_save_column{
						display: none;
					}
				</style>
			<?php
		}
		
		public function addf_drpc_disable_template_heading() {
			?>
				<style>
					.addf_drpc_template_header h2{
						display:none;
					}
				</style>
			<?php
		}

		public function addf_drpc_disable_template_icon() {
			?>
				<style>
					.addf_drpc_deals_icon{
						display: none;
					}
				</style>
			<?php
		}
		
		public function addf_drpc_apply_table_header_background_color( $background_color ) {
			?>
				<style>
					.addf_drpc_table_div table th{
						background: <?php echo esc_attr($background_color); ?>;
					}
				</style>
			<?php
		}

		public function addf_drpc_apply_table_header_text_color( $text_color ) {
			?>
				<style>
					.addf_drpc_table_div table th{
						color: <?php echo esc_attr($text_color); ?>;
					}
				</style>
			<?php
		}

		public function addf_drpc_apply_table_header_font_size( $font_size ) {
			?>
				<style>
					.addf_drpc_table_div table th {
						font-size: <?php echo esc_attr($font_size) . 'px'; ?>;
					}
				</style>
			<?php
		}

		public function addf_drpc_apply_table_row_font_size( $font_size ) {
			?>
				<style>
					.addf_drpc_table_div table td {
						font-size: <?php echo esc_attr($font_size) . 'px'; ?>;
					}
				</style>
			<?php
		}
		
		public function addf_drpc_table_border( $border_color ) {
			?>
				<style>
					.addf_drpc_table_div table {
						border-collapse: collapse;
						border: 2px solid <?php echo esc_attr($border_color); ?>;
					}
					.addf_drpc_table_div table th, .addf_drpc_table_div table td {
						border: 1px solid <?php echo esc_attr($border_color); ?>;
						text-align:center
					}
				</style>
			<?php
		}

		public function addf_drpc_table_odd_rows_background_color( $af_odd_row_color ) {
			?>
				<style>
					.addf_drpc_table_div table:not( .has-background )  td {
						background-color: initial;
					}
					.addf_drpc_table_div table  tr:nth-child(odd) {
						background-color: <?php echo esc_attr($af_odd_row_color); ?>;
					}
				</style>
			<?php
		}

		public function addf_drpc_odd_row_text_color( $af_odd_row_color ) {
			?>
				<style>
					.addf_drpc_table_div table  tr:nth-child(odd) {
						color: <?php echo esc_attr($af_odd_row_color); ?>;
					}
				</style>
			<?php
		}

		public function addf_drpc_table_even_rows_background_color( $af_even_row_color ) {
			?>
				<style>
					.addf_drpc_table_div table:not( .has-background )  tr:nth-child(2n) td {
						background-color: initial;
					}
					.addf_drpc_table_div table  tr:nth-child(even) {
						background-color: <?php echo esc_attr($af_even_row_color); ?>;
					}
				</style>
			<?php
		}

		public function addf_drpc_even_row_text_color( $af_even_row_color ) {
			?>
				<style>
					.addf_drpc_table_div table tr:nth-child(even) {
						color: <?php echo esc_attr($af_even_row_color); ?>;
					}
				</style>
			<?php
		}

		public function addf_drpc_table_row_font_size( $af_table_row_font_size ) {
			?>
				<style>
					.addf_drpc_table_div table tr td {
						font-size: <?php echo esc_attr($af_table_row_font_size); ?>px;
					}
				</style>
			<?php
		}


		public function addf_drpc_list_border_color( $border_color ) {
			?>
			<style>
				.addf_drpc_list_box{
					border: 1px solid <?php echo esc_attr($border_color); ?>;
				}
			</style>
			<?php
		}

		public function addf_drpc_list_background_color( $background_color ) {
			?>
			<style>
				.addf_drpc_list_box{
					background-color: <?php echo esc_attr($background_color); ?>;
				}
			</style>
			<?php
		}

		public function addf_drpc_list_text_color( $text_color ) {
			?>
			<style>
				.addf_drpc_list_box{
					color: <?php echo esc_attr($text_color); ?>;
				}
			</style>
			<?php
		}

		public function addf_drpc_selected_list_background_color( $background_color ) {
			?>
			<style>
				.addf_drpc_selected_list{
					background-color: <?php echo esc_attr($background_color); ?>;
				}
			</style>
			<?php
		}

		public function addf_drpc_selected_list_text_color( $text_color ) {
			?>
			<style>
				.addf_drpc_selected_list{
					color: <?php echo esc_attr($text_color); ?>;
				}
			</style>
			<?php
		}

		public function addf_drpc_card_border_color( $border_color ) {
			?>
			<style>
				.addf_drpc_inner_small_box{
					border: 1px solid <?php echo esc_attr($border_color); ?>;
				}
			</style>
			<?php
		}

		public function addf_drpc_card_text_color( $text_color ) {
			?>
			<style>
				.addf_drpc_inner_small_box{
					color:<?php echo esc_attr($text_color); ?>;
				}
			</style>
			<?php
		}

		public function addf_drpc_card_backgrorund_color( $background_color ) {
			?>
			<style>
				.addf_drpc_inner_small_box{
					background-color: <?php echo esc_attr($background_color); ?>;
				}
			</style>
			<?php
		}

		public function addf_drpc_card_selected_border_color( $border_color ) {
			?>
			<style>
				.addf_drpc_selected_card{
					border: 2px solid <?php echo esc_attr($border_color); ?>;
				}
			</style>
			<?php
		}

		public function addf_drpc_disable_sale_tag() {
			?>
			<style>
				.addf_drpc_sale_tag{
					display:none
				}
			</style>
			<?php
		}

		public function addf_drpc_sale_tag_background_color( $background_color ) {
			?>
			<style>
				.addf_drpc_sale_tag{
					background-color: <?php echo esc_attr($background_color); ?>;

				}
			</style>
			<?php
		}

		public function addf_drpc_sale_tag_text_color( $text_color ) {
			?>
			<style>
				.addf_drpc_sale_tag{
					color: <?php echo esc_attr($text_color); ?>;
				}
			</style>
			<?php
		}

		public function addf_disc_rpc_price_table_on_product_page_create_table( $product_id ) {

			$product = wc_get_product($product_id);
			if ( 'ignore' == get_option('addf_drpc_option_sale_price') ) {
				if ( $product->is_on_sale() ) {
					return '';
				}
			}
			$check_product = wc_get_product($product_id);
			if ( is_user_logged_in() ) {
				$user           = wp_get_current_user();
				$curr_user_role = current( $user->roles );
			} else {
				$curr_user_role = 'guest';
			}
			
			$curr_user_id  = get_current_user_id();
			$default_price = $product->get_price_html();
			$return_array  = (array) $this->product_price_and_table( $default_price , $product );

			
			if ( ( 0 == $return_array['rule_id_for_table'] ) || ( !get_post( $return_array['rule_id_for_table'] ) ) ) {
				return '';
			}
			$return_value = '';
			$value        = $return_array['rule_id_for_table'];

			$addf_disc_rpc_product_selection_op = get_post_meta( $value, 'addf_disc_rpc_product_selection_op' , true );
			$all_products                       = $this->addf_disc_rpc_merge_all_product_cats($value);

			if ( ( 'specific' == $addf_disc_rpc_product_selection_op ) && !empty($all_products) ) {
				if ( $product->is_type('variation') ) {
					if ( in_array( $product->get_parent_id() , (array) $all_products ) ) {
						return '';
					}
				}
			} elseif ( $product->is_type('variation') ) {
					return '';
			}


			
			$discount_choice = get_post_meta($value, 'addf_drpc_discount_type_choice', true);
			if ('dynamic_price_adj' == $discount_choice) {

				$addf_disc_rpc_show_prc_table = get_post_meta($value, 'addf_disc_rpc_show_prc_table', true);
				$addf_dsic_rpc_template_type  = get_post_meta($value, 'addf_drpc_pricing_template_design', true)?get_post_meta($value, 'addf_drpc_pricing_template_design', true):'table';
				// $selected_cust             = (array) empty( get_post_meta($value, 'addf_disc_rpc_select_customer', true) ) ? array() : get_post_meta($value, 'addf_disc_rpc_select_customer', true);
				$selected_cust                = (array) get_post_meta($value, 'addf_disc_rpc_select_customer', true);
				$cust_choice                  = (array) get_post_meta($value, 'addf_drpc_cust_disc_choice', true);
				$disc_val_cust                = (array) get_post_meta($value, 'addf_disc_rpc_disc_val_tbl_cust', true);
				$min_qty_cust                 = (array) get_post_meta($value, 'addf_disc_rpc_min_qty_tbl_cust', true);
				$max_qty_cust                 = (array) get_post_meta($value, 'addf_disc_rpc_max_qty_tbl_cust', true);
				$replace_price_cust           = (array) get_post_meta($value, 'addf_disc_rpc_replace_prc_cust_cb', true);
				$disc_val_for_cust            = array();
				$disc_choice_for_cust         = array();
				$min_qty_for_cust             = array();
				$max_qty_for_cust             = array();
				$replace_price_for_cust       = array();

				
				if (!empty($selected_cust)) {
					foreach ($selected_cust as $disc_tbl_key => $disc_tbl_value) {
						if ($curr_user_id == $disc_tbl_value) {
							$disc_choice_for_cust[]         = $cust_choice[ $disc_tbl_key ];
							$disc_val_for_cust[]            = $disc_val_cust[ $disc_tbl_key ];
							$min_qty_for_cust[]             = $min_qty_cust[ $disc_tbl_key ];
							$max_qty_for_cust[]             = $max_qty_cust[ $disc_tbl_key ];
							$replace_price_for_cust[]       = isset($replace_price_cust[ $disc_tbl_key ])?$replace_price_cust[ $disc_tbl_key ]:'';
							
							
						}
					}
				}

				if (empty(array_filter($disc_val_for_cust))) {
					$selected_roles         = (array) get_post_meta($value, 'addf_disc_rpc_roles_select', true);
					$user_role_choice       = (array) get_post_meta($value, 'addf_drpc_discount_amount_choice', true);
					$disc_val_role          = (array) get_post_meta($value, 'addf_disc_rpc_disc_val_tbl', true);
					$min_qty_role           = (array) get_post_meta($value, 'addf_disc_rpc_min_qty_tbl', true);
					$max_qty_role           = (array) get_post_meta($value, 'addf_disc_rpc_max_qty_tbl', true);
					$replace_price_role     = (array) get_post_meta($value, 'addf_disc_rpc_replace_prc_roles_cb', true);

					
					if (!empty($selected_roles)) {
						foreach ($selected_roles as $disc_role_key => $disc_role_value) {
							if (( $curr_user_role == $disc_role_value ) || ( 'all' == $disc_role_value )) {
								$disc_choice_for_cust[]     = $user_role_choice[ $disc_role_key ];
								$disc_val_for_cust[]        = $disc_val_role[ $disc_role_key ];
								$min_qty_for_cust[]         = $min_qty_role[ $disc_role_key ];
								$max_qty_for_cust[]         = $max_qty_role[ $disc_role_key ];
								$replace_price_for_cust[]   = isset($replace_price_role[ $disc_role_key ])?$replace_price_role[ $disc_role_key ]:'';

							}
						}
					}
				}


				$array_count = count( $disc_choice_for_cust );    
								
				?>

				<table class="addf_drpc_table_for_dynamic_pricing" style="display:none">
				<?php
					$product_price = $product->get_price();
				if ( $product->is_type('variable') ) {
					$product_price = $product->get_variation_price('max');
				}
				$product_price = $this->addf_disc_rpc_add_tax_to_product_price_fn($product, $product_price);

				foreach ($disc_choice_for_cust as $key => $type) {
					$value = $disc_val_for_cust[ $key ];
					if ( '' == $value ) {
						continue;
					}
					$min              = $min_qty_for_cust[ $key ];
					$max              = $max_qty_for_cust[ $key ];
					$replace_price    = isset($replace_price_for_cust[ $key ]) && '' != $replace_price_for_cust[ $key ] ?$replace_price_for_cust[ $key ] :'no';
					$discounted_price = $this->calculate_discounted_price_for_table( $type , $product_price , $product , $value );
					$saved_amount     = ( $product_price-$discounted_price )>0? $product_price-$discounted_price: 0;
					if ( '' == $min ) {
						$min = '-';
					}
					if ( '' == $max ) {
						$max = '0';
					}
					?>
						<tr>
							<td data-replace = <?php echo esc_attr($replace_price); ?>>
							<?php echo esc_attr( $min ); ?>
							</td>
							<td>
							<?php echo esc_attr( $max ); ?>
							</td>
							<td>
							<?php
							echo wp_kses_post( wc_price( $discounted_price));
							?>
							</td>
							<td>
							<?php
							echo wp_kses_post( wc_price( $saved_amount));
							?>
							</td>
						</tr>
						<?php
				} 
				?>
				</table>
				<?php

				if ('yes' == $addf_disc_rpc_show_prc_table) {
					if ('below_cart' == get_option('addf_drpc_option_pricing_table_location') ) {
						?>
							<div class="af-price-tbl-btn-space"></div>
						<?php
					}           
					$return_value = $this->addf_disc_rpc_template_html($addf_dsic_rpc_template_type, $disc_choice_for_cust, $disc_val_for_cust, $min_qty_for_cust, $max_qty_for_cust , $product);           
				} 
			}
				return $return_value;
		}


		public function addf_disc_rpc_template_html( $addf_dsic_rpc_template_type, $disc_choice_for_cust, $disc_val_for_cust, $min_qty_for_cust, $max_qty_for_cust, $product ) {
			
			$template_html = '';
			
			if ('table' == $addf_dsic_rpc_template_type) {
				$table_style = get_option('addf_drpc_option_pricing_table_layout');

				if ('vertical' == $table_style) {
					$template_html = $this->addf_disc_rpc_vertical_table_html_cb($disc_choice_for_cust, $disc_val_for_cust, $min_qty_for_cust, $max_qty_for_cust , $product);
				} elseif ('horizontal' == $table_style) {
					$template_html = $this->addf_disc_rpc_horizontal_table_html_cb($disc_choice_for_cust, $disc_val_for_cust, $min_qty_for_cust, $max_qty_for_cust , $product);
				}
				
				return $template_html;
			} else if ('list' == $addf_dsic_rpc_template_type) {
				$template_html = $this->addf_disc_rpc_list_html_cb($disc_choice_for_cust, $disc_val_for_cust, $min_qty_for_cust, $max_qty_for_cust , $product);
				return $template_html;
			} else if ('card' == $addf_dsic_rpc_template_type) {
				$template_html = $this->addf_disc_rpc_card_html_cb($disc_choice_for_cust, $disc_val_for_cust, $min_qty_for_cust, $max_qty_for_cust , $product);
				return $template_html;
			}
		}

		// Show calculated price from table 
		public function product_price_html( $default_price, $product ) {

			$af_id           = $product->get_id();
			$current_product = wc_get_product( $af_id );
			//$current_product = wc_get_product( get_the_ID() );

			if ( $product->is_type('variable') ) {
				$variations    = $product->get_available_variations();
				$variations_id = wp_list_pluck( $variations, 'variation_id' );

				$regular_prices = array();
				$sale_prices    = array();
				foreach ($variations_id as $single_variation  ) {
					$single_var_obj = wc_get_product($single_variation);
					$return_array   = (array) $this->product_price_and_table( $single_var_obj->get_price_html() , $single_var_obj );

					if ( isset( $return_array['regular_price'] ) ) {
						if ( '' != $return_array['regular_price'] ) {
							$regular_prices[ $single_variation ] = $return_array['regular_price'];
						}
					}
					if ( isset( $return_array['sale_price'] ) ) {
						if ( '' != $return_array['sale_price'] ) {
							$sale_prices[ $single_variation ] = $return_array['sale_price'];
						}
					}
				}

				if ( is_array($regular_prices) && ( !empty($regular_prices) ) ) {
					$regular_prc_html = '';
					$sale_prc_html    = '';
					if ( floatval(min($regular_prices)) < floatval(max($regular_prices)) ) {
						$regular_prc_html = '<del>' . wc_price( min($regular_prices) ) . ' - ' . wc_price( max($regular_prices) ) . '</del>';
					} elseif ( 0 != max($regular_prices) ) {
						$regular_prc_html = '<del>' . wc_price( max($regular_prices) ) . '</del>';
					}
					if ( floatval(min($sale_prices)) < floatval(max($sale_prices)) ) {
						$sale_prc_html = '<ins>' . wc_price( min($sale_prices) ) . ' - ' . wc_price( max($sale_prices) ) . '</ins>';
					} else {
						$sale_prc_html = '<ins>' . wc_price( min($sale_prices) ) . '</ins>';
					}
					return $regular_prc_html . ' ' . $sale_prc_html;

				} elseif ( is_array($sale_prices) && ( !empty($sale_prices) ) ) {
					if ( floatval(min($sale_prices)) < floatval(max($sale_prices)) ) {
						return '<ins>' . wc_price( min($sale_prices) ) . ' - ' . wc_price( max($sale_prices) ) . '</ins>';
					} else {
						return '<ins>' . wc_price( min($sale_prices) ) . '</ins>';
					}
				}

				return $default_price;
			}


			$return_array = (array) $this->product_price_and_table( $default_price , $product );

			$check_price = $current_product->get_price();
			if ( $current_product->is_type('variable') ) {
				$check_price = $current_product->get_variation_price('max');
			} else {
				$check_price = $current_product->get_price();
			}
			if ( isset( $return_array['product_price'] ) ) {
				if ( $return_array['product_price'] == $check_price ) {
					return $default_price;
				}
			}

			$calc_reg_price = '';
			if ( array_key_exists( 'regular_price' , $return_array ) ) {
				$calc_reg_price = $return_array['regular_price'];
			}
			if ( array_key_exists( 'sale_price' , $return_array ) ) {
				$calc_sale_price = $return_array['sale_price'];
				if ( ( '' != $calc_reg_price ) && ( 0 != $calc_reg_price ) ) {
					return '<del>' . wc_price( $calc_reg_price ) . '</del> <ins>' . wc_price( $calc_sale_price ) . '</ins>' ;
				} else {
					$calc_sale_price = $return_array['product_price'];
					
					return '<ins>' . wc_price( $calc_sale_price ) . '</ins>' ;
				}
			}
			return $default_price;
		}

		public function product_price_and_table( $default_price, $product ) {
			if ( '' == $default_price ) {
				return $default_price;
			}
			if ( 'ignore' == get_option('addf_drpc_option_sale_price') ) {
				return $default_price;
			}
			if ( '' == $default_price ) {
				return $default_price;
			}
			$price = $product->get_price();
			if ( $product->is_type('variable') ) {
				$price = $product->get_variation_price('max');
			} else {
				$price = $product->get_price();
				if ( 'regular' == get_option('addf_drpc_option_sale_price') ) {
					$price = $product->get_regular_price();
				}
			}
			$product_default_price = $price;
			$bs_price_priority     = get_option('addf_drpc_option_rules_priority');
			if ( is_user_logged_in() ) {
				$user           = wp_get_current_user();
				$curr_user_role = current( $user->roles );
			} else {
				$curr_user_role = 'guest';
			}
			$parent_product_id = $product->get_id();
			$product_id        = $product->get_id();
			if ( $product->is_type('variation') ) {
				$parent_product_id = $product->get_parent_id();
			}
			$curr_user_id             = get_current_user_id();
			$all_product_rules        = array();
			$must_apply_product_rules = array();
			$product                  = wc_get_product( $product_id );
			foreach ($this->addf_disc_rpc_product_rules_obj as $key => $value) {
				if ( $this->addf_disc_rpc_user_spent_amount_check( $value ) ) {
					continue;
				}
				$priority                           = get_post_meta($value, 'addf_disc_rpc_rule_priority', true);
				$addf_disc_rpc_product_selection_op = get_post_meta( $value, 'addf_disc_rpc_product_selection_op' , true );
				$all_products                       = $this->addf_disc_rpc_merge_all_product_cats($value);
				if ( ( 'specific' == $addf_disc_rpc_product_selection_op ) && !empty($all_products) ) {
					if ( $product->is_type('variation') ) {
						if ( ( !in_array($product->get_id(), $all_products) ) && ( !in_array($product->get_parent_id(), $all_products) ) ) {
							continue;
						}
					} elseif ( !in_array( $product->get_id() , $all_products ) ) {
							continue;
					}
					if ('must_apply' == $priority) {
						$must_apply_product_rules[] = $value;
					}
						$all_product_rules[] = $value;
				} else {
					if ('must_apply' == $priority) {
						$must_apply_product_rules[] = $value;
					}
					$all_product_rules[] = $value;
				}
			}
			if ( empty($must_apply_product_rules) ) {
				$must_apply_product_rules = $all_product_rules;
			}
			$change_price_check  = false;
			$product_price_array = array(
				'regular_price'     => 0,
				'sale_price'        => $product_default_price,
				'product_price'     => $product_default_price,
				'rule_id_for_table' => 0,
			);

			$rule_id_for_price_table = 0;
			foreach ( $must_apply_product_rules as $key => $value) {
				$price_comparison_array = array(
					'type'          => 'empty',
					'regular'       => 0,
					'sale'          => 0,
					'product_price' => $product_default_price,
				);              

				$priority                           = get_post_meta($value, 'addf_disc_rpc_rule_priority', true);
				$all_products                       = $this->addf_disc_rpc_merge_all_product_cats($value);
				$addf_disc_rpc_product_selection_op = get_post_meta( $value, 'addf_disc_rpc_product_selection_op' , true );
				$discount_choice                    = get_post_meta($value, 'addf_drpc_discount_type_choice', true);
				if ('dynamic_price_adj' == $discount_choice) {
					$selected_cust            = (array) get_post_meta($value, 'addf_disc_rpc_select_customer', true);
					$cust_choice              = (array) get_post_meta($value, 'addf_drpc_cust_disc_choice', true);
					$disc_val_cust            = (array) get_post_meta($value, 'addf_disc_rpc_disc_val_tbl_cust', true);
					$min_qty_cust             = (array) get_post_meta($value, 'addf_disc_rpc_min_qty_tbl_cust', true);
					$max_qty_cust             = (array) get_post_meta($value, 'addf_disc_rpc_max_qty_tbl_cust', true);
					$replace_price_cb         = (array) get_post_meta($value, 'addf_disc_rpc_replace_prc_cust_cb', true);
					$disc_val_for_cust        = array();
					$disc_choice_for_cust     = array();
					$min_qty_for_cust         = array();
					$max_qty_for_cust         = array();
					$all_disc_val_for_cust    = array();
					$all_disc_choice_for_cust = array();
					$all_min_qty_for_cust     = array();
					$all_max_qty_for_cust     = array();
					if (!empty($selected_cust)) {
						foreach ($selected_cust as $disc_tbl_key => $disc_tbl_value) {
							$replace_price_cust_cb_value = '';
							if ( array_key_exists( $disc_tbl_key , $replace_price_cb ) ) {
								if ( 'yes' == $replace_price_cb[ $disc_tbl_key ] ) {
									$replace_price_cust_cb_value = 'yes';
								}
							}
							if ( $curr_user_id != $disc_tbl_value ) {
								continue;
							}
							if ( ( 1 == $min_qty_cust[ $disc_tbl_key ] ) &&  ( '' != $disc_val_cust[ $disc_tbl_key ] ) && ( 'yes' == $replace_price_cust_cb_value ) && ( 'fixed_price' == $cust_choice[ $disc_tbl_key ] ) ) {
								$price                  = $this->addf_disc_rpc_add_tax_to_product_price_fn( $product , $disc_val_cust[ $disc_tbl_key ] );
								$price_comparison_array = array(
									'type'          => 'replace',
									'regular'       => '',
									'sale'          => $price,
									'product_price' => $price,
								);

								if ( 'follow_sequence' == $bs_price_priority ) {
									$product_price_array = array(
										'regular_price' => 0,
										'sale_price'    => $price,
										'product_price' => $price,
										'rule_id_for_table' => $value,
									);

								} else {
									
									$return_array        = $this->compare_price_array_in_loop( $price_comparison_array , $product_price_array , $product_default_price , $value );
									$product_price_array = array(
										'regular_price' => $return_array['regular_price'],
										'sale_price'    => $return_array['sale_price'],
										'product_price' => $return_array['product_price'],
										'rule_id_for_table' => $return_array['rule_id_for_table'],
									);

									$change_price_check = true;
									continue 2;
								}
							} else {
								$all_disc_choice_for_cust[] = $cust_choice[ $disc_tbl_key ];
								$all_disc_val_for_cust[]    = $disc_val_cust[ $disc_tbl_key ];
								$all_min_qty_for_cust[]     = $min_qty_cust[ $disc_tbl_key ];
								$all_max_qty_for_cust[]     = $max_qty_cust[ $disc_tbl_key ];

							}
						}
					}
					//  Selecting price from role array
					if ( empty($disc_val_for_cust)  ) {
						$disc_val_for_cust    = $all_disc_val_for_cust;
						$disc_choice_for_cust = $all_disc_choice_for_cust;
						$min_qty_for_cust     = $all_min_qty_for_cust;
						$max_qty_for_cust     = $all_max_qty_for_cust;
					}

					if ( empty( $this->af_remove_all_empty_spaces( $disc_val_for_cust ) ) && empty( $this->af_remove_all_empty_spaces( $all_disc_val_for_cust ) ) ) {
						$selected_roles        = (array) get_post_meta($value, 'addf_disc_rpc_roles_select', true);
						$user_role_choice      = (array) get_post_meta($value, 'addf_drpc_discount_amount_choice', true);
						$disc_val_role         = (array) get_post_meta($value, 'addf_disc_rpc_disc_val_tbl', true);
						$min_qty_role          = (array) get_post_meta($value, 'addf_disc_rpc_min_qty_tbl', true);
						$max_qty_role          = (array) get_post_meta($value, 'addf_disc_rpc_max_qty_tbl', true);
						$replace_price_role_cb = (array) get_post_meta($value, 'addf_disc_rpc_replace_prc_roles_cb', true);
						if (!empty($selected_roles)) {
							foreach ($selected_roles as $disc_role_key => $disc_role_value) {
								$replace_price_role_cb_value = '';
								if ( array_key_exists( $disc_role_key , $replace_price_role_cb ) ) {
									if ( 'yes' == $replace_price_role_cb[ $disc_role_key ] ) {
										$replace_price_role_cb_value = $replace_price_role_cb[ $disc_role_key ];
									}
								}
								if ( ( $curr_user_role != $disc_role_value ) && ( 'all' != $disc_role_value ) ) {
									continue;
								}
								if ( ( 'fixed_price' == $user_role_choice[ $disc_role_key ] ) && ( 'yes' == $replace_price_role_cb_value ) && ( 1 == $min_qty_role[ $disc_role_key ] ) && ( '' != $disc_val_role[ $disc_role_key ] ) ) {
										$price                  = $this->addf_disc_rpc_add_tax_to_product_price_fn( $product , $disc_val_role[ $disc_role_key ] );
										$change_price_check     = true;
										$price_comparison_array = array(
											'type'    => 'replace',
											'regular' => '',
											'sale'    => $price,
											'product_price' => $price,
										);

										if ( 'follow_sequence' == $bs_price_priority ) {
											$product_price_array = array(
												'regular_price' => 0,
												'sale_price' => $price,
												'product_price' => $price,
												'rule_id_for_table' => $value,
											);
												break;
										} else {

											$return_array        = $this->compare_price_array_in_loop( $price_comparison_array , $product_price_array , $product_default_price , $value );
											$product_price_array = array(
												'regular_price' => $return_array['regular_price'],
												'sale_price' => $return_array['sale_price'],
												'product_price' => $return_array['product_price'],
												'rule_id_for_table' => $return_array['rule_id_for_table'],
											);

												continue 2;
										}
								} else {
									$all_disc_choice_for_cust[] = $user_role_choice[ $disc_role_key ];
									$all_disc_val_for_cust[]    = $disc_val_role[ $disc_role_key ];
									$all_min_qty_for_cust[]     = $min_qty_role[ $disc_role_key ];
									$all_max_qty_for_cust[]     = $max_qty_role[ $disc_role_key ];
								}
							}
						}
					}
					
					$key_to_replace = -1;
					if ( empty( $this->af_remove_all_empty_spaces( $disc_val_for_cust ) ) ) {
						$disc_val_for_cust    = $all_disc_val_for_cust;
						$disc_choice_for_cust = $all_disc_choice_for_cust;
						$min_qty_for_cust     = $all_min_qty_for_cust;
						$max_qty_for_cust     = $all_max_qty_for_cust;
					}
					
					$product_price               = wc_get_product( $product->get_id() )->get_price();
					$addf_drpc_option_sale_price = get_option( 'addf_drpc_option_sale_price' );
					if ( $product->is_type('variable') ) {
						$product_price = $product->get_variation_price('max');
					}
					if ('regular' == $addf_drpc_option_sale_price) {
						if ( $product->is_type('variable') ) {
							$product_price = $product->get_variation_price('max');
						} else {
							$product_price = wc_get_product( $product->get_id() )->get_regular_price();
						}
					} elseif ( 'ignore' == $addf_drpc_option_sale_price ) {
						continue;
					}

					foreach ( $disc_val_for_cust as $rep_key => $rep_value ) {
						if ( '' == $rep_value ) {
							continue;
						}
						if ( 1 == $min_qty_for_cust[ $rep_key ] ) {
							$key_to_replace = $rep_key;
							break;
						}
					}

					if ( 0 <= $key_to_replace ) {
						$disc_choice_user     = $disc_choice_for_cust[ $key_to_replace ];
						$disc_value_for_price = $disc_val_for_cust[ $key_to_replace ];
						$disc_value_for_price = floatval( $disc_value_for_price);
						$product_price        = floatval( $product_price);
						if ( 'fixed_price' == $disc_choice_user ) {
							$reg_price              = $this->addf_disc_rpc_add_tax_to_product_price_fn( $product , $product_price );
							$sale_price             = $this->addf_disc_rpc_add_tax_to_product_price_fn( $product , $disc_value_for_price );
							$price_comparison_array = array(
								'type'          => 'both',
								'regular'       => $reg_price,
								'sale'          => $sale_price,
								'product_price' => $sale_price,
							);

						} elseif ( 'fixed_price_increase' == $disc_choice_user ) {
							$disc_value_for_price   = $product_price + $disc_value_for_price;
							$price                  = $this->addf_disc_rpc_add_tax_to_product_price_fn( $product , $disc_value_for_price );
							$price_comparison_array = array(
								'type'          => 'replace',
								'regular'       => '',
								'sale'          => $price,
								'product_price' => $price,
							);

						} elseif ( 'fixed_price_decrease' == $disc_choice_user ) {
							$disc_value_for_price   = $product_price - $disc_value_for_price;
							$reg_price              = $this->addf_disc_rpc_add_tax_to_product_price_fn( $product , $product_price );
							$sale_price             = $this->addf_disc_rpc_add_tax_to_product_price_fn( $product , $disc_value_for_price );
							$price_comparison_array = array(
								'type'          => 'both',
								'regular'       => $reg_price,
								'sale'          => $sale_price,
								'product_price' => $sale_price,
							);

						} elseif ( 'fixed_percent_increase' == $disc_choice_user ) {
							$disc_value_for_price   = ( $product_price / 100 ) * $disc_value_for_price;
							$disc_value_for_price   = $product_price + $disc_value_for_price;
							$price                  = $this->addf_disc_rpc_add_tax_to_product_price_fn( $product , $disc_value_for_price );
							$price_comparison_array = array(
								'type'          => 'replace',
								'regular'       => '',
								'sale'          => $price,
								'product_price' => $price,
							);

						} elseif ( 'fixed_percent_decrease' == $disc_choice_user ) {
							$disc_value_for_price   = ( $product_price / 100 ) * $disc_value_for_price;
							$disc_value_for_price   = $product_price - $disc_value_for_price;
							$reg_price              = $this->addf_disc_rpc_add_tax_to_product_price_fn( $product , $product_price );
							$sale_price             = $this->addf_disc_rpc_add_tax_to_product_price_fn( $product , $disc_value_for_price );
							$price_comparison_array = array(
								'type'          => 'both',
								'regular'       => $reg_price,
								'sale'          => $sale_price,
								'product_price' => $sale_price,
							);

						}
					}
					$change_price_check = true;
					if ( 'follow_sequence' == $bs_price_priority ) {
						$rule_id_for_price_table = $value;

						$product_price_array = array(
							'regular_price'     => $price_comparison_array['regular'],
							'sale_price'        => $price_comparison_array['sale'],
							'product_price'     => $price_comparison_array['product_price'],
							'rule_id_for_table' => $value,
						);

						if ( 'empty' != $price_comparison_array['type']  ) {
							break;
						}
					} else {
						
						$return_array        = $this->compare_price_array_in_loop( $price_comparison_array , $product_price_array , $product_default_price , $value );
						$product_price_array = array(
							'regular_price'     => $return_array['regular_price'],
							'sale_price'        => $return_array['sale_price'],
							'product_price'     => $return_array['product_price'],
							'rule_id_for_table' => $return_array['rule_id_for_table'],
						);

							$rule_id_for_price_table = $value;
					}

				} else {
					$change_price_check = true;
					if ( 'follow_sequence' == $bs_price_priority ) {
						$rule_id_for_price_table = $value;
						$product_price_array     = array(
							'regular_price'     => $price_comparison_array['regular'],
							'sale_price'        => $price_comparison_array['sale'],
							'product_price'     => $price_comparison_array['product_price'],
							'rule_id_for_table' => $value,
						);

						break;
					} else {
						$rule_id_for_price_table = $value;
						$return_array            = $this->compare_price_array_in_loop( $price_comparison_array , $product_price_array , $product_default_price , $value );
						$product_price_array     = array(
							'regular_price'     => $return_array['regular_price'],
							'sale_price'        => $return_array['sale_price'],
							'product_price'     => $return_array['product_price'],
							'rule_id_for_table' => $return_array['rule_id_for_table'],
						);
					}
				}
			}
			$product_price_array['rule_id'] = $rule_id_for_price_table;
			return $product_price_array;
		}


		public function compare_price_array_in_loop( $price_comparison_array, $product_price_array, $product_price, $rule_id ) {

			if ( $product_price == $product_price_array['product_price'] ) {
				$internal_price      = $price_comparison_array['product_price'];
				$product_price_array = array(
					'regular_price'     => $price_comparison_array['regular'],
					'sale_price'        => $internal_price,
					'product_price'     => $internal_price,
					'rule_id_for_table' => $rule_id,
				);

			}
			$bs_price_priority = get_option('addf_drpc_option_rules_priority');
			if ( 'smaller_price' == $bs_price_priority ) {
				$internal_price = $price_comparison_array['product_price'];
				$global_price   = $product_price_array['product_price'];
				if ( $internal_price < $global_price ) {
					$product_price_array = array(
						'regular_price'     => $price_comparison_array['regular'],
						'sale_price'        => $internal_price,
						'product_price'     => $internal_price,
						'rule_id_for_table' => $rule_id,
					);
				}
			} elseif ( 'more_price' == $bs_price_priority ) {
				$internal_price = $price_comparison_array['product_price'];
				$global_price   = $product_price_array['product_price'];
				if ( $internal_price > $global_price ) {
					$product_price_array = array(
						'regular_price'     => $price_comparison_array['regular'],
						'sale_price'        => $internal_price,
						'product_price'     => $internal_price,
						'rule_id_for_table' => $rule_id,
					);
				}
			}
			return $product_price_array;
		}

		// showing Calculated discounted price in mini cart 
		public function show_price_cart_basket( $price, $cart_item, $cart_item_key ) {
			
			$this->apply_discounts_before_cart_totals( wc()->cart );
			if ( is_cart() ) {
				return $price;
			}
			$cart    = wc()->cart->get_cart();
			$product = $cart_item['data'];
			$this->af_free_gift_price_fn();
			$args = array(
				'qty'   => 1,
				'price' => $cart[ $cart_item_key ]['data']->get_price(),
			);

			if ( wc()->cart->display_prices_including_tax() ) {
				$price = wc_get_price_including_tax( $product  , $args );
			} else {
				$price = wc_get_price_excluding_tax( $product  , $args );
			}

			$price = wc_price($price);

			return $price;
		}

		// Apply tax on calculated price for product on shop/product page
		public function addf_disc_rpc_add_tax_to_product_price_fn( $product, $price ) {
			$args             = array(
				'qty'   => 1,
				'price' => $price,
			);
			$price_to_display = wc_get_price_to_display( $product , $args );
			return $price_to_display;
		}

		public function calculate_discounted_price_for_table( $type, $product_price, $product, $value ) {
			$value        = abs($value);
			
			$percentage   = ( $product_price / 100 ) * $value;
			$return_price = $product_price;
			if ('fixed_price' == $type) {
				$return_price = $value;
			} elseif ('fixed_price_increase' == $type) {
				$return_price = $product_price + $value;
			} elseif ('fixed_price_decrease' == $type) {
				$return_price = $product_price - $value;
			} elseif ('fixed_percent_increase' == $type) {
				$return_price = $product_price - $percentage;
			} elseif ('fixed_percent_decrease' == $type) {
				$return_price = $product_price - $percentage;
			}
			if ( 0 > $return_price ) {
				$return_price = 0;
			}
			return $this->addf_disc_rpc_add_tax_to_product_price_fn( $product, $return_price );
		}

		public function af_remove_all_empty_spaces( $array ) {
			$array = (array) $array;

			foreach ( $array as $key => $value ) {

				if ( '' == $value ) {
					unset( $array[ $key ] );
				}
			}
			return $array;
		}
		// Vertical table shown on product page
		public function addf_disc_rpc_vertical_table_html_cb( $disc_choice_for_cust, $disc_val_for_cust, $min_qty_for_cust, $max_qty_for_cust, $product ) {

			$variation_class_tbl = 'af_drpc_variable_table';
			if ( $product->is_type('variation') ) {
				$variation_class_tbl = 'af_drpc_variation_table';
			}
			if ( empty( $this->af_remove_all_empty_spaces( $disc_val_for_cust ) ) ) {
				return '';
			}
			ob_start();
			if ('no' != $this->addf_drpc_enable_template_heading || 'no' != $this->addf_drpc_enable_template_icon) {
				?>
				<div class="addf_drpc_template_header">
				<img src=<?php echo esc_url($this->addf_drpc_template_icon); ?> class="addf_drpc_deals_icon" >
				<h2 style="font-size: <?php echo esc_attr($this->addf_drpc_template_heading_font_size) . 'px'; ?>"> <?php echo esc_attr($this->addf_drpc_template_heading_text); ?></h2>
				</div>
				<?php
			}
			?>
			<div class="addf_drpc_vertical_tbl_div addf_drpc_table_div <?php echo esc_attr($variation_class_tbl); ?>">
				<table class="addf_drpc_vertical_tbl">
					<thead>
						<tr>
							<th><?php echo esc_html__('Min', 'woo-af-drpc'); ?></th>
							<th><?php echo esc_html__('Max', 'woo-af-drpc'); ?></th>
							<th><?php echo esc_html__('Price', 'woo-af-drpc'); ?></th>
							<th class='addf_drpc_save_column'><?php echo esc_html__('Save', 'woo-af-drpc'); ?></th>
						</tr>
					</thead>
					<?php
					$product_price = $product->get_price();
					if ( $product->is_type('variable') ) {
						$product_price = $product->get_variation_price('max');
					}
					$product_price = $this->addf_disc_rpc_add_tax_to_product_price_fn($product, $product_price);
					foreach ($disc_choice_for_cust as $key => $type) {
						$value = $disc_val_for_cust[ $key ];
						if ( '' == $value ) {
							continue;
						}
						$min              = $min_qty_for_cust[ $key ];
						$max              = $max_qty_for_cust[ $key ];
						$discounted_price = $this->calculate_discounted_price_for_table( $type , $product_price , $product , $value );
						$saved_amount     = ( $product_price-$discounted_price )>0? $product_price-$discounted_price: 0;
						if ( '' == $min ) {
							$min = '-';
						}
						if ( '' == $max ) {
							$max = '-';
						}
						?>
						<tr>
							<td>
								<?php echo esc_attr( $min ); ?>
							</td>
							<td>
								<?php echo esc_attr( $max ); ?>
							</td>
							<td>
								<?php
								echo wp_kses_post( wc_price( $discounted_price));
								?>
							</td>
							<td class='addf_drpc_save_column'>
								<?php
								echo wp_kses_post( wc_price( $saved_amount));
								?>
							</td>
						</tr>
						<?php
					} 
					?>
				</table>
			</div>
				<?php
			return ob_get_clean();
		}

		// Horizontal table shown on product page
		public function addf_disc_rpc_horizontal_table_html_cb( $disc_choice_for_cust, $disc_val_for_cust, $min_qty_for_cust, $max_qty_for_cust, $product ) {
			$variation_class_tbl = 'af_drpc_variable_table';
			if ( $product->is_type('variation') ) {
				$variation_class_tbl = 'af_drpc_variation_table';
			}
			if ( empty( $this->af_remove_all_empty_spaces( $disc_val_for_cust ) ) ) {
				return '';
			}
			ob_start();
			if ('no' != $this->addf_drpc_enable_template_heading || 'no' != $this->addf_drpc_enable_template_icon) {
				?>
				<div class="addf_drpc_template_header">
				<img src=<?php echo esc_url($this->addf_drpc_template_icon); ?> class="addf_drpc_deals_icon" >
				<h2 style="font-size: <?php echo esc_attr($this->addf_drpc_template_heading_font_size) . 'px'; ?>"> <?php echo esc_attr($this->addf_drpc_template_heading_text); ?></h2>
				</div>
				<?php
			}
			?>
			<div class="addf_drpc_horizontal_tbl_div addf_drpc_table_div <?php echo esc_attr($variation_class_tbl); ?>">
				<table class="addf_drpc_horizontal_tbl">
					<tr>
						<th>
							<strong>
								<?php echo esc_html__('Range (Qty)', 'woo-af-drpc'); ?>
							</strong>
						</th>
						<?php
						$product_price = $product->get_price();
						if ( $product->is_type('variable') ) {
							$product_price = $product->get_variation_price('max');
						}
						foreach ($min_qty_for_cust as $key => $value) {
							$end = $max_qty_for_cust[ $key ];   
							if ('' == $end) {
								$end = '+';
							} else {
								$end = '-' . $end;
							} 
							?>
							<td>
								<strong>
									<?php echo esc_html__($value . $end, 'woo-af-drpc'); ?>
								</strong>
							</td>
							<?php
						} 
						?>
					</tr>
					<tr>
						<th>
							<strong>
								<?php echo esc_html__('Price', 'woo-af-drpc'); ?>
							</strong>
						</th>
						<?php
						foreach ( $disc_val_for_cust as $key => $value) {
							$addf_drpc_discount_amount_choice = $disc_choice_for_cust[ $key ];
							?>
							<td>
								<?php 
								if ('' == $value) {
									$value = '-';
								} else {
									echo wp_kses_post( wc_price( $this->calculate_discounted_price_for_table( $addf_drpc_discount_amount_choice , $product_price , $product , $value )));
								} 
								?>
							</td>
							<?php
						} 
						?>
					</tr>
					<tr class='addf_drpc_save_column'>
						<th>
							<strong>
								<?php echo esc_html__('Save', 'woo-af-drpc'); ?>
							</strong>
						</th>
						<?php
						$product_price = $this->addf_disc_rpc_add_tax_to_product_price_fn($product, $product_price);
						foreach ( $disc_val_for_cust as $key => $value) {
							$addf_drpc_discount_amount_choice = $disc_choice_for_cust[ $key ];
							$discounted_price = $this->calculate_discounted_price_for_table( $addf_drpc_discount_amount_choice , $product_price , $product , $value );
							$saved_amount     = ( $product_price-$discounted_price )>0? $product_price-$discounted_price: 0;
							?>
							<td>
								<?php 
								if ('' == $value) {
									$value = '-';
								} else {
									echo wp_kses_post( wc_price( $saved_amount ));
								} 
								?>
							</td>
							<?php
						} 
						?>
					</tr>
				</table>
			</div>
			<?php
			return ob_get_clean();
		}
		

		//list shown on product page
		public function addf_disc_rpc_list_html_cb( $disc_choice_for_cust, $disc_val_for_cust, $min_qty_for_cust, $max_qty_for_cust, $product ) {
			$variation_class_tbl = 'af_drpc_variable_card';
			if ( $product->is_type('variation') ) {
				$variation_class_tbl = 'af_drpc_variation_card';
			}
			if ( empty( $this->af_remove_all_empty_spaces( $disc_val_for_cust ) ) ) {
				return '';
			}
			ob_start();
		
			if ('no' != $this->addf_drpc_enable_template_heading || 'no' != $this->addf_drpc_enable_template_icon) {
				?>
				<div class="addf_drpc_template_header">
				<img src=<?php echo esc_url($this->addf_drpc_template_icon); ?> class="addf_drpc_deals_icon" >
				<h2 style="font-size: <?php echo esc_attr($this->addf_drpc_template_heading_font_size) . 'px'; ?>"> <?php echo esc_attr($this->addf_drpc_template_heading_text); ?></h2>
				</div>
				<?php
			}
			?>
			<div class="addf_drpc_list_div <?php echo esc_attr($variation_class_tbl); ?>">
					<?php
					$product_price = $product->get_price();
					if ( $product->is_type('variable') ) {
						$product_price = $product->get_variation_price('max');
					}
					$product_price = $this->addf_disc_rpc_add_tax_to_product_price_fn($product, $product_price);
					foreach ($disc_choice_for_cust as $key => $type) {
						$value = $disc_val_for_cust[ $key ];
						if ( '' == $value ) {
							continue;
						}
					
						$min_qty              = $min_qty_for_cust[ $key ];
						$max_qty              = $max_qty_for_cust[ $key ];
						$discounted_price = $this->calculate_discounted_price_for_table( $type , $product_price , $product , $value );
						$saved_amount     = ( $product_price-$discounted_price )>0? $product_price-$discounted_price: 0;
						$discount_percentage = round(( $saved_amount / $product_price ) * 100);

						$discount_text = $saved_amount > 0 ? '<del>' . wc_price($product_price) . '</del>' : '<span class="addf_drpc_no_discount">No Discount</span>';
						
						$headingText = "Buy $min_qty or more";
						$headingText .= $saved_amount > 0 ? " & save upto $discount_percentage%" : '';

						?>
						<div class="addf_drpc_list_box" data-min-qty=<?php echo esc_attr($min_qty); ?>>
							<div class="addf_drpc_list_inner_container">
								<div class="addf_drpc_radio_div"></div>
								<div class="heading"><?php echo esc_attr($headingText); ?></div>
								<div class="addf_drpc_list_price_text">
									<p><?php echo wp_kses_post( wc_price($discounted_price) ) . '/each'; ?></p>
									<p><?php echo wp_kses_post($discount_text ); ?></p>
								</div>
							</div>
						</div>
							

							<?php
					}
					?>
				</div>
				<?php
				return ob_get_clean();
		}

		//card shown on product page
		public function addf_disc_rpc_card_html_cb( $disc_choice_for_cust, $disc_val_for_cust, $min_qty_for_cust, $max_qty_for_cust, $product ) {
			$variation_class_tbl = 'af_drpc_variable_card';
			if ( $product->is_type('variation') ) {
				$variation_class_tbl = 'af_drpc_variation_card';
			}
			if ( empty( $this->af_remove_all_empty_spaces( $disc_val_for_cust ) ) ) {
				return '';
			}
			ob_start();
		
			if ('no' != $this->addf_drpc_enable_template_heading || 'no' != $this->addf_drpc_enable_template_icon) {
				?>
				<div class="addf_drpc_template_header">
				<img src=<?php echo esc_url($this->addf_drpc_template_icon); ?> class="addf_drpc_deals_icon" >
				<h2 style="font-size: <?php echo esc_attr($this->addf_drpc_template_heading_font_size) . 'px'; ?>"> <?php echo esc_attr($this->addf_drpc_template_heading_text); ?></h2>
				</div>
				<?php
			}
			?>
			<div class="addf_drpc_card_div <?php echo esc_attr($variation_class_tbl); ?>">
					<?php
					$product_price = $product->get_price();
					if ( $product->is_type('variable') ) {
						$product_price = $product->get_variation_price('max');
					}
					$product_price = $this->addf_disc_rpc_add_tax_to_product_price_fn($product, $product_price);
					foreach ($disc_choice_for_cust as $key => $type) {
						$value = $disc_val_for_cust[ $key ];
						if ( '' == $value ) {
							continue;
						}
					
						$min_qty              = $min_qty_for_cust[ $key ];
						$max_qty              = $max_qty_for_cust[ $key ];
						$discounted_price = $this->calculate_discounted_price_for_table( $type , $product_price , $product , $value );
						$saved_amount     = ( $product_price-$discounted_price )>0? $product_price-$discounted_price: 0;
						$discount_percentage = round(( $saved_amount / $product_price ) * 100);

						$discount_text = $saved_amount > 0 ? '<del>' . wc_price($product_price) . '</del>' : '<span class="addf_drpc_no_discount">No Discount</span>';
						$headingText = "Buy $min_qty  or more";

						?>
							<div class="addf_drpc_inner_small_box" data-min-qty=<?php echo esc_attr($min_qty); ?>>
								<div class="addf_drpc_offer_data_contianer">
									<div class="addf_drpc_card_inner_heading"><?php echo esc_attr($headingText); ?></div>
									<div class="addf_drpc_card_inner_text">
										<p><?php echo wp_kses_post( wc_price($discounted_price) ) . '/each'; ?> </p>
										<p><?php echo wp_kses_post($discount_text ); ?> </p>
									</div>
								</div>
								<div class="addf_drpc_sale_tag"><?php echo esc_attr($discount_percentage) . '%'; ?></div>
							</div>

							<?php
					}
					?>
				</div>
				<?php
				return ob_get_clean();
		}
	}
	new AF_Product_Discount_Front();
}

