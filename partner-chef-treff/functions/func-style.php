<?php
/**
 * Style functions
 *
 * @author   <Author>
 * @version  1.0.0
 * @package  <Package>
 */

/**
 * Enqueue theme styles.
 */
function gulp_wp_theme_styles ()
{

  /**
   * Set a script handle prefix based on theme name.
   * Note that this should be the same as the `themePrefix` var set in your Gulpfile.js.
   */
  $theme_handle_prefix = 'pct';

//  wp_enqueue_style(
//      'swiper-css',
//      'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css',
//      array(),
//      '11.0.6',
//      'all'
//  );
  wp_enqueue_style ($theme_handle_prefix . '-styles', get_template_directory_uri () . '/assets/css/' . $theme_handle_prefix . '.min.css', array(), '1.0.0', 'all');
}

add_action ('wp_enqueue_scripts', 'gulp_wp_theme_styles');

function is_login_page() {
  if (basename($_SERVER['PHP_SELF']) == 'wp-login.php') {
    echo '
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
        ';
  }
}
add_action('login_enqueue_style()', 'is_login_page');

function custom_login_styles ()
{
  echo '<style>
        body.login {
          display: flex;
          align-items: center;
          justify-content: center;
          
          background-image: url(' . get_stylesheet_directory_uri () . '/assets/images/login-bg.png);
          background-size: auto;
          background-repeat: repeat-x;
          background-position: top center;
        }
        body.login #login {
          display: flex;
          padding: 75px 50px;
          flex-direction: column;
          justify-content: center;
          align-items: center;
          gap: 0;
          
          border-radius: 32px;
          background-color: #FFF;
          box-shadow: 0px 4px 32px 0px rgba(25, 25, 84, 0.08);
        }
        body.login .message {
          margin-bottom: 0;
        }
        body.login h1 a {
          width: 160px;
          height: 128px;
          background-image: url(' . get_stylesheet_directory_uri () . '/assets/images/logo.svg);
          background-size: 160px 128px;
        }
        body.login form {
          background: rgba(255, 255, 255, 0.8);
          padding: 0;
          margin-top: 0;
          border: none;
          border-radius: 0;
          box-shadow: none;
        }
        body.login label {
          font-family: "Poppins", sans-serif;
          color: rgba(25, 25, 84, 0.60);
        }
        body.login form .input {
          width: 100%;
          margin-right: 0;
          padding: 10px 12px;
          border: 2px solid rgba(25, 25, 84, 0.32);
          background-color: #fff;
          font-family: "Poppins", sans-serif;
          font-size: 16px; 
          font-weight: 400;
          color: #191954;
        }
        body.login #login form p.forgetmenot {
          float: none;
        }
        body.login #login form p.submit {
          display: flex;
          align-items: center;
          justify-content: center;
          margin-top: 20px;
        }
        body.login #wp-submit {
          padding: 6px 32px;
          background: #E71869;
          border-color: #E71869;
          border-radius: 8px;
          font-size: 16px;
          color: #fff;
          line-height: 1.6;
          text-shadow: none;
          box-shadow: none;
        }
        body.login #wp-submit:hover {
          background: #E71869;
          border-color: #E71869;
        }
    </style>';
}

add_action ('login_enqueue_scripts', 'custom_login_styles');
