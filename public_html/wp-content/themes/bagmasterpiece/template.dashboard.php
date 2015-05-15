<?php
	/**
	 * Template Name: Dashboard
	 */

global $bagmasterpiece;

?>

<?php get_header();?>

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

        		<div id="theme-my-login" class="profile">
        		      <div class="membership-type">
        		          <p class="text-center">
                			<?php
                			     if( current_user_can('regular') ){
                			         echo 'Regular Member';
                			     }
                			     elseif( current_user_can('pending') ){
                			         echo 'Pending Membership';
                			     }
                			     elseif( current_user_can('vip') ){
                			         echo 'VIP Member';
                			     }
                			     elseif( current_user_can('Special') ){
                			         echo 'Special Member';
                			     }
                			     else{
                			         echo 'System Member';
                			     }
                			?>
        			     </p>
        		      </div>
        		      <div class="row">

        		        <div class="col-md-12">
                		  <!-- Nav tabs -->
                		  <ul class="nav nav-tabs" role="tablist">
                		    <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">My Profile</a></li>
                		    <li role="presentation"><a href="#concierge" aria-controls="accounts" role="tab" data-toggle="tab">My Concierge</a></li>
                		    <li role="presentation"><a href="#consignment" aria-controls="orders" role="tab" data-toggle="tab">My Consignment</a></li>
                		    <li role="presentation"><a href="#subscriptions" aria-controls="orders" role="tab" data-toggle="tab">My Subscriptions</a></li>
                		    <li role="presentation"><a href="#orders" aria-controls="orders" role="tab" data-toggle="tab">My Orders</a></li>
                		    <li role="presentation"><a href="#billing-shipping" aria-controls="orders" role="tab" data-toggle="tab">Billing &amp; Shipping</a></li>
                		    <li role="presentation"><a href="#change-pass" aria-controls="orders" role="tab" data-toggle="tab">Change Password</a></li>
                		  </ul>
                		</div>
                		<div class="tab-content">
		                  <div role="tabpanel" class="tab-pane active" id="profile">



		                  </div>
		                  <div role="tabpanel" class="tab-pane" id="concierge"></div>
		                  <div role="tabpanel" class="tab-pane" id="consignment"></div>
		                  <div role="tabpanel" class="tab-pane" id="subscriptions"></div>
		                  <div role="tabpanel" class="tab-pane" id="orders">
            		    	<div class="col-md-12">
            		    		<?php wc_get_template( 'myaccount/my-orders.php' ); ?>
            		    	</div>
            		      </div>
		                  <div role="tabpanel" class="tab-pane" id="billing-shipping">
		                      <div class="col-md-12">
            					<?php wc_get_template( 'myaccount/my-address.php' ); ?>
            				  </div>
		                  </div>
		                  <div role="tabpanel" class="tab-pane" id="change-pass"></div>


		                </div>

        		      </div>
        		    </div>
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