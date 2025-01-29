<?php
/**
 * class-my-checkout.php
 * Логіка кастомного створення замовлення, відключення checkout, AJAX
 */
if ( ! defined('ABSPATH') ) {
  exit;
}

class My_Checkout {

  public function __construct() {
    // We turn off payment and delivery
//    add_filter('woocommerce_cart_needs_payment', '__return_false');
    add_filter('woocommerce_cart_needs_shipping', '__return_false');
    add_filter('woocommerce_shipping_calculator_enable', '__return_false');

    // We remove the shipping and billing fields
    add_filter('woocommerce_checkout_fields', array($this, 'remove_shipping_and_billing_fields'));

    // We take away the "Proceed to checkout" button
    remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);

    // We change the title of the checkout page (if it is suddenly opened)
    add_filter('woocommerce_checkout_page_title', array($this, 'rename_checkout_page_title'));

    // Redirect from checkout page to cart (except order-received)
    add_action('template_redirect', array($this, 'redirect_checkout_to_cart'));

    // AJAX request processing (order creation)
    add_action('wp_ajax_place_order', array($this, 'handle_place_order'));
    add_action('wp_ajax_nopriv_place_order', array($this, 'handle_place_order'));

    // Auto-completion of orders (optional)
//    add_action('woocommerce_thankyou', array($this, 'auto_complete_order'));

    // You can add other actions / filters related to checkout
  }

  // 1. We delete the shipping and billing fields
  public function remove_shipping_and_billing_fields($fields) {
    unset($fields['shipping']);
    unset($fields['billing']);
    return $fields;
  }

  // 2. YOUR CART
  public function rename_checkout_page_title($page_title) {
    return 'YOUR CART';
  }

  // 3. Redirect from checkout page (except order-received)
  public function redirect_checkout_to_cart() {
    if ( is_checkout() && ! is_wc_endpoint_url('order-received') ) {
      wp_safe_redirect( wc_get_cart_url() );
      exit;
    }
  }


   // 4. AJAX order creation handler
  public function handle_place_order() {
    error_log('AJAX place_order called. User ID = ' . get_current_user_id());

    if (!check_ajax_referer('place_order_nonce', 'security', false)) {
        error_log('Invalid nonce for user ID = ' . get_current_user_id());
        wp_send_json_error(array('error' => __('Invalid security token.', 'woocommerce')));
    }

    // Check if cart is empty
    if ( WC()->cart->is_empty() ) {
        error_log('Cart empty for user ID = ' . get_current_user_id());
      wp_send_json_error(array('error' => __('Your cart is empty.', 'woocommerce')));
    }

    // Check user
    $customer_id = get_current_user_id();
    if ( ! $customer_id ) {
        error_log('No customer ID found at user ID ' . $customer_id);
      wp_send_json_error(array('error' => __('No customer ID found.', 'woocommerce')));
    }

    // Create order
    $order = wc_create_order(array('customer_id' => $customer_id));
    if ( is_wp_error($order) ) {
      wp_send_json_error(array('error' => __('Failed to create order.', 'woocommerce')));
    }

    // Add products from cart
    foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
      $result = $order->add_product($values['data'], $values['quantity']);
      if ( is_wp_error($result) ) {
        wp_send_json_error(array('error' => __('Failed to add product to order.', 'woocommerce')));
      }
    }

    // Fill in the name/email from the profile
    $this->set_billing_data($order, $customer_id);

    // Update status, totals, origin
    $order->calculate_totals();
    $order->update_status('processing');
//    $order->update_status('completed');

    // To avoid "Origin: Unknown":
    $order->set_created_via('direct');

    // Additional meta
    $order->update_meta_data('_order_attribution', 'direct');
    $order->save();

    // Empty cart
    WC()->cart->empty_cart();

    // Return success
    wp_send_json_success(array(
        'redirect_url' => $order->get_checkout_order_received_url()
    ));
  }


   // An example of a data set for billing from a WP profile
  private function set_billing_data($order, $user_id) {
    $user_info = get_userdata($user_id);
    if ( $user_info ) {
      $order->set_billing_first_name($user_info->first_name);
      $order->set_billing_last_name($user_info->last_name);
      $order->set_billing_email($user_info->user_email);
      // $order->set_billing_phone(...), if necessary
      $order->save();
    }
  }

  /**
   * 5. Autocomplete (completed)
   * Change if necessary, or don't do it at all
   */
//  public function auto_complete_order($order_id) {
//    if (!$order_id) return;
//    $order = wc_get_order($order_id);
//    if ( $order ) {
//      $order->update_status('completed');
//    }
//  }

}
