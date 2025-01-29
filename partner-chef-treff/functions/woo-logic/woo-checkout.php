<?php
/**
 * CheckOut functions
 *
 * @author   <Author>
 * @version  1.0.0
 * @package  <Package>
 */
add_action('wp_enqueue_scripts', function() {
  wp_enqueue_script('custom-order-ajax', get_template_directory_uri() . '/assets/js/custom-order.js', array('jquery'), '1.0', true);
  wp_localize_script('custom-order-ajax', 'customOrderParams', array(
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('place_order_nonce'),
  ));
});

add_action('wp_ajax_place_order', 'handle_place_order');
add_action('wp_ajax_nopriv_place_order', 'handle_place_order');

function handle_place_order() {
  if (!check_ajax_referer('place_order_nonce', 'security', false)) {
    wp_send_json_error(array('error' => __('Invalid security token.', 'woocommerce')));
  }

  if (WC()->cart->is_empty()) {
    wp_send_json_error(array('error' => __('Your cart is empty.', 'woocommerce')));
  }

  $customer_id = get_current_user_id();
  if (!$customer_id) {
    wp_send_json_error(array('error' => __('No customer ID found.', 'woocommerce')));
  }

  $order = wc_create_order(array('customer_id' => $customer_id));
  if (is_wp_error($order)) {
    wp_send_json_error(array('error' => __('Failed to create order.', 'woocommerce')));
  }

  foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
    $result = $order->add_product($values['data'], $values['quantity']);
    if (is_wp_error($result)) {
        wp_send_json_error(array('error' => __('Failed to add product to order.', 'woocommerce')));
    }
  }
  $order->calculate_totals();
  $order->update_status('processing');
  WC()->cart->empty_cart();

    // Add custom meta field for order attribution
    $order->update_meta_data('_order_attribution', 'direct');
    $order->save();

  wp_send_json_success(array('redirect_url' => $order->get_checkout_order_received_url()));
}



// Remove payment gateways
add_filter('woocommerce_cart_needs_payment', '__return_false');

// Remove shipping fields from checkout
add_filter('woocommerce_checkout_fields', 'remove_shipping_fields');
function remove_shipping_fields($fields) {
  unset($fields['shipping']);
  return $fields;
}

// Disable shipping calculations
add_filter('woocommerce_cart_needs_shipping', '__return_false');
add_filter('woocommerce_shipping_calculator_enable', '__return_false');


// Remove the "Proceed to checkout" button
remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);


// Change the checkout page title to "YOUR CART"
add_filter('woocommerce_checkout_page_title', 'rename_checkout_page_title');
function rename_checkout_page_title($page_title) {
  return 'YOUR CART';
}

add_action('template_redirect', function() {
  if (is_checkout() && !is_wc_endpoint_url('order-received')) {
    wp_redirect(wc_get_cart_url());
    exit;
  }
});

// Remove shipping and billing fields from checkout
add_filter('woocommerce_checkout_fields', 'remove_shipping_and_billing_fields');
function remove_shipping_and_billing_fields($fields) {
  unset($fields['shipping']);
  unset($fields['billing']);
  return $fields;
}


// Add a custom "Place Order" button
add_action('woocommerce_proceed_to_checkout', function() {
//  echo '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" value="1">' . __('Place Order', 'woocommerce') . '</button>';

  echo '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" value="1">';
  _e('Place Order', 'woocommerce');
  echo '</button>';
});



// Add event date and time to the cart total
add_action('woocommerce_cart_totals_after_order_total', 'add_event_datetime_to_cart_total');
function add_event_datetime_to_cart_total() {
    // Get the date and time from ACF
    $datetime = get_field('event_datetime', 'options'); // 'options' if the field is global

  if ($datetime && strtotime($datetime)) {
    $formatted_datetime = date_i18n('F j, Y, g:i A', strtotime($datetime));
    echo '<tr class="event-datetime">
                <th>' . __('Event Date & Time', 'woocommerce') . '</th>
                <td>' . esc_html($formatted_datetime) . '</td>
              </tr>';
  }
}


// Automatically complete orders
add_action('woocommerce_thankyou', 'auto_complete_order');
function auto_complete_order($order_id) {
  if (!$order_id) {
    return;
  }

  $order = wc_get_order($order_id);
  $order->update_status('processing');
}



// ...........................................................




// Зміна заголовка сторінки "Thank You"
add_filter('the_title', 'woo_title_order_received', 10, 2);
function woo_title_order_received($title, $id) {
	if (function_exists('is_order_received_page') && is_order_received_page() && get_the_ID() === $id) {
		$title = "Thank you for your order! :)";
	}
	return $title;
}

// Зміна тексту на сторінці "Thank You"
add_filter('woocommerce_thankyou_order_received_text', 'woo_change_order_received_text', 10, 2);
function woo_change_order_received_text($str, $order) {
	$new_str = str_replace('Thank you. ', '', $str) . '';
	return $new_str;
}

// Підключення кастомного скрипта
//add_action('wp_enqueue_scripts', 'enqueue_order_summary_scripts');
//function enqueue_order_summary_scripts() {
//	if (is_checkout()) {
//		wp_enqueue_script('order-summary-ajax', get_template_directory_uri() . '/assets/js/order-summary.js', array('jquery'), '1.0', true);
//		wp_localize_script('order-summary-ajax', 'orderSummaryParams', array(
//			'ajax_url' => admin_url('admin-ajax.php'),
//		));
//	}
//}

// Додавання редагованого Order Summary
//remove_action('woocommerce_review_order_before_payment', 'woocommerce_order_review', 10); // Видаляємо стандартний Order Summary
//add_action('woocommerce_checkout_order_review', 'add_editable_cart_to_checkout', 5); // Додаємо його в новому місці

//function add_editable_cart_to_checkout() {
//	if (WC()->cart->get_cart_contents_count() > 0) {
//		echo '<h3>' . __('Order Summary', 'woocommerce') . '</h3>';
//		echo do_shortcode('[woocommerce_cart]');
//	}
//}

// AJAX для оновлення Order Summary
//add_action('wp_ajax_update_order_summary', 'update_order_summary');
//add_action('wp_ajax_nopriv_update_order_summary', 'update_order_summary');
//function update_order_summary() {
//	$cart_item_key = sanitize_text_field($_POST['cart_item_key']);
//	$quantity = sanitize_text_field($_POST['quantity']);
//
//	if ($quantity > 0) {
//		WC()->cart->set_quantity($cart_item_key, $quantity);
//	} else {
//		WC()->cart->remove_cart_item($cart_item_key);
//	}
//
//	WC()->cart->calculate_totals();
//
//	ob_start();
//	woocommerce_cart_totals();
//	$cart_totals = ob_get_clean();
//
//	wp_send_json_success(array('cart_totals' => $cart_totals));
//}

// Кастомізація кнопки "Remove" у кошику
//add_filter('woocommerce_cart_item_remove_link', 'custom_remove_item_link', 10, 2);
//function custom_remove_item_link($remove_link, $cart_item_key) {
//	return '<a href="#" class="remove-item" data-cart_item_key="' . $cart_item_key . '">' . __('Remove', 'woocommerce') . '</a>';
//}



