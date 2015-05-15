<?php 
	/**
	 * Template Name: Home Page
	 */
?>
<?php get_header();?>
	
			<section id="main-content">
			    <div class="container">
			    	<div class="row">
			    		<div class="col-xs-12">
			    			<div class="home-logo">
			    				<img src="<?php echo $bagmasterpiece['home-logo']['url']?>" width="" height="">
			    			</div>
			    		</div>
			    	</div>
			        <div class="row">
			        	<div class="home-featured-boxes">			            
			            	<?php dynamic_sidebar( 'home-category-widget' ); ?>
			            </div>			            
			        </div>			        
			    </div>
			</section>			
	
<?php get_footer();?>	