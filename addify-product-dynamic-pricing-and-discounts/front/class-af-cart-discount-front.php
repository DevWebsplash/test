<?php
defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'AF_Cart_Discount_Front' ) ) {
	class AF_Cart_Discount_Front {

		public function __construct() {
			// All Cart Rules
			$this->addf_disc_rpc_cart_rules_obj = $this->addf_disc_rpc_cart_all_rules_fn();

			// Adding css/js
			add_action('wp_enqueue_scripts', array( $this, 'add_scripts' ));

			// Cart before total
			add_action( 'woocommerce_before_calculate_totals' , array( $this, 'apply_discount_before_cart_total' ) , 90 , 1 );

			// replace quantity
			add_filter( 'woocommerce_cart_item_quantity', array( $this, 'disable_gift_quantity_cart' ), 10, 2 ); 

			// Cart after subtotal
			add_action( 'woocommerce_cart_calculate_fees' , array( $this, 'apply_discount_on_cart' ) , 90 , 1 );

			// show gift item text gift product on cart page
			add_filter('woocommerce_get_item_data', array( $this, 'gift_product_label_cart' ), 10 , 2 );

			// show gift item text gift product on checkout page
			add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'gift_product_label_after_checkout' ) , 10, 4 );

			// remove price for gift products 
			add_filter('woocommerce_cart_item_price', array( $this, 'show_price_cart_basket' ), 100, 3);
		}

		// Add css
		public function add_scripts() {
			wp_enqueue_style( 'addf_drpc_css', plugins_url('../includes/css/addf-drpc-style.css', __FILE__ ), false, '1.0.0' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'drpc-front-script', plugins_url( '../includes/js/addf-drpc-front.js', __FILE__ ), false, '1.0.0' , $in_footer = false );

			$addf_drpc_data = array(
				'admin_url'         => admin_url( 'admin-ajax.php' ),
				'nonce'             => wp_create_nonce( 'addf-drpc-ajax-nonce' ),
			);
			wp_localize_script( 'drpc-front-script', 'addf_drpc_php_vars', $addf_drpc_data );
		}

		// All eligible cart rules
		public function addf_disc_rpc_cart_all_rules_fn() {
			$disc_all_rules = array(
				'post_type'        => 'af_dis_cart_rule',
				'post_status'      => 'publish',
				'numberposts'      =>   '-1',
				'fields'           => 'ids',
				'orderby'          => 'menu_order',
				'order'            => 'ASC',
				'suppress_filters' => true,
				'meta_query'       => array(
					array(
						'key'     => 'addf_disc_rpc_start_time',
						'value'   => gmdate( 'Y-m-d' ),
						'compare' => '<=',
					),
				),
			);
			$all_rule_posts = get_posts( $disc_all_rules );
			$all_posts      = array();
			foreach ( $all_rule_posts as $key => $value ) {
				// check days in rule
				if ( $this->addf_disc_rpc_verify_curr_cust_user_roles_date_of_rule( $value ) ) {
					continue;
				}
				$end_date = get_post_meta( $value , 'addf_disc_rpc_end_time' , true );
				if ( ''!= $end_date ) {
					if ( $end_date <= gmdate( 'Y-m-d' ) ) {
						continue;
					}
				}
				$all_posts[] = $value;
			}
			return $all_posts;
		}

		// check days in rule
		public function addf_disc_rpc_verify_curr_cust_user_roles_date_of_rule( $value ) {
			$addf_disc_rpc_days_radio = get_post_meta( $value , 'addf_disc_rpc_days_radio' , true );
			$addf_disc_week_days_arr  = (array) get_post_meta( $value , 'addf_disc_week_days_arr' , true );
			$today                    = gmdate('l');
			if ( ( 'specific' == $addf_disc_rpc_days_radio )  && ( !in_array( $today , $addf_disc_week_days_arr ) ) && ( !empty($addf_disc_week_days_arr) ) ) {
				return true;
			}
			return false;
		}

		// check minimum amount spent by user in rule
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

		// remove price for gift products
		public function apply_discount_before_cart_total( $cart ) {
			foreach ($cart->get_cart() as $cart_key => $cart_value) {
				if ( array_key_exists( 'addf_disc_rpc_cart_rule_gift_item' , $cart_value ) ) {
						$cart_value['data']->set_price(0);
				}
			}
		}

		// remove price for gift product from basket
		public function show_price_cart_basket( $price, $cart_item, $cart_item_key ) {
			if ( array_key_exists( 'addf_disc_rpc_cart_rule_gift_item' , $cart_item ) ) {
				$price = wc_price(0);
			}
			return $price;
		}


		// replace product quantity for gift products in cart
		public function disable_gift_quantity_cart( $product_quantity, $cart_item_key ) {
			$cart_all = WC()->cart->get_cart();
			if ( array_key_exists( 'addf_disc_rpc_cart_rule_gift_item' , $cart_all[ $cart_item_key ] ) ) {
				return $cart_all[ $cart_item_key ]['quantity'];
			} else {
				return $product_quantity;
			}
		}


		// remove all gifts from specific rule
		public function addf_disc_rpc_remove_all_gift_for_specific_rule_fn( $value ) {
			$cart = wc()->cart;
			foreach ( $cart->get_cart() as $_key_cart => $_key_value) {
				if ( array_key_exists( 'addf_disc_rpc_cart_rule_gift_item' , $_key_value ) ) {
					$gift_unique_cart = $_key_value['addf_disc_rpc_cart_rule_gift_item'];
					if ( $value == $gift_unique_cart['rule_id'] ) {
						WC()->cart->remove_cart_item( $_key_value['key'] );
					}
				}
			}
		}

		// After cart total function
		public function apply_discount_on_cart( $cart ) {
			$addf_drpc_option_multi_rule           = get_option( 'addf_drpc_option_multi_rule' );
			$addf_disc_rpc_do_not_allow_cart_rules = false;
			foreach ( $cart->get_cart() as $key => $value) {
				if ( 'both' == $addf_drpc_option_multi_rule ) {
					break;
				}
				// remove cart gift if only products rules are applied
				if ( array_key_exists( 'addf_disc_rpc_cart_rule_gift_item' , $value ) ) {
					WC()->cart->remove_cart_item( $value['key'] );
					$addf_disc_rpc_do_not_allow_cart_rules = true;
				}
				$product_id_in_cart = $value['product_id'];
				if ( wc_get_product($product_id_in_cart)->is_type('variable') ) {
					$product_id_in_cart = $value['variation_id'];
				}
				$cart_product_obj   = wc_get_product($product_id_in_cart);
				$product_price      = $cart_product_obj->get_price();
				$cart_product_price = $value['data']->get_price();
				if ( $product_price != $cart_product_price ) {
					$addf_disc_rpc_do_not_allow_cart_rules = true;
				}
			}
			// return if only product rules are applied
			if ( $addf_disc_rpc_do_not_allow_cart_rules ) {
				return;
			}
			$priority_setting = get_option( 'addf_drpc_option_rules_cart_priority' );
			if ( is_user_logged_in() ) {
				$user           = wp_get_current_user();
				$curr_user_role = current( $user->roles );
			} else {
				$curr_user_role = 'guest';
			}
			$curr_user_id          = get_current_user_id();
			$all_cart_rules        = array();
			$must_apply_cart_rules = array();
			foreach ( $cart->get_cart() as $cart_key => $cart_value) {
				$cart_product_id   = $cart_value['product_id'];
				$cart_product      = wc_get_product( $cart_product_id );
				$cart_variation_id = $cart_value['variation_id'];
				foreach ( $this->addf_disc_rpc_cart_rules_obj as $key => $value) {
					// check minimum amount spent by user
					if ( $this->addf_disc_rpc_user_spent_amount_check( $value ) ) {
						// remove gift products from rule where minimum spent amount 
						$this->addf_disc_rpc_remove_all_gift_for_specific_rule_fn( $value );
						continue;
					}
					// All merged products
					$all_products                       = $this->addf_disc_rpc_merge_all_product_cats( $value );
					$addf_disc_rpc_product_selection_op = get_post_meta( $value, 'addf_disc_rpc_product_selection_op' , true );
					$addf_disc_rpc_rule_priority        = get_post_meta( $value , 'addf_disc_rpc_rule_priority' , true );
					if ( ( 'specific' == $addf_disc_rpc_product_selection_op ) || ( empty( $all_products ) ) ) {
						if ( ( in_array( $cart_product_id , $all_products ) ) || ( empty( $all_products ) ) || ( ( $cart_product->is_type('variable') ) &&  ( in_array( $cart_variation_id , $all_products ) ) ) ) {
							if ( !in_array( $value , $all_cart_rules ) ) {
								if ( 'must_apply' == $addf_disc_rpc_rule_priority ) {
									if ( !in_array( $value , $must_apply_cart_rules ) ) {
										$must_apply_cart_rules[] = $value;
									}
								}
								if ( !in_array( $value , $all_cart_rules ) ) {
									$all_cart_rules[] = $value;
								}
							}
						}
					} else {
						if ( 'must_apply' == $addf_disc_rpc_rule_priority ) {
							if ( !in_array( $value , $must_apply_cart_rules ) ) {
								$must_apply_cart_rules[] = $value;
							}
						}
						if ( !in_array( $value , $all_cart_rules ) ) {
							$all_cart_rules[] = $value;
						}
					}
				}
			}
			// check and apply must rules if exists

			if ( !empty( $must_apply_cart_rules ) ) {
				$all_cart_rules = $must_apply_cart_rules;
			}
			$total_fee_or_discount_from_all = 0;
			$apply_all_discount_from_all    = 0;
			foreach ( $cart->get_cart() as $key => $value) {
				if ( array_key_exists( 'addf_disc_rpc_cart_rule_gift_item' , $value ) ) {
					$gift_item_arr = $value['addf_disc_rpc_cart_rule_gift_item'];
					if ( !in_array( $gift_item_arr['rule_id'] , $all_cart_rules ) ) {
						WC()->cart->remove_cart_item( $value['key'] );
					}
				}
			}
			$addf_disc_rpc_cart_rule_notices = array();
			foreach ( $all_cart_rules as $key => $value) {
				$total_fee_or_discount         = 0;
				$discount_gift_applied_check   = false;
				$cart_qty_of_selected          = 0;
				$cart_qty_product_string       = '';
				$cart_qty_of_selected_for_gift = 0;
				$price_added                   = 0;
				$discount_choice               = get_post_meta( $value , 'addf_drpc_discount_type_choice' , true );

				// cart loop to calculate price and quantity of selected products
				foreach ( $cart->get_cart() as $cart_key => $cart_value) {
					if (array_key_exists('addf_disc_rpc_product_rule_gift_item', $cart_value) || array_key_exists('addf_disc_rpc_cart_rule_gift_item', $cart_value)) {
						continue;
					}
					// continue if the current product if a gift product from product rule or cart rules
					$cart_product_id     = $cart_value['product_id'];
					$cart_product_var_id = $cart_value['product_id'];
					$cart_product        = wc_get_product( $cart_product_id );
					if ( $cart_product->is_type('variable') ) {
						$cart_product_var_id = $cart_value['variation_id'];
					}
					$cart_product_price                 = $cart_value['data']->get_price();
					$cart_variation_id                  = $cart_value['variation_id'];
					$addf_disc_rpc_product_selection_op = get_post_meta( $value, 'addf_disc_rpc_product_selection_op' , true );
					if ('specific' == $addf_disc_rpc_product_selection_op) {
						$all_products = $this->addf_disc_rpc_merge_all_product_cats($value);
						if (( in_array($cart_product_id, $all_products) ) || ( empty($all_products) ) || ( ( $cart_product->is_type('variable') ) &&  ( in_array($cart_variation_id, $all_products) ) )) {
							$cart_product_price = $cart_value['data']->get_price();
							if ('' == $cart_qty_product_string) {
								$cart_qty_product_string .= get_the_title($cart_product_var_id);
							} else {
								$cart_qty_product_string .= ' , ' . get_the_title($cart_product_var_id);
							}
							if ('dynamic_disc_on_qty' == $discount_choice) {
								$cart_qty_of_selected += $cart_value['quantity'];
							} elseif ('dynamic_disc_on_amount' == $discount_choice) {
								$cart_qty_of_selected += $cart_value['quantity'] * $cart_product_price;
							}
							if (( !array_key_exists('addf_disc_rpc_product_rule_gift_item', $cart_value) ) && ( !array_key_exists('addf_disc_rpc_cart_rule_gift_item', $cart_value) )) {
								if ('gift_on_qty' == $discount_choice) {
									$cart_qty_of_selected_for_gift += $cart_value['quantity'];
								} elseif ('gift_on_price' == $discount_choice) {
									$cart_qty_of_selected_for_gift += $cart_value['quantity'] * $cart_product_price;
								}
							}
							$price_added += $cart_value['quantity'] * $cart_product_price;
						}
					} else {
						$cart_qty_product_string = '';
						if ('dynamic_disc_on_qty' == $discount_choice) {
							$cart_qty_of_selected += $cart_value['quantity'];
						} elseif ('dynamic_disc_on_amount' == $discount_choice) {
							$cart_qty_of_selected += $cart_value['quantity'] * $cart_product_price;
						}
						if (( !array_key_exists('addf_disc_rpc_product_rule_gift_item', $cart_value) ) && ( !array_key_exists('addf_disc_rpc_cart_rule_gift_item', $cart_value) )) {
							if ('gift_on_qty' == $discount_choice) {
								$cart_qty_of_selected_for_gift += $cart_value['quantity'];
							} elseif ('gift_on_price' == $discount_choice) {
								$cart_qty_of_selected_for_gift += $cart_value['quantity'] * $cart_product_price;
							}
						}
						$price_added += $cart_value['quantity'] * $cart_product_price;
					}
				}
				if ( ( 'dynamic_disc_on_qty' == $discount_choice ) || ( 'dynamic_disc_on_amount' == $discount_choice ) ) {
					// remove all gift products for this rule if added earlier
					$this->addf_disc_rpc_remove_all_gift_for_specific_rule_fn( $value );
					// calculate total discount or fees
					$return_discount_array = $this->addf_disc_rpc_gen_table_of_for_customer( $cart_qty_of_selected , $price_added , $value );
					$return_discount       = $return_discount_array['discount'];
					$minimum_requirement   = $return_discount_array['min_req'];
					$addf_cart_disc_on     = $cart_qty_of_selected;
					if ( 'dynamic_disc_on_amount' == $discount_choice ) {
						$addf_cart_disc_on = $price_added;
					}
					if ( $addf_cart_disc_on < $minimum_requirement ) {
						$discount_msg_type = 'before';
					} else {
						$discount_msg_type = 'after_discount';
					}
					
					if ( 'follow_sequence' ==  $priority_setting ) {
						$total_fee_or_discount             = $return_discount;
						$addf_disc_rpc_cart_rule_notices[] = array(
							'type'            => $discount_msg_type,
							'rule_id'         => $value,
							'cart_qty'        => $addf_cart_disc_on,
							'cart_product'    => $cart_qty_product_string,
							'min_requirement' => $minimum_requirement,
							'discount'        => abs($return_discount),
							'gift_string'     => '',
						);
						$total_fee_or_discount_from_all    =  $total_fee_or_discount;
						break;
					} elseif ( 0 != $total_fee_or_discount ) {
						
						if ( ( 'smaller_price' ==  $priority_setting ) && ( $return_discount < $total_fee_or_discount ) ) {
							$total_fee_or_discount = $return_discount;
						} elseif ( ( 'more_price' ==  $priority_setting ) && ( $return_discount > $total_fee_or_discount ) ) {
							$total_fee_or_discount = $return_discount;
						} else {
							$total_fee_or_discount += $return_discount;
						}
							$notice_check_in_array = true;
						foreach ($addf_disc_rpc_cart_rule_notices as $notice_key => $notice_value ) {
							if ( $value == $notice_value['rule_id'] ) {
								$notice_check_in_array                          = false;
								$addf_disc_rpc_cart_rule_notices[ $notice_key ] = array(
									'type'            => $discount_msg_type,
									'rule_id'         => $value,
									'cart_qty'        => $addf_cart_disc_on,
									'cart_product'    => $cart_qty_product_string,
									'min_requirement' => $minimum_requirement,
									'discount'        => abs($total_fee_or_discount),
									'gift_string'     => '',
								);
							}
						}
						if ( $notice_check_in_array ) {
							$addf_disc_rpc_cart_rule_notices[] = array(
								'type'            => $discount_msg_type,
								'rule_id'         => $value,
								'cart_qty'        => $addf_cart_disc_on,
								'cart_product'    => $cart_qty_product_string,
								'min_requirement' => $minimum_requirement,
								'discount'        => abs($total_fee_or_discount),
								'gift_string'     => '',
							);
						}
					} else {
						$total_fee_or_discount             = $return_discount;
						$addf_disc_rpc_cart_rule_notices[] = array(
							'type'            => $discount_msg_type,
							'rule_id'         => $value,
							'cart_qty'        => $addf_cart_disc_on,
							'cart_product'    => $cart_qty_product_string,
							'min_requirement' => $minimum_requirement,
							'discount'        => abs($total_fee_or_discount),
							'gift_string'     => '',
						);
					}
				} elseif ( ( 'gift_on_qty' == $discount_choice ) || ( 'gift_on_price' == $discount_choice ) ) {
					$customers    = (array) get_post_meta( $value , 'addf_disc_rpc_cond_cart_select_customer' , true );
					$gift_product = (array) get_post_meta( $value , 'addf_disc_cust_cart_gift_list' , true );
					$amount       = (array) get_post_meta( $value , 'addf_disc_rpc_cond_qty_tbl_cust' , true );
					$min          = (array) get_post_meta( $value , 'addf_disc_rpc_cond_min_qty_tbl_cust' , true );
					$max          = (array) get_post_meta( $value , 'addf_disc_rpc_cond_max_qty_tbl_cust' , true );
					
					$gift_product_arr = array();
					$amount_arr       = array();
					$min_arr          = array();
					$max_arr          = array();
					$gift_from        = '';
					foreach ( $customers as $cust_key => $cust_value) {
						if ( $curr_user_id == $cust_value ) {
							$gift_product_arr[] = $gift_product[ $cust_key ];
							$amount_arr[]       = $amount[ $cust_key ];
							$min_arr[]          = $min[ $cust_key ];
							$max_arr[]          = $max[ $cust_key ];
							$gift_from          = 'Customer';
						}
					}
					if ( empty( $gift_product_arr ) ) {
						$user_roles   = (array) get_post_meta( $value , 'addf_disc_rpc_cart_cond_user_role' , true );
						$gift_product = (array) get_post_meta( $value , 'addf_disc_user_role_cond_cart_gift_list' , true );
						$amount       = (array) get_post_meta( $value , 'addf_disc_rpc_cond_disc_val_tbl_user_role' , true );
						$min          = (array) get_post_meta( $value , 'addf_disc_rpc_cond_min_qty_tbl_user_role' , true );
						$max          = (array) get_post_meta( $value , 'addf_disc_rpc_cond_max_qty_tbl_user_role' , true );
						foreach ( $user_roles as $role_key => $role_value) {
							if ( ( $curr_user_role == $role_value ) || ( 'all' == $role_value ) ) {
								$gift_product_arr[] = $gift_product[ $role_key ];
								$amount_arr[]       = $amount[ $role_key ];
								$min_arr[]          = $min[ $role_key ];
								$max_arr[]          = $max[ $role_key ];
								$gift_from          = 'user role';
							}
						}
					}
					
					// remove gift products for this rule if not exists in gift list
					$cart_items = WC()->cart->get_cart();
					foreach ( $cart_items as $child_cart_key => $child_cart_value) {
						if ( array_key_exists( 'addf_disc_rpc_cart_rule_gift_item' , $child_cart_value ) ) {
							$gift_unique_cart = $child_cart_value['addf_disc_rpc_cart_rule_gift_item'];
							$gift_id_in_cart  = $gift_unique_cart['gift_id_array'];
							if ( $value == $gift_unique_cart['rule_id'] ) {
								if ( !in_array( $child_cart_value['data']->get_id() , $gift_product_arr ) ) {
									WC()->cart->remove_cart_item( $child_cart_value['key'] );
								}
							}
						}
					}

					if ( !empty( $gift_product_arr ) ) {
						remove_action( 'woocommerce_cart_calculate_fees' , array( $this, 'apply_discount_on_cart' ) , 90 , 1 );
						foreach ( $gift_product_arr as $gift_key => $gift_value) {
							if ( '' == $gift_value ) {
								continue;
							}
							$quantity = $amount_arr[ $gift_key ];
							if ( '' == $quantity ) {
								continue;
							}
							if ( 1 > $quantity ) {
								continue;
							}
							$min                      = $min_arr[ $gift_key ];
							$max                      = $max_arr[ $gift_key ];
							$remove_product_from_cart = false;
							$add_gift_to_cart         = false;
							if ( ( '' != $min ) && ( '' != $max ) ) {
								if ( ( $min <= $cart_qty_of_selected_for_gift ) && ( $cart_qty_of_selected_for_gift <= $max ) ) {
									$add_gift_to_cart = true;
								} else {
									$remove_product_from_cart = true;
								}
							} elseif ( ( '' != $min ) && ( '' == $max ) ) {
								if ( $min <= $cart_qty_of_selected_for_gift ) {
									$add_gift_to_cart = true;
								} else {
									$remove_product_from_cart = true;
								}
							} elseif ( ( '' == $min ) && ( '' != $max ) ) {
								if ( $cart_qty_of_selected_for_gift <= $max ) {
									$add_gift_to_cart = true;
								} else {
									$remove_product_from_cart = true;
								}
							}
							
							$cart_items         = WC()->cart->get_cart();
							$not_exists_in_cart = true;
							// remove gift product if quantity is less than min required and more than max required
							foreach ( $cart_items as $child_cart_key => $child_cart_value) {
								if ( array_key_exists( 'addf_disc_rpc_cart_rule_gift_item' , $child_cart_value ) ) {
									$gift_unique_cart = $child_cart_value['addf_disc_rpc_cart_rule_gift_item'];
									$gift_id_in_cart  = $gift_unique_cart['gift_id_array'];
									if ( ( $value == $gift_unique_cart['rule_id'] ) && ( $gift_value == $gift_unique_cart['gift_product'] ) && ( $gift_key == $gift_unique_cart['gift_key'] ) ) {
										if ( $remove_product_from_cart ) {
											WC()->cart->remove_cart_item( $child_cart_value['key'] );
										} else {
											$not_exists_in_cart = false;
										}
									}
								}
							}
							if ( $remove_product_from_cart ) {
								$check_current_notice_in_array = true;
								foreach ( $addf_disc_rpc_cart_rule_notices as $check_notice_key => $check_notice_value ) {
									if ( $value == $check_notice_value['rule_id'] ) {
										$check_current_notice_in_array = false;
										if ( $min < $check_notice_value['min_requirement'] ) {
											$addf_disc_rpc_cart_rule_notices[] = array(
												'type'     => 'before',
												'rule_id'  => $value,
												'cart_qty' => $cart_qty_of_selected_for_gift,
												'cart_product' => $cart_qty_product_string,
												'min_requirement' => $min,
												'discount' => '',
												'gift_string' => $quantity . ' X ' . get_the_title( $gift_value ),
											);
										}
									}
								}
								if ( $check_current_notice_in_array ) {
									$addf_disc_rpc_cart_rule_notices[] = array(
										'type'            => 'before',
										'rule_id'         => $value,
										'cart_qty'        => $cart_qty_of_selected_for_gift,
										'cart_product'    => $cart_qty_product_string,
										'min_requirement' => $min,
										'discount'        => '',
										'gift_string'     => $quantity . ' X ' . get_the_title( $gift_value ),
									);
								}
							}
							if ( ( $add_gift_to_cart ) && ( $not_exists_in_cart ) ) {
								$array_identity = array(
									'addf_disc_rpc_cart_rule_gift_item' => array(
										'rule_id'       =>$value,
										'gift_key'      =>$gift_key,
										'gift_qty'      => $quantity,
										'gift_product'  => $gift_value,
										'gift_id_array' => array(
											'gift_product_id' => $gift_value,
											'min_qty'      => $min,
											'cart_product' => $cart_qty_product_string,
											'max_qty'      => $max,
											'quantity'     => $quantity,
											'gift_string'  => $quantity . ' X ' . get_the_title( $gift_value ),
										),
									),
								);
								
								if ( WC()->session->get('af_disc_cart_rule_remove_gift_list' ) ) {
									$session_array = (array) WC()->session->get('af_disc_cart_rule_remove_gift_list' );
									if (  array_key_exists( $value , $session_array ) ) {
										foreach ( $session_array[ $value ] as $session_key => $session_value) {
											if ( array_key_exists( 'gift_product' , (array) $session_value ) ) {
												if ( ( $session_value['gift_product'] == $gift_value ) && ( $session_value['gift_key'] == $gift_key ) ) {
													continue 2;
												}
											}
										}
									}
								}
								// adding gift product
									WC()->cart->add_to_cart( $gift_value , $quantity , $variation_id = 0 , $variation_attr = array() , $array_identity );
								$discount_gift_applied_check = true;
							}
						}
						add_action( 'woocommerce_cart_calculate_fees' , array( $this, 'apply_discount_on_cart' ) , 90 , 1 );
					}
				}
				if ( ( false == $discount_gift_applied_check ) && ( 0 == $total_fee_or_discount ) ) {
					$total_fee_or_discount_from_all =  $total_fee_or_discount;
					continue;
				}
				if ( 0 == $total_fee_or_discount_from_all ) {
					$total_fee_or_discount_from_all =  $total_fee_or_discount;
				}
				if ( ( $discount_gift_applied_check ) || ( 0 != $total_fee_or_discount ) ) {
					if ( 'follow_sequence' == $priority_setting ) {
						$total_fee_or_discount_from_all =  $total_fee_or_discount;
						break;
					} else {
						if ( 'smaller_price' == $priority_setting ) {
							if ( $total_fee_or_discount_from_all > $total_fee_or_discount ) {
								$total_fee_or_discount_from_all =  $total_fee_or_discount;
							}
						} elseif ( 'more_price' == $priority_setting ) {
							if ( $total_fee_or_discount_from_all < $total_fee_or_discount ) {
								$total_fee_or_discount_from_all =  $total_fee_or_discount;
							}
						} elseif ( 'apply_all' == $priority_setting ) {
								$apply_all_discount_from_all +=  $total_fee_or_discount;
						}
						continue;
					}
				}
			}
			if ( 'apply_all' == $priority_setting ) {
				$total_fee_or_discount_from_all = $apply_all_discount_from_all;
			}
			// applying total fees or discount 
			if ( 0 > $total_fee_or_discount_from_all ) {
				$cart->add_fee( esc_html__( 'Discount' , 'woo-af-drpc' ), $total_fee_or_discount_from_all , false );
			} elseif ( 0 < $total_fee_or_discount_from_all ) {
				$cart->add_fee( esc_html__( 'Fees' , 'woo-af-drpc' ), $total_fee_or_discount_from_all , false );
			}
			$cart_notices_shown = array();
			// show all notices for gifted products
			foreach ( WC()->cart->get_cart() as $cart_key => $cart_value ) {
				if ( !array_key_exists( 'addf_disc_rpc_cart_rule_gift_item' , $cart_value ) ) {
					continue;
				}
				$value                = $cart_value['addf_disc_rpc_cart_rule_gift_item'];
				$gift_id_array        = $value['gift_id_array'];
				$rule_id              = $value['rule_id'];
				$cart_notices_shown[] = $rule_id;
				$notice_message       = get_post_meta( $rule_id, 'addf_disc_rpc_after_disc_msg', true);
				if ( '' != $notice_message ) {
					$gift_string   = $gift_id_array['gift_string'];
					$discount      = '';
					$req_qty       = $gift_id_array['min_qty'];
					$product_names = $gift_id_array['cart_product'];
					$product_qty   = $gift_id_array['min_qty'];
					$this->addf_disc_rpc_cart_rule_notices( $rule_id , $notice_message , $product_names , $product_qty , $req_qty , $discount , $gift_string);
				}
			}

			foreach ( $addf_disc_rpc_cart_rule_notices as $key => $value) {
				$rule_id = $value['rule_id'];
				if ( in_array( $rule_id , $cart_notices_shown ) ) {
					continue;
				}
				
				$cart_notices_shown[] = $rule_id;
				if ( array_key_exists( 'type' , $value ) ) {
					if ( 'after_discount' == $value['type'] ) {
						$notice_message = get_post_meta( $rule_id, 'addf_disc_rpc_after_disc_msg', true);
					} else {
						$notice_message = get_post_meta( $rule_id, 'addf_disc_rpc_before_disc_msg', true);
					}
					if ( '' != $notice_message ) {
						
						$gift_string   = $value['gift_string'];
						$discount      = $value['discount'];
						$req_qty       = $value['min_requirement'];
						$product_names = $value['cart_product'];
						$product_qty   = $value['cart_qty'];

						if ( ( 0 != $discount ) || ( 'after_discount' != $value['type'] ) ) {
							$this->addf_disc_rpc_cart_rule_notices( $rule_id , $notice_message , $product_names , $product_qty , $req_qty , $discount , $gift_string);
						}
					}
				}
			}
		}

		

		// show notices
		public function addf_disc_rpc_cart_rule_notices( $rule_id, $disc_gift_msg, $product_names, $product_qty, $req_qty, $discount, $gift_string ) {
			if ( !is_cart() ) {
				return;
			}
			$start_date                      = get_post_meta($rule_id, 'addf_disc_rpc_start_time', true);
			$end_date                        = get_post_meta($rule_id, 'addf_disc_rpc_end_time', true);
			$min_spent_amount                = get_post_meta($rule_id, 'addf_disc_rpc_min_spent_amount', true);
			$addf_drpc_disc_min_spent_amount = get_post_meta($rule_id, 'addf_drpc_disc_min_spent_amount', true);
			if ( 'ignore' == $addf_drpc_disc_min_spent_amount ) {
				$min_spent_amount = 0;
			}
			$rem_qty = (int) $req_qty - (int) $product_qty;
			if ( $rem_qty <= 0 ) {
				$rem_qty = 0;
			}
			$disc_gift_msg = str_replace('{product_names}', $product_names , $disc_gift_msg);
			$disc_gift_msg = str_replace('{cart_qty}', $product_qty , $disc_gift_msg);
			$disc_gift_msg = str_replace('{req_products}', $req_qty , $disc_gift_msg);
			$disc_gift_msg = str_replace('{rem_qty}', $rem_qty , $disc_gift_msg);
			$disc_gift_msg = str_replace('{rec_discount}', $discount , $disc_gift_msg);
			$disc_gift_msg = str_replace('{start_date}', $start_date, $disc_gift_msg);
			$disc_gift_msg = str_replace('{end_date}', $end_date, $disc_gift_msg);
			$disc_gift_msg = str_replace('{min_spent_amount}', $min_spent_amount, $disc_gift_msg);
			$disc_gift_msg = str_replace('{gift_products}', $gift_string, $disc_gift_msg);
			wc_add_notice( esc_html__( $disc_gift_msg , 'woo-af-drpc' ), 'notice');
		}

		// calculating discount/fees from customer and roles table for current rule
		public function addf_disc_rpc_gen_table_of_for_customer( $qty_added, $price_added, $rule_id ) {
			$setting_priority = get_option( 'addf_drpc_option_rules_cart_priority' );
			if ( is_user_logged_in() ) {
				$user           = wp_get_current_user();
				$curr_user_role = current( $user->roles );
			} else {
				$curr_user_role = 'guest';
			}

			$curr_user_id = get_current_user_id();

			$customers = (array) get_post_meta( $rule_id , 'addf_disc_rpc_cart_select_customer' , true );
			$choice    = (array) get_post_meta( $rule_id , 'addf_drpc_cust_disc_choice' , true );
			$amount    = (array) get_post_meta( $rule_id , 'addf_disc_rpc_disc_val_tbl_cust' , true );
			$min       = (array) get_post_meta( $rule_id , 'addf_disc_rpc_min_qty_tbl_cust' , true );
			$max       = (array) get_post_meta( $rule_id , 'addf_disc_rpc_max_qty_tbl_cust' , true );
			
			$choice_arr = array();
			$amount_arr = array();
			$min_arr    = array();
			$max_arr    = array();
			foreach ( $customers as $key => $value) {
				if ( $curr_user_id == $value ) {
					$choice_arr[] = $choice[ $key ];
					$amount_arr[] = $amount[ $key ];
					$min_arr[]    = $min[ $key ];
					$max_arr[]    = $max[ $key ];
				}
			}
			if (empty($choice_arr) || ( ( 2 > count($choice_arr) ) && ( current($choice_arr) == '' ) )  ) {
				$user_roles = (array) get_post_meta( $rule_id , 'addf_disc_rpc_cart_select_user_role' , true );
				$choice     = (array) get_post_meta( $rule_id , 'addf_drpc_user_role_disc_choice' , true );
				$amount     = (array) get_post_meta( $rule_id , 'addf_disc_rpc_disc_val_tbl_user_role' , true );
				$min        = (array) get_post_meta( $rule_id , 'addf_disc_rpc_min_qty_tbl_user_role' , true );
				$max        = (array) get_post_meta( $rule_id , 'addf_disc_rpc_max_qty_tbl_user_role' , true );
				foreach ( $user_roles as $key => $value) {
					if ( ( $curr_user_role == $value ) || ( 'all' == $value ) ) {
						$choice_arr[] = $choice[ $key ];
						$amount_arr[] = $amount[ $key ];
						$min_arr[]    = $min[ $key ];
						$max_arr[]    = $max[ $key ];
					}
				}
			}
			$return_discount        = 0;
			$minimum_req            = 0;
			$table_discount_applied = false;
			foreach ( $choice_arr as $key => $value) {
				$min_qty    = $min_arr[ $key ];
				$max_qty    = $max_arr[ $key ];
				$amount_ite = $amount_arr[ $key ];
				if ( 'fixed_price_increase' == $value ) {
					$amount_ite = $amount_arr[ $key ];
				} elseif ( 'fixed_price_decrease' == $value ) {
					$amount_ite = -$amount_arr[ $key ];
				} elseif ( 'fixed_percent_increase' == $value ) {
					$amount_ite = ( $price_added / 100 ) * $amount_arr[ $key ];
				} elseif ( 'fixed_percent_decrease' == $value ) {
					$amount_ite = -( $price_added / 100 ) * $amount_arr[ $key ];
				}
				$minimum_req = $min_qty;
				if ( ( '' != $min_qty ) && ( '' != $max_qty ) ) {
					if ( ( $min_qty <= $qty_added ) && ( $qty_added <= $max_qty ) ) {
						if ( 'more_price' == $setting_priority ) {
							if ( 0 == $return_discount ) {
								$return_discount = $amount_ite;
							} elseif ( $return_discount < $amount_ite  ) {
								$return_discount = $amount_ite;
							}
						} elseif ( 0 == $return_discount ) {
								$return_discount = $amount_ite;
						} elseif ( $return_discount > $amount_ite  ) {
							$return_discount = $amount_ite;
						}
						if ( 'apply_all' == $setting_priority ) {
							$return_discount = $amount_ite;
						}
						if ( 'follow_sequence' == $setting_priority ) {
							$return_discount = $amount_ite;
						}
						$table_discount_applied = true;
					}
				} elseif ( ( '' != $min_qty ) && ( '' == $max_qty ) ) {
					if ( $min_qty <= $qty_added ) {
						if ( 'more_price' == $setting_priority ) {
							if ( 0 == $return_discount ) {
								$return_discount = $amount_ite;
							} elseif ( $return_discount < $amount_ite  ) {
								$return_discount = $amount_ite;
							}
						} elseif ( 0 == $return_discount ) {
								$return_discount = $amount_ite;
						} elseif ( $return_discount > $amount_ite  ) {
							$return_discount = $amount_ite;
						}
						if ( 'apply_all' == $setting_priority ) {
							$return_discount = $amount_ite;
						}
						if ( 'follow_sequence' == $setting_priority ) {
							$return_discount = $amount_ite;
						}
						$table_discount_applied = true;
					}
				} elseif ( ( '' == $min_qty ) && ( '' != $max_qty ) ) {
					if ( $qty_added <= $max_qty  ) {
						if ( 'more_price' == $setting_priority ) {
							if ( 0 == $return_discount ) {
								$return_discount = $amount_ite;
							} elseif ( $return_discount < $amount_ite  ) {
								$return_discount = $amount_ite;
							}
						} elseif ( 0 == $return_discount ) {
								$return_discount = $amount_ite;
						} elseif ( $return_discount > $amount_ite  ) {
							$return_discount = $amount_ite;
						}
						if ( 'apply_all' == $setting_priority ) {
							$return_discount = $amount_ite;
						}
						if ( 'follow_sequence' == $setting_priority ) {
							$return_discount = $amount_ite;
						}
						$table_discount_applied = true;
					}
				}
				if ( 'follow_sequence' == get_option('addf_drpc_option_rules_cart_priority') ) {
					if ( $table_discount_applied ) {
						break;
					}
				}
			}
			return array(
				'discount' => $return_discount,
				'min_req'  => $minimum_req,
			);
		}

		// show gift item on gift product in cart
		public function gift_product_label_cart( $item_data, $cart_item_data ) {
			if ( isset( $cart_item_data['addf_disc_rpc_cart_rule_gift_item'] ) ) {
				$item_data[] = array(
					'key'   =>esc_html__( 'Gift ' , 'woo-af-drpc' ),
					'value' => wc_clean( 'With Cart Products' ),
				);
			}
			return $item_data;
		}

		// show gift item on gift product on checkout
		public function gift_product_label_after_checkout( $item, $cart_item_key, $values, $order ) {
			if ( isset( $values['addf_disc_rpc_cart_rule_gift_item'] ) ) {
				$item->add_meta_data(
					esc_html__( 'Gift ' , 'woo-af-drpc' ),
					esc_html__( 'With Cart Products ' , 'woo-af-drpc' ),
					true
				);
			}
		}
	}
	new AF_Cart_Discount_Front();
}
