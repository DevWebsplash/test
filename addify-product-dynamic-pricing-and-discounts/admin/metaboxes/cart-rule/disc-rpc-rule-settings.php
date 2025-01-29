<?php
defined( 'ABSPATH' ) || exit;
$addf_disc_rpc_rule_priority = get_post_meta( get_the_ID() , 'addf_disc_rpc_rule_priority' , true );
$addf_disc_rpc_start_time    = get_post_meta( get_the_ID() , 'addf_disc_rpc_start_time' , true );
$addf_disc_rpc_end_time      = get_post_meta( get_the_ID() , 'addf_disc_rpc_end_time' , true );
$addf_disc_rpc_days_radio    = get_post_meta( get_the_ID() , 'addf_disc_rpc_days_radio' , true );
$addf_disc_week_days_arr     = (array) get_post_meta( get_the_ID() , 'addf_disc_week_days_arr' , true );
if ( ( !$addf_disc_rpc_start_time ) || ( '' == $addf_disc_rpc_start_time ) ) {
	$addf_disc_rpc_start_time = gmdate( 'Y-m-d' );
}
?>
	<table class="addf_disc_rpc_table">
		<!-- Rule Priority -->
		<tr>
			<td>
				<?php echo esc_html__( 'Rule priority', 'woo-af-drpc' ); ?>
			</td>
			<td>
				<select name="addf_disc_rpc_rule_priority" class="addf_disc_rpc_input_fields">
					<option value="follow_seq" <?php selected( $addf_disc_rpc_rule_priority , 'follow_seq' , true ); ?> ><?php echo esc_html__( 'Follow sequence', 'woo-af-drpc' ); ?></option>
					<option value="must_apply" <?php selected( $addf_disc_rpc_rule_priority , 'must_apply' , true ); ?> ><?php echo esc_html__( 'Apply this rule must', 'woo-af-drpc' ); ?></option>
				</select>
			</td>
		</tr>
		<!-- Days -->
		<tr >
			<td>
				<?php echo esc_html__( 'Days for discount', 'woo-af-drpc' ); ?>
			</td>
			<td>
				<select name="addf_disc_rpc_days_radio" id="addf_disc_rpc_days_radio" class="addf_disc_rpc_input_fields addf_disc_rpc_on_change">
					<option value="all" <?php selected( $addf_disc_rpc_days_radio , 'all'  ); ?> ><?php echo esc_html__( 'All days', 'woo-af-drpc' ); ?></option>
					<option value="specific" <?php selected( $addf_disc_rpc_days_radio , 'specific'  ); ?>><?php echo esc_html__( 'Specific days', 'woo-af-drpc' ); ?></option>
				</select>
				<div class="addf_disc_rpc_days_radio addf_disc_rpc_days_radio_specific addf_drpc_hidden_fields">
					<div>
						<input type="checkbox" value="Monday" 
						<?php 
						if ( in_array( 'Monday' , $addf_disc_week_days_arr ) ) {
							echo 'checked="checked"'; } 
						?>
						name="addf_disc_week_days_arr[]" id="addf_disc_day_monday">
						<label for="addf_disc_day_monday"><?php echo esc_html__( 'Monday', 'woo-af-drpc' ); ?></label>
						<br>
						<input type="checkbox" value="Tuesday" 
						<?php 
						if ( in_array( 'Tuesday' , $addf_disc_week_days_arr ) ) {
							echo 'checked="checked"'; } 
						?>
						name="addf_disc_week_days_arr[]" id="addf_disc_day_tuesday">
						<label for="addf_disc_day_tuesday"><?php echo esc_html__( 'Tuesday', 'woo-af-drpc' ); ?></label>
						<br>
						<input type="checkbox" value="Wednesday" 
						<?php 
						if ( in_array( 'Wednesday' , $addf_disc_week_days_arr ) ) {
							echo 'checked="checked"'; } 
						?>
						name="addf_disc_week_days_arr[]" id="addf_disc_day_wednesday">
						<label for="addf_disc_day_wednesday"><?php echo esc_html__( 'Wednesday', 'woo-af-drpc' ); ?></label>
						<br>
						<input type="checkbox" value="Thursday" 
						<?php 
						if ( in_array( 'Thursday' , $addf_disc_week_days_arr ) ) {
							echo 'checked="checked"'; } 
						?>
						name="addf_disc_week_days_arr[]" id="addf_disc_day_thursday">
						<label for="addf_disc_day_thursday"><?php echo esc_html__( 'Thursday', 'woo-af-drpc' ); ?></label>
					</div>
					<div>
						<input type="checkbox" value="Friday" 
						<?php 
						if ( in_array( 'Friday' , $addf_disc_week_days_arr ) ) {
							echo 'checked="checked"'; } 
						?>
						name="addf_disc_week_days_arr[]" id="addf_disc_day_friday">
						<label for="addf_disc_day_friday"><?php echo esc_html__( 'Friday', 'woo-af-drpc' ); ?></label>
						<br>
						<input type="checkbox" value="Saturday" 
						<?php 
						if ( in_array( 'Saturday' , $addf_disc_week_days_arr ) ) {
							echo 'checked="checked"'; } 
						?>
						name="addf_disc_week_days_arr[]" id="addf_disc_day_saturday">
						<label for="addf_disc_day_saturday"><?php echo esc_html__( 'Saturday', 'woo-af-drpc' ); ?></label>
						<br>
						<input type="checkbox" value="Sunday" 
						<?php 
						if ( in_array( 'Sunday' , $addf_disc_week_days_arr ) ) {
							echo 'checked="checked"'; } 
						?>
						name="addf_disc_week_days_arr[]" id="addf_disc_day_sunday">
						<label for="addf_disc_day_sunday"><?php echo esc_html__( 'Sunday', 'woo-af-drpc' ); ?></label>
					</div>
				</div>
			</td>
		</tr>
		<!-- Date -->
		<!-- Start Date -->
		<tr>
			<td>
				<?php echo esc_html__( 'Start Date of Rule', 'woo-af-drpc' ); ?>
			</td>
			<td>
				<input type="date" name="addf_disc_rpc_start_time" min="" max="" id="addf_disc_rpc_start_time" class="addf_disc_rpc_input_fields" value="<?php echo esc_attr($addf_disc_rpc_start_time); ?>">
				<p class="description"><?php echo esc_html__( 'Select start Date for discount on products', 'woo-af-drpc' ); ?></p>
			</td>
		</tr>
		<!-- End Date -->
		<tr>
			<td>
				<?php echo esc_html__( 'End Date OF Rule', 'woo-af-drpc' ); ?>
			</td>
			<td>
				<input type="date" name="addf_disc_rpc_end_time" min="" max="" id="addf_disc_rpc_end_time" class="addf_disc_rpc_input_fields" value="<?php echo esc_attr($addf_disc_rpc_end_time); ?>">
				<p class="description"><?php echo esc_html__( 'Select end Date for discount on products', 'woo-af-drpc' ); ?></p>
			</td>
		</tr>
	</table>
<?php
