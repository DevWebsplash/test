<?php
defined( 'ABSPATH' ) || exit;
$addf_disc_rpc_product_selection_op = get_post_meta( get_the_ID(), 'addf_disc_rpc_product_selection_op', true );
wp_nonce_field('addf_discount_rpc', 'addf_discount_rpc');
?>
<table class="addf_disc_rpc_table">
	<tr>
		<td>
			<?php echo esc_html__( 'Choose product selection method', 'woo-af-drpc' ); ?>
		</td>
		<td>
			<select name="addf_disc_rpc_product_selection_op" id="addf_disc_rpc_product_selection_op" class="addf_disc_rpc_on_change addf_disc_rpc_input_fields">
				<option value="all" <?php selected( $addf_disc_rpc_product_selection_op , 'all', true ); ?>><?php echo esc_html__( 'All Products', 'woo-af-drpc' ); ?></option>
				<option value="specific" <?php selected( $addf_disc_rpc_product_selection_op , 'specific', true ); ?>><?php echo esc_html__( 'Specific Products', 'woo-af-drpc' ); ?></option>
			</select>
			<br>
			<p class="description"><?php echo esc_html__( 'Choose product selection method for product rule', 'woo-af-drpc' ); ?></p>
		</td>
	</tr>
	<tr class="addf_drpc_hidden_fields addf_disc_rpc_product_selection_op addf_disc_rpc_product_selection_op_specific">
		<td>
			<?php echo esc_html__( 'Choose products', 'woo-af-drpc' ); ?>
		</td>
		<td>
			<!-- addf_disc_rpc_input_fields -->
			<select name="addf_disc_rpc_products[]" id="addf_disc_rpc_products" data-placeholder="<?php echo esc_html__('Choose Products...', 'woo-af-drpc' ); ?>" class=" addf_disc_rpc_product_live_search chosen-select" multiple="multiple" tabindex="-1" style="width:60%;" >							
				<?php
					$specific_product = get_post_meta( get_the_ID(), 'addf_disc_rpc_products', true );
				if ( ! empty( $specific_product ) ) {
					foreach ( $specific_product as $pro ) {
						$prod_post = get_post( $pro );
						?>
								<option value="<?php echo intval( $pro ); ?>" selected="selected"><?php echo esc_html__( $prod_post->post_title, 'woo-af-drpc' ); ?></option>
							<?php
					}
				}
				?>
			</select>
			<br>
			<p class="description"><?php echo esc_html__( 'Choose specific products for product rule', 'woo-af-drpc' ); ?></p>
		</td>
	</tr>
	<tr class="addf_drpc_hidden_fields addf_disc_rpc_product_selection_op addf_disc_rpc_product_selection_op_specific">
		<td>
			<?php echo esc_html__( 'Choose product categories', 'woo-af-drpc' ); ?>
		</td>
		<td>
		<select id="addf_disc_rpc_categories" name="addf_disc_rpc_categories[]" multiple="multiple" style="width:60%">
			<?php
			$addf_drpc_applied_on_categories = get_post_meta(get_the_ID(), 'addf_disc_rpc_categories', true);
			$pre_vals = !empty($addf_drpc_applied_on_categories) ? $addf_drpc_applied_on_categories : array();

			foreach (addf_drpc_get_all_categories() as $category_id) {
				$selected = in_array($category_id, $pre_vals) ? 'selected' : '';
				$category = get_term_by('id', $category_id, 'product_cat');
				if ($category) {
					echo '<option value="' . esc_attr($category_id) . '" ' . esc_attr($selected) . '>' . esc_html($category->name) . '</option>';
				}
			}
			?>
		</select>
		
			
			<br>
			<p class="description"><?php echo esc_html__( 'Choose categories for product rule', 'woo-af-drpc' ); ?></p>
		</td>
	</tr>
</table>
