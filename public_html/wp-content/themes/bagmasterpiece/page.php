<?php get_header(); ?>

<section id="main-content">
	    
        <?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
            
        
     <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			
			<?php $value = get_post_meta( get_the_ID(), '_header_title_bg', true );?>
			
			<?php if( $value != '' ):?>
			<div class="page-header-image">
				<div class="phc"></div>
				<img src="<?php echo $value;?>">
				<h1 class="entry-title"><?php the_title();?></h1>
			</div>
			<?php endif;?>
		</header>
		
		<div class="page-main">
			<?php if( $value == '' ):?>
			<h1 class="entry-title"><?php the_title();?></h1>
			<?php endif;?>
			
		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->
		</div>
		<footer class="entry-meta">
			
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
        
        
        <?php endwhile; else: ?>
            <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
        <?php endif; ?>
</section>

<?php get_footer();?>