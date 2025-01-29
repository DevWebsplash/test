<?php
/**
 * Header template
 *
 * @author   <Author>
 * @version  1.0.0
 * @package  <Package>
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="format-detection" content="telephone=no"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <!--  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">-->
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
  <meta name="apple-mobile-web-app-capable" content="yes"/>
  <meta name="mobile-web-app-capable" content="yes">
  <title><?php wp_title('&ndash;', true, 'right'); ?></title>
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">
<!-- LOADER -->
<div id="loader-wrapper"></div>

<div class="page-wrapper">
  <header class="header">
    <div class="cn">
      <div class="header__inner">
        <div class="header__logo">
	        <?php
	        $image_repeater = get_field('header_logo', 'options');
	        if ($image_repeater): // Перевіряємо, чи існує поле 'image'
		        if (is_front_page()): // Перевіряємо, чи це головна сторінка
			        ?>
                <img src="<?php echo esc_url($image_repeater['url']); ?>" alt="<?php echo esc_attr($image_repeater['alt']); ?>">
		        <?php
		        else: // Якщо це не головна сторінка, додаємо лінк
			        ?>
                <a href="/" title="Home page">
                    <img src="<?php echo esc_url($image_repeater['url']); ?>" alt="<?php echo esc_attr($image_repeater['alt']); ?>">
                </a>
		        <?php
		        endif;
	        endif;
	        ?>

        </div>

        <?php if (is_user_logged_in ()) : ?>
          <div class="header__action">
            <strong>Welcome,</strong>
            <a href="/my-account/orders/" class="username">
              <?php
              $current_user = wp_get_current_user();
              $first_name = $current_user->user_firstname;
              $display_name = $first_name ? $first_name : $current_user->display_name;
              echo esc_html ($display_name);
              ?>
            </a>
            <?php
            $customer_orders = wc_get_orders (array(
                'customer' => $current_user->ID,
                'limit' => 1, // Check if there is at least one order
            ));

            if (is_checkout ()) { ?>
	            <?php $link = get_field ('back_to_product_page', 'options');
	            if ($link):
		            $link_url = $link[ 'url' ];
		            $link_title = $link[ 'title' ];
		            $link_target = $link[ 'target' ] ? $link[ 'target' ] : '_self';
		            ?>
                  <a  href="<?php echo esc_url ($link_url); ?>" class="btn btn--primary" <?php echo esc_attr ($link_target); ?>><?php echo esc_html ($link_title); ?></a>
	            <?php endif; ?>

           <?php  } else {
              if (!empty($customer_orders)) {
	              ?>
	              <?php $link = get_field ('edit_your_order', 'options');
	              if ($link):
		              $link_url = $link[ 'url' ];
		              $link_title = $link[ 'title' ];
		              $link_target = $link[ 'target' ] ? $link[ 'target' ] : '_self';
		              ?>
                  <a  href="<?php echo esc_url ($link_url); ?>" class="btn btn--primary" <?php echo esc_attr ($link_target); ?>><?php echo esc_html ($link_title); ?></a>
	              <?php endif; ?>
	              <?php
              } else {
                if (WC ()->cart->is_empty ()) {
	                ?>
                    <span class="text"> <?php echo get_field ('text_before_button_start_ordering', 'options');?></span>
	                <?php $link = get_field ('start_ordering', 'options');
	                if ($link):
		                $link_url = $link[ 'url' ];
		                $link_title = $link[ 'title' ];
		                $link_target = $link[ 'target' ] ? $link[ 'target' ] : '_self';
		                ?>
                      <a  href="<?php echo esc_url ($link_url); ?>" class="btn btn--primary" <?php echo esc_attr ($link_target); ?>><?php echo esc_html ($link_title); ?></a>
	                <?php endif; ?>
	                <?php


                } else {
	                $link = get_field ('view_your_cart', 'options');
	                if ($link):
		                $link_url = $link[ 'url' ];
		                $link_title = $link[ 'title' ];
		                $link_target = $link[ 'target' ] ? $link[ 'target' ] : '_self';
		                ?>
                      <a  href="<?php echo esc_url ($link_url); ?>" class="btn btn--primary" <?php echo esc_attr ($link_target); ?>><?php echo esc_html ($link_title); ?></a>
	                <?php endif; ?>
	                <?php
                }
              }
            }
            ?>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </header>
  <main class="content">
