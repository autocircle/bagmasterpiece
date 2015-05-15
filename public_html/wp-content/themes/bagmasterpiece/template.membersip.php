<?php
	/**
	 * Template Name: Membership
	 */

global $bagmasterpiece;

?>
<?php get_header();?>

			<section id="main-content">
			    <div class="container">
			        <div class="membership-featured-boxes">
			        	<div class="row">
			        		<div class="col-md-12">
			        			<div class="page-main">
									<h1 class="entry-title"><?php the_title();?></h1>
								</div>
							</div>
			            	<?php dynamic_sidebar( 'membership-page-widget' ); ?>
			            </div>
			        </div>
			    </div>
			</section>


	<!-- Modal -->
<div class="modal fade" id="membershipPage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
    		<div class="modal-body">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      		<div class="row">
	      			<div class="col-xs-6">
	      				<img src="<?php echo get_template_directory_uri()?>/images/after_ogin_image.png">
	      			</div>
	      			<div class="col-xs-6">
	      				<div class="text-center">
		      				<h3>Welcome to</h3>
		      				<h1>BagMasterPiece</h1>
		      				<p class="sub">Become a VIP member by completing your profile</p>
		      				<p><a class="btn btn-primary btn-custom-profile" href="<?php echo get_permalink($bagmasterpiece['profile-page-id']);?>">Member's Profile</a></p>
		      				<p><a class="text-muted" href="#" data-dismiss="modal">Fill up Profile Later</a></p>
		      			</div>
	      			</div>
	      		</div>
      		</div>
    	</div>
  	</div>
</div>
	<!-- Modal -->
<div class="modal fade" id="LoginMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
    		<div class="modal-body">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      		<div class="row">
	      			<div class="col-xs-6">
	      				<img src="<?php echo get_template_directory_uri()?>/images/after_ogin_image.png">
	      			</div>
	      			<div class="col-xs-6">
	      				<h3>Welcome to</h3>
	      				<h1>BagMasterPiece</h1>
	      				<button type="button" class="btn btn-default" data-dismiss="modal">Fill up Profile Later</button>
	      			</div>
	      		</div>
      		</div>
    	</div>
  	</div>
</div>


<script type="text/javascript">
	jQuery(document).ready(function($){

		<?php if( isset($_GET['message']) ):?>
			$('#LoginMessage').modal('show');
		<?php elseif( current_user_can('regular') ):?>
			$('#membershipPage').modal('show');
		<?php endif;?>
	});

</script>

<?php get_footer();?>