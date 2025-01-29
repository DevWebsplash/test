<?php
/**
 * Cart functions
 *
 * @author   <Author>
 * @version  1.0.0
 * @package  <Package>
 */

add_action('woocommerce_proceed_to_checkout', 'my_custom_place_order_btn');
function my_custom_place_order_btn() {
  echo '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" value="1">';
  _e('Place Order', 'woocommerce');
  echo '</button>';
}

// Add event date and time to the cart total
add_action('woocommerce_cart_totals_after_order_total', 'add_event_datetime_to_cart_total');
function add_event_datetime_to_cart_total() {
  // Get the date and time from ACF
  $datetime = get_field('event_datetime', 'options'); // 'options' if the field is global

  if ($datetime && strtotime($datetime)) {
    $formatted_datetime = date_i18n('F j, Y, g:i A', strtotime($datetime));
    echo '<tr class="event-datetime">
                <th>' . __('Remaining Time', 'woocommerce') . '</th>
                <td data-title="' . __('Remaining Time', 'woocommerce') . '">' . esc_html($formatted_datetime) . '</td>
              </tr>';
  }
}

// Function to output the countdown timer
function display_countdown_timer() {
  // Get the date and time from ACF
  $datetime = get_field('event_datetime', 'options'); // 'options' if the field is global
	$custom_deadline = get_field('woo_deadline_text', 'options');
	$custom_order_text = get_field('woo_change_order', 'options');
  if ($datetime && strtotime($datetime)) {
    $formatted_datetime = date_i18n('F j, Y, g:i A T', strtotime($datetime));
    $formatted_datetime1 = preg_replace('/GMT\+0?(\d{1,2})00/', 'GMT+$1', $formatted_datetime);
    echo '<div id="countdown-timer" class="countdown-timer" data-datetime="' . esc_attr($datetime) .'"><div class="timer"></div><div class="text"><div class="actual">'. $custom_order_text .'</div> <div class="deadline">'. $custom_deadline .'</div></div>
    <div class="date">' . esc_html($formatted_datetime1) . '</div></div>';
  }
}
function display_countdown_timer2() {
  // Get the date and time from ACF
  $datetime = get_field('event_datetime', 'options'); // 'options' if the field is global
  $custom_deadline = get_field('woo_deadline_text', 'options');
  $custom_order_text = get_field('woo_change_order', 'options');
  echo '<div class="actual text">You can still make adjustments within:</div>';
  if ($datetime && strtotime($datetime)) {
    $formatted_datetime = date_i18n('F j, Y, g:i A T', strtotime($datetime));
    $formatted_datetime1 = preg_replace('/GMT\+0?(\d{1,2})00/', 'GMT+$1', $formatted_datetime);
    echo '<div id="countdown-timer" class="countdown-timer" data-datetime="' . esc_attr($datetime) .'"><div class="timer"></div>    
    </div>';
  }
}
// Hook the function to the appropriate action
//add_action('woocommerce_cart_totals_after_order_total', 'display_countdown_timer');

// Зміна заголовка сторінки "Thank You"
add_filter('the_title', 'woo_title_order_received', 10, 2);
function woo_title_order_received($title, $id) {
  if (function_exists('is_order_received_page') && is_order_received_page() && get_the_ID() === $id) {
    $title = "Thank you!";
  }
  return $title;
}

// Зміна тексту на сторінці "Thank You"
add_filter('woocommerce_thankyou_order_received_text', 'woo_change_order_received_text', 10, 2);
function woo_change_order_received_text($str, $order) {
  $new_str = str_replace('Thank you. ', '', $str) . '';
  return $new_str;
}
