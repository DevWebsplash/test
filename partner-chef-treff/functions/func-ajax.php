<?php //function ts_load_more_scripts() {
//
//  global $wp_query;
//
//  // In most cases it is already included on the page and this line can be removed
//  wp_enqueue_script( 'jquery' );
//
//  // register our main script but do not enqueue it yet
//  wp_register_script( 'ts_loadmore', get_template_directory_uri() . '/assets/js/ts_loadmore.js', array( 'jquery' ) );
//
//  // now the most interesting part
//  // we have to pass parameters to myloadmore.js script but we can get the parameters values only in PHP
//  // you can define variables directly in your HTML but I decided that the most proper way is wp_localize_script()
//  wp_localize_script( 'ts_loadmore', 'ts_loadmore_params', array(
//      'ajaxurl'      => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
//      'posts'        => json_encode( $wp_query->query_vars ), // everything about your loop is here
//      'current_page' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
//      'max_page'     => $wp_query->max_num_pages
//  ) );
//
//  wp_enqueue_script( 'ts_loadmore' );
//}
//
//add_action( 'wp_enqueue_scripts', 'ts_load_more_scripts' );
//
//
//function ts_loadmore_ajax_handler() {
//
//  // prepare our arguments for the query
//  $args                = json_decode( stripslashes( $_POST['query'] ), true );
//  $args['paged']       = $_POST['page'] + 1; // we need next page to be loaded
//  $args['post_status'] = 'publish';
//  $PozCat              = get_category( get_query_var( 'cat' ) );
//  // it is always better to use WP_Query but not here
//  query_posts( $args );
//
////  query_posts('cat=' . $PozCat->cat_ID);
//  if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<!---->
<!---->
<!--  --><?php
//  endwhile;
//  endif;
//
//  die; // here we exit the script and even no wp_reset_query() required!
//}
//
//
//add_action( 'wp_ajax_loadmore', 'ts_loadmore_ajax_handler' ); // wp_ajax_{action}
//add_action( 'wp_ajax_nopriv_loadmore', 'ts_loadmore_ajax_handler' ); // wp_ajax_nopriv_{action}
