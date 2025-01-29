<?php
/**
 * Admin functions
 *
 * @author   <Author>
 * @version  1.0.0
 * @package  <Package>
 */

/**
 * Credit in admin footer
 */
function gulp_wp_admin_footer() {
	echo 'Developed by <a href="http://author.com" target="_blank" rel="noreferrer noopener">Author Name</a>';
}
add_filter( 'admin_footer_text', 'gulp_wp_admin_footer' );

/**
 * Change default greeting
 */
function gulp_wp_greeting( $wp_admin_bar ) {
	$user_id = get_current_user_id();
	$current_user = wp_get_current_user();
	$profile_url = get_edit_profile_url( $user_id );

	if ( 0 !== $user_id ) {
		$avatar = get_avatar( $user_id, 28 );
		$howdy = sprintf( __( 'Hi, %1$s' ), $current_user->display_name );
		$class = empty( $avatar ) ? '' : 'with-avatar';

		$wp_admin_bar->add_menu(array(
			'id' => 'my-account',
			'parent' => 'top-secondary',
			'title' => $howdy . $avatar,
			'href' => $profile_url,
			'meta' => array(
				'class' => $class,
			),
		));
	}
}
add_action( 'admin_bar_menu', 'gulp_wp_greeting', 11 );
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page();
}
add_action('admin_enqueue_scripts', 'fix_select2_error', 99);

function fix_select2_error() {
	wp_deregister_script('select2');
	wp_register_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);
	wp_enqueue_script('select2');
}


// Hide admin bar and disable admin panel access for users who are not administrators or editors
add_action('after_setup_theme', function() {
  // Якщо користувач не адміністратор і не редактор...
  if (!current_user_can('administrator') && !current_user_can('editor')) {
    // Приховуємо адмін-бар
    add_filter('show_admin_bar', '__return_false');

    // Блокуємо доступ до wp-admin, окрім AJAX
    add_action('admin_init', function() {
      // Якщо це НЕ AJAX-запит, і користувач не адміністратор/редактор → редірект
      if (!wp_doing_ajax()) {
        wp_redirect(home_url());
        exit;
      }
    });
  }
});

// Add 'customer' to body class
add_filter('body_class', function($classes) {
  if (!current_user_can('administrator') && !current_user_can('editor')) {
    $classes[] = 'customer';
  }
  return $classes;
});
