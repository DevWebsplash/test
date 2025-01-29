<?php get_header();
/* Template Name: Home */
?>

  <section class="s-hero align-center section">
    <div class="cn">
      <h1 class="h1-hidden"><?php the_title(); ?></h1>
      <div class="s-hero__title h1"><?php echo get_field ('hero_title'); ?></div>
      <div class="s-hero__video">
<!--        <img src="--><?php //echo get_template_directory_uri(); ?><!--/assets/images/instruction-video-placeholder.png" alt="">-->
        <video id="instruction-video" width="320" height="240"
	        <?php $image_repeater = get_field ('hero_poster'); ?>
               poster="<?php echo esc_url ($image_repeater[ 'url' ]); ?>">
          <source src="<?php echo get_field ('hero_video'); ?>" type="video/mp4">
<!--          <source src="--><?php //echo get_template_directory_uri(); ?><!--/assets/video/movie.mp4" type="video/mp4">-->
<!--          <source src="--><?php //echo get_template_directory_uri(); ?><!--/assets/video/movie.ogg" type="video/ogg">-->
	        <?php echo get_field ('hero_text_if_video_cant_load'); ?>
        </video>
      </div>
      <div class="h4 text-center"><?php echo get_field ('hero_text_under_video'); ?></div>

    </div>
  </section>

  <section class="s-steps section">
      <div class="cn">
	      <?php $i=0;
          if (have_rows ('steps')): $i++;?>
		      <?php while (have_rows ('steps')) : the_row (); ?>
			      <?php $image_repeater = get_sub_field ('image'); ?>

                  <div class="s-steps__item">
                      <div class="h3">STEP <?php echo $i;?></div>
                      <div class="h4"><?php echo get_sub_field ('title'); ?></div>
                      <figure class="instruction">
                          <img src="<?php echo esc_url ($image_repeater[ 'url' ]); ?>" loading="lazy"  alt="<?php echo esc_attr ($image_repeater[ 'alt' ]); ?>">
                      </figure>
                      <p><?php echo get_sub_field ('descriptions'); ?></p>
                  </div>
		      <?php endwhile; ?>
	      <?php endif; ?>

      </div>
      <div class="cn text-center">
        <p class="cta-line"><span class="h4"><?php echo get_field ('steps_ready_button_text'); ?></span>
	        <?php $link = get_field ('steps_ready_button');
	        if ($link):
		        $link_url = $link[ 'url' ];
		        $link_title = $link[ 'title' ];
		        $link_target = $link[ 'target' ] ? $link[ 'target' ] : '_self';
		        ?>
              <a  href="<?php echo esc_url ($link_url); ?>" class="btn btn--primary" <?php echo esc_attr ($link_target); ?>><?php echo esc_html ($link_title); ?></a>
	        <?php endif; ?>
        </p>
      </div>
      <div class="cn text-center">
        <p class="support-line">Or <a href="mailto:konrad@chef-treff.de" class="link" target="_blank">reach out to Support
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path d="M17.9996 6H8.28711C8.0882 6 7.89743 5.92098 7.75678 5.78033C7.61613 5.63968 7.53711 5.44891 7.53711 5.25C7.53711 5.05109 7.61613 4.86032 7.75678 4.71967C7.89743 4.57902 8.0882 4.5 8.28711 4.5H18.7496C18.9485 4.5 19.1393 4.57902 19.2799 4.71967C19.4206 4.86032 19.4996 5.05109 19.4996 5.25V15.75C19.4996 15.9489 19.4206 16.1397 19.2799 16.2803C19.1393 16.421 18.9485 16.5 18.7496 16.5C18.5507 16.5 18.3599 16.421 18.2193 16.2803C18.0786 16.1397 17.9996 15.9489 17.9996 15.75V6Z" fill="#E71869"/>
              <path d="M18.219 4.71897C18.3598 4.57814 18.5508 4.49902 18.75 4.49902C18.9491 4.49902 19.1401 4.57814 19.281 4.71897C19.4218 4.8598 19.5009 5.05081 19.5009 5.24997C19.5009 5.44913 19.4218 5.64014 19.281 5.78097L6.53097 18.531C6.46124 18.6007 6.37846 18.656 6.28735 18.6938C6.19624 18.7315 6.09859 18.7509 5.99997 18.7509C5.90135 18.7509 5.8037 18.7315 5.7126 18.6938C5.62149 18.656 5.5387 18.6007 5.46897 18.531C5.39924 18.4612 5.34392 18.3785 5.30619 18.2873C5.26845 18.1962 5.24902 18.0986 5.24902 18C5.24902 17.9014 5.26845 17.8037 5.30619 17.7126C5.34392 17.6215 5.39924 17.5387 5.46897 17.469L18.219 4.71897Z" fill="#E71869"/>
            </svg></a> If you have any question.</p>
      </div>
  </section>


<?php get_footer(); ?>
