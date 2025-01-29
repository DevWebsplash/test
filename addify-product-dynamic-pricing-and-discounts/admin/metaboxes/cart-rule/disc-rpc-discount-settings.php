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
$addf_disc_rpc_min_spent_amount       = get_post_meta( get_the_ID() , 'addf_disc_rpc_min_spent_amount' , true );
$addf_disc_rpc_min_start_date         = get_post_meta( get_the_ID() , 'addf_disc_rpc_min_start_date' , true );
$addf_disc_rpc_min_end_date           = get_post_meta( get_the_ID() , 'addf_disc_rpc_min_end_date' , true );
$addf_disc_rpc_same_price_s_time      = get_post_meta( get_the_ID() , 'addf_disc_rpc_same_price_s_time' , true );
$addf_disc_rpc_start_time             = get_post_meta( get_the_ID() , 'addf_disc_rpc_start_time' , true );
$addf_disc_rpc_end_time               = get_post_meta( get_the_ID() , 'addf_disc_rpc_end_time' , true );
$addf_disc_rpc_days_radio             = get_post_meta( get_the_ID() , 'addf_disc_rpc_days_radio' , true );
$addf_disc_week_days_arr              = (array) get_post_meta( get_the_ID() , 'addf_disc_week_days_arr' , true );
$addf_disc_rpc_price_or_qty           = get_post_meta( get_the_ID() , 'addf_disc_rpc_price_or_qty' , true );
$addf_disc_rpc_qty_from               = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_qty_from' , true );
$addf_disc_rpc_qty_to                 = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_qty_to' , true );
$addf_disc_rpc_qty_disc_apply         = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_qty_disc_apply' , true );
$addf_disc_rpc_gift_products_list     = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_gift_products_list' , true );
$addf_disc_rpc_gift_products_list_qty = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_gift_products_list_qty' , true );
if ( ( !$addf_drpc_discount_amount ) || ( '' == $addf_drpc_discount_amount ) ) {
	$addf_drpc_discount_amount = '1';
}
if ( ( !$addf_disc_rpc_start_time ) || ( '' == $addf_disc_rpc_start_time ) ) {
	$addf_disc_rpc_start_time = gmdate( 'Y-m-d' );
}
		// dynamic discount for customer
		$addf_disc_rpc_customer = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_cart_select_customer' , true );
		$cust_disc_on           = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_disc_on_cart_rule' , true );
		$cust_choice            = (array) get_post_meta( get_the_ID() , 'addf_drpc_cust_disc_choice' , true );
		$cust_val               = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_disc_val_tbl_cust' , true );
		$cust_min_qty           = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_min_qty_tbl_cust' , true );
		$cust_max_qty           = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_max_qty_tbl_cust' , true );
		// dynamic discount for user roles
		$addf_rpc_user_role = get_post_meta( get_the_ID() , 'addf_disc_rpc_cart_select_user_role' , true );
		$user_role_disc_on  = get_post_meta( get_the_ID() , 'addf_disc_rpc_role_disc_on_cart_rule' , true );
		$user_role_choice   = get_post_meta( get_the_ID() , 'addf_drpc_user_role_disc_choice' , true );
		$user_role_val      = get_post_meta( get_the_ID() , 'addf_disc_rpc_disc_val_tbl_user_role' , true );
		$user_role_min_qty  = get_post_meta( get_the_ID() , 'addf_disc_rpc_min_qty_tbl_user_role' , true );
		$user_role_max_qty  = get_post_meta( get_the_ID() , 'addf_disc_rpc_max_qty_tbl_user_role' , true );
		// gift a product for customer
		$cond_addf_rpc_user_role  = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_cart_cond_user_role' , true );
		$cond_user_role_disc_on   = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_cond_role_disc_on_cart_rule' , true );
		$cond_user_role_gift_list = (array) get_post_meta( get_the_ID() , 'addf_disc_user_role_cond_cart_gift_list' , true );
		$cond_user_role_val       = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_cond_disc_val_tbl_user_role' , true );
		$cond_user_role_min_qty   = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_cond_min_qty_tbl_user_role' , true );
		$cond_user_role_max_qty   = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_cond_max_qty_tbl_user_role' , true );
		// gift a product for user role
		$cond_cart_customer  = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_cond_cart_select_customer' , true );
		$cond_cust_disc_on   = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_cond_disc_on_cart_rule' , true );
		$cond_cust_gift_list = (array) get_post_meta( get_the_ID() , 'addf_disc_cust_cart_gift_list' , true );
		$cond_cust_val       = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_cond_qty_tbl_cust' , true );
		$cond_cust_min_qty   = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_cond_min_qty_tbl_cust' , true );
		$cond_cust_max_qty   = (array) get_post_meta( get_the_ID() , 'addf_disc_rpc_cond_max_qty_tbl_cust' , true );
