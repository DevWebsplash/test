<?php
/**
 * Default page template
 *
 * @author   <Author>
 * @version  1.0.0
 * @package  <Package>
 */


?>
<?php
get_header ();
if (is_woocommerce () || is_checkout () || is_checkout_pay_page ()) {
  ?>
  <div class="block-woocommerce">
    <header>
      <div class="cn">
        <h1 class="block__title"><?php the_title (); ?></h1>
      </div>
    </header>
    <div class="cn">
    <?php the_content (); ?>
    </div>
  </div>
  <?php
} else if (is_cart ()) {
    ?>
  <div class="block-woocommerce">
    <header>
      <div class="cn">
        <h1 class="block__title">YOUR CART</h1>
      </div>
    </header>
    <div class="cn">
      <?php the_content (); ?>
    </div>
  </div>
  <?php
} else if (is_account_page () && is_user_logged_in()) {
  ?>
  <header class="woo-account-head">
    <div class="cn">
      <h1 class="xl"><?php the_title (); ?></h1>
    </div>
  </header>
  <div class="block-woocommerce">
    <div class="cn">
      <?php the_content (); ?>
    </div>
  </div>
  <?php
} else if (is_account_page() && !is_user_logged_in()) { ?>
  <div class="block-woocommerce myAccount-login">
    <div class="cn">
      <div class="myAccount-login__wrapper">
        <figure class="myAccount-login__logo">
          <img src="<?php echo get_stylesheet_directory_uri (); ?>/assets/images/logo.svg" alt="partner chef-treff logo">
        </figure>
        <?php the_content(); ?>
      </div>

    </div>
  </div>
  <?php
} else {
  ?>
  <article class="2">
    <header>
      <h1><?php the_title (); ?></h1>
    </header>
    <?php the_content (); ?>
  </article>
  <?php
}
get_footer ();
