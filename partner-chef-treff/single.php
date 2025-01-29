<?php
/**
 * Default post template
 *
 * @author   <Author>
 * @version  1.0.0
 * @package  <Package>
 */

get_header();
?>
	<article class="single-post"  data-scroll-section>
    <div class="cn cn--md">
        <?php the_content(); ?>
    </div>
	</article>
<?php
get_footer();
