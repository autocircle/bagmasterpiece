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
				</nav>				
			</header>	