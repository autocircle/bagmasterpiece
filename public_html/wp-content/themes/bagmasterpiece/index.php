<?php get_header(); ?>

<section id="main-content">
    <div class="container">
        <?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
            <?php get_template_part( 'content', get_post_format() ); ?>
        <?php endwhile; else: ?>
            <?php get_template_part('content','none');?>
        <?php endif; ?>
    </div>
</section>

<?php get_footer();?>