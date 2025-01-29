<?php
/**
 * Footer template
 *
 * @author   <Author>
 * @version  1.0.0
 * @package  <Package>
 */
?>

</main>
<!--POPUPS-->
<!--<div id="template-popup" class="template-popup mfp-hide">-->
<!---->
<!--</div>-->

<footer class="footer text-center">
  <div class="cn">
    <div class="footer-bottom">
      <span><?php echo get_field ('footer_copyright', 'options');?></span>
    </div>
  </div>
</footer>
</div>


<?php wp_footer(); ?>
<script type="text/javascript">
  window.onload = function () {
    jQuery('#loader-wrapper').fadeOut(300);
  };
</script>
<?php if (is_home() || is_front_page()) : ?>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
    let video = document.getElementById('instruction-video');
    video.addEventListener('click', function () {
      if (video.readyState >= 3) {
        video.play();
        video.setAttribute('controls', 'controls');
      } else {
        video.addEventListener('canplay', function () {
          video.play();
          video.setAttribute('controls', 'controls');
        }, { once: true });
      }
    });
  });
</script>
<?php endif; ?>
</body>
</html>
