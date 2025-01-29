<?php
/**
 * WooCommerce Global Functions
 *
 * @version 1.0.0
 * @package <Package>
 */

/**
 * 1. Theme & WooCommerce supports
 */
add_action('after_setup_theme', function() {
  // WooCommerce basic supports
  add_theme_support('woocommerce');
  add_theme_support('wc-product-gallery-lightbox');

  // If you don't need a slider/zoom:
  remove_theme_support('wc-product-gallery-slider');
  remove_theme_support('wc-product-gallery-zoom');
});

/**
 * 2. Display currency name instead of symbol
 *    (якщо використовуєте таку логіку)
 */
add_filter('woocommerce_currency_symbol', 'display_currency_name', 10, 2);
function display_currency_name($currency_symbol, $currency) {
  $currency_names = array(
      'USD' => 'US Dollar',
      'EUR' => 'Euro',
      'GBP' => 'British Pound',
    // ... Інші валюти
  );

  return isset($currency_names[$currency]) ? $currency_names[$currency] : $currency_symbol;
}

/**
 * 3. Disable WooCommerce sidebar
 */
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

/**
 * 4. Remove default WooCommerce wrappers + breadcrumbs + default hooks
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

/**
 * If you need to remove the price after the title (in your case it was in the code)
 */
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

/**
 * 5. Add custom WooCommerce wrappers (контейнери для контенту)
 */
add_action('woocommerce_before_main_content', function() {
  echo '<div class="cn"><div id="primary" class="content-area"><main id="main" class="site-main" role="main">';
}, 10);

add_action('woocommerce_after_main_content', function() {
  echo '</main></div></div>';
}, 10);

/**
 * 6. Custom content after product title (опис та сток)
 */
add_action('woocommerce_shop_loop_item_title', function() {
  echo '<p class="woocommerce-loop-product__description">' . wp_trim_words(get_the_content(), 40, '...') . '</p>';
}, 15);

add_action('woocommerce_shop_loop_item_title', function() {
  global $product;
  $stock_quantity = $product->get_stock_quantity();
  $display_stock_quantity = ($stock_quantity > 0) ? $stock_quantity : '';
  $availability_text = $product->is_in_stock() ? esc_html__('In stock', 'woocommerce') : esc_html__('Out of stock', 'woocommerce');
  echo '<div class="product-availability">' . esc_html($display_stock_quantity) . ' ' . esc_html($availability_text) . '</div>';
}, 16);

/**
 * 7. Custom bottom wrapper around product listing
 */
add_action('woocommerce_after_shop_loop_item', function() {
  echo '<div class="woocommerce-loop-product__bottom">';
}, 6);

add_action('woocommerce_after_shop_loop_item', function() {
  echo '</div>';
}, 11);

/**
 * 8. Add price hook after product link close & before "Add to cart"
 */
add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 7);

/**
 * 9. Replace WooCommerce archive page title
 */
add_filter('woocommerce_show_page_title', '__return_false');

/**
 * 10. Add custom title and categories to WooCommerce archive pages
 */


