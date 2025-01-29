<?php
defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'AF_Discount_Rpc_Admin' ) ) {
	class AF_Discount_Rpc_Admin {
		public function __construct() {
			// adding css
			add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );

			add_filter ('bulk_actions-edit-af_disc_p_rules' , array( $this, 'dynamic_discount_remove_bulk_edit' ));
			add_filter ('bulk_actions-edit-af_dis_cart_rule' , array( $this, 'dynamic_discount_remove_bulk_edit' ));

			// add to menu
			//add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

			// add tab in wc setting
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'dynamic_discount_settings_tab_cb' ), 50);
			
			// save settings
			add_action('admin_init', array( $this, 'save_discount_settings' ), 10 );

			// add meta box
			add_action( 'add_meta_boxes', array( $this, 'add_discount_meta_box' ) );

			//  save meta values
			add_action( 'save_post_af_disc_p_rules', array( $this, 'save_discount_p_meta_fields' ), 10, 2 );
			add_action( 'save_post_af_dis_cart_rule', array( $this, 'save_discount_c_meta_fields' ), 10, 2 );

			// product live search multi 
			add_action( 'wp_ajax_addf_disc_rpc_product_live_search', array( $this, 'product_live_search' ) );

			//  add product single select
			add_action( 'wp_ajax_addf_disc_choose_new_gift_product', array( $this, 'choose_gift_product' ) );
			add_action( 'wp_ajax_nopriv_addf_disc_choose_new_gift_product', array( $this, 'choose_gift_product' ) );   

			// customers live search
			add_action( 'wp_ajax_addf_disc_rpc_customer_live_search', array( $this, 'customer_live_search_cb' ) );
			add_action( 'wp_ajax_nopriv_addf_disc_rpc_customer_live_search', array( $this, 'customer_live_search_cb' ) );

			// customers live search
			add_action( 'wp_ajax_addf_disc_rpc_add_cust_rule_row', array( $this, 'add_new_row_customer_table' ) );
			add_action( 'wp_ajax_nopriv_addf_disc_rpc_add_cust_rule_row', array( $this, 'add_new_row_customer_table' ) );

			// add new row for customer cart rule
			add_action( 'wp_ajax_addf_disc_rpc_add_cust_row_cart_action', array( $this, 'addf_disc_rpc_add_cust_row_cart_action_cb' ) );
			add_action( 'wp_ajax_nopriv_addf_disc_rpc_add_cust_row_cart_action', array( $this, 'addf_disc_rpc_add_cust_row_cart_action_cb' ) );

			// add new row for customer cart rule
			add_action( 'wp_ajax_addf_disc_rpc_add_user_role_row_cart_action', array( $this, 'addf_disc_rpc_add_user_role_row_cart_action_cb' ) );
			add_action( 'wp_ajax_nopriv_addf_disc_rpc_add_user_role_row_cart_action', array( $this, 'addf_disc_rpc_add_user_role_row_cart_action_cb' ) );

			// add new row for conditional customer cart rule
			add_action( 'wp_ajax_addf_disc_rpc_conditional_add_cust_row_cart_tbl', array( $this, 'addf_disc_rpc_conditional_add_cust_row_cart_tbl_cb' ) );
			add_action( 'wp_ajax_nopriv_addf_disc_rpc_conditional_add_cust_row_cart_tbl', array( $this, 'addf_disc_rpc_conditional_add_cust_row_cart_tbl_cb' ) );

			// add new row for conditional user role cart rule
			add_action( 'wp_ajax_addf_disc_rpc_conditional_add_user_role_row_cart_tbl', array( $this, 'addf_disc_rpc_conditional_add_user_role_row_cart_tbl_cb' ) );
			add_action( 'wp_ajax_nopriv_addf_disc_rpc_conditional_add_user_role_row_cart_tbl', array( $this, 'addf_disc_rpc_conditional_add_user_role_row_cart_tbl_cb' ) );

			// dynamic discount for user roles
			add_action( 'wp_ajax_addf_disc_rpc_discount_adj_table', array( $this, 'addf_disc_rpc_discount_adj_table_cb' ) );
			add_action( 'wp_ajax_nopriv_addf_disc_rpc_discount_adj_table', array( $this, 'addf_disc_rpc_discount_adj_table_cb' ) );

			// gift row for user role
			add_action( 'wp_ajax_addf_disc_rpc_add_user_role_gift_row_tbl', array( $this, 'addf_disc_rpc_add_user_role_gift_row_tbl_cb' ) );
			add_action( 'wp_ajax_nopriv_addf_disc_rpc_add_user_role_gift_row_tbl', array( $this, 'addf_disc_rpc_add_user_role_gift_row_tbl_cb' ) );

			// Add gift row in table
			add_action( 'wp_ajax_addf_disc_rpc_add_gift_row_tbl', array( $this, 'addf_disc_rpc_add_gift_row_tbl_cb' ) );
			add_action( 'wp_ajax_nopriv_addf_disc_rpc_add_gift_row_tbl', array( $this, 'addf_disc_rpc_add_gift_row_tbl_cb' ) );

			// reset pricing template settings
			add_action( 'wp_ajax_addf_drpc_reset_pricing_template_settings', array( $this, 'addf_drpc_reset_pricing_template_settings' ) );
			add_action( 'wp_ajax_nopriv_addf_drpc_reset_pricing_template_settings', array( $this, 'addf_drpc_reset_pricing_template_settings' ) );

			// add column for product rules
			add_filter( 'manage_af_disc_p_rules_posts_columns', array( $this, 'set_custom_edit_addf_disc_rpc_columns' ) );
			add_action( 'manage_af_disc_p_rules_posts_custom_column' , array( $this, 'add_column_data_rules' ), 10, 2 );

			// add column for cart rules
			add_filter( 'manage_af_dis_cart_rule_posts_columns', array( $this, 'set_custom_edit_addf_disc_rpc_columns' ) );
			add_action( 'manage_af_dis_cart_rule_posts_custom_column' , array( $this, 'add_column_data_rules' ), 10, 2 );
			
			add_action('admin_head-edit.php' , array( $this, 'addf_dp_goto_main_setting' ) );
			add_action('edit_form_top', array( $this, 'af_dp_add_custom_button' ) );
		}

		// adding css
		public function add_scripts() {

			$addf_drpc_current_screen = get_current_screen();

			if ( $addf_drpc_current_screen && ( in_array($addf_drpc_current_screen->id, $this-> get_screen_tab_id() ) )) {
				wp_enqueue_style( 'drpc-admin-css', plugins_url('../includes/css/addf-drpc-admin-style.css', __FILE__ ), false, '1.0.0' );
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'drpc-admin-script', plugins_url( '../includes/js/addf-drpc-admin-script.js', __FILE__ ), false, '1.0.0' , $in_footer = false );
				wp_enqueue_style( 'select2', plugins_url( 'assets/css/select2.css', WC_PLUGIN_FILE ), array(), '5.7.2' );
				wp_enqueue_script( 'select2', plugins_url( 'assets/js/select2/select2.min.js', WC_PLUGIN_FILE ), array( 'jquery' ), '4.0.3', true );
				wp_enqueue_script( 'media-upload' );
				wp_enqueue_media();

				$aurgs = array(
					'ajaxurl' => admin_url('admin-ajax.php'),
					'nonce'   => wp_create_nonce('addify_dynamic_pricing_nonce'),
				);

				wp_localize_script('drpc-admin-script', 'php_var', $aurgs);
			}
		}

		
		public function dynamic_discount_remove_bulk_edit( $actions ) {
			unset($actions['edit']);
			return $actions;
		}

		public function get_screen_tab_id() {
			$tabs=array( 'edit-af_dis_cart_rule', 'af_dis_cart_rule', 'woocommerce_page_wc-settings', 'edit-af_disc_p_rules', 'af_disc_p_rules' );
			return $tabs;
		}

		public function addf_dp_goto_main_setting() {
			
			global $current_screen;

			if ( 'af_disc_p_rules' == $current_screen->post_type) {
				?>
				<script type="text/javascript">
					jQuery(document).ready( function($){
						$('<a href="<?php echo esc_url(admin_url('admin.php?page=wc-settings&tab=af_dynamic_discounts_settings')); ?>"  class="page-title-action" ><?php echo esc_html__( 'General Settings' , 'woo-af-drpc' ); ?></a>').insertBefore('hr.wp-header-end');
					});
				</script>

				<script type="text/javascript">
					jQuery(document).ready( function($){
						$('<a href="<?php echo esc_url(admin_url('edit.php?post_type=af_dis_cart_rule')); ?>"  class="page-title-action" ><?php echo esc_html__( 'Cart Discount Rules' , 'woo-af-drpc' ); ?></a>').insertBefore('hr.wp-header-end');
					});
				</script>
				<?php
			} elseif ('af_dis_cart_rule' == $current_screen->post_type ) {

				?>

				<script type="text/javascript">
					jQuery(document).ready( function($){
						$('<a href="<?php echo esc_url(admin_url('admin.php?page=wc-settings&tab=af_dynamic_discounts_settings')); ?>"  class="page-title-action" ><?php echo esc_html__( 'General Settings' , 'woo-af-drpc' ); ?></a>').insertBefore('hr.wp-header-end');
					});
				</script>

				<script type="text/javascript">
					jQuery(document).ready( function($){
						$('<a href="<?php echo esc_url(admin_url('edit.php?post_type=af_disc_p_rules')); ?>"  class="page-title-action" ><?php echo esc_html__( 'Product Pricing Rules' , 'woo-af-drpc' ); ?></a>').insertBefore('hr.wp-header-end');
					});
				</script>
				<?php
			}
		}

		public function af_dp_add_custom_button( $id ) {
			$post = get_post($id);
			if ( 'af_disc_p_rules' == $post->post_type ) {
				?>
				<script type="text/javascript">
					jQuery(document).ready( function($){
						$('<a href="<?php echo esc_url(admin_url('edit.php?post_type=af_disc_p_rules')); ?>"  class="page-title-action" ><?php echo esc_html__( 'All Product Rules' , 'woo-af-drpc' ); ?></a>').insertBefore('hr.wp-header-end');
					});
				</script>
				<?php
			}
			if ( 'af_dis_cart_rule' == $post->post_type ) {
				?>
				<script type="text/javascript">
					jQuery(document).ready( function($){
						$('<a href="<?php echo esc_url(admin_url('edit.php?post_type=af_dis_cart_rule')); ?>"  class="page-title-action" ><?php echo esc_html__( 'All Cart Rules' , 'woo-af-drpc' ); ?></a>').insertBefore('hr.wp-header-end');
					});
				</script>
				<?php
			}
		}

		// add setting in menu
		public function add_admin_menu() {
			add_submenu_page( 
				'woocommerce',
				esc_html__('Cart Discounts Rules', 'woo-af-drpc' ), 
				esc_html__('Cart Discounts Rules', 'woo-af-drpc' ), 
				'manage_options', 
				'edit.php?post_type=af_dis_cart_rule', 
				''
			);

			add_submenu_page( 
				'edit.php?post_type=product',
				esc_html__('Product Pricing Rules', 'woo-af-drpc' ), 
				esc_html__('Product Pricing Rules', 'woo-af-drpc' ), 
				'manage_options', 
				'edit.php?post_type=af_disc_p_rules', 
				''
			);
		}

		public function dynamic_discount_settings_tab_cb( $settings ) {
			$settings[] = include ADD_DISC_RPC_DIR . 'admin/settings/class-admin-drpc-settings.php' ;  
			return $settings;
		}

		// backend settings fields
		public function save_discount_settings() {
			if ( !get_option('addf_drpc_option_rules_priority') ) {
				update_option( 'addf_drpc_option_rules_priority' , 'follow_sequence' , true );
			}
			if ( !get_option('addf_drpc_option_rules_cart_priority') ) {
				update_option( 'addf_drpc_option_rules_cart_priority' , 'follow_sequence' , true );
			}
			if ( !get_option('addf_drpc_option_sale_price') ) {
				update_option( 'addf_drpc_option_sale_price' , 'sale' , true );
			}
			if ( !get_option('addf_drpc_option_multi_rule') ) {
				update_option( 'addf_drpc_option_multi_rule' , 'both' , true );
			}
			if ( !get_option('addf_drpc_option_pricing_table_location') ) {
				update_option( 'addf_drpc_option_pricing_table_location' , 'above_cart' , true );
			}
			if ( !get_option('addf_drpc_option_pricing_table_layout') ) {
				update_option( 'addf_drpc_option_pricing_table_layout' , 'horizontal' , true );
			}
		}

		//  add meta boxed
		public function add_discount_meta_box() {

			// for product rules
			add_meta_box(
				'discount_rpc_select_products',
				esc_html__( 'Select Product and Categories', 'woo-af-drpc' ),
				array( $this, 'addify_dynamic_pricing_nonce_select_products_metabox_pl_cb' ),
				'af_disc_p_rules'
			);
			add_meta_box(
				'discount_rpc_settings',
				esc_html__( 'Discount Settings', 'woo-af-drpc' ),
				array( $this, 'addify_dynamic_pricing_nonce_discount_settings_metabox_pl_cb' ),
				'af_disc_p_rules'
			);
			add_meta_box(
				'discount_rpc_discount_adj_tbl',
				esc_html__( 'Discount Adjustments', 'woo-af-drpc' ),
				array( $this, 'addify_dynamic_pricing_nonce_user_spent_metabox_pl_cb' ),
				'af_disc_p_rules'
			);
			add_meta_box(
				'discount_rpc_discount_type',
				esc_html__( 'Rule Settings', 'woo-af-drpc' ),
				array( $this, 'addify_dynamic_pricing_nonce_discount_type_metabox_pl_cb' ),
				'af_disc_p_rules'
			);
			add_meta_box(
				'discount_rpc_message_settings',
				esc_html__( 'Message Settings', 'woo-af-drpc' ),
				array( $this, 'addify_dynamic_pricing_nonce_message_settings_metabox_pl_cb' ),
				'af_disc_p_rules'
			);
			// for cart rules af_dis_cart_rule
			add_meta_box(
				'discount_rpc_select_products_cart',
				esc_html__( 'Select Product and Categories', 'woo-af-drpc' ),
				array( $this, 'addify_dynamic_pricing_nonce_select_products_metabox_cart_l_cb' ),
				'af_dis_cart_rule'
			);
			add_meta_box(
				'discount_rpc_discount_type_cart_l',
				esc_html__( 'Discount Settings', 'woo-af-drpc' ),
				array( $this, 'addify_dynamic_pricing_nonce_discount_settings_metabox_cart_l_cb' ),
				'af_dis_cart_rule'
			);
			add_meta_box(
				'discount_rpc_discount_adjustments',
				esc_html__( 'Discount Adjustments', 'woo-af-drpc' ),
				array( $this, 'addify_dynamic_pricing_nonce_discount_adjustments_metabox_cart_l_cb' ),
				'af_dis_cart_rule'
			);
			add_meta_box(
				'discount_rpc_settings_cart',
				esc_html__( 'Rule Settings', 'woo-af-drpc' ),
				array( $this, 'addify_dynamic_pricing_nonce_rule_settings_metabox_cart_l_cb' ),
				'af_dis_cart_rule'
			);
			add_meta_box(
				'discount_rpc_messages_settings',
				esc_html__( 'Messages Settings', 'woo-af-drpc' ),
				array( $this, 'addify_dynamic_pricing_nonce_messages_settings_metabox_cart_l_cb' ),
				'af_dis_cart_rule'
			);
		}
		
		// choose products for product rules
		public function addify_dynamic_pricing_nonce_select_products_metabox_pl_cb() {
			include_once ADD_DISC_RPC_DIR . 'admin/metaboxes/product-rule/disc-rpc-choose-products.php';
		}

			// choose user roles for product rules
		public function addify_dynamic_pricing_nonce_discount_type_metabox_pl_cb() {
			include_once ADD_DISC_RPC_DIR . 'admin/metaboxes/product-rule/disc-rpc-rule-settings.php';
		}

			// discount settings for product rules
		public function addify_dynamic_pricing_nonce_discount_settings_metabox_pl_cb() {
			include_once ADD_DISC_RPC_DIR . 'admin/metaboxes/product-rule/disc-rpc-discount-settings.php';
		}

		public function addify_dynamic_pricing_nonce_user_spent_metabox_pl_cb() {
			include_once ADD_DISC_RPC_DIR . 'admin/metaboxes/product-rule/discount-rpc-customer-spent.php';
		}

		public function addify_dynamic_pricing_nonce_message_settings_metabox_pl_cb() {
			include_once ADD_DISC_RPC_DIR . 'admin/metaboxes/product-rule/disc-rpc-messages-settings.php';
		}

		// Call backs for Cart Rules
			// choose products for cart tules
		public function addify_dynamic_pricing_nonce_select_products_metabox_cart_l_cb() {
			include_once ADD_DISC_RPC_DIR . 'admin/metaboxes/cart-rule/disc-rpc-choose-products.php';
		}

			// discount settings
		public function addify_dynamic_pricing_nonce_discount_settings_metabox_cart_l_cb() {
			include_once ADD_DISC_RPC_DIR . 'admin/metaboxes/cart-rule/disc-rpc-discount-settings.php';
		}

			// discount settings for cart tules
		public function addify_dynamic_pricing_nonce_discount_adjustments_metabox_cart_l_cb() {
			include_once ADD_DISC_RPC_DIR . 'admin/metaboxes/cart-rule/discount-rpc-customer-spent.php';
		}

			// discount settings for cart tules
		public function addify_dynamic_pricing_nonce_rule_settings_metabox_cart_l_cb() {
			include_once ADD_DISC_RPC_DIR . 'admin/metaboxes/cart-rule/disc-rpc-rule-settings.php';
		}

			// Messages meta box for cart tules
		public function addify_dynamic_pricing_nonce_messages_settings_metabox_cart_l_cb() {
			include_once ADD_DISC_RPC_DIR . 'admin/metaboxes/cart-rule/disc-rpc-messages-settings.php';
		}

		// save meta values
		public function save_discount_p_meta_fields( $post_id, $post ) {
			// For Product Rules
			include_once ADD_DISC_RPC_DIR . 'admin/metaboxes/product-rule/disc-rpc-product-rule-save-meta.php';
		}

		public function save_discount_c_meta_fields( $post_id, $post ) {
			// For Cart Rules
			include_once ADD_DISC_RPC_DIR . 'admin/metaboxes/cart-rule/disc-rpc-cart-rule-save-meta.php';
		}

		//  product live search
		public function product_live_search() {

			$return = array(); 

			if (isset($_GET['q'])) {
				$search =  sanitize_text_field( wp_unslash( $_GET['q'] ));
			}
			$nonce = isset($_GET['nonce']) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_dynamic_pricing_nonce'  ) ) {
				die( esc_html__('Security Violated !', 'woo-af-drpc' ) );
			}
			$search_results = new WP_Query(
				array(
					's'              => $search, 
					'post_type'      => array( 'product', 'product_variation' ), 
					'post_status'    => 'publish', 
					'posts_per_page' => -1, 
				)
			);
			if ( $search_results->have_posts() ) :
				while ( $search_results->have_posts() ) :
					$search_results->the_post();
					$title    = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
					$return[] = array( $search_results->post->ID, $title ); 
				endwhile;
			endif;
			wp_send_json( $return );
		}
		
		public function choose_gift_product() {
			$return = array(); 
			
			if (isset($_GET['q'])) {
				$search =  sanitize_text_field( wp_unslash( $_GET['q'] ));
			}

			$nonce = isset($_GET['nonce']) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_dynamic_pricing_nonce'  ) ) {
				die( esc_html__('Security Violated !', 'woo-af-drpc' ) );
			}

			$search_results = new WP_Query(
				array(
					's'              => $search, 
					'post_type'      => array( 'product', 'product_variation' ), 
					'post_status'    => 'publish', 
					'posts_per_page' => -1, 
				)
			);
			if ( $search_results->have_posts() ) :
				while ( $search_results->have_posts() ) :
					$search_results->the_post();
					if ( ( 'variation' == wc_get_product( get_the_ID() )->get_type() ) || ( ( 'simple' == wc_get_product( get_the_ID() )->get_type() ) ) ) {
						$product = wc_get_product( get_the_ID() );
						if ( $product->is_type('variation') ) {
							$_product_id    = $product->get_parent_id();
							$parent_product = wc_get_product( $_product_id );
							$attributes     = $parent_product->get_variation_attributes() ;
							$check          = false;
							foreach ( $product->get_attributes() as $taxonomy => $terms_slug ) {
								if ( ( '' == $terms_slug ) && ( '' != $taxonomy )  ) {
									$check = true;
								}
							}
							if ( $check ) {
								continue;
							}
						}
						if (( '' == $product->is_on_backorder() && '' == $product->is_in_stock() ) || '' == $product->get_price() ) {
							continue;
						}
						$title    = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
						$return[] = array( $search_results->post->ID, $title ); 
					}
				endwhile;
			endif;
			wp_send_json( $return );
		}

		public function customer_live_search_cb() {
			if (isset($_POST['q'])) {
				$pro = sanitize_text_field( wp_unslash( $_POST['q'] ) );
			}

			$nonce = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_dynamic_pricing_nonce'  ) ) {
				die( esc_html__('Sorry, your nonce did not verify.', 'woo-af-drpc' ) );
			}

			$data_array  = array();
			$users       = new WP_User_Query(
				array(
					'search'         => '*' . esc_attr( $pro ) . '*',
					'search_columns' => array(
						'user_login',
						'user_nicename',
						'user_email',
						'user_url',
					),
				)
			);
			$users_found = $users->get_results();
			if ( ! empty( $users_found ) ) {
				foreach ( $users_found as $proo ) {
					$title        = $proo->display_name;
					$data_array[] = array( $proo->ID, $title . '(' . $proo->user_email . ')' );
				}
			}
			echo wp_json_encode( $data_array );
			die();
		}

		public function addf_disc_rpc_add_gift_row_tbl_cb() {

			$nonce = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_dynamic_pricing_nonce'  ) ) {
				die( esc_html__('Sorry, your nonce did not verify.', 'woo-af-drpc' ) );
			}

			ob_start();
			?>
			<tr>
				<td>
					<select name="addf_disc_rpc_select_customer_gift[]" class="addf_disc_rpc_customers" style="width:100%;"  data-placeholder="<?php echo esc_html__( 'Choose Customer... ', 'woo-af-drpc' ); ?>" >
					</select>
				</td>
				<td>
					<select name="addf_disc_choose_new_gift_list[]" data-placeholder="<?php echo esc_html__( 'Choose products...', 'woo-af-drpc' ); ?>" class="addf_disc_choose_new_gift_product" style="width:100%;">
					</select>
				</td>
				<td>
					<input type="number" name="addf_disc_choose_new_gift_qty[]"  min="1" value="1">
				</td>
				<td>
					<input type="number" name="addf_disc_choose_gift_min_qty[]"  min="1" value="1">
				</td>
				<td>
					<span class="addf_disc_rpc_remove_row_tbl">&#x2717;</span>
				</td>
			</tr>
			<?php
			$row = ob_get_clean(); 
			wp_send_json(
				array(
					'success' => 'yes',
					'tr_data' => $row, 
				)
			);
			die();
		}

		public function addf_drpc_reset_pricing_template_settings() {
			$nonce = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_dynamic_pricing_nonce'  ) ) {
				die( esc_html__('Sorry, your nonce did not verify.', 'woo-af-drpc' ) );
			}

			update_option('addf_drpc_reset_settings', '');
			update_option('addf_drpc_template_type', 'table');
			update_option('addf_drpc_template_heading_text', 'Select your Deal');
			update_option('addf_drpc_enable_table_save_column', 'yes');
			update_option('addf_drpc_template_heading_font_size', '28');
			update_option('addf_drpc_table_header_background_color', '#FFFFFF');
			update_option('addf_drpc_table_odd_rows_background_color', '#FFFFFF');
			update_option('addf_drpc_table_even_rows_background_color', '#FFFFFF');
			update_option('addf_drpc_table_header_text_color', '#000000');
			update_option('addf_drpc_table_odd_rows_text_color', '#000000');
			update_option('addf_drpc_table_even_rows_text_color', '#000000');
			update_option('addf_drpc_enable_table_border', 'yes');
			update_option('addf_drpc_table_border_color', '#CFCFCF');
			update_option('addf_drpc_table_header_font_size', '18');
			update_option('addf_drpc_table_row_font_size', '16');
			update_option('addf_drpc_list_border_color', '#95B0EE');
			update_option('addf_drpc_list_background_color', '#FFFFFF');
			update_option('addf_drpc_list_text_color', '#000000');
			update_option('addf_drpc_selected_list_background_color', '#DFEBFF');
			update_option('addf_drpc_selected_list_text_color', '#000000');
			update_option('addf_drpc_card_border_color', '#A3B39E');
			update_option('addf_drpc_card_background_color', '#FFFFFF');
			update_option('addf_drpc_card_text_color', '#000000');
			update_option('addf_drpc_selected_card_border_color', '#27CA34');
			update_option('addf_drpc_sale_tag_background_color', '#FF0000');
			update_option('addf_drpc_sale_tag_text_color', '#FFFFFF');

			die();          
		}

		public function addf_disc_rpc_add_user_role_gift_row_tbl_cb() {

			$nonce = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_dynamic_pricing_nonce'  ) ) {
				die( esc_html__('Sorry, your nonce did not verify.', 'woo-af-drpc' ) );
			}

			ob_start();
			?>
			<tr>
				<td>
					<select name="addf_disc_rpc_select_user_role_gift[]" >
						<option value="all" ><?php echo esc_html__( 'All User Roles' , 'woo-af-drpc' ); ?></option>
						<?php
						global $wp_roles;
						$roles = $wp_roles->get_names();
						foreach ( $roles as $role_id => $role_name ) {
							?>
							<option value="<?php echo esc_attr( $role_id ); ?>" ><?php echo esc_html__( $role_name , 'woo-af-drpc' ); ?></option>
							<?php
						}
						?>
						<option value="guest" ><?php echo esc_html__( 'Guest' , 'woo-af-drpc' ); ?></option>
					</select>
				</td>
				<td>
					<select name="addf_disc_user_role_gift_list[]" data-placeholder="<?php echo esc_html__( 'Choose products...', 'woo-af-drpc' ); ?>" class="addf_disc_choose_new_gift_product" style="width:100%;">
					</select>
				</td>
				<td>
					<input type="number" min="1" name="addf_disc_user_role_gift_qty[]" value="1">
				</td>
				<td>
					<input type="number" min="1" name="addf_disc_user_role_gift_min_qty[]" value="1">
				</td>
				<td>
					<span class="addf_disc_rpc_remove_row_tbl">&#x2717;</span>
				</td>
			</tr>
			<?php
			$row = ob_get_clean(); 
			wp_send_json(
				array(
					'success' => 'yes',
					'tr_data' => $row, 
				)
			);
			die();
		}

		public function addf_disc_rpc_discount_adj_table_cb() {

			$nonce = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_dynamic_pricing_nonce'  ) ) {
				die( esc_html__('Sorry, your nonce did not verify.', 'woo-af-drpc' ) );
			}

			$array_size = sanitize_text_field( isset($_POST['array_size']) ? $_POST['array_size'] : '');
			ob_start();
			?>
			<tr>
				<td>
					<select name="addf_disc_rpc_roles_select[]" >
						<option value="all" ><?php echo esc_html__( 'All User Roles' , 'woo-af-drpc' ); ?></option>
						<?php
						global $wp_roles;
						$roles = $wp_roles->get_names();
						foreach ( $roles as $role_id => $role_name ) {
							?>
							<option value="<?php echo esc_attr( $role_id ); ?>" ><?php echo esc_html__( $role_name , 'woo-af-drpc' ); ?></option>
							<?php
						}
						?>
						<option value="guest"><?php echo esc_html__( 'Guest' , 'woo-af-drpc' ); ?></option>
					</select>
				</td>
				<td>
					<select name="addf_drpc_discount_amount_choice[]" class="addf_disc_rpc_cb_dis_allow">
						<option value="fixed_price" ><?php echo esc_html__( 'Fixed price ', 'woo-af-drpc' ); ?></option>
						<option value="fixed_price_increase" ><?php echo esc_html__( 'Fixed increase', 'woo-af-drpc' ); ?></option>
						<option value="fixed_price_decrease" ><?php echo esc_html__( 'Fixed decrease', 'woo-af-drpc' ); ?></option>
						<option value="fixed_percent_increase" ><?php echo esc_html__( 'Percentage increase', 'woo-af-drpc' ); ?></option>
						<option value="fixed_percent_decrease" ><?php echo esc_html__( 'Percentage decrease', 'woo-af-drpc' ); ?></option>
					</select>
				</td>
				<td>
					<input type="number" name="addf_disc_rpc_disc_val_tbl[]" min="0" step="any" value="" >
				</td>
				<td>
					<input type="number" name="addf_disc_rpc_min_qty_tbl[]" min="0" value="" >
				</td>
				<td>
					<input type="number" name="addf_disc_rpc_max_qty_tbl[]" min="0" value="" >
				</td>
				<td>
					<input type="checkbox" name="addf_disc_rpc_replace_prc_roles_cb[<?php echo esc_attr($array_size); ?>]" value="yes" >
				</td>
				<td>
					<span class="addf_disc_rpc_remove_row_tbl">&#x2717;</span>
				</td>
			</tr>
			<?php
			$row = ob_get_clean(); 
			wp_send_json(
				array(
					'success' => 'yes',
					'tr_data' => $row, 
				)
			);
			die();
		}

		public function addf_disc_rpc_conditional_add_user_role_row_cart_tbl_cb() {

			$nonce = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_dynamic_pricing_nonce'  ) ) {
				die( esc_html__('Sorry, your nonce did not verify.', 'woo-af-drpc' ) );
			}

			ob_start();
			?>
			<tr>
				<td>
					<select name="addf_disc_rpc_cart_cond_user_role[]" class="" style="width:100%;"  data-placeholder="<?php echo esc_html__( 'Choose user role... ', 'woo-af-drpc' ); ?>" >
						<option value="all"  ><?php echo esc_html__( 'All User Roles' , 'woo-af-drpc' ); ?></option>
						<?php
						global $wp_roles;
						$roles = $wp_roles->get_names();
						foreach ( $roles as $role_id => $role_name ) {
							?>
							<option value="<?php echo esc_attr( $role_id ); ?>"  ><?php echo esc_html__( $role_name , 'woo-af-drpc' ); ?></option>
							<?php
						}
						?>
						<option value="guest"  ><?php echo esc_html__( 'Guest' , 'woo-af-drpc' ); ?></option>
					</select>
				</td>
				<td>
					<select name="addf_disc_user_role_cond_cart_gift_list[]" data-placeholder="<?php echo esc_html__( 'Choose products...', 'woo-af-drpc' ); ?>" class="addf_disc_choose_new_gift_product" style="width:100%;"></select>
				</td>
				<td>
					<input type="number" min="1" name="addf_disc_rpc_cond_disc_val_tbl_user_role[]" step="any" value="1" >
				</td>
				<td>
					<input type="number" min="1" name="addf_disc_rpc_cond_min_qty_tbl_user_role[]" value="1" >
				</td>
				<td>
					<input type="number" min="1" name="addf_disc_rpc_cond_max_qty_tbl_user_role[]" value="" >
				</td>
				<td>
					<span class="addf_disc_rpc_remove_row_tbl">&#x2717;</span>
				</td>
			</tr>
			<?php
			$row = ob_get_clean(); 
			wp_send_json(
				array(
					'success' => 'yes',
					'tr_data' => $row, 
				)
			);
			die();
		}

		public function addf_disc_rpc_conditional_add_cust_row_cart_tbl_cb() {

			$nonce = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_dynamic_pricing_nonce'  ) ) {
				die( esc_html__('Sorry, your nonce did not verify.', 'woo-af-drpc' ) );
			}

			ob_start();
			?>
			<tr>
				<td>
					<select name="addf_disc_rpc_cond_cart_select_customer[]" class="addf_disc_rpc_customers" style="width:100%;"  data-placeholder="<?php echo esc_html__( 'Choose Customer... ', 'woo-af-drpc' ); ?>" ></select>
				</td>
				<td>
					<select name="addf_disc_cust_cart_gift_list[]" data-placeholder="<?php echo esc_html__( 'Choose products...', 'woo-af-drpc' ); ?>" class="addf_disc_choose_new_gift_product" style="width:100%;">
					</select>
				</td>
				<td>
					<input type="number" min="1" step="any" name="addf_disc_rpc_cond_qty_tbl_cust[]" value="1" >
				</td>
				<td>
					<input type="number" min="1" name="addf_disc_rpc_cond_min_qty_tbl_cust[]" value="1" >
				</td>
				<td>
					<input type="number" min="1" name="addf_disc_rpc_cond_max_qty_tbl_cust[]" value="" >
				</td>
				<td>
					<span class="addf_disc_rpc_remove_row_tbl">&#x2717;</span>
				</td>
			</tr>
			<?php
			$row = ob_get_clean(); 
			wp_send_json(
				array(
					'success' => 'yes',
					'tr_data' => $row, 
				)
			);
			die();
		}

		public function addf_disc_rpc_add_user_role_row_cart_action_cb() {

			$nonce = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_dynamic_pricing_nonce'  ) ) {
				die( esc_html__('Sorry, your nonce did not verify.', 'woo-af-drpc' ) );
			}

			ob_start();
			?>
			<tr>
				<td>
					<select name="addf_disc_rpc_cart_select_user_role[]" class="" style="width:100%;"  data-placeholder="<?php echo esc_html__( 'Choose user role... ', 'woo-af-drpc' ); ?>" >
						<option value="all"  ><?php echo esc_html__( 'All User Roles' , 'woo-af-drpc' ); ?></option>
						<?php
						global $wp_roles;
						$roles = $wp_roles->get_names();
						foreach ( $roles as $role_id => $role_name ) {
							?>
							<option value="<?php echo esc_attr( $role_id ); ?>"><?php echo esc_html__( $role_name , 'woo-af-drpc' ); ?></option>
							<?php
						}
						?>
						<option value="guest"><?php echo esc_html__( 'Guest' , 'woo-af-drpc' ); ?></option>
					</select>
				</td>
				<td>
					<select name="addf_drpc_user_role_disc_choice[]" >
						<option value="fixed_price_increase" ><?php echo esc_html__( 'Fixed increase', 'woo-af-drpc' ); ?></option>
						<option value="fixed_price_decrease" ><?php echo esc_html__( 'Fixed decrease', 'woo-af-drpc' ); ?></option>
						<option value="fixed_percent_increase" ><?php echo esc_html__( 'Percentage increase', 'woo-af-drpc' ); ?></option>
						<option value="fixed_percent_decrease" ><?php echo esc_html__( 'Percentage decrease', 'woo-af-drpc' ); ?></option>
					</select>
				</td>
				<td>
					<input type="number" min="1" name="addf_disc_rpc_disc_val_tbl_user_role[]" step="any" value="1" >
				</td>
				<td>
					<input type="number" min="1" name="addf_disc_rpc_min_qty_tbl_user_role[]" value="1" >
				</td>
				<td>
					<input type="number" min="1" name="addf_disc_rpc_max_qty_tbl_user_role[]" value="" >
				</td>
				<td>
					<span class="addf_disc_rpc_remove_row_tbl">&#x2717;</span>
				</td>
			</tr>
			<?php
			$row = ob_get_clean(); 
			wp_send_json(
				array(
					'success' => 'yes',
					'tr_data' => $row, 
				)
			);
			die();
		}

		public function addf_disc_rpc_add_cust_row_cart_action_cb() {

			$nonce = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_dynamic_pricing_nonce'  ) ) {
				die( esc_html__('Sorry, your nonce did not verify.', 'woo-af-drpc' ) );
			}

			ob_start();
			?>
			<tr>
				<td>
					<select name="addf_disc_rpc_cart_select_customer[]" class="addf_disc_rpc_customers" style="width:100%;"  data-placeholder="<?php echo esc_html__( 'Choose Customer... ', 'woo-af-drpc' ); ?>" ></select>
				</td>
				<td>
					<select name="addf_drpc_cust_disc_choice[]" >
						<option value="fixed_price_increase" ><?php echo esc_html__( 'Fixed increase', 'woo-af-drpc' ); ?></option>
						<option value="fixed_price_decrease" ><?php echo esc_html__( 'Fixed decrease', 'woo-af-drpc' ); ?></option>
						<option value="fixed_percent_increase" ><?php echo esc_html__( 'Percentage increase', 'woo-af-drpc' ); ?></option>
						<option value="fixed_percent_decrease" ><?php echo esc_html__( 'Percentage decrease', 'woo-af-drpc' ); ?></option>
					</select>
				</td>
				<td>
					<input type="number" class="addf_drpc_number_input_field" min="1" step="any" name="addf_disc_rpc_disc_val_tbl_cust[]" value="1" >
				</td>
				<td>
					<input type="number" class="addf_drpc_number_input_field" min="1" name="addf_disc_rpc_min_qty_tbl_cust[]" value="1" >
				</td>
				<td>
					<input type="number" class="addf_drpc_number_input_field" min="1" name="addf_disc_rpc_max_qty_tbl_cust[]" value="" >
				</td>
				<td>
					<span class="addf_disc_rpc_remove_row_tbl">&#x2717;</span>
				</td>
			</tr>
			<?php
			$row = ob_get_clean(); 
			wp_send_json(
				array(
					'success' => 'yes',
					'tr_data' => $row, 
				)
			);
			die();
		}

		public function add_new_row_customer_table() {

			$nonce = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'addify_dynamic_pricing_nonce'  ) ) {
				die( esc_html__('Sorry, your nonce did not verify.', 'woo-af-drpc' ) );
			}

			$array_size = sanitize_text_field( isset($_POST['array_size']) ? $_POST['array_size'] : '');
			ob_start();
			?>
			<tr>
				<td>
					<select name="addf_disc_rpc_select_customer[]" class="addf_disc_rpc_customers" style="width:100%;"  data-placeholder="<?php echo esc_html__( 'Choose Customer... ', 'woo-af-drpc' ); ?>" ></select>
				</td>
				<td>
					<select name="addf_drpc_cust_disc_choice[]" class="addf_disc_rpc_cb_dis_allow">
						<option value="fixed_price" ><?php echo esc_html__( 'Fixed price ', 'woo-af-drpc' ); ?></option>
						<option value="fixed_price_increase" ><?php echo esc_html__( 'Fixed increase', 'woo-af-drpc' ); ?></option>
						<option value="fixed_price_decrease" ><?php echo esc_html__( 'Fixed decrease', 'woo-af-drpc' ); ?></option>
						<option value="fixed_percent_increase" ><?php echo esc_html__( 'Percentage increase', 'woo-af-drpc' ); ?></option>
						<option value="fixed_percent_decrease" ><?php echo esc_html__( 'Percentage decrease', 'woo-af-drpc' ); ?></option>
					</select>
				</td>
				<td>
					<input type="number" min="1" step="any" class="addf_drpc_number_input_field" name="addf_disc_rpc_disc_val_tbl_cust[]" value="1" >
				</td>
				<td>
					<input type="number" min="1" class="addf_drpc_number_input_field" name="addf_disc_rpc_min_qty_tbl_cust[]" value="1" >
				</td>
				<td>
					<input type="number" min="1" class="addf_drpc_number_input_field" name="addf_disc_rpc_max_qty_tbl_cust[]" value="" >
				</td>
				<td>
					<input type="checkbox" name="addf_disc_rpc_replace_prc_cust_cb[<?php echo esc_attr($array_size); ?>]" value="yes" >
				</td>
				<td>
					<span class="addf_disc_rpc_remove_row_tbl">&#x2717;</span>
				</td>
			</tr>
			<?php
			$row = ob_get_clean(); 
			wp_send_json(
				array(
					'success' => 'yes',
					'tr_data' => $row, 
				)
			);
			die();
		}

		// Add the custom columns to the addf_gift_registry post type:
		public function set_custom_edit_addf_disc_rpc_columns( $columns ) {
			$columns['addf_disc_rpc_start_time'] = esc_html__( 'Start Date of rule', 'woo-af-drpc' );
			$columns['addf_disc_rpc_end_time']   = esc_html__( 'End Date of rule', 'woo-af-drpc' );
			$columns['addf_disc_rpc_menu_order'] = esc_html__( 'Rule order', 'woo-af-drpc' );
			$columns['addf_disc_rpc_must_apply'] = esc_html__( 'Rule priority ', 'woo-af-drpc' );
			return $columns;
		}

		// Add the data to the custom columns for the addf_gift_registry post type:
		public function add_column_data_rules( $column, $post_id ) {
			global $post;
			switch ( $column ) {
				case 'addf_disc_rpc_start_time':
				$start_date = get_post_meta( $post_id , 'addf_disc_rpc_start_time' , true );
					if ( ( !$start_date ) || ( '' == $start_date ) ) {
						echo esc_attr('---');
					} else {
						$start_date = gmdate('M-d-Y', strtotime($start_date));
						echo esc_attr($start_date);
					}
					break;
				case 'addf_disc_rpc_end_time':
				$end_date = get_post_meta( $post_id , 'addf_disc_rpc_end_time' , true ); 
					if ( ( !$end_date ) || ( '' == $end_date ) ) {
						echo esc_attr('---');
					} else {
						$end_date = gmdate('M-d-Y', strtotime($end_date));
						echo esc_attr($end_date);
					}
					break;
				case 'addf_disc_rpc_menu_order':
				echo esc_attr($post->menu_order );
					break;
				case 'addf_disc_rpc_must_apply':
				$must_apply = get_post_meta( $post_id , 'addf_disc_rpc_rule_priority' , true ); 
					if ( 'must_apply' == $must_apply ) {
						?>
					<span class="addf_disc_rpc_green_color">
						<?php
						echo esc_html__( 'Must Apply ', 'woo-af-drpc' );
						?>
					</span>
						<?php
					} else {
						?>
					<span class="addf_disc_rpc_yellow_color">
						<?php
						echo esc_html__( 'Follow Sequence ', 'woo-af-drpc' );
						?>
					</span>
						<?php
					}
					break;
			}
		}
	}
	new AF_Discount_Rpc_Admin();
}
