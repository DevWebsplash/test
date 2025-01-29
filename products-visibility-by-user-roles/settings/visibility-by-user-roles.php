<?php

defined('ABSPATH') || exit;

//Visibility By User Role

add_settings_section(  
	'page_1_section',         // ID used to identify this section and with which to register options  
	'',   // Title to be displayed on the administration page  
	'afpvu_page_2_section_callback', // Callback used to render the description of the section  
	'addify-products-visibility-2'                           // Page on which to add this section of options  
);


add_settings_field (   
	'afpvu_user_role_visibility',                      // ID used to identify the field throughout the theme  
	esc_html__('Visibility By User Roles', 'addify_products_visibility'),    // The label to the left of the option interface element  
	'afpvu_user_role_visibility_callback',   // The name of the function responsible for rendering the option interface  
	'addify-products-visibility-2',                          // The page on which this option will be displayed  
	'page_1_section',         // The name of the section to which this field belongs  
	'' 
);  
register_setting(  
	'setting-group-afvisi-2',  
	'afpvu_user_role_visibility'  
);

function afpvu_page_2_section_callback() { 
	?>
	

	<p class="impnote"><?php echo esc_html__('Please note that Visibility by User Roles have high priority. If following configurations are active for any user role – the global settings won’t work for that specific role.', 'addify_products_visibility'); ?></p>

	<?php 
} // function afreg_page_1_section_callback

