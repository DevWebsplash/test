<?php
/**
 * Script functions
 *
 * @author   <Author>
 * @version  1.0.0
 * @package  <Package>
 */

/**
 * Enqueue theme scripts
 */
function gulp_wp_theme_scripts() {

	/**
	 * Set a script handle prefix based on theme name.
	 * Note that this should be the same as the `themePrefix` var set in your Gulpfile.js.
	 */
	$theme_handle_prefix = 'pct';

	/**
	 * Enqueue common scripts.
	 */

  /**
   * Pages with carousel.
   */

  if ( function_exists( 'is_product' ) && is_product() ) {
    wp_enqueue_script(
        'swiper-js',
        get_template_directory_uri() . '/assets/js/libs/swiper-bundle.min.js',
        array(),
        '11.0.6',
        true
    );
  }

  wp_enqueue_script( $theme_handle_prefix . '-scripts', get_template_directory_uri() . '/assets/js/' . $theme_handle_prefix . '.min.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'gulp_wp_theme_scripts' );



