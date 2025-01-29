<?php

/**
 * my-custom-checkout.php
 */

if (!defined ('ABSPATH')) {
  exit; // protection against direct challenge
}


require_once __DIR__ . '/includes/class-my-checkout.php';

new My_Checkout();


add_action ('wp_enqueue_scripts', function () {
  // if necessary, you can check: is_user_logged_in(), is_woocommerce(), etc
  // if ( ! is_user_logged_in() ) return;

  wp_enqueue_script (
      'my-custom-order-ajax',
      get_template_directory_uri () . '/functions/my-custom-checkout/assets/js/custom-order.js',
      array('jquery'),
      '1.0.0',
      true
  );

  wp_localize_script ('my-custom-order-ajax', 'customOrderParams', array(
      'ajax_url' => admin_url ('admin-ajax.php'),
      'nonce' => wp_create_nonce ('place_order_nonce'),
  ));
});
