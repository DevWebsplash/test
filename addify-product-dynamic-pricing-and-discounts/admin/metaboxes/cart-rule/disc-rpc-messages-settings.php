<?php
defined( 'ABSPATH' ) || exit;
$addf_disc_rpc_before_disc_msg = get_post_meta( get_the_ID() , 'addf_disc_rpc_before_disc_msg' , true );
$addf_disc_rpc_after_disc_msg  = get_post_meta( get_the_ID() , 'addf_disc_rpc_after_disc_msg' , true );
?>
<table class="addf_disc_rpc_table">
	<tr>
		<td>
			<?php echo esc_html__( 'Message for before discount applied', 'woo-af-drpc' ); ?>
		</td>
		<td>
			<textarea name="addf_disc_rpc_before_disc_msg" class="addf_disc_rpc_disc_msg" cols="30" rows="10"><?php echo esc_html__( $addf_disc_rpc_before_disc_msg , 'woo-af-drpc' ); ?></textarea>
			<p class="description">
				<?php echo esc_html__( 'Enter message which will show to inform user about remaining discount requirement to avail discount', 'woo-af-drpc' ); ?>
				</p>
			<p class="description addf_disc_rpc_description">
				<?php 
				echo esc_html__( 'Use {req_products} for required products, {cart_qty} for current cart quantity / price, 
					{product_names} for product names of selected in cart , {rem_qty} for remaining quantity to get discount 
					, {start_date} for start date of discount rule , {end_date} for ending of discount rule  and {min_spent_amount} 
					for informing user about min spent amount to get discount from rule', 'woo-af-drpc' ); 
				?>
			</p>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo esc_html__( 'Message for after discount applied', 'woo-af-drpc' ); ?>
		</td>
		<td>
			<textarea name="addf_disc_rpc_after_disc_msg" class="addf_disc_rpc_disc_msg" cols="30" rows="10"><?php echo esc_html__( $addf_disc_rpc_after_disc_msg , 'woo-af-drpc' ); ?></textarea>
			<p class="description">
				<?php echo esc_html__( 'Enter congratulation message which will show to when discount is applied', 'woo-af-drpc' ); ?>
			</p>
			<p class="description addf_disc_rpc_description">
				<?php 
				echo esc_html__( 'Use  {cart_qty} for current cart quantity / price, {product_names} for product names of selected in cart , 
						{rem_qty} for remaining quantity to get discount,  {start_date} for start date of discount rule , {end_date} for 
						ending of discount rule, {gift_products} for gift products in conditional discount,  {rec_discount} for the discount
						 user have received and {min_spent_amount} for informing  user about min spent amount to get discount from rule', 'woo-af-drpc' ); 
				?>
			</p>
		</td>
	</tr>
</table>
<?php