?>
<table class="addf_disc_rpc_table">
	<!-- Rule type -->
	<tr>
		<td>
			<?php echo esc_html__( 'Discount rule type', 'woo-af-drpc' ); ?>
		</td>
		<td>
			<select name="addf_drpc_discount_type_choice" id="addf_drpc_discount_type_choice" class=" addf_disc_rpc_input_fields addf_disc_rpc_on_change">
				<option value="dynamic_disc_on_qty" <?php selected( $addf_drpc_discount_type_choice , 'dynamic_disc_on_qty'  ); ?>><?php echo esc_html__( 'Dynamic Adjustment Based on Cart Quantity', 'woo-af-drpc' ); ?></option>
				<option value="dynamic_disc_on_amount" <?php selected( $addf_drpc_discount_type_choice , 'dynamic_disc_on_amount'  ); ?>><?php echo esc_html__( 'Dynamic Adjustment Based on Cart Amount', 'woo-af-drpc' ); ?></option>
				<option value="gift_on_qty" <?php selected( $addf_drpc_discount_type_choice , 'gift_on_qty'  ); ?>><?php echo esc_html__( 'Gift a Product based on Quantity', 'woo-af-drpc' ); ?></option>
				<option value="gift_on_price" <?php selected( $addf_drpc_discount_type_choice , 'gift_on_price'  ); ?>><?php echo esc_html__( 'Gift a Product based on Amount', 'woo-af-drpc' ); ?></option>
			</select>
			<p class="description"><?php echo esc_html__( 'Select a type for products and users for discount in cart', 'woo-af-drpc' ); ?></p>
		</td>
	</tr>
	<!-- For Dynamic Discounts -->
	<tr class="addf_drpc_discount_type_choice addf_drpc_discount_type_choice_dynamic_disc_on_qty addf_drpc_discount_type_choice_dynamic_disc_on_amount ">
		<td colspan="2">
			<!-- Table for dynamic Discount for Customers -->
			<div class="addf_disc_rpc_cart_cust_dynamic_div">
				<table class="addf_disc_rpc_cart_cust_dynamic_tbl">
					<tr>
						<th>
							<?php echo esc_html__( 'Customers', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Adjustment Type', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Value', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Min Required', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Max Required', 'woo-af-drpc' ); ?>
						</th>
						<th></th>
					</tr>
					<?php 
					if ( !empty($addf_disc_rpc_customer) ) {
						foreach ( $addf_disc_rpc_customer as $key => $value) {
							if ( '' == $value ) {
								continue;
							}
							$addf_disc_rpc_cart_select_customer = $value;
							$addf_drpc_cust_disc_choice         = $cust_choice[ $key ];
							$addf_disc_rpc_disc_val_tbl_cust    = $cust_val[ $key ];
							$addf_disc_rpc_min_qty_tbl_cust     = $cust_min_qty[ $key ];
							$addf_disc_rpc_max_qty_tbl_cust     = $cust_max_qty[ $key ];
							?>
						<tr>
							<td>
								<select name="addf_disc_rpc_cart_select_customer[]" class="addf_disc_rpc_customers" style="width:100%;"  data-placeholder="<?php echo esc_html__( 'Choose Customer... ', 'woo-af-drpc' ); ?>" >
									<?php 
										$users = get_users( array( 'fields' => array( 'ID', 'display_name', 'user_email' ) ) );
									foreach ($users as $cust_key => $cust_value) {
										if ( $cust_value->ID == $addf_disc_rpc_cart_select_customer ) {
											?>
											<option value="<?php echo esc_attr($cust_value->ID); ?>" selected><?php echo esc_html__( $cust_value->display_name . '(' . $cust_value->user_email . ')' , 'woo-af-drpc' ); ?></option>
											<?php
										}
									}
									?>
								</select>
							</td>
							<td>
								<select name="addf_drpc_cust_disc_choice[]" >
									<option value="fixed_price_increase" <?php selected( $addf_drpc_cust_disc_choice , 'fixed_price_increase'  ); ?>><?php echo esc_html__( 'Fixed increase', 'woo-af-drpc' ); ?></option>
									<option value="fixed_price_decrease" <?php selected( $addf_drpc_cust_disc_choice , 'fixed_price_decrease'  ); ?>><?php echo esc_html__( 'Fixed decrease', 'woo-af-drpc' ); ?></option>
									<option value="fixed_percent_increase" <?php selected( $addf_drpc_cust_disc_choice , 'fixed_percent_increase'  ); ?>><?php echo esc_html__( 'Percentage increase', 'woo-af-drpc' ); ?></option>
									<option value="fixed_percent_decrease" <?php selected( $addf_drpc_cust_disc_choice , 'fixed_percent_decrease'  ); ?>><?php echo esc_html__( 'Percentage decrease', 'woo-af-drpc' ); ?></option>
								</select>
							</td>
							<td>
								<input type="number" min="0" step="any" name="addf_disc_rpc_disc_val_tbl_cust[]" value="<?php echo esc_attr( $addf_disc_rpc_disc_val_tbl_cust ); ?>" >
							</td>
							<td>
								<input type="number" min="0" name="addf_disc_rpc_min_qty_tbl_cust[]" value="<?php echo esc_attr( $addf_disc_rpc_min_qty_tbl_cust ); ?>" >
							</td>
							<td>
								<input type="number" min="0" name="addf_disc_rpc_max_qty_tbl_cust[]" value="<?php echo esc_attr( $addf_disc_rpc_max_qty_tbl_cust ); ?>" >
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
			<!-- Add new Row for customers -->
			<div class="addf_disc_rpc_align_right_tbl">
				<input type="button" class="button addf_disc_rpc_add_cust_row_cart_tbl"  value="Add row">
			</div>
			<!-- Table for dynamic Discount for User Roles -->
			<div class="addf_disc_rpc_cart_roles_dynamic_div">
				<table class="addf_disc_rpc_cart_roles_dynamic_tbl">
					<tr>
						<th>
							<?php echo esc_html__( 'User Role', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Adjustment Type', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Value', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Min Required', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Max Required', 'woo-af-drpc' ); ?>
						</th>
						<th></th>
					</tr>
					<?php 
					if ( !empty($addf_rpc_user_role) ) {
						foreach ( $addf_rpc_user_role as $key => $value) {
							$addf_disc_rpc_cart_select_user_role  = $value;
							$addf_drpc_user_role_disc_choice      = $user_role_choice[ $key ];
							$addf_disc_rpc_disc_val_tbl_user_role = $user_role_val[ $key ];
							$addf_disc_rpc_min_qty_tbl_user_role  = $user_role_min_qty[ $key ];
							$addf_disc_rpc_max_qty_tbl_user_role  = $user_role_max_qty[ $key ];
							?>
							<tr>
								<td>
									<select name="addf_disc_rpc_cart_select_user_role[]"  style="width:100%;"  data-placeholder="<?php echo esc_html__( 'Choose Customer... ', 'woo-af-drpc' ); ?>" >
									<option value="all" <?php selected( $addf_disc_rpc_cart_select_user_role , 'all' , true ); ?> ><?php echo esc_html__( 'All User Roles' , 'woo-af-drpc' ); ?></option>
												<?php
												global $wp_roles;
												$roles = $wp_roles->get_names();
												foreach ( $roles as $role_id => $role_name ) {
													?>
													<option value="<?php echo esc_attr( $role_id ); ?>" <?php selected( $addf_disc_rpc_cart_select_user_role , $role_id , true ); ?> ><?php echo esc_html__( $role_name , 'woo-af-drpc' ); ?></option>
													<?php
												}
												?>
												<option value="guest" <?php selected( $addf_disc_rpc_cart_select_user_role , 'guest' , true ); ?> ><?php echo esc_html__( 'Guest' , 'woo-af-drpc' ); ?></option>
									</select>
								</td>
								<td>
									<select name="addf_drpc_user_role_disc_choice[]" >
										<option value="fixed_price_increase" <?php selected( $addf_drpc_user_role_disc_choice , 'fixed_price_increase'  ); ?>><?php echo esc_html__( 'Fixed increase', 'woo-af-drpc' ); ?></option>
										<option value="fixed_price_decrease" <?php selected( $addf_drpc_user_role_disc_choice , 'fixed_price_decrease'  ); ?>><?php echo esc_html__( 'Fixed decrease', 'woo-af-drpc' ); ?></option>
										<option value="fixed_percent_increase" <?php selected( $addf_drpc_user_role_disc_choice , 'fixed_percent_increase'  ); ?>><?php echo esc_html__( 'Percentage increase', 'woo-af-drpc' ); ?></option>
										<option value="fixed_percent_decrease" <?php selected( $addf_drpc_user_role_disc_choice , 'fixed_percent_decrease'  ); ?>><?php echo esc_html__( 'Percentage decrease', 'woo-af-drpc' ); ?></option>
									</select>
								</td>
								<td>
									<input type="number" min="0" step="any" name="addf_disc_rpc_disc_val_tbl_user_role[]" value="<?php echo esc_attr( $addf_disc_rpc_disc_val_tbl_user_role ); ?>" >
								</td>
								<td>
									<input type="number" min="0" name="addf_disc_rpc_min_qty_tbl_user_role[]" value="<?php echo esc_attr( $addf_disc_rpc_min_qty_tbl_user_role ); ?>" >
								</td>
								<td>
									<input type="number" min="0" name="addf_disc_rpc_max_qty_tbl_user_role[]" value="<?php echo esc_attr( $addf_disc_rpc_max_qty_tbl_user_role ); ?>" >
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
			<!-- Add new row for User Roles -->
			<div class="addf_disc_rpc_align_right_tbl">
				<input type="button" class="button addf_disc_rpc_add_user_role_row_cart_tbl"  value="Add row">
			</div>
		</td>
	</tr>
	<!-- For Gift a product -->
	<tr class="addf_drpc_discount_type_choice addf_drpc_discount_type_choice_gift_on_qty addf_drpc_discount_type_choice_gift_on_price ">
		<td colspan="2">
			<div class="addf_disc_rpc_cart_cust_conditional">
				<table class="addf_disc_rpc_cart_cust_conditional_tbl">
					<tr>
						<th>
							<?php echo esc_html__( 'Customers', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Gift Product', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Gift Quantity', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Min Required', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Max Required', 'woo-af-drpc' ); ?>
						</th>
						<th></th>
					</tr>
					<?php 
					if ( !empty($cond_cart_customer) ) {
						foreach ( $cond_cart_customer as $key => $value) {
							if ( '' == $value ) {
								continue;
							}
							$addf_disc_rpc_cond_cart_select_customer = $value;
							$addf_disc_cust_cart_gift_list           = $cond_cust_gift_list[ $key ];
							$addf_disc_rpc_cond_qty_tbl_cust         = $cond_cust_val[ $key ];
							$addf_disc_rpc_cond_min_qty_tbl_cust     = $cond_cust_min_qty[ $key ];
							$addf_disc_rpc_cond_max_qty_tbl_cust     = $cond_cust_max_qty[ $key ];
							?>
						<tr>
							<td>
								<select name="addf_disc_rpc_cond_cart_select_customer[]" class="addf_disc_rpc_customers" style="width:100%;"  data-placeholder="<?php echo esc_html__( 'Choose Customer... ', 'woo-af-drpc' ); ?>" >
									<?php 
										$users = get_users( array( 'fields' => array( 'ID', 'display_name', 'user_email' ) ) );
									foreach ($users as $cust_key => $cust_value) {
										if ( $cust_value->ID == $addf_disc_rpc_cond_cart_select_customer ) {
											?>
											<option value="<?php echo esc_attr($cust_value->ID); ?>" selected><?php echo esc_html__( $cust_value->display_name . '(' . $cust_value->user_email . ')' , 'woo-af-drpc' ); ?></option>
											<?php
										}
									}
									?>
								</select>
							</td>
							<td>
								<select name="addf_disc_cust_cart_gift_list[]" data-placeholder="<?php echo esc_html__( 'Choose products...', 'woo-af-drpc' ); ?>" class="addf_disc_choose_new_gift_product" style="width:100%;">
									<option value="<?php echo esc_attr( $addf_disc_cust_cart_gift_list ); ?>"><?php echo esc_html__( get_the_title( $addf_disc_cust_cart_gift_list ) , 'woo-af-drpc' ); ?></option>
								</select>
							</td>
							<td>
								<input type="number" min="0" step="any" name="addf_disc_rpc_cond_qty_tbl_cust[]" value="<?php echo esc_attr( $addf_disc_rpc_cond_qty_tbl_cust ); ?>" >
							</td>
							<td>
								<input type="number" min="0" name="addf_disc_rpc_cond_min_qty_tbl_cust[]" value="<?php echo esc_attr( $addf_disc_rpc_cond_min_qty_tbl_cust ); ?>" >
							</td>
							<td>
								<input type="number" min="0" name="addf_disc_rpc_cond_max_qty_tbl_cust[]" value="<?php echo esc_attr( $addf_disc_rpc_cond_max_qty_tbl_cust ); ?>" >
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
				<input type="button" class="button addf_disc_rpc_conditional_add_cust_row_cart_tbl"  value="Add row">
			</div>
			<div class="addf_disc_rpc_cart_roles_conditional_div">
				<table class="addf_disc_rpc_cart_roles_conditional_tbl">
					<tr>
						<th>
							<?php echo esc_html__( 'User Roles', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Gift Product', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Gift Quantity', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Min Required', 'woo-af-drpc' ); ?>
						</th>
						<th>
							<?php echo esc_html__( 'Max Required', 'woo-af-drpc' ); ?>
						</th>
						<th></th>
					</tr>
					<?php 
					if ( !empty($cond_addf_rpc_user_role) ) {
						foreach ( $cond_addf_rpc_user_role as $key => $value) {
							$addf_disc_rpc_cart_cond_user_role = $value;
							if ( !array_key_exists( $key , $cond_user_role_gift_list ) ) {
								continue;
							}
							$addf_disc_user_role_cond_cart_gift_list   = $cond_user_role_gift_list[ $key ];
							$addf_disc_rpc_cond_disc_val_tbl_user_role = $cond_user_role_val[ $key ];
							$addf_disc_rpc_cond_min_qty_tbl_user_role  = $cond_user_role_min_qty[ $key ];
							$addf_disc_rpc_cond_max_qty_tbl_user_role  = $cond_user_role_max_qty[ $key ];

							?>
							<tr>
								<td>
									<select name="addf_disc_rpc_cart_cond_user_role[]" class="" style="width:100%;"  data-placeholder="<?php echo esc_html__( 'Choose Customer... ', 'woo-af-drpc' ); ?>" >
										<option value="all" <?php selected( $addf_disc_rpc_cart_cond_user_role , 'all' , true ); ?> ><?php echo esc_html__( 'All User Roles' , 'woo-af-drpc' ); ?></option>
												<?php
												global $wp_roles;
												$roles = $wp_roles->get_names();
												foreach ( $roles as $role_id => $role_name ) {
													?>
													<option value="<?php echo esc_attr( $role_id ); ?>" <?php selected( $addf_disc_rpc_cart_cond_user_role , $role_id , true ); ?> ><?php echo esc_html__( $role_name , 'woo-af-drpc' ); ?></option>
													<?php
												}
												?>
												<option value="guest" <?php selected( $addf_disc_rpc_cart_cond_user_role , 'guest' , true ); ?> ><?php echo esc_html__( 'Guest' , 'woo-af-drpc' ); ?></option>
									</select>
								</td>
								<td>
									<select name="addf_disc_user_role_cond_cart_gift_list[]" data-placeholder="<?php echo esc_html__( 'Choose products...', 'woo-af-drpc' ); ?>" class="addf_disc_choose_new_gift_product" style="width:100%;">
										<option value="<?php echo esc_attr( $addf_disc_user_role_cond_cart_gift_list ); ?>"><?php echo esc_html__( get_the_title( $addf_disc_user_role_cond_cart_gift_list ) , 'woo-af-drpc' ); ?></option>
									</select>
								</td>
								<td>
									<input type="number" min="0" step="any" name="addf_disc_rpc_cond_disc_val_tbl_user_role[]" value="<?php echo esc_attr( $addf_disc_rpc_cond_disc_val_tbl_user_role ); ?>" >
								</td>
								<td>
									<input type="number" min="0" name="addf_disc_rpc_cond_min_qty_tbl_user_role[]" value="<?php echo esc_attr( $addf_disc_rpc_cond_min_qty_tbl_user_role ); ?>" >
								</td>
								<td>
									<input type="number" min="0" name="addf_disc_rpc_cond_max_qty_tbl_user_role[]" value="<?php echo esc_attr( $addf_disc_rpc_cond_max_qty_tbl_user_role ); ?>" >
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
				<input type="button" class="button addf_disc_rpc_conditional_add_user_role_row_cart_tbl"  value="Add row">
			</div>
		</td>
	</tr>
</table>
<?php
