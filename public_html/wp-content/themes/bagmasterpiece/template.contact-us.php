<?php

/**
 * Template Name: Contact Us
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
			
				<div class="col-md-8 col-lg-8">
				
					<p class="msg-style">MESSAGE</p>				
					<form class="form-horizontal" role="form" method="" action="">
						<div class="form-group">
							<label for="name" class="col-sm-3 control-label msg-text">Name</label>
							<div class="col-sm-9">
								<input type="text" class="form-control msg-text-bg" id="name" name="name" placeholder="First & Last Name" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label msg-text">Email Address</label>
							<div class="col-sm-9">
								<input type="email" class="form-control msg-text-bg" id="email" name="email" placeholder="example@domain.com" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="message" class="col-sm-3 control-label msg-text">Description</label>
							<div class="col-sm-9">
								<textarea class="form-control msg-text-bg" rows="10" name="message"></textarea>
							</div>
						</div>											
					</form>					
				</div>				
				
				<div class="col-md-4 col-lg-4">
					<p class="msg-style">SITE MAP</p>
					<div class="site-map-area">
						<div class="map"></div>
						<div class="btn-group zoom" role="group" aria-label="...">
							<p>ZOOM</p>
							<button type="button" class="btn btn-default left-butn"><i class="fa fa-search-minus"></i></button>
							<button type="button" class="btn btn-default"><i class="fa fa-search-plus"></i></button>
						</div>
					</div>
					<form class="form-horizontal">
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label msg-text">From</label>
							<div class="col-sm-10">
								<input type="text" class="form-control msg-text-bg" id="name" name="name" placeholder="First & Last Name" value="">
							</div>
						</div>
						  
						<button type="submit" class="btn btn-default take-me"><img src="<?php echo get_template_directory_uri(); ?>/images/take-me.png" class="img-responsive" alt="Responsive image"></button>
					</form>										
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