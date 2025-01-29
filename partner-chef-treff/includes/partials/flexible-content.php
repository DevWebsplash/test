<?php
if ( have_rows( 'gallery_content' ) ):

    // Loop through rows.
    while ( have_rows( 'gallery_content' ) ) : the_row();
        if ( get_row_layout() == '2_links_and_static_image' ):?>




        <?php endif;
    endwhile;
endif; ?>
