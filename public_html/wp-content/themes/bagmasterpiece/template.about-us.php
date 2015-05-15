<?php

/**
 * Template Name: About Us
 */
?>

<?php get_header();?>

<section id="main-content">
	    
        <?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
            
        
     <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			
			<?php $value = get_post_meta( get_the_ID(), '_header_title_bg', true );?>
			
			<?php if( $value != '' ):?>
			<div class="page-header-image">
				<img src="<?php echo $value;?>">
				<h1 class="entry-title"><?php the_title();?></h1>
			</div>
			<?php endif;?>
		</header>
		
		<div class="container">
			<?php if( $value == '' ):?>
			<h1 class="entry-title"><?php the_title();?></h1>
			<?php endif;?>
				
			<div class="entry-content">
				<?php the_content(); ?>
			</div><!-- .entry-content -->
		
			<div class="row about-padding">
				<div class="col-md-5 col-lg-5">
					<img src="<?php echo get_template_directory_uri()?>/images/pic-4.jpg" class="img-responsive about-img" alt="Responsive image">
				
				</div>
				<div class="col-md-7 col-lg-7 about-heading about-us-pad">
					<h3>ABOUT US</h3>
					<div class="about-text">
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
					</div>
				</div>
			</div>
			
			<div class="row about-padding">
			
				<div class="col-md-7 col-lg-7 about-heading">
					<h3>MEMBERS ONLY SHOPPING</h3>
					<div class="about-text">
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages.
					It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages.</p>
					</div>
				</div>
				<div class="col-md-5 col-lg-5">
					<img src="<?php echo get_template_directory_uri()?>/images/pic-5.jpg" class="img-responsive about-img" alt="Responsive image">
				</div>
			</div>
		</div>
		<footer class="entry-meta">
			
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
        
        
        <?php endwhile; else: ?>
            <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
        <?php endif; ?>
</section>

		

<?php get_footer();?>