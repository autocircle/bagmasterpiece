<?php get_header(); ?>

<section id="main-content">
<div class="container">
 
            <?php while ( have_posts() ) : the_post(); ?>
 
                
 
                <?php get_template_part( 'content', 'single' ); ?>
 
                <?php //tod_content_nav( 'nav-below' ); ?>
 
                <?php
                    // If comments are open or we have at least one comment, load up the comment template
                    if ( comments_open() || '0' != get_comments_number() )
                        comments_template( '', true );
                ?>
 
            <?php endwhile; // end of the loop. ?>
</div>
</section>
<?php get_footer(); ?>