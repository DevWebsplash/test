<?php

class WC_Settings_Addify_Dynamic_Discounts extends WC_Settings_Page {

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->id = 'af_dynamic_discounts_settings';

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
		add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Add plugin options tab
	 *
	 * @return array
	 */
	public function add_settings_tab( $settings_tabs ) {
		$settings_tabs[ $this->id ] = __( 'Dynamic Pricing', 'woo-af-drpc' );
		return $settings_tabs;
	}

	/**
	 * Get sections
	 *
	 * @return array
	 */
	public function get_sections() {

		$sections = array(
			''                          => __( 'General Settings', 'woo-af-drpc' ),
			'pricing-template-settings' => __( 'Pricing Template Settings', 'woo-af-drpc' ),
			'pricing-template-design'   => __( 'Pricing Template Design', 'woo-af-drpc' ),
			'product-rule'              => __( 'Product Pricing Rules', 'woo-af-drpc' ),
			'cart-rule'                 => __( 'Cart Discount Rules', 'woo-af-drpc' ),
		);
		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}


	/**
	 * Get sections
	 *
	 * @return array
	 */
	public function get_settings( $section = null, $settings = null ) {

		$settings = array();

		if (null == $section) {

			// Add Title to the Settings
			$settings[] = array(
				'name' => __( 'General Settings', 'woo-af-drpc' ),
				'type' => 'title',
				'id'   => 'af_dynamic_discounts_gen_section',
			);
			
			$settings[] = array(
				'name'    => __( 'Priority of product rules', 'woo-af-drpc' ),
				'id'      => 'addf_drpc_option_rules_priority',
				'type'    => 'radio',
				'desc'    => esc_html__( 'Choose type of rules applied for product discount rules ', 'woo_addf_pc' ) 
							. '<br>' . esc_html__( 'Gift Products will be added by sequence of rules ', 'woo_addf_pc' ),
				'options' => array(
					'follow_sequence' => __( 'Follow the priority sequence', 'woo-af-drpc' ),
					'smaller_price'   => __( 'Use rule with smaller price', 'woo-af-drpc' ),
					'more_price'      => __( 'Use rule with highest price', 'woo-af-drpc' ),
				),
			);
			$settings[] = array(
				'name'    => __( 'Priority of cart rules', 'woo-af-drpc' ),
				'id'      => 'addf_drpc_option_rules_cart_priority',
				'type'    => 'radio',
				'desc'    => esc_html__( 'Choose type of rules applied for cart discount rules ', 'woo_addf_pc' ),
				'options' => array(
					'follow_sequence' => __( 'Follow the priority sequence', 'woo-af-drpc' ),
					'smaller_price'   => __( 'Use rule with smaller Discount/Fees', 'woo-af-drpc' ),
					'more_price'      => __( 'Use rule with highest Discount/Fees', 'woo-af-drpc' ),
					'apply_all'       => __( 'Apply all rules', 'woo-af-drpc' ),
				),
			);
			$settings[] = array(
				'name'    => __( 'Price for sale price products', 'woo-af-drpc' ),
				'id'      => 'addf_drpc_option_sale_price',
				'type'    => 'radio',
				'desc'    => esc_html__( 'Choose the discount setting for sale price product ', 'woo_addf_pc' ),
				'options' => array(
					'sale'    => __( 'Apply discount on sale price', 'woo-af-drpc' ),
					'regular' => __( 'Apply discount on regular price', 'woo-af-drpc' ),
					'ignore'  => __( 'Ignore discount', 'woo-af-drpc' ),
				),
			);
			$settings[] = array(
				'name'    => __( 'Multi rules selection', 'woo-af-drpc' ),
				'id'      => 'addf_drpc_option_multi_rule',
				'type'    => 'radio',
				'desc'    => esc_html__( 'Choose a method when there are both (cart rule and product rule) rules applies ', 'woo_addf_pc' ),
				'options' => array(
					'both'         => __( 'Apply discount from both rules', 'woo-af-drpc' ),
					'product_rule' => __( 'Apply discount only from product rule', 'woo-af-drpc' ),
				),
			);
			
			$settings[] = array(
				'type' => 'sectionend',
				'id'   => 'af_dynamic_discounts_gen_section',
			);
			
			
		} elseif ('pricing-template-settings' == $section) {
			// Add Title to the Settings

			$settings[] = array(
				'name' => __( 'Pricing Template Settings', 'woo-af-drpc' ),
				'type' => 'title',
				'id'   => 'af_dynamic_discounts_table_section',
			);

			
			$settings[] = array(
				'name'    => __( 'Location for tier pricing template', 'woo-af-drpc' ),
				'id'      => 'addf_drpc_option_pricing_table_location',
				'type'    => 'radio',
				'desc'    => esc_html__( 'Choose location for pricing template shown on product page ', 'woo_addf_pc' ),
				'options' => array(
					'below_price' => __( 'Below price of product', 'woo-af-drpc' ),
					'above_cart'  => __( 'Above "Add to Cart " button', 'woo-af-drpc' ),
					'below_cart'  => __( 'Below "Add to Cart " button', 'woo-af-drpc' ),
				),
			);
			$settings[] = array(
				'name'    => __( 'Layout of tier table pricing', 'woo-af-drpc' ),
				'id'      => 'addf_drpc_option_pricing_table_layout',
				'type'    => 'radio',
				'desc'    => esc_html__( 'Choose style for pricing table shown on product page (Only Applicable for table)', 'woo_addf_pc' ),
				'options' => array(
					'vertical'   => __( 'Vertical table', 'woo-af-drpc' ),
					'horizontal' => __( 'Horizontal table', 'woo-af-drpc' ),
				),
			);

			$settings[] = array(
				'type' => 'sectionend',
				'id'   => 'af_dynamic_discounts_table_section',
			);

		} else if ('pricing-template-design' == $section) {
			// Add Title to the Settings
			$settings[] = array(
				'name' => __( 'Pricing Template Design', 'woo-af-drpc' ),
				'type' => 'title',
				'id'   => 'af_dynamic_discounts_template_design_section',
			);
			
			$table_img = ADDF_DISC_RPC_URL . 'includes/images/table.png';
			$card_img = ADDF_DISC_RPC_URL . 'includes/images/card.png';
			$list_img = ADDF_DISC_RPC_URL . 'includes/images/list.png';

			$settings [] = array(
				'name' => esc_html__( 'Pricing Design Template ', 'woo-af-drpc' ),
				'id'   => 'addf_drpc_template_type',
				'type' => 'select',
				'options'   => array(
					'table'         => __( 'Table', 'woo-af-drpc' ),
					'card'          => __( 'Card', 'woo-af-drpc' ),
					'list'          => __('List', 'woo-af-drpc'),
				),
				'desc' => '<br><div>
							<img class="addf_drpc_table_img" style="display:none" src="' . $table_img . '" />
							<img class="addf_drpc_card_img" style="display:none" src="' . $card_img . '" />
							<img class="addf_drpc_list_img" style="display:none" src="' . $list_img . '" />
						</div>',
			);

			$settings [] = array(
				'type' => 'addf_drpc_nonce_field',
			);

			$settings [] = array(
				'name' => esc_html__( 'Enable Template Heading', 'woo-af-drpc' ),
				'id'   => 'addf_drpc_enable_template_heading',
				'type' => 'checkbox',
				'desc' => '<br>' . esc_html__( 'Enable the heading for template.', 'woo-af-drpc' ),
			);

			$settings [] = array(
				'name' => esc_html__( 'Template Heading Text', 'woo-af-drpc' ),
				'id'   => 'addf_drpc_template_heading_text',
				'type' => 'text',
				'desc' => esc_html__( 'Enter template heading text.', 'woo-af-drpc' ),
			);

			$settings [] = array(
				'name' => esc_html__( 'Template Heading Font Size', 'woo-af-drpc' ),
				'id'   => 'addf_drpc_template_heading_font_size',
				'type' => 'number',
				'desc' =>  esc_html__( 'Enter font size for template heading, by default theme values will be inherted.', 'woo-af-drpc' ),
			);

			$settings [] = array(
				'name' => esc_html__( 'Enable Template Icon', 'woo-af-drpc' ),
				'id'   => 'addf_drpc_enable_template_icon',
				'type' => 'checkbox',
				'desc' => '<br>' . esc_html__( 'Enable the icon for template.', 'woo-af-drpc' ),
			);

			$image = get_option( 'addf_drpc_template_icon' );

			$settings[] = array(
				'name' => esc_html__( 'Template Icon', 'woo-af-drpc' ),
				'id'   => 'addf_drpc_template_icon',
				'type' => 'text',
				'desc' => '<div id="addf_drpc_template_icon_container">
								<div>
									<img id="addf_drpc_selected_image_display" src="' . $image . '" width="50" />
								</div>      
								<button  id="addf-drpc-upload-image-btn" class="button-secondary">Upload Image</button>
								<button  id="addf-drpc-remove-image-btn" class="button-secondary">Remove Image</button>
								<p>Upload the icon for template. Leave it blank to use deafult icon.</p>
							</div>',
			);
			
			
			
			$settings [] = array(
				'name' => esc_html__( 'Enter Font Family for Template', 'woo-af-drpc' ),
				'id'   => 'addf_drpc_template_font_family',
				'type' => 'text',
				'desc' => esc_html__( "Specify the font family for the template text, or leave it blank to use the website's default font family.", 'woo-af-drpc' ),
			);


			$settings [] = array(
				'name' => esc_html__( 'Enable Save Column', 'woo-af-drpc' ),
				'id'   => 'addf_drpc_enable_table_save_column',
				'type' => 'checkbox',
				'class'=> 'addf_drpc_table_field ',
				'desc' => '<br>' . esc_html__( 'Enable save column in pricing table.', 'woo-af-drpc' ),
			);

				$settings [] = array(
					'name' => esc_html__( 'Table Header Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_table_header_background_color',
					'type' => 'color',
					'class'=> 'addf_drpc_table_field ',
					'desc' => esc_html__( 'Choose table header background color.', 'woo-af-drpc' ),
				);
				
				$settings [] = array(
					'name' => esc_html__( 'Table Header Text Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_table_header_text_color',
					'type' => 'color',
					'class'=> 'addf_drpc_table_field ',
					'desc' => esc_html__( 'Choose table header text color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Table Odd Rows Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_table_odd_rows_background_color',
					'type' => 'color',
					'class'=> 'addf_drpc_table_field ',
					'desc' => esc_html__( 'Choose table odd rows background color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Table Odd Rows Text Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_table_odd_rows_text_color',
					'type' => 'color',
					'class'=> 'addf_drpc_table_field ',
					'desc' => esc_html__( 'Choose table odd rows text color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Table Even Rows Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_table_even_rows_background_color',
					'type' => 'color',
					'class'=> 'addf_drpc_table_field ',
					'desc' => esc_html__( 'Choose table even rows background color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Table Even Rows Text Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_table_even_rows_text_color',
					'type' => 'color',
					'class'=> 'addf_drpc_table_field ',
					'desc' => esc_html__( 'Choose table even rows text color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Enable Table Border', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_enable_table_border',
					'type' => 'checkbox',
					'class'=> 'addf_drpc_table_field ',
					'desc' => '<br>' . esc_html__( 'Enable if do you want to use table border as a separator.', 'woo-af-drpc' ),
				);
				
				$settings [] = array(
					'name' => esc_html__( 'Table Border Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_table_border_color',
					'type' => 'color',
					'class'=> 'addf_drpc_table_field ',
					'desc' => esc_html__( 'Choose table border color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Table Header Font Size', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_table_header_font_size',
					'class'=> 'addf_drpc_table_field ',
					'type' => 'number',
					'desc' =>  esc_html__( 'Enter font size for table header, by default theme values will be inherted.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Table Rows Font Size', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_table_row_font_size',
					'class'=> 'addf_drpc_table_field ',
					'type' => 'number',
					'desc' =>  esc_html__( 'Enter font size for table rows, by default theme values will be inherted.', 'woo-af-drpc' ),
				);


				$settings [] = array(
					'name' => esc_html__( 'List Border Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_list_border_color',
					'type' => 'color',
					'class'=> 'addf_drpc_list_field ',
					'desc' => esc_html__( 'Choose List border color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'List Background Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_list_background_color',
					'type' => 'color',
					'class'=> 'addf_drpc_list_field ',
					'desc' => esc_html__( 'Choose List background color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'List Text Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_list_text_color',
					'type' => 'color',
					'class'=> 'addf_drpc_list_field ',
					'desc' => esc_html__( 'Choose List text color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Selected List Background Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_selected_list_background_color',
					'type' => 'color',
					'class'=> 'addf_drpc_list_field ',
					'desc' => esc_html__( 'Choose Selected List background color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Selected List Text Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_selected_list_text_color',
					'type' => 'color',
					'class'=> 'addf_drpc_list_field ',
					'desc' => esc_html__( 'Choose Selected List text color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Card Border Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_card_border_color',
					'type' => 'color',
					'class'=> 'addf_drpc_card_field ',
					'desc' => esc_html__( 'Choose Card border color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Card Background Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_card_background_color',
					'type' => 'color',
					'class'=> 'addf_drpc_card_field ',
					'desc' => esc_html__( 'Choose Card background color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Card Text Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_card_text_color',
					'type' => 'color',
					'class'=> 'addf_drpc_card_field ',
					'desc' => esc_html__( 'Choose Card text color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Selected Card Border Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_selected_card_border_color',
					'type' => 'color',
					'class'=> 'addf_drpc_card_field ',
					'desc' => esc_html__( 'Choose Selected Card border color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Enable Sale Tag', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_enable_sale_tag',
					'class'=> 'addf_drpc_card_field ',
					'type' => 'checkbox',
					'desc' => '<br>' . esc_html__( 'Enable Sale tag for card.', 'woo-af-drpc' ),
				);
				
				$settings [] = array(
					'name' => esc_html__( 'Sale Tag Background Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_sale_tag_background_color',
					'type' => 'color',
					'class'=> 'addf_drpc_card_field ',
					'desc' => esc_html__( 'Choose Sale Tag Background Color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Sale Tag Text Color', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_sale_tag_text_color',
					'type' => 'color',
					'class'=> 'addf_drpc_card_field ',
					'desc' => esc_html__( 'Choose Sale Tag Text Color.', 'woo-af-drpc' ),
				);

				$settings [] = array(
					'name' => esc_html__( 'Reset', 'woo-af-drpc' ),
					'id'   => 'addf_drpc_reset_settings',
					'type' => 'text',
					'desc' => '<button class="button addf_drpc_reset_settings_button">Reset to Default</button><br><p style="color:red">' . esc_html__( 'To revert all settings to default.', 'woo-af-drpc' ) . '</p>',
				);


			$settings[] = array(
				'type' => 'sectionend',
				'id'   => 'af_dynamic_discounts_table_section',
			);
		} elseif ('product-rule' == $section) {
			wp_safe_redirect( admin_url('edit.php?post_type=af_disc_p_rules') );
			exit();
		} elseif ('cart-rule' == $section) {
			wp_safe_redirect( admin_url('edit.php?post_type=af_dis_cart_rule') );
			exit();
		}

		return apply_filters( 'wc_settings_tab_af_dynamic_discounts_settings', $settings, $section );
	}

	/**
	 * Output the settings
	 */
	public function output() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		if (!empty($settings )) {
			WC_Admin_Settings::output_fields( $settings );
		}
	}


	/**
	 * Save settings
	 */
	public function save() {

		global $current_section;
		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );

		//setting default option
		addf_drpc_set_default_setting();
	}
}

return new WC_Settings_Addify_Dynamic_Discounts();
