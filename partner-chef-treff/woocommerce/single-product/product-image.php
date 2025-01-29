<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.0.0
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$attachment_ids = $product->get_gallery_image_ids();

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	)
);
?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
  <div class="slider">
    <div class="slider__flex">
      <div class="slider__col">

        <div class="slider__prev">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
            <path d="M1.66699 11L11.0003 20.3334M11.0003 20.3334L20.3337 11M11.0003 20.3334V1.66669" stroke="#191954" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>

        <div class="slider__thumbs">
          <div class="swiper-container">
            <div class="swiper-wrapper">
              <?php
              if ( $attachment_ids ) {
                foreach ( $attachment_ids as $attachment_id ) {
                  $thumb_url = wp_get_attachment_image_url( $attachment_id, 'thumbnail' ); ?>
                  <div class="swiper-slide">
                    <div class="slider__image"><img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr(get_the_title($attachment_id)); ?>"></div>
                  </div>
                  <?php
                }
              } ?>
            </div>
          </div>
        </div>

        <div class="slider__next">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
            <path d="M1.66699 11L11.0003 20.3334M11.0003 20.3334L20.3337 11M11.0003 20.3334V1.66669" stroke="#191954" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>

      </div>

      <div class="slider__images">
        <div class="swiper-container">
          <div class="swiper-wrapper">
            <?php
            if ( $attachment_ids ) {
              foreach ( $attachment_ids as $attachment_id ) {
                $image_url = wp_get_attachment_image_url( $attachment_id, 'large' );
                ?>
                <div class="swiper-slide">
                  <div class="slider__image"><img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr(get_the_title($attachment_id)); ?>"></div>
                </div>
                <?php
              }
            } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
