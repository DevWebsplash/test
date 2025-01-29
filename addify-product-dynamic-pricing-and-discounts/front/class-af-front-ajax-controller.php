<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Af_Front_Ajax_Controller {

	public function __construct() {
		add_action('wp_ajax_addf_drpc_get_variation_price', array( $this, 'addf_drpc_get_variation_price' ));
			
		add_action('wp_ajax_nopriv_addf_drpc_get_variation_price', array( $this, 'addf_drpc_get_variation_price' ));
	}


	public function addf_drpc_get_variation_price() {

		$nonce = isset( $_POST['nonce'] ) && '' !== $_POST['nonce'] ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( $nonce, 'addf-drpc-ajax-nonce' ) ) {
		 wp_die( esc_html__( 'Failed security check!', 'woo-af-drpc' ) );
		}
		
		$variation_id = isset( $_POST['variation_id'] ) ? sanitize_text_field( wp_unslash( $_POST['variation_id'] ) ) : '';

		$variable_product = wc_get_product($variation_id);

		$price = $variable_product->get_price();        

		$response = array(
			'price'   => $price,
			'message' => 'Price Fetched Successfully!',
		);
		wp_send_json_success( $response );
		die();
	}
}

new Af_Front_Ajax_Controller();
