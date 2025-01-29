<?php
defined( 'ABSPATH' ) || exit;
$addf_drpc_discount_type_choice   = get_post_meta( get_the_ID() , 'addf_drpc_discount_type_choice' , true );
$addf_drpc_disc_min_spent_amount  = get_post_meta( get_the_ID() , 'addf_drpc_disc_min_spent_amount' , true );
$addf_drpc_discount_amount_choice = get_post_meta( get_the_ID() , 'addf_drpc_discount_amount_choice' , true );
$addf_drpc_discount_amount        = get_post_meta( get_the_ID() , 'addf_drpc_discount_amount' , true );
$addf_disc_rpc_min_qty            = get_post_meta( get_the_ID() , 'addf_disc_rpc_min_qty' , true );
if ( ( !$addf_disc_rpc_min_qty ) || ( '' == $addf_disc_rpc_min_qty ) ) {
	$addf_disc_rpc_min_qty = '1';
}
$addf_disc_rpc_show_prc_table            = get_post_meta( get_the_ID() , 'addf_disc_rpc_show_prc_table' , true );
$addf_drpc_pricing_template_design       = get_post_meta( get_the_ID() , 'addf_drpc_pricing_template_design' , true );
$addf_disc_rpc_min_spent_amount          = get_post_meta( get_the_ID() , 'addf_disc_rpc_min_spent_amount' , true );
$addf_disc_rpc_min_start_date            = get_post_meta( get_the_ID() , 'addf_disc_rpc_min_start_date' , true );
$addf_disc_rpc_min_end_date              = get_post_meta( get_the_ID() , 'addf_disc_rpc_min_end_date' , true );
$addf_disc_rpc_same_price_s_time         = get_post_meta( get_the_ID() , 'addf_disc_rpc_same_price_s_time' , true );
$addf_disc_rpc_start_time                = get_post_meta( get_the_ID() , 'addf_disc_rpc_start_time' , true );
$addf_disc_rpc_end_time                  = get_post_meta( get_the_ID() , 'addf_disc_rpc_end_time' , true );
$addf_disc_rpc_days_radio                = get_post_meta( get_the_ID() , 'addf_disc_rpc_days_radio' , true );
$addf_disc_week_days_arr                 = (array) get_post_meta( get_the_ID() , 'addf_disc_week_days_arr' , true );
$addf_disc_rpc_qty_from                  = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_qty_from' , true );
$addf_disc_rpc_qty_to                    = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_qty_to' , true );
$addf_disc_rpc_qty_disc_apply            = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_qty_disc_apply' , true );
$addf_disc_rpc_gift_products_list        = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_gift_products_list' , true );
$addf_disc_rpc_gift_products_list_qty    = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_gift_products_list_qty' , true );
$addf_disc_rpc_select_customer_gift_arr  = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_select_customer_gift' , true );
$addf_disc_choose_new_gift_list          = (array) get_post_meta( get_the_ID() , 'addf_disc_choose_new_gift_list' , true );
$addf_disc_choose_gift_min_qty           = (array) get_post_meta( get_the_ID() , 'addf_disc_choose_gift_min_qty' , true );
$addf_disc_choose_new_gift_qty           = (array) get_post_meta( get_the_ID() , 'addf_disc_choose_new_gift_qty' , true );
$addf_disc_rpc_select_user_role_gift_arr = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_select_user_role_gift' , true );
$addf_disc_user_role_gift_list           = (array) get_post_meta( get_the_ID() , 'addf_disc_user_role_gift_list' , true );
$addf_disc_user_role_gift_qty            = (array) get_post_meta( get_the_ID() , 'addf_disc_user_role_gift_qty' , true );
$addf_disc_user_role_gift_min_qty        = (array) get_post_meta( get_the_ID() , 'addf_disc_user_role_gift_min_qty' , true );
$addf_disc_rpc_replace_prc_cust_arr      = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_replace_prc_cust_cb' , true );
$addf_disc_rpc_replace_prc_roles_arr     = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_replace_prc_roles_cb' , true );