add_action('woocommerce_archive_description', function() {
	// Логування початку виконання функції
	error_log('--- WooCommerce Archive Description Hook Fired ---');

	// Отримання користувацької назви архіву
	$custom_title = get_field('woo_custom_archive_title', 'options');
	error_log('Custom Archive Title: ' . print_r($custom_title, true));

	echo '<h1 class="woocommerce-products-header__title page-title">'. esc_html($custom_title) .'</h1>';

	// Налаштування аргументів для отримання категорій
	$args = array(
		'taxonomy'   => 'product_cat',
		'orderby'    => 'name',
		'order'      => 'ASC',
		'hide_empty' => true,
		// Якщо хочете виключити "uncategorized"
		'exclude'    => get_term_by('slug', 'uncategorized', 'product_cat') ? get_term_by('slug', 'uncategorized', 'product_cat')->term_id : '',
	);

	// Логування аргументів запиту
	error_log('get_terms Args: ' . print_r($args, true));

	// Отримання категорій продуктів
	$product_categories = get_terms($args);

	// Логування результату get_terms
	if (is_wp_error($product_categories)) {
		error_log('get_terms Error: ' . $product_categories->get_error_message());
	} else {
		error_log('Number of Categories Retrieved: ' . count($product_categories));
	}

	if (!empty($product_categories) && !is_wp_error($product_categories)) {
		echo '<ul class="product-categories">';

		// "All Products" посилання
		$shop_class = is_shop() ? ' class="current"' : '';
//		echo '<li' . $shop_class . '>
//                    <a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '">
//                        <img src="' . esc_url(get_template_directory_uri() . '/assets/images/icons/all_products.svg') . '" alt="All products">All Products
//                    </a>
//                  </li>';
//		error_log('Displayed "All Products" link.');

		// Перебір категорій
		foreach ($product_categories as $category) {
			// Отримання мета-поля 'visible_roles' для категорії
			$visible_roles = get_term_meta($category->term_id, 'visible_roles', true);
			error_log('Category: ' . $category->name . ' | visible_roles: ' . print_r($visible_roles, true));

			// Перевірка видимості категорії на основі ролі користувача
			$is_visible = false;
			if ( ! empty($visible_roles) ) {
				if ( ! is_array($visible_roles) ) {
					$visible_roles = array($visible_roles);
				}

				// Отримання ролей користувача
				if ( is_user_logged_in() ) {
					$user = wp_get_current_user();
					$user_roles = (array) $user->roles;
				} else {
					$user_roles = array('guest');
				}

				// Перевірка перетину ролей
				if ( array_intersect( $user_roles, $visible_roles ) ) {
					$is_visible = true;
				}
			} else {
				// Якщо visible_roles не встановлено, категорія видима всім
				$is_visible = true;
			}

			// Логування видимості категорії
			error_log('Category "' . $category->name . '" is ' . ($is_visible ? 'visible' : 'hidden') . ' for user.');

			if ( $is_visible ) {
				$thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
				$image_url = wp_get_attachment_url($thumbnail_id);
				$current_class = is_product_category($category->slug) ? ' class="current"' : '';

				echo '<li' . $current_class . '><a href="' . esc_url(get_term_link($category)) . '">';
				if ($image_url) {
					echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($category->name) . '"> ';
				}
				echo esc_html($category->name) . '</a></li>';

				error_log('Displayed Category: ' . $category->name);
			}
		}

		echo '</ul>';
	} else {
		echo '<p>' . esc_html__('Немає доступних категорій для вашої ролі.', 'your-text-domain') . '</p>';
		error_log('No categories available for the current user role.');
	}

	// Логування завершення функції
	error_log('--- WooCommerce Archive Description Hook Completed ---');
}, 5);




/**
 * 11. Remove tabs from My Account page
 */
add_filter('woocommerce_account_menu_items', function($items) {
  unset($items['dashboard'], $items['downloads'], $items['edit-address'], $items['edit-account']);
  return $items;
}, 999);


/**
 * 12. Redirect to login if not logged in
 *     After login, redirect based on order status
 */
add_action('template_redirect', function() {
  if (!is_user_logged_in() && !is_page('my-account')) {
    wp_redirect(wc_get_page_permalink('myaccount'));
    exit;
  }
});



// Function to add custom class to body
function add_my_account_login_class($classes) {
  if (is_account_page() && !is_user_logged_in()) {
    $classes[] = 'myAccount-login-page';
  }
  return $classes;
}
// Hook the function to the body_class filter
add_filter('body_class', 'add_my_account_login_class');

add_filter('woocommerce_login_redirect', 'custom_login_redirect', 10, 2);

function custom_login_redirect($redirect, $user) {
	if (!empty($user->ID)) {
		return home_url(); // Редірект на головну сторінку
	}

	return $redirect; // У разі помилки використовуємо стандартний редірект
}


/**
 * Redirect after login based on order status
 */
//add_filter('woocommerce_login_redirect', function($redirect_to, $user) {
//  $customer_orders = wc_get_orders(array(
//      'customer_id' => $user->ID,
//      'limit' => 1,
//  ));
//
//  if (empty($customer_orders)) {
//    return home_url();
//  } else {
//    return wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount'));
//  }
//}, 10, 2);

/**
 * Використовуємо стандартне повідомлення WooCommerce
 * і замінюємо лише кнопку “View cart” на “Return to shop”.
 */
add_filter( 'wc_add_to_cart_message_html', 'custom_return_to_shop_button', 10, 2 );
function custom_return_to_shop_button( $message, $products ) {
	// URL сторінки магазину
	$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );

	// Формуємо кнопку “Return to shop”
	$button_text = __( 'Return to shop', 'your-text-domain' );
	$return_button = sprintf(
		' <a href="%1$s" class="button wc-forward">%2$s</a>',
		esc_url( $shop_page_url ),
		esc_html( $button_text )
	);

	// За допомогою регулярного виразу шукаємо й вилучаємо
	// оригінальне посилання (кнопку) WooCommerce на кошик
	// (яке містить class="button wc-forward")
	$message = preg_replace( '/<a.*?class="button wc-forward".*?<\/a>/', '', $message );

	// Додаємо власну кнопку "Повернутися до магазину" (Return to shop)
	$message .= $return_button;

	return $message;
}
