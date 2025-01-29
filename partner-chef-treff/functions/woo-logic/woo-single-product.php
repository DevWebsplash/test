<?php
/**
 * Single Product functions
 *
 * @version  1.0.0
 * @package  <Package>
 */

// Remove the short description field from the product edit screen
add_action ('add_meta_boxes', function () {
  remove_meta_box ('postexcerpt', 'product', 'normal');
}, 999);

// Remove all product tabs
add_filter ('woocommerce_product_tabs', function ($tabs) {
  unset($tabs[ 'description' ], $tabs[ 'additional_information' ], $tabs[ 'reviews' ]);
  return $tabs;
}, 98);

// Remove default WooCommerce hooks
remove_action ('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action ('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action ('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

// Add custom hooks in the desired order
add_action ('woocommerce_single_product_summary', 'woocommerce_template_single_category', 2);
add_action ('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10);
add_action ('woocommerce_single_product_summary', 'woocommerce_template_single_attr', 11);
add_action ('woocommerce_single_product_summary', 'custom_price_and_stock_block', 20);

// Custom function to display product category
function woocommerce_template_single_category (){
  $product = wc_get_product (get_the_ID ());

  if ($product) {
    $categories = wc_get_product_category_list ($product->get_id (), ', ', '<p class="product__categories">', '</p>');
    if ($categories) {
      echo wp_kses_post ($categories);
    }
  }
}

// Custom function to display product attributes
function woocommerce_template_single_attr (){
  $product = wc_get_product (get_the_ID ());

  if ($product) {
    $attributes = $product->get_attributes ();
    if ($attributes) {
      echo '<div class="woocommerce-product-details__attributes">';

      foreach ($attributes as $attribute) {
        $label = wc_attribute_label ($attribute->get_name ());
        $value = $attribute->is_taxonomy () ? wc_get_product_terms ($product->get_id (), $attribute->get_name (), ['fields' => 'names']) : $attribute->get_options ();
        echo '<p class="product-attribute"><strong>' . esc_html ($label) . ':</strong> ' . esc_html (implode (', ', $value)) . '</p>';
      }
      echo '</div>';
    }
  }
}

// Add custom hook to display price and stock status inside .price__block container
function custom_price_and_stock_block (){
  global $product;
  $stock_quantity = $product->get_stock_quantity ();

  echo '<div class="price__block">';
  echo $product->is_in_stock () ? '<p class="stock in-stock"><strong>' . esc_html ($stock_quantity) . '</strong> <span>' . esc_html__ ('In stock', 'woocommerce') . '</span></p>' : '<p class="stock out-of-stock">' . esc_html__ ('Out of stock', 'woocommerce') . '</p>';
  echo '<div class="price-wrapper">';
  woocommerce_template_single_price ();
  echo '<span> Excl. VAT.</span>';
  echo '</div></div>';
}

