<?php
$addf_drpc_disc_min_spent_amount = get_post_meta( get_the_ID() , 'addf_drpc_disc_min_spent_amount' , true );
$addf_disc_rpc_min_spent_amount  = get_post_meta( get_the_ID() , 'addf_disc_rpc_min_spent_amount' , true );
$addf_disc_rpc_min_start_date    = get_post_meta( get_the_ID() , 'addf_disc_rpc_min_start_date' , true );
$addf_disc_rpc_min_end_date      = get_post_meta( get_the_ID() , 'addf_disc_rpc_min_end_date' , true );
?>
<table class="addf_disc_rpc_table">
	<!-- min amount spent -->
	<tr>
		<td>
			<?php echo esc_html__( 'Minimum spent', 'woo-af-drpc' ); ?>
		</td>
		<td>
			<select name="addf_drpc_disc_min_spent_amount" id="addf_drpc_disc_min_spent_amount" class=" addf_disc_rpc_input_fields addf_disc_rpc_on_change">
				<option value="ignore" <?php selected( $addf_drpc_disc_min_spent_amount , 'ignore'  ); ?>><?php echo esc_html__( 'Ignore amount spend by user', 'woo-af-drpc' ); ?></option>
				<option value="up_till_now" <?php selected( $addf_drpc_disc_min_spent_amount , 'up_till_now'  ); ?>><?php echo esc_html__( 'Amount spent up till now', 'woo-af-drpc' ); ?></option>
				<option value="start_end_date" <?php selected( $addf_drpc_disc_min_spent_amount , 'start_end_date'  ); ?>><?php echo esc_html__( 'Select start and end date', 'woo-af-drpc' ); ?></option>
			</select>
			<p class="description"><?php echo esc_html__( 'Choose a method for minimum amount spent by user to get discount', 'woo-af-drpc' ); ?></p>
		</td>
	</tr>
	<!-- min input -->
	<tr class="addf_drpc_disc_min_spent_amount addf_drpc_disc_min_spent_amount_up_till_now  addf_drpc_disc_min_spent_amount_start_end_date addf_drpc_hidden_fields">
		<td>
			<?php echo esc_html__( 'Minimum spent amount by user', 'woo-af-drpc' ); ?>
		</td>
		<td>
			<input type="number" min="0" name="addf_disc_rpc_min_spent_amount" class="addf_disc_rpc_input_fields" value="<?php echo esc_html__( $addf_disc_rpc_min_spent_amount , 'woo-af-drpc' ); ?>">
			<p class="description"><?php echo esc_html__( 'Enter minimum amount spent by user to get discount', 'woo-af-drpc' ); ?></p>
		</td>
	</tr>
	<!-- min amount start date -->
	<tr class="addf_drpc_disc_min_spent_amount addf_drpc_disc_min_spent_amount_start_end_date addf_drpc_hidden_fields">
		<td>
			<?php echo esc_html__( 'Minimum spent amount start date', 'woo-af-drpc' ); ?>
		</td>
		<td>
			<input type="date" min="0" name="addf_disc_rpc_min_start_date" class="addf_disc_rpc_input_fields" value="<?php echo esc_html__( $addf_disc_rpc_min_start_date , 'woo-af-drpc' ); ?>">
			<p class="description"><?php echo esc_html__( 'Select start date of minimum amount spent by user to get discount', 'woo-af-drpc' ); ?></p>
		</td>
	</tr>
	<!-- min amount end date -->
	<tr class="addf_drpc_disc_min_spent_amount addf_drpc_disc_min_spent_amount_start_end_date addf_drpc_hidden_fields">
		<td>
			<?php echo esc_html__( 'Minimum spent amount end date', 'woo-af-drpc' ); ?>
		</td>
		<td>
			<input type="date" min="0" name="addf_disc_rpc_min_end_date" class="addf_disc_rpc_input_fields" value="<?php echo esc_html__( $addf_disc_rpc_min_end_date , 'woo-af-drpc' ); ?>">
			<p class="description"><?php echo esc_html__( 'Select end date of minimum amount spent by user to get discount', 'woo-af-drpc' ); ?></p>
		</td>
	</tr>
</table>
