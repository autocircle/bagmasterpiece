<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 */

global $bagmasterpiece;

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<script>(function(){document.documentElement.className='js'})();</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php //prefy($bagmasterpiece);?>

<!-- main page starts  -->
<div class="bagmasterpiece-wrap">
	<div class="<?php echo hero_header_class();?>">
		<div class="<?php echo hero_header_class();?>-content">
			<header id="main-header">
				<nav class="navbar navbar-inverse">
				  <div class="container-fluid">
				  	<div class="nav-wrap">
				    <!-- Brand and toggle get grouped for better mobile display -->
				    <div class="navbar-header">
				        <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
				       	  <?php get_image_from_option('primary-logo','BagMasterPiece');?>
				        </a>
				        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				          <span class="sr-only">Toggle navigation</span>
				          <span class="icon-bar"></span>
				          <span class="icon-bar"></span>
				          <span class="icon-bar"></span>
				        </button>
				    </div>

				    <!-- Collect the nav links, forms, and other content for toggling -->
				    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				        <?php bootstrap_menu('visitor'); ?>
				    </div><!-- /.navbar-collapse -->
				    <div class="pull-right social-fb">
				    	<?php facebook_share_box()?>
				    </div>
				    </div>
				  </div><!-- /.container-fluid -->


				  <?php if( is_user_logged_in( ) ):?>

    				  <?php

        				  global $current_user;

        				  wp_get_current_user();

        				  $name = get_user_meta( get_current_user_id(), 'first_name', true);
        				  //$name .= ' ';
        				  //$name .= get_user_meta( get_current_user_id(), 'last_name', true);

        				  $name = $name == '' ? $current_user->get('display_name') : $name;

        				  $role = '';
        				  $class = '';

        				  if( current_user_can('regular') ){
        				      $role = 'Regular Member';
        				  }
        				  elseif( current_user_can('vip') ){
        				      $role = 'VIP Member';
        				      $class = 'red';
        				  }
        				  elseif( current_user_can('Special') ){
        				      $role = 'Special Member';
        				      $class = 'red';
        				  }
    				  ?>

    				  <div class="container-fluid">
    				  	<div class="nav-wrap">
    				    <!-- Brand and toggle get grouped for better mobile display -->
    				    <div class="navbar-header">
    				        <span class="role <?php echo $class;?>"><?php echo $role;?></span> <span class="colon">:</span> <span class="name">Hello <?php echo $name;?></span>
    				        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
    				          <span class="sr-only">Toggle navigation</span>
    				          <span class="icon-bar"></span>
    				          <span class="icon-bar"></span>
    				          <span class="icon-bar"></span>
    				        </button>
    				    </div>

    				    <!-- Collect the nav links, forms, and other content for toggling -->
    				    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
    				        <div class="secondary-nav">
    				        <?php
        				        if( current_user_can('regular') ){
        				            global $bagmasterpiece;
        				            ?>
        				            <ul id="menu-secondary" class="nav navbar-nav navbar-right">
                                        <li class="pull-left"><a class="red" href="<?php echo get_permalink($bagmasterpiece['sms-verification-page-id']);?>">Upgrade to VIP</a></li>
                                        <li class="pull-right"><a class="" href="<?php echo wp_logout_url( home_url() );?>">Logout</a></li>
                                    </ul>
                                    <?php
        				        }
        				        else{

        				            global $bagmasterpiece;

        				            $profile_page_id = $bagmasterpiece['profile-page-id'];

        				            $concierge = $bagmasterpiece['concierge-page-id'] ? get_permalink( $bagmasterpiece['concierge-page-id'] ) : '';
        				            $consignment = $bagmasterpiece['consignment-page-id'] ? get_permalink( $bagmasterpiece['consignment-page-id'] ) : '';

        				            $profile = '#profile';
        				            $subscriptions = '#subscriptions';
        				            $orders = '#orders';
        				            $accounts = '#accounts';
        				            $change_pass = '#change-pass';

        				            $current_page_id = get_queried_object_id();

        				            if( $profile_page_id != $current_page_id ){

        				                $profile = get_permalink( $bagmasterpiece['profile-page-id'] );
        				                $subscriptions = add_query_arg( array('tab'=>'subscriptions'), $profile);
        				                $orders = add_query_arg( array('tab'=>'orders'), $profile);
        				                $accounts = add_query_arg( array('tab'=>'accounts'), $profile);
        				                $change_pass = add_query_arg( array('tab'=>'change-pass'), $profile);
        				            }

        				            ?>
        				            <ul id="menu-secondary" class="nav navbar-nav navbar-right">
        				                <li role="presentation"><a href="<?php echo $profile;?>">Profile</a></li>
                                	    <li role="presentation"><a href="<?php echo $concierge;?>">Concierge</a></li>
                                	    <li role="presentation"><a href="<?php echo $consignment;?>">Consignment</a></li>
                                	    <li role="presentation"><a href="<?php echo $orders;?>">Orders</a></li>
                                	    <li class="pull-right"><a class="" href="<?php echo wp_logout_url( home_url() );?>">Logout</a></li>
        				            </ul>
        				            <?php
        				        }
    				        ?>
    				        </div>
    				        <?php //bootstrap_menu('visitor'); ?>
    				    </div><!-- /.navbar-collapse -->
    				    </div>
    				  </div><!-- /.container-fluid -->

				  <?php endif;?>

				</nav>
			</header>