if ( ( !$addf_drpc_discount_amount ) || ( '' == $addf_drpc_discount_amount ) ) {
	$addf_drpc_discount_amount = '1';
}
// for User Roles
$addf_disc_rpc_roles       = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_roles_select' , true );
$addf_drpc_discount_choice = (array) get_post_meta( get_the_ID() , 'addf_drpc_discount_amount_choice' , true );
$addf_disc_rpc_value       = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_disc_val_tbl' , true );
$addf_disc_rpc_min         = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_min_qty_tbl' , true );
$addf_disc_rpc_max         = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_max_qty_tbl' , true );
// for Customers
$addf_disc_rpc_select_cust   = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_select_customer' , true );
$addf_drpc_cust_choice       = (array) get_post_meta( get_the_ID() , 'addf_drpc_cust_disc_choice' , true );
$addf_disc_rpc_disc_val_cust = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_disc_val_tbl_cust' , true );
$addf_disc_rpc_min_qty_cust  = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_min_qty_tbl_cust' , true );
$addf_disc_rpc_max_qty_cust  = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_max_qty_tbl_cust' , true );

?>
<table class="addf_disc_rpc_table">
	
	
	
	<!-- Rule type -->
	<tr>
		<td>
			<?php echo esc_html__( 'Discount rule type', 'woo-af-drpc' ); ?>
		</td>
		<td>
			<select name="addf_drpc_discount_type_choice" id="addf_drpc_discount_type_choice" class=" addf_disc_rpc_input_fields addf_disc_rpc_on_change">
				<option value="dynamic_price_adj" <?php selected( $addf_drpc_discount_type_choice , 'dynamic_price_adj'  ); ?>><?php echo esc_html__( 'Dynamic pricing adjustment', 'woo-af-drpc' ); ?></option>
				<option value="conditional" <?php selected( $addf_drpc_discount_type_choice , 'conditional'  ); ?>><?php echo esc_html__( 'Gift a Product', 'woo-af-drpc' ); ?></option>
			</select>
			<p class="description"><?php echo esc_html__( 'Select a type for product and users for discount', 'woo-af-drpc' ); ?></p>
		</td>
	</tr>
	<!--  for conditional discount -->
	<tr class="addf_drpc_discount_type_choice addf_drpc_discount_type_choice_conditional addf_drpc_hidden_fields">
		<td colspan="2">
			<p class="description"><?php echo esc_html__( 'Select gift products in table for specific users', 'woo-af-drpc' ); ?></p>
			<div class="addf_disc_rpc_table_div_max_height">
				<table class="addf_disc_rpc_gift_table">
					<tr>
						<th>
							<?php echo esc_html__( 'Customer', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Gift Product', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Gift Quantity', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Min (Qty)', 'woo-af-drpc' ); ?>
						</th>
						<th></th>
					</tr>
					<?php
					if ( is_array($addf_disc_rpc_select_customer_gift_arr) && ( !empty($addf_disc_rpc_select_customer_gift_arr) ) ) {
						foreach ($addf_disc_rpc_select_customer_gift_arr as $key => $addf_disc_rpc_select_customer_gift ) {
							if ( '' == $addf_disc_rpc_select_customer_gift ) {
								continue;
							}
							?>
								<tr>
									<td>
										<select name="addf_disc_rpc_select_customer_gift[]" class="addf_disc_rpc_customers" style="width:100%;"  data-placeholder="<?php echo esc_html__( 'Choose Customer... ', 'woo-af-drpc' ); ?>" >
											<?php 
												$users = get_users( array( 'fields' => array( 'ID', 'display_name', 'user_email' ) ) );
											foreach ($users as $cust_key => $cust_value) {
												if ( $cust_value->ID == $addf_disc_rpc_select_customer_gift ) {
													?>
													<option value="<?php echo esc_attr($cust_value->ID); ?>" selected><?php echo esc_html__( $cust_value->display_name . '(' . $cust_value->user_email . ')' , 'woo-af-drpc' ); ?></option>
													<?php
												}
											}
											?>
										</select>
									</td>
									<td>
										<select name="addf_disc_choose_new_gift_list[]" data-placeholder="<?php echo esc_html__( 'Choose products...', 'woo-af-drpc' ); ?>" class="addf_disc_choose_new_gift_product" style="width:100%;">
											<option value="<?php echo esc_attr( $addf_disc_choose_new_gift_list[ $key ] ); ?>"><?php echo esc_html__( get_the_title( $addf_disc_choose_new_gift_list[ $key ] ) , 'woo-af-drpc' ); ?></option>
										</select>
									</td>
									<td>
										<input type="number" name="addf_disc_choose_new_gift_qty[]" min="1" value="<?php echo esc_attr( $addf_disc_choose_new_gift_qty[ $key ] ); ?>">
									</td>
									<td>
										<input type="number" name="addf_disc_choose_gift_min_qty[]" min="1" value="<?php echo esc_attr( $addf_disc_choose_gift_min_qty[ $key ] ); ?>">
									</td>
									<td>
										<span class="addf_disc_rpc_remove_row_tbl">&#x2717;</span>
									</td>
								</tr>
								<?php
						}
					}
					?>
				</table>
				<div class="addf_disc_rpc_align_right_tbl">
					<input type="button" class="button addf_disc_rpc_add_gift_row_tbl"  value="Add row">
				</div>
			</div>
			<p class="description"><?php echo esc_html__( 'Select gift products for user roles', 'woo-af-drpc' ); ?></p>
			<div class="addf_disc_rpc_table_div_max_height">
				<table class="addf_disc_rpc_user_role_gift_table">
					<tr>
						<th>
							<?php echo esc_html__( 'User Role', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Gift Product', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Gift Quantity', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Min (Qty)', 'woo-af-drpc' ); ?>
						</th>
						<th></th>
					</tr>
					<?php
					if ( is_array($addf_disc_rpc_select_user_role_gift_arr) && ( !empty($addf_disc_rpc_select_user_role_gift_arr) ) ) {
						foreach ($addf_disc_rpc_select_user_role_gift_arr as $key => $addf_disc_rpc_select_user_role_gift ) {
							if ( '' == $addf_disc_rpc_select_user_role_gift ) {
								continue;
							}
							if ( !array_key_exists( $key , $addf_disc_user_role_gift_list ) ) {
								continue;
							}
							?>
								<tr>
									<td>
										<select name="addf_disc_rpc_select_user_role_gift[]" >
										<option value="all" <?php selected( $addf_disc_rpc_select_user_role_gift , 'all' , true ); ?> ><?php echo esc_html__( 'All User Roles' , 'woo-af-drpc' ); ?></option>
											<?php
											global $wp_roles;
											$roles = $wp_roles->get_names();
											foreach ( $roles as $role_id => $role_name ) {
												?>
												<option value="<?php echo esc_attr( $role_id ); ?>" <?php selected( $addf_disc_rpc_select_user_role_gift , $role_id , true ); ?> ><?php echo esc_html__( $role_name , 'woo-af-drpc' ); ?></option>
												<?php
											}
											?>
											<option value="guest" <?php selected( $addf_disc_rpc_select_user_role_gift , 'guest' , true ); ?> ><?php echo esc_html__( 'Guest' , 'woo-af-drpc' ); ?></option>
										</select>
									</td>
									<td>
										<select name="addf_disc_user_role_gift_list[]" data-placeholder="<?php echo esc_html__( 'Choose products...', 'woo-af-drpc' ); ?>" class="addf_disc_choose_new_gift_product" style="width:100%;">
											<option value="<?php echo esc_attr( $addf_disc_user_role_gift_list[ $key ] ); ?>"><?php echo esc_html__( get_the_title( $addf_disc_user_role_gift_list[ $key ] ) , 'woo-af-drpc' ); ?></option>
										</select>
									</td>
									<td>
										<input type="number" name="addf_disc_user_role_gift_qty[]" min="1" value="<?php echo esc_attr( $addf_disc_user_role_gift_qty[ $key ] ); ?>">
									</td>
									<td>
										<input type="number" name="addf_disc_user_role_gift_min_qty[]" min="1" value="<?php echo esc_attr( $addf_disc_user_role_gift_min_qty[ $key ] ); ?>">
									</td>
									<td>
										<span class="addf_disc_rpc_remove_row_tbl">&#x2717;</span>
									</td>
								</tr>
								<?php
						}
					}
					?>
				</table>
				<div class="addf_disc_rpc_align_right_tbl">
					<input type="button" class="button addf_disc_rpc_add_user_role_gift_row_tbl"  value="Add row">
				</div>
			</div>
			
		</td>
	</tr>
	<!-- price template -->
	<tr class="addf_drpc_discount_type_choice addf_drpc_discount_type_choice_dynamic_price_adj addf_drpc_hidden_fields">
		<td>
			<?php echo esc_html__( 'Show Pricing Template', 'woo-af-drpc' ); ?>
		</td>
		<td>
			<input type="checkbox" name="addf_disc_rpc_show_prc_table" id="addf_disc_rpc_show_prc_table" value="yes" <?php checked( $addf_disc_rpc_show_prc_table , 'yes' , true ); ?>>
			<br>
			<p class="description">
				<?php echo esc_html__( 'Check if you want to show pricing template on product page', 'woo-af-drpc' ); ?>
			</p>
		</td>
	</tr>
	<tr class="addf_drpc_pricing_template">
		<td>
			<?php echo esc_html__( 'Choose pricing template', 'woo-af-drpc' ); ?>
		</td>
		<td>
			<select name="addf_drpc_pricing_template_design" id="addf_drpc_pricing_template_design" class="addf_disc_rpc_input_fields">
				<option value="table" <?php selected( $addf_drpc_pricing_template_design , 'table', true ); ?>><?php echo esc_html__( 'Table', 'woo-af-drpc' ); ?></option>
				<option value="card" <?php selected( $addf_drpc_pricing_template_design , 'card', true ); ?>><?php echo esc_html__( 'Card', 'woo-af-drpc' ); ?></option>
				<option value="list" <?php selected( $addf_drpc_pricing_template_design , 'list', true ); ?>><?php echo esc_html__( 'List', 'woo-af-drpc' ); ?></option>
			</select>
			<br>
			<p class="description"><?php echo esc_html__( 'Choose pricing template for product page', 'woo-af-drpc' ); ?></p>
		</td>
	</tr>
	<tr class="addf_drpc_discount_type_choice addf_drpc_discount_type_choice_dynamic_price_adj addf_drpc_hidden_fields">
		<td colspan="2">	
		<p class="description"><?php echo esc_html__( 'Select discounts for specific customers ', 'woo-af-drpc' ); ?></p>	
			<div class="addf_disc_rpc_disc_for_customer_div">
				<table class="addf_disc_rpc_disc_for_customer_table">
					<tr>
						<th>
							<?php echo esc_html__( 'Customers ', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Adjustment Type ', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Value ', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Min (Qty) ', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Max (Qty) ', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Replace price', 'woo-af-drpc' ); ?>
						</th>
						<th>
						</th>
					</tr>
					<?php
					$key = 0;
					if ( !empty( $addf_disc_rpc_disc_val_cust ) && is_array($addf_disc_rpc_disc_val_cust) ) {
						foreach ($addf_disc_rpc_disc_val_cust as $key => $value) {
							if ( !array_key_exists( $key , $addf_disc_rpc_select_cust ) ) {
								continue;
							}
							$addf_disc_rpc_select_customer = $addf_disc_rpc_select_cust[ $key ];
							if ( '' == $addf_disc_rpc_select_customer ) {
								continue;
							}
							$addf_drpc_cust_disc_choice        = $addf_drpc_cust_choice[ $key ];
							$addf_disc_rpc_disc_val_tbl_cust   = $addf_disc_rpc_disc_val_cust[ $key ];
							$addf_disc_rpc_min_qty_tbl_cust    = $addf_disc_rpc_min_qty_cust[ $key ];
							$addf_disc_rpc_max_qty_tbl_cust    = $addf_disc_rpc_max_qty_cust[ $key ];
							$addf_disc_rpc_replace_prc_cust_cb = '';
							if ( array_key_exists( $key , $addf_disc_rpc_replace_prc_cust_arr ) ) {
								if ( 'yes' == $addf_disc_rpc_replace_prc_cust_arr[ $key ] ) {
									$addf_disc_rpc_replace_prc_cust_cb = 'yes';
								}
							}
							?>
							<tr>
								<td>
									<select name="addf_disc_rpc_select_customer[<?php echo esc_attr($key); ?>]" class="addf_disc_rpc_customers" style="width:100%;"  data-placeholder="<?php echo esc_html__( 'Choose Customer... ', 'woo-af-drpc' ); ?>" >
										<?php 
											$users = get_users( array( 'fields' => array( 'ID', 'display_name', 'user_email' ) ) );
										foreach ($users as $cust_key => $cust_value) {
											if ( $cust_value->ID == $addf_disc_rpc_select_customer ) {
												?>
												<option value="<?php echo esc_attr($cust_value->ID); ?>" selected><?php echo esc_html__( $cust_value->display_name . '(' . $cust_value->user_email . ')' , 'woo-af-drpc' ); ?></option>
												<?php
											}
										}
										?>
									</select>
								</td>
								<td>
									<select name="addf_drpc_cust_disc_choice[<?php echo esc_attr($key); ?>]" class="addf_disc_rpc_cb_dis_allow">
										<option value="fixed_price" <?php selected( $addf_drpc_cust_disc_choice , 'fixed_price'  ); ?>><?php echo esc_html__( 'Fixed price ', 'woo-af-drpc' ); ?></option>
										<option value="fixed_price_increase" <?php selected( $addf_drpc_cust_disc_choice , 'fixed_price_increase'  ); ?>><?php echo esc_html__( 'Fixed increase', 'woo-af-drpc' ); ?></option>
										<option value="fixed_price_decrease" <?php selected( $addf_drpc_cust_disc_choice , 'fixed_price_decrease'  ); ?>><?php echo esc_html__( 'Fixed decrease', 'woo-af-drpc' ); ?></option>
										<option value="fixed_percent_increase" <?php selected( $addf_drpc_cust_disc_choice , 'fixed_percent_increase'  ); ?>><?php echo esc_html__( 'Percentage increase', 'woo-af-drpc' ); ?></option>
										<option value="fixed_percent_decrease" <?php selected( $addf_drpc_cust_disc_choice , 'fixed_percent_decrease'  ); ?>><?php echo esc_html__( 'Percentage decrease', 'woo-af-drpc' ); ?></option>
									</select>
								</td>
								<td>
									<input type="number" min="0" step="any" class="addf_drpc_number_input_field" name="addf_disc_rpc_disc_val_tbl_cust[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr( $addf_disc_rpc_disc_val_tbl_cust ); ?>" >
								</td>
								<td>
									<input type="number" min="0" class="addf_drpc_number_input_field" name="addf_disc_rpc_min_qty_tbl_cust[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr( $addf_disc_rpc_min_qty_tbl_cust ); ?>" >
								</td>
								<td>
									<input type="number" min="0" class="addf_drpc_number_input_field" name="addf_disc_rpc_max_qty_tbl_cust[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr( $addf_disc_rpc_max_qty_tbl_cust ); ?>" >
								</td>
								<td>
									<input type="checkbox" 
									<?php 
									if ( 'fixed_price' != $addf_drpc_cust_disc_choice ) {
										echo 'disabled'; } 
									?>
									name="addf_disc_rpc_replace_prc_cust_cb[<?php echo esc_attr($key); ?>]" value="yes" <?php checked( $addf_disc_rpc_replace_prc_cust_cb , 'yes' , true ); ?>>
								</td>
								<td>
									<span class="addf_disc_rpc_remove_row_tbl">&#x2717;</span>
								</td>
							</tr>
							<?php
						}
					}
					?>
				</table>
			</div>
			<div class="addf_disc_rpc_align_right_tbl">
				<input type="button" class="button addf_disc_rpc_add_cust_row_tbl" data-size="<?php echo esc_attr($key); ?>"  value="Add row">
			</div>
			<div class="addf_disc_rpc_discount_adj_div">
				<p class="description"><?php echo esc_html__( 'Select discounts for user roles ', 'woo-af-drpc' ); ?></p>	
				<table class="addf_disc_rpc_discount_adj_table">
					<tr>
						<th>
							<?php echo esc_html__( 'User Role ', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Adjustment Type ', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Value ', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Min (Qty) ', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Max (Qty) ', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Replace Price', 'woo-af-drpc' ); ?>
						</th>
						<th>
						</th>
					</tr>
					<?php
					$key = 0;
					if ( !empty( $addf_disc_rpc_roles ) ) {
						foreach ($addf_disc_rpc_roles as $key => $value) {
							$addf_disc_rpc_roles_select = $value;
							if ( !array_key_exists( $key , $addf_drpc_discount_choice ) ) {
								continue;
							}
							$addf_drpc_discount_amount_choice = $addf_drpc_discount_choice[ $key ];
							if ( '' == $addf_drpc_discount_amount_choice ) {
								continue;
							}
							$addf_disc_rpc_disc_val_tbl         = $addf_disc_rpc_value[ $key ];
							$addf_disc_rpc_min_qty_tbl          = $addf_disc_rpc_min[ $key ];
							$addf_disc_rpc_max_qty_tbl          = $addf_disc_rpc_max[ $key ];
							$addf_disc_rpc_replace_prc_roles_cb = '';
							if ( array_key_exists( $key , $addf_disc_rpc_replace_prc_roles_arr ) ) {
								$addf_disc_rpc_replace_prc_roles_cb = $addf_disc_rpc_replace_prc_roles_arr[ $key ];
							}
							?>
							<tr>
								<td>
									<select name="addf_disc_rpc_roles_select[<?php echo esc_attr($key); ?>]" >
										<option value="all" <?php selected( $addf_disc_rpc_roles_select , 'all' , true ); ?> ><?php echo esc_html__( 'All User Roles' , 'woo-af-drpc' ); ?></option>
										<?php
										global $wp_roles;
										$roles = $wp_roles->get_names();
										foreach ( $roles as $role_id => $role_name ) {
											?>
											<option value="<?php echo esc_attr( $role_id ); ?>" <?php selected( $addf_disc_rpc_roles_select , $role_id , true ); ?> ><?php echo esc_html__( $role_name , 'woo-af-drpc' ); ?></option>
											<?php
										}
										?>
										<option value="guest" <?php selected( $addf_disc_rpc_roles_select , 'guest' , true ); ?> ><?php echo esc_html__( 'Guest' , 'woo-af-drpc' ); ?></option>
									</select>
								</td>
								<td>
									<select name="addf_drpc_discount_amount_choice[<?php echo esc_attr($key); ?>]" class="addf_disc_rpc_cb_dis_allow" >
										<option value="fixed_price" <?php selected( $addf_drpc_discount_amount_choice , 'fixed_price'  ); ?>><?php echo esc_html__( 'Fixed price ', 'woo-af-drpc' ); ?></option>
										<option value="fixed_price_increase" <?php selected( $addf_drpc_discount_amount_choice , 'fixed_price_increase'  ); ?>><?php echo esc_html__( 'Fixed increase', 'woo-af-drpc' ); ?></option>
										<option value="fixed_price_decrease" <?php selected( $addf_drpc_discount_amount_choice , 'fixed_price_decrease'  ); ?>><?php echo esc_html__( 'Fixed decrease', 'woo-af-drpc' ); ?></option>
										<option value="fixed_percent_increase" <?php selected( $addf_drpc_discount_amount_choice , 'fixed_percent_increase'  ); ?>><?php echo esc_html__( 'Percentage increase', 'woo-af-drpc' ); ?></option>
										<option value="fixed_percent_decrease" <?php selected( $addf_drpc_discount_amount_choice , 'fixed_percent_decrease'  ); ?>><?php echo esc_html__( 'Percentage decrease', 'woo-af-drpc' ); ?></option>
									</select>
								</td>
								<td>
									<input type="number" min="0" step="any" name="addf_disc_rpc_disc_val_tbl[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr( $addf_disc_rpc_disc_val_tbl ); ?>" >
								</td>
								<td>
									<input type="number" min="0" name="addf_disc_rpc_min_qty_tbl[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr( $addf_disc_rpc_min_qty_tbl ); ?>" >
								</td>
								<td>
									<input type="number" min="0" name="addf_disc_rpc_max_qty_tbl[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr( $addf_disc_rpc_max_qty_tbl ); ?>" >
								</td>
								<td>
									<input type="checkbox" 
									<?php 
									if ( 'fixed_price' != $addf_drpc_discount_amount_choice ) {
										echo 'disabled'; } 
									?>
										name="addf_disc_rpc_replace_prc_roles_cb[<?php echo esc_attr($key); ?>]" value="yes" <?php checked( $addf_disc_rpc_replace_prc_roles_cb , 'yes', true ); ?> >
								</td>
								<td>
									<span class="addf_disc_rpc_remove_row_tbl">&#x2717;</span>
								</td>
							</tr>
							<?php
							
							
						}
					}
					?>
				</table>
			</div>
			<div class="addf_disc_rpc_align_right_tbl">
				<input type="button" class="button addf_disc_rpc_add_row_tbl" data-size="<?php echo esc_attr( $key ); ?>" value="Add row">
			</div>
		</td>
	</tr>	
</table>
<?php