//User Role Visibility
function afpvu_user_role_visibility_callback() {
	?>
	<div class="afpvu_accordian">
		<div id="accordion">
			<?php

			global $wp_roles;
			$roless = $wp_roles->get_names();

				//Guest
			$guest_role = array( 'guest' => 'Guest' );

			$roles = array_merge($roless, $guest_role);

			if (!empty(get_option('afpvu_user_role_visibility'))) {

				$role_selected_data = maybe_unserialize(get_option('afpvu_user_role_visibility'));




			} else {

				$role_selected_data = array();

			}




			if ( !empty( $roles)) {

				foreach ($roles as $key => $value) {

					if (!empty($role_selected_data[ esc_attr( $key ) ]['afpvu_enable_role']) && 'yes' == $role_selected_data[ esc_attr( $key ) ]['afpvu_enable_role']) {

						$active_role = '( Rule Active )';

					} else {

						$active_role = '';

					}

					?>

					<h3><?php echo esc_attr( $value ); ?><span class="ruleactive"><?php echo esc_html__($active_role, 'addify_products_visibility'); ?></span></h3>

					<div class="<?php echo esc_attr($key); ?>">
						<p>
							<input type="hidden" name="afpvu_user_role_visibility[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $key ); ?>">


							<div class="afpuv_role_inner">
								<div class="afpuv_role_inner_left">
									<label><?php echo esc_html__('Enable for this Role', 'addify_products_visibility'); ?></label>
								</div>
								<div class="afpuv_role_inner_right">
									<input type="checkbox" name="afpvu_user_role_visibility[<?php echo esc_attr( $key ); ?>][afpvu_enable_role]" value="yes" 
									<?php 
									if (!empty($role_selected_data[ esc_attr( $key ) ]['afpvu_enable_role'])) {
										echo checked('yes', $role_selected_data[ esc_attr( $key ) ]['afpvu_enable_role']); } 
									?>
										>
									</div>
								</div>

								<div class="afpuv_role_inner">
									<div class="afpuv_role_inner_left">
										<label><?php echo esc_html__('Show/Hide', 'addify_products_visibility'); ?></label>
									</div>
									<div class="afpuv_role_inner_right">
										<select name="afpvu_user_role_visibility[<?php echo esc_attr( $key ); ?>][afpvu_show_hide_role]">
											<option value="hide" <?php echo isset($role_selected_data[ esc_attr( $key ) ]['afpvu_show_hide_role']) && 'hide' == $role_selected_data[ esc_attr( $key ) ]['afpvu_show_hide_role'] ? 'selected' : ''; ?>><?php echo esc_html__('Hide', 'addify_products_visibility'); ?></option>
											<option value="show" <?php echo isset($role_selected_data[ esc_attr( $key ) ]['afpvu_show_hide_role']) && 'show' == $role_selected_data[ esc_attr( $key ) ]['afpvu_show_hide_role'] ? 'selected' : ''; ?>><?php echo esc_html__('Show', 'addify_products_visibility'); ?></option>
										</select>
									</div>
								</div>


								<div class="afpuv_role_inner">
									<div class="afpuv_role_inner_left">
										<label><?php echo esc_html__('Select Products', 'addify_products_visibility'); ?></label>
									</div>
									<div class="afpuv_role_inner_right">

										<select name="afpvu_user_role_visibility[<?php echo esc_attr( $key ); ?>][afpvu_applied_products_role][]" class="afpvu_applied_products" multiple="multiple">

											<?php

											if (!empty($role_selected_data[ esc_attr( $key ) ]['afpvu_applied_products_role'])) {

												foreach ( $role_selected_data[ esc_attr( $key ) ]['afpvu_applied_products_role'] as $pro) {

													$prod_post = get_post($pro);

													?>

													<option value="<?php echo intval($pro); ?>" selected="selected"><?php echo esc_attr($prod_post->post_title); ?></option>

													<?php 
												}
											}
											?>

										</select>

									</div>
								</div>


					<div class="afpuv_role_inner">
	<div class="afpuv_role_inner_left">
		<label><?php echo esc_html__('Select Categories', 'addify_products_visibility'); ?></label>
	</div>
	<div class="afpuv_role_inner_right">
		<select class="afpvu_applied_categories" name="afpvu_user_role_visibility[<?php echo esc_attr($key); ?>][afpvu_applied_categories_role][]" multiple style="width:80%;">
			<?php
					if (!empty($role_selected_data[ esc_attr($key) ]['afpvu_applied_categories_role'])) {
						$pre_vals = $role_selected_data[ esc_attr($key) ]['afpvu_applied_categories_role'];
					} else {
						$pre_vals = array();
					}

					foreach ((array) $pre_vals as $value) {
						if ($value) {
							$term_name = get_term($value)->name;
							?>
					<option value="<?php echo esc_attr($value); ?>" selected><?php echo esc_attr($term_name); ?></option>
					<?php
						}
					}
					?>
		</select>
	</div>
</div>



								<div class="afpuv_role_inner">
									<div class="afpuv_role_inner_left">
										<label><?php echo esc_html__('Redirection Mode', 'addify_products_visibility'); ?></label>
									</div>
									<div class="afpuv_role_inner_right">

										<?php

										if (!empty($role_selected_data[ esc_attr( $key ) ]['afpvu_role_redirection_mode'])) {

											$afpvu_role_redirection_mode = $role_selected_data[ esc_attr( $key ) ]['afpvu_role_redirection_mode'];
										} else {
											$afpvu_role_redirection_mode = '';
										}

										if (!empty($role_selected_data[ esc_attr( $key ) ]['afpvu_role_custom_url'])) {

											$afpvu_role_custom_url = $role_selected_data[ esc_attr( $key ) ]['afpvu_role_custom_url'];
										} else {
											$afpvu_role_custom_url = '';
										}

										?>

										<select name="afpvu_user_role_visibility[<?php echo esc_attr( $key ); ?>][afpvu_role_redirection_mode]" onchange="getRedirectMode(this.value, '<?php echo esc_attr($key); ?>')" id="<?php echo esc_attr( $key ); ?>afpvu_role_redirection_mode" class="af-acc-select" data-key="<?php echo esc_attr( $key ); ?>" >
											<option value="custom_url" <?php echo selected('custom_url', esc_attr($afpvu_role_redirection_mode)); ?>><?php echo esc_html__('Custom URL', 'addify_products_visibility'); ?></option>
											<option value="custom_message" <?php echo selected('custom_message', esc_attr($afpvu_role_redirection_mode)); ?>><?php echo esc_html__('Custom Message', 'addify_products_visibility'); ?></option>
										</select>


									</div>
								</div>


								<div class="afpuv_role_inner showcustomurl">
									<div class="afpuv_role_inner_left">
										<label><?php echo esc_html__('Custom URL', 'addify_products_visibility'); ?></label>
									</div>
									<div class="afpuv_role_inner_right">

										<input type="text" id="afpvu_global_custom_url_<?php echo esc_attr( $key ); ?>" class="setting_input_fields" name="afpvu_user_role_visibility[<?php echo esc_attr( $key ); ?>][afpvu_role_custom_url]" value="<?php echo esc_attr($afpvu_role_custom_url); ?>">

									</div>
								</div>



								<div class="afpuv_role_inner showcustommessage">
									<div class="afpuv_role_inner_left">
										<label><?php echo esc_html__('Custom Message', 'addify_products_visibility'); ?></label>
									</div>
									<div class="afpuv_role_inner_right">

										<?php

										if (!empty($role_selected_data[ esc_attr( $key ) ]['afpvu_custom_message_role'])) {
											$content = $role_selected_data[ esc_attr( $key ) ]['afpvu_custom_message_role'];
										} else {
											$content = '';
										}

										$editor_id = 'afpvu_user_role_visibility' . esc_attr( $key );
										$settings  = array(
											'tinymce'   => true,
											'textarea_rows' => 10,
											'quicktags' => array( 'buttons' => 'em,strong,link' ),
											'textarea_name' => 'afpvu_user_role_visibility[' . esc_attr( $key ) . '][afpvu_custom_message_role]',
										);

										wp_editor( $content, $editor_id, $settings );


										?>


									</div>
								</div>
								<script>

									jQuery(function($){

										var value1 = $("#<?php echo esc_attr( $key ); ?>afpvu_role_redirection_modeoption:selected").val();
										if ('custom_url' == value1) {

											jQuery('.<?php echo esc_attr( $key ); ?> .showcustomurl').show();
											jQuery('.<?php echo esc_attr( $key ); ?> .showcustommessage').hide();
										} else if ('custom_message' == value1) {

											jQuery('.<?php echo esc_attr( $key ); ?> .showcustomurl').hide();
											jQuery('.<?php echo esc_attr( $key ); ?> .showcustommessage').show();
										}
									});

									jQuery('.af-acc-select').each(function(){

										getRedirectMode(jQuery(this).val(), jQuery(this).data('key'));

									});

									function getRedirectMode(value, role) {

										if ('custom_url' == value) {

											jQuery('.'+role+' .showcustomurl').show();
											jQuery('.'+role+' .showcustommessage').hide();
										} else if ('custom_message' == value) {

											jQuery('.'+role+' .showcustomurl').hide();
											jQuery('.'+role+' .showcustommessage').show();
										}

									}
								</script>

							</p>
						</div>
						
						<?php 
				}
			}

			?>
			</div>
		</div>


		<?php 
} // function afpvu_user_role_visibility_callback
