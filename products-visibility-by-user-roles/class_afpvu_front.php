<?php
if (!defined('WPINC')) {
	die;
}

if (!class_exists('Addify_Products_Visibility_Front')) {

	class Addify_Products_Visibility_Front extends Addify_Products_Visibility {









		public function __construct() {

			add_action('wp_enqueue_scripts', array( $this, 'afpvu_front_script' ));
			$g_boots = get_option('afpvu_allow_seo');

			if (!( !empty($_SERVER['HTTP_USER_AGENT']) && 'googlebot' == $_SERVER['HTTP_USER_AGENT'] && 'yes' == $g_boots )) {

				add_action('woocommerce_product_query', array( $this, 'afpvu_custom_pre_get_posts_query' ), 100, 2);
				add_filter('woocommerce_product_is_visible', array( $this, 'afpvu_check_visibility_rules' ), 10, 2); 

				add_action('wp', array( $this, 'afpvu_redirect_to_custom_page' ));
				add_filter('page_template', array( $this, 'afpvu_custom_page_template' ));

				add_filter('get_terms', array( $this, 'afpvu_hide_terms' ), 10, 3);

			}
		}


		public function afpvu_hide_terms( $terms, $taxonomies, $args ) {
			global $afpvu_show_hide;
			$afpuv_terms              = array();
			$afpvu_show_hide          = 'hide';
			$afpvu_enable_global      = get_option('afpvu_enable_global');
			$role_selected_data       = (array) get_option('afpvu_user_role_visibility');
			$curr_role                = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';
			$role_data                = isset($role_selected_data[ $curr_role ]['afpvu_enable_role']) ? $role_selected_data[ $curr_role ]['afpvu_enable_role'] : 'no';
			$afpvu_applied_categories = array();


			if ('yes' === $role_data) {
				$_data                    = $role_selected_data[ $curr_role ];
				$afpvu_show_hide          = isset($_data['afpvu_show_hide_role']) ? $_data['afpvu_show_hide_role'] : 'hide';
				$afpvu_applied_categories = isset($_data['afpvu_applied_categories_role']) ? (array) $_data['afpvu_applied_categories_role'] : array();
			} elseif ('yes' === $afpvu_enable_global) {
				$afpvu_show_hide          = get_option('afpvu_show_hide');
				$afpvu_applied_categories = (array) get_option('afpvu_applied_categories');
			}


			if (in_array('product_cat', $taxonomies) && ( is_shop() || is_product() )) {
				foreach ($terms as $term) {

					if (( 'hide' == $afpvu_show_hide && !in_array($term->term_id, $afpvu_applied_categories) ) ||
						( 'show' == $afpvu_show_hide && in_array($term->term_id, $afpvu_applied_categories) )) {


						$afpuv_terms[] = $term;
					}
				}


				if (!empty($afpuv_terms)) {
					$terms = $afpuv_terms;
				}
			}

		return $terms;
		}




		public function afpvu_front_script() {

			wp_enqueue_style('afpvu-front', plugins_url('/assets/css/afpvu_front.css', __FILE__), false, '1.0');
		}

		public function afpvu_product_hidden( $product_id, $applied_products, $applied_categories, $show_hide ) {

			$show = true;

			$applied_products   = custom_pvbur_array_filter($applied_products);
			$applied_categories = custom_pvbur_array_filter($applied_categories);

			if (empty($applied_products) && empty($applied_categories) && 'hide' === $show_hide) {
				$show = false;
			}


			if (empty($applied_products) && empty($applied_categories) && 'hide' != $show_hide) {
				$show = true;
			}

			if ('hide' === $show_hide) {

				if (in_array($product_id, (array) $applied_products)) {
					$show = false;
				}

				if (!empty($applied_categories) && has_term($applied_categories, 'product_cat', $product_id)) {
					$show = false;
				}
			} else {

				if (in_array($product_id, (array) $applied_products)) {
					$show = true;
				}
				if (!empty($applied_categories) && has_term($applied_categories, 'product_cat', $product_id)) {
					$show = true;
				}

			}
			return $show;
		}

		public function afpvu_remove_cart_product() {
			$afpvu_enable_global = get_option('afpvu_enable_global');
			$curr_role           = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';
			$role_selected_data  = (array) get_option('afpvu_user_role_visibility');
			$all_product_ids     = get_posts(array(
				'post_type'   => 'product',
				'numberposts' => -1,
				'post_status' => 'publish',
				'fields'      => 'ids',
			));

			if (empty($role_selected_data) && 'yes' !== $afpvu_enable_global) {
				return;
			}


			$role_data                = isset($role_selected_data[ $curr_role ]['afpvu_enable_role']) ? $role_selected_data[ $curr_role ]['afpvu_enable_role'] : 'no';
			$role_settings            = isset($role_selected_data[ $curr_role ]) ? $role_selected_data[ $curr_role ] : array();
			$afpvu_show_hide_pro      = isset($role_settings['afpvu_show_hide_role']) ? $role_settings['afpvu_show_hide_role'] : 'hide';
			$afpvu_applied_products   = isset($role_settings['afpvu_applied_products_role']) ? $role_settings['afpvu_applied_products_role'] : array();
			$afpvu_applied_categories = isset($role_settings['afpvu_applied_categories_role']) ? $role_settings['afpvu_applied_categories_role'] : array();


			if ('yes' === $afpvu_enable_global) {
				$afpvu_show_hide           = get_option('afpvu_show_hide');
				$global_applied_products   = (array) get_option('afpvu_applied_products');
				$global_applied_categories = (array) get_option('afpvu_applied_categories');


				if ('hide' === $afpvu_show_hide && 'hide' === $afpvu_show_hide_pro) {
					$this->remove_products_from_cart($global_applied_products, $global_applied_categories);
				} elseif ('hide' === $afpvu_show_hide) {
					$all_show_products = array_diff($all_product_ids, $global_applied_products);
					$this->remove_products_from_cart($all_show_products, $global_applied_categories, true);
				}
			}


			if ('yes' === $role_data) {
				if ('hide' === $afpvu_show_hide_pro) {
					$this->remove_products_from_cart($afpvu_applied_products, $afpvu_applied_categories);
				} else {
					$all_show_products = array_diff($all_product_ids, $afpvu_applied_products);
					$this->remove_products_from_cart($all_show_products, $afpvu_applied_categories, true);
				}
			} elseif ('yes' !== $role_data) {
				$afpvu_show_hide = get_option('afpvu_show_hide');
				$this->remove_products_from_cart($afpvu_applied_products, $afpvu_applied_categories);
			}
		}

		private function remove_products_from_cart( $products_to_remove, $categories_to_remove, $invert = false ) {
			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				$product_id = $cart_item['product_id'];

				if ($invert) {

					if (!in_array($product_id, $products_to_remove)) {
						WC()->cart->remove_cart_item($cart_item_key);
					}
				} elseif (in_array($product_id, $products_to_remove)) {

					WC()->cart->remove_cart_item($cart_item_key);
				}


				foreach ($categories_to_remove as $cat_id) {
					if (has_term($cat_id, 'product_cat', $product_id)) {
						WC()->cart->remove_cart_item($cart_item_key);
						break;
					}
				}
			}
		}


		public function afpvu_check_visibility_rules( $visible, $product_id ) {



			if (did_action('addify_query_visibility_applied')) {
				return $visible;
			}

			$product             = wc_get_product($product_id);
			$afpvu_enable_global = get_option('afpvu_enable_global');
			$curr_role           = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';
			$role_selected_data  = (array) get_option('afpvu_user_role_visibility');

			$role_selected_data = custom_pvbur_array_filter($role_selected_data);




			if (empty($role_selected_data) && 'yes' !== $afpvu_enable_global) {
				return $visible;
			}

			$role_data = isset($role_selected_data[ $curr_role ]['afpvu_enable_role']) ? $role_selected_data[ $curr_role ]['afpvu_enable_role'] : 'no';



			$role = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';

			$role_data = isset($role_selected_data[ $role ]['afpvu_enable_role']) ? $role_selected_data[ $role ]['afpvu_enable_role'] : '';

			if ('yes' == $role_data) {

				$_data           = $role_selected_data[ $role ];
				$afpvu_show_hide = isset($_data['afpvu_show_hide_role']) ? $_data['afpvu_show_hide_role'] : 'hide';

				$afpvu_applied_products = isset($_data['afpvu_applied_products_role']) ? (array) $_data['afpvu_applied_products_role'] : array();

				$afpvu_applied_categories = isset($_data['afpvu_applied_categories_role']) ? (array) $_data['afpvu_applied_categories_role'] : array();


				$afpvu_applied_products   = custom_pvbur_array_filter($afpvu_applied_products);
				$afpvu_applied_categories = custom_pvbur_array_filter($afpvu_applied_categories);


				$visible = $this->afpvu_product_hidden($product_id, $afpvu_applied_products, $afpvu_applied_categories, $afpvu_show_hide);


				return $visible;
			}



			if ('yes' === $afpvu_enable_global) {

				$afpvu_show_hide          = get_option('afpvu_show_hide');
				$afpvu_applied_products   = (array) get_option('afpvu_applied_products');
				$afpvu_applied_categories = (array) get_option('afpvu_applied_categories');

				$visible = $this->afpvu_product_hidden($product_id, $afpvu_applied_products, $afpvu_applied_categories, $afpvu_show_hide);

			}

			return $visible;
		}

		public function afpvu_custom_pre_get_posts_query( $q ) {

			global $product;

			$afpvu_enable_global = get_option('afpvu_enable_global');
			$curr_role           = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';
			$role_selected_data  = (array) get_option('afpvu_user_role_visibility');



			if (empty($role_selected_data) && 'yes' !== $afpvu_enable_global) {

				return;
			}
			$role_data = isset($role_selected_data[ $curr_role ]['afpvu_enable_role']) ? $role_selected_data[ $curr_role ]['afpvu_enable_role'] : 'no';

			if ('yes' === $afpvu_enable_global) {


				$afpvu_show_hide          = get_option('afpvu_show_hide');
				$afpvu_applied_products   = (array) get_option('afpvu_applied_products');
				$afpvu_applied_categories = (array) get_option('afpvu_applied_categories');
			}

			if ('yes' === $role_data) {


				$_data                    = $role_selected_data[ $curr_role ];
				$afpvu_show_hide          = isset($_data['afpvu_show_hide_role']) ? $_data['afpvu_show_hide_role'] : 'hide';
				$afpvu_applied_products   = isset($_data['afpvu_applied_products_role']) ? (array) $_data['afpvu_applied_products_role'] : array();
				$afpvu_applied_categories = isset($_data['afpvu_applied_categories_role']) ? (array) $_data['afpvu_applied_categories_role'] : array();
			} else {

				$all_roles = is_user_logged_in() ? wp_get_current_user()->roles : array( '' );
				$flag      = true;
				foreach ($all_roles as $role) {
					$role_data = isset($role_selected_data[ $role ]['afpvu_enable_role']) ? $role_selected_data[ $role ]['afpvu_enable_role'] : '';

					if (empty($role_data)) {

						continue;
					}
					if ('yes' === $role_data && $flag) {


						$flag = false;

						$_data                    = $role_selected_data[ $role ];
						$afpvu_show_hide          = isset($_data['afpvu_show_hide_role']) ? $_data['afpvu_show_hide_role'] : 'hide';
						$afpvu_applied_products   = isset($_data['afpvu_applied_products_role']) ? (array) $_data['afpvu_applied_products_role'] : array();
						$afpvu_applied_categories = isset($_data['afpvu_applied_categories_role']) ? (array) $_data['afpvu_applied_categories_role'] : array();
					}
				}
			}

			if (!empty($afpvu_applied_products)) {
				$afpvu_applied_products = custom_pvbur_array_filter($afpvu_applied_products); 
			} else {

				$afpvu_applied_products = array();  
			}

			if (!empty($afpvu_applied_categories)) {

				$afpvu_applied_categories = custom_pvbur_array_filter($afpvu_applied_categories);
			} else {

				$afpvu_applied_categories = array();
			}
			

			$products_ids = array();


			if (!empty($afpvu_applied_categories)) {

				$product_args = array(
					'numberposts' => -1,
					'post_status' => array( 'publish' ),
					'post_type'   => array( 'product' ), //skip types
					'fields'      => 'ids',
				);

				$product_args['tax_query'] = array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'id',
						'terms'    => $afpvu_applied_categories,
						'operator' => 'IN',
					),
				);

				$products_ids = (array) get_posts($product_args);
			}

			$afpvu_applied_products = array_merge((array) $afpvu_applied_products, (array) $products_ids);

			if (!empty($afpvu_show_hide) && 'hide' == $afpvu_show_hide) {

				$post__not_in = (array) $q->get('post__not_in');
				$q->set('post__not_in', array_merge($post__not_in, $afpvu_applied_products));
			} elseif (!empty($afpvu_show_hide) && 'show' == $afpvu_show_hide) {

				$q->set('post__in', $afpvu_applied_products);
			}
			do_action('addify_query_visibility_applied', $q);
		}





		public function afpvu_redirect_to_custom_page() {
			$afpvu_show_hide = get_option('afpvu_show_hide');
			global $wp_query;

			$afpvu_enable_global = get_option('afpvu_enable_global', 'no');
			$curr_role           = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';
			$role_selected_data  = (array) get_option('afpvu_user_role_visibility', array());


			$Page_ID = is_shop() ? wc_get_page_id('shop') : $wp_query->get_queried_object_id();




			$role_data = isset($role_selected_data[ esc_attr($curr_role) ]['afpvu_enable_role']) ? $role_selected_data[ esc_attr($curr_role) ]['afpvu_enable_role'] : 'no';

			if ('no' === $role_data && 'yes' === $afpvu_enable_global) {

				$afpvu_applied_products   = (array) get_option('afpvu_applied_products', array());
				$afpvu_applied_categories = (array) get_option('afpvu_applied_categories', array());

				$redirection_mode = get_option('afpvu_global_redirection_mode');
				$redirect_url     = 'custom_url' === $redirection_mode
				? get_option('afpvu_global_custom_url')
				: get_permalink(get_page_by_path('af-product-visibility'));

				if ('custom_message' === $redirection_mode) {
					$redirect_url .= '?afpvu_message=' . get_option('afpvu_global_custom_msg');
				}


			} elseif ('yes' == $role_data) {

				$role_settings            = $role_selected_data[ esc_attr($curr_role) ];
				$afpvu_show_hide          = $role_settings['afpvu_show_hide_role'];
				// $afpvu_applied_products   = (array) $role_settings['afpvu_applied_products_role'];.

				$afpvu_applied_products = isset($role_settings['afpvu_applied_products_role']) ? (array) $role_settings['afpvu_applied_products_role']: array();

				$afpvu_applied_categories = isset($role_settings['afpvu_applied_categories_role']) ? (array) $role_settings['afpvu_applied_categories_role'] : array();

				$redirection_mode = $role_settings['afpvu_role_redirection_mode'];
				$redirect_url     = 'custom_url' === $redirection_mode
				? $role_settings['afpvu_role_custom_url']
				: get_permalink(get_page_by_path('af-product-visibility'));

				if ('custom_message' === $redirection_mode) {
					$redirect_url .= '?afpvu_message=' . get_option('afpvu_user_role_visibility' . $curr_role);
				}

			} else {
				return;
			}


			$redirect_url = wp_nonce_url($redirect_url, 'afpvu_nonce', 'afpvu_nonce');


			if ('hide' === $afpvu_show_hide) {

				if (is_product() || is_product_category() ) {
		 


					if (( !empty( $afpvu_applied_products)  && in_array($Page_ID, $afpvu_applied_products) )  ) {

						if ('custom_message' === $redirection_mode) {
							wp_safe_redirect($redirect_url);
						} else {
							wp_safe_redirect($redirect_url);
						}
						exit();
					}

					if (!empty($afpvu_applied_categories) && has_term($afpvu_applied_categories, 'product_cat', $Page_ID)) {
						if ('custom_message' === $redirection_mode) {
							wp_safe_redirect($redirect_url);
						} else {
							wp_safe_redirect($redirect_url);
						}
						exit();
					}
				}
			} 
		}



		public function afpvu_custom_page_template( $page_template ) {
			$afpvu_visibility_page = get_page_by_path('af-product-visibility');

			if (is_page($afpvu_visibility_page->ID)) {
				$page_template = AFPVU_PLUGIN_DIR . 'templates/product_visibility_page.php';
			}

			return $page_template;
		}
	}

new Addify_Products_Visibility_Front();
}
