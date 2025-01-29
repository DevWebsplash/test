<?php

defined('ABSPATH') || exit;

add_settings_section(
'page_1_section',         // ID used to identify this section and with which to register options  
'',   // Title to be displayed on the administration page  
'afpvu_page_1_section_callback', // Callback used to render the description of the section  
'addify-products-visibility-1'                           // Page on which to add this section of options  
);

add_settings_field(
'afpvu_enable_global',                      // ID used to identify the field throughout the theme  
esc_html__('Enable Global Visibility', 'addify_b2b'),    // The label to the left of the option interface element  
'afpvu_enable_global_callback',   // The name of the function responsible for rendering the option interface  
'addify-products-visibility-1',                          // The page on which this option will be displayed  
'page_1_section',         // The name of the section to which this field belongs  
array(                              // The array of arguments to pass to the callback. In this case, just a description.  
	esc_html__('Enable or Disable global visibility.', 'addify_b2b'),
)
);
register_setting(
'setting-group-afvisi-1',
'afpvu_enable_global'
);

add_settings_field(
'afpvu_show_hide',                      // ID used to identify the field throughout the theme  
esc_html__('Show/Hide', 'addify_products_visibility'),    // The label to the left of the option interface element  
'afpvu_show_hide_callback',   // The name of the function responsible for rendering the option interface  
'addify-products-visibility-1',                          // The page on which this option will be displayed  
'page_1_section',         // The name of the section to which this field belongs  
array(                              // The array of arguments to pass to the callback. In this case, just a description.  
	esc_html__('Select either you want to show products or hide products.', 'addify_products_visibility'),
)
);
register_setting(
'setting-group-afvisi-1',
'afpvu_show_hide'
);



add_settings_field(
'afpvu_applied_products',                      // ID used to identify the field throughout the theme  
esc_html__('Select Products', 'addify_products_visibility'),    // The label to the left of the option interface element  
'afpvu_applied_products_callback',   // The name of the function responsible for rendering the option interface  
'addify-products-visibility-1',                          // The page on which this option will be displayed  
'page_1_section',         // The name of the section to which this field belongs  
array(                              // The array of arguments to pass to the callback. In this case, just a description.  
	esc_html__('Select products on which you want to apply.', 'addify_products_visibility'),
)
);
register_setting(
'setting-group-afvisi-1',
'afpvu_applied_products'
);

add_settings_field(
'afpvu_applied_categories',                      // ID used to identify the field throughout the theme  
esc_html__('Select Categories', 'addify_products_visibility'),    // The label to the left of the option interface element  
'afpvu_applied_categories_callback',   // The name of the function responsible for rendering the option interface  
'addify-products-visibility-1',                          // The page on which this option will be displayed  
'page_1_section',         // The name of the section to which this field belongs  
array(                              // The array of arguments to pass to the callback. In this case, just a description.  
	esc_html__('Select categories on which products on which you want to apply.', 'addify_products_visibility'),
)
);
register_setting(
'setting-group-afvisi-1',
'afpvu_applied_categories'
);


add_settings_field(
'afpvu_global_redirection_mode',                      // ID used to identify the field throughout the theme  
esc_html__('Redirection Mode', 'addify_products_visibility'),    // The label to the left of the option interface element  
'afpvu_global_redirection_mode_callback',   // The name of the function responsible for rendering the option interface  
'addify-products-visibility-1',                          // The page on which this option will be displayed  
'page_1_section',         // The name of the section to which this field belongs  
array(                              // The array of arguments to pass to the callback. In this case, just a description.  
	esc_html__('Select redirection mode for restricted items.', 'addify_products_visibility'),
)
);
register_setting(
'setting-group-afvisi-1',
'afpvu_global_redirection_mode'
);


add_settings_field(
'afpvu_global_custom_url',                      // ID used to identify the field throughout the theme  
'<div class="showcustomurl">' . esc_html__('Custom URL', 'addify_products_visibility') . '</div>',    // The label to the left of the option interface element  
'afpvu_global_custom_url_callback',   // The name of the function responsible for rendering the option interface  
'addify-products-visibility-1',                          // The page on which this option will be displayed  
'page_1_section',         // The name of the section to which this field belongs  
array(                              // The array of arguments to pass to the callback. In this case, just a description.  
	esc_html__('Redirect to this custom URL when user try to access restricted catalog. e.g http://www.example.com', 'addify_products_visibility'),
)
);
register_setting(
'setting-group-afvisi-1',
'afpvu_global_custom_url'
);



add_settings_field(
'afpvu_global_custom_msg',                      // ID used to identify the field throughout the theme  
'<div class="showcustommessage">' . esc_html__('Custom Message', 'addify_products_visibility') . '</div>',    // The label to the left of the option interface element  
'afpvu_global_custom_msg_callback',   // The name of the function responsible for rendering the option interface  
'addify-products-visibility-1',                          // The page on which this option will be displayed  
'page_1_section',         // The name of the section to which this field belongs  
array(                              // The array of arguments to pass to the callback. In this case, just a description.  
	esc_html__('This message will be displayed when user try to access restricted catalog.', 'addify_products_visibility'),
)
);
register_setting(
'setting-group-afvisi-1',
'afpvu_global_custom_msg'
);

