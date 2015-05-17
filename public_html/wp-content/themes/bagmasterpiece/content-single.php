<?php
/**
 * @package Tod
 * @since Tod 1.0
 */
?>
 
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <h1 class="entry-title"><?php the_title(); ?></h1>
 
        <div class="entry-meta">
            <?php //tod_posted_on(); ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->
 
    <div class="entry-content">
        <?php the_content(); ?>
        <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'tod' ), 'after' => '</div>' ) ); ?>
    </div><!-- .entry-content -->
 
    <footer class="entry-meta">

    </footer><!-- .entry-meta -->
</article>