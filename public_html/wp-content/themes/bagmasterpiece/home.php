<?php 
	/**
	 * Template Name: Blog Page
	 */
?>
<?php get_header();?>

<div class="container-fluid">
	<div class="row">
		<div class="_col-xs-12">
			<div class="image-block"><?php get_image_from_option('blog-header','BagMasterPiece');?></div>
			<div class="title-block">
				<div class="blog-logo-block"><?php get_image_from_option('blog-logo','BagMasterPiece');?></div>
				<div class="blog-submenu"><?php wp_nav_menu(array('theme_location'=>'blog'));?></div>
			</div>
		</div>
	</div>
</div>	
<div class="clearfix"></div>
<div id="main-content" class="main-content container">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<div class="row">
				<div class="col-lg-9">
				
					<?php			
						if(have_posts()): 
						
							while ( have_posts() ) :
							 
								the_post();
								get_template_part( 'content', 'blog' );				
								
							endwhile;
							
							wp_bootstrap_pagination();
							
		                else :                                    
							get_template_part( 'content', 'none' );
		                endif;
		                                
					?>
				
				</div>
				<div class="col-lg-3">
					<?php get_sidebar();?>
				</div>
			</div>			
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->			
	
<?php get_footer();?>	