function afpvu_page_1_section_callback() {
	?>
	<h2><?php echo esc_html__('Global Visibility Settings', 'addify_products_visibility'); ?></h2>
	<p><?php echo esc_html__('This will help you to show or hide products for all customers including guests.', 'addify_products_visibility'); ?>
	</p>
	<p class="impnote">
		<?php echo esc_html__('Please note that Visibility by User Roles have high priority. If following configurations are active for any user role – the global settings won’t work for that specific role.', 'addify_products_visibility'); ?>
	</p>

	<?php
} // function afreg_page_1_section_callback

function afpvu_enable_global_callback( $args ) {
	?>
	<input type="checkbox" id="afpvu_enable_global" class="setting_fields" name="afpvu_enable_global" value="yes" <?php echo checked('yes', esc_attr(get_option('afpvu_enable_global'))); ?>>
	<p class="description afpvu_enable_global"> <?php echo esc_attr($args[0]); ?> </p>
	<?php
} // end afpvu_enable_global_callback 

function afpvu_show_hide_callback( $args ) {
	?>
	<select name="afpvu_show_hide">
		<option value="hide" <?php echo selected('hide', esc_attr(get_option('afpvu_show_hide'))); ?>>
			<?php echo esc_html__('Hide', 'addify_products_visibility'); ?>
		</option>
		<option value="show" <?php echo selected('show', esc_attr(get_option('afpvu_show_hide'))); ?>>
			<?php echo esc_html__('Show', 'addify_products_visibility'); ?>
		</option>
	</select>
	<p class="description afpvu_show_hide"> <?php echo esc_attr($args[0]); ?> </p>
	<?php
} // end afpvu_show_hide_callback 


function afpvu_applied_products_callback( $args ) {

	$afpvu_applied_products = get_option('afpvu_applied_products');
	?>

	<select name="afpvu_applied_products[]" class="afpvu_applied_products" multiple="multiple">

		<?php

		if (!empty($afpvu_applied_products)) {

			foreach ($afpvu_applied_products as $pro) {

				$prod_post = get_post($pro);

				?>

				<option value="<?php echo intval($pro); ?>" selected="selected"><?php echo esc_attr($prod_post->post_title); ?>
				</option>

				<?php
			}
		}
		?>

	</select>
	<p class="description afpvu_applied_productss"> <?php echo esc_attr($args[0]); ?> </p>

	<?php
} // end afpvu_applied_products_callback 

function afpvu_global_custom_msg_callback( $args ) {
	?>
	<div class="showcustommessage">
		<?php
		$content   = get_option('afpvu_global_custom_msg');
		$editor_id = 'afpvu_global_custom_msg';
		$settings  = array(
			'tinymce'       => true,
			'textarea_rows' => 10,
			'quicktags'     => array( 'buttons' => 'em,strong,link' ),
		);

		wp_editor($content, $editor_id, $settings);

		?>

		<p class="description afpvu_global_custom_msg"> <?php echo esc_attr($args[0]); ?> </p>
	</div>
	<?php
}



function afpvu_applied_categories_callback() {
	$afpvu_applied_categories = get_option('afpvu_applied_categories');
	?>


	<select class="afpvu_applied_categories" name="afpvu_applied_categories[]" multiple style="width:80%;">
		<?php
		foreach ((array) $afpvu_applied_categories as $value) {
			if ($value) {
				$term_name = get_term($value)->name;

				?>
				<option value="<?php echo esc_attr($value); ?>" selected><?php echo esc_attr($term_name); ?></option>
				<?php

			}
		}
		?>
	</select>

	<?php
}







function afpvu_global_redirection_mode_callback( $args ) {
	?>
	<select name="afpvu_global_redirection_mode" onchange="setGlobalRedirect(this.value)"
		id="afpvu_global_redirection_mode">
		<option value="custom_url" <?php echo selected('custom_url', esc_attr(get_option('afpvu_global_redirection_mode'))); ?>><?php echo esc_html__('Custom URL', 'addify_products_visibility'); ?></option>
		<option value="custom_message" <?php echo selected('custom_message', esc_attr(get_option('afpvu_global_redirection_mode'))); ?>>
			<?php echo esc_html__('Custom Message', 'addify_products_visibility'); ?>
		</option>
	</select>
	<p class="description afpvu_show_hide"> <?php echo esc_attr($args[0]); ?> </p>
	<?php
} // end afpvu_global_redirection_mode_callback



function afpvu_global_custom_url_callback( $args ) {
	?>
	<div class="showcustomurl">
		<input type="text" id="afpvu_global_custom_url" class="setting_input_fields" name="afpvu_global_custom_url"
			value="<?php echo esc_attr(get_option('afpvu_global_custom_url')); ?>">
		<p class="description afpvu_global_custom_url"> <?php echo esc_attr($args[0]); ?> </p>
	</div>
	<?php
} // end afpvu_global_custom_url_callback  
