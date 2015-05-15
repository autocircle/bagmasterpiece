<?php

define( 'LIBPATH', get_template_directory() .'/lib/' );

//require_once (dirname(__FILE__) . '/lib/options/sample-config.php');
require_once (dirname(__FILE__) . '/lib/options/theme-config.php');

require_once (dirname(__FILE__) . '/lib/init.php');
require_once (dirname(__FILE__) . '/lib/shortcodes/shortcode.php');

//	init

add_role( 'regular', 'Regular User', array( 'regular_user' ) );
add_role( 'vip', 'Vip User', array( 'vip_user' ) );
add_role( 'Special', 'Special User', array( 'special_user' ) );

function add_theme_caps() {
	// gets the author role
	$role = get_role( 'administrator' );

	// This only works, because it accesses the class instance.
	// would allow the author to edit others' posts for current theme only

	//$role->add_cap( 'vip_user' );
	//$role->add_cap( 'special_user' );
	$role->add_cap( 'Special' );
	$role->add_cap( 'vip' );
}
add_action( 'init', 'add_theme_caps');

define("THEME_DIR", get_template_directory_uri());

//	 nav enus

register_nav_menus(array(
	'visitor' => __( 'Seperate menu for visitors only', 'woocommerce' ),
	'regular' => __( 'Seperate menu for regular logged in regular user', 'woocommerce' ),
	'vip' => __( 'Seperate menu for regular logged in VIP user', 'woocommerce' ),
	'special' => __( 'Seperate menu for regular logged in special user', 'woocommerce' ),
));

function BMP_wp_enqueue_scripts(){

	wp_register_style('dropzone', trailingslashit( get_template_directory_uri() ) .'assets/css/dropzone.css', '', '4.0');
	wp_register_script('dropzone', trailingslashit( get_template_directory_uri() ) .'assets/js/dropzone.js', '', '4.0', true);

	if( is_shop() || is_product_category() ){
		$assets_path = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';
		wp_enqueue_script( 'select2', $assets_path . 'js/select2/select2.min.js', array( 'jquery' ), '3.5.2' );
		wp_enqueue_style( 'select2', $assets_path . 'css/select2.css' );
	}

	wp_enqueue_script( 'functions', get_template_directory_uri() . '/assets/js/functions.js', array( 'jquery' ), '3.5.2' );

	if( is_product() ){
	    wp_enqueue_style( 'bxslider', get_template_directory_uri() . '/bxslider/jquery.bxslider.css' );
	    wp_enqueue_script( 'bxslider', get_template_directory_uri() . '/bxslider/jquery.bxslider.min.js', array( 'jquery' ), '4.1.2' );
	}

}

add_action('wp_enqueue_scripts','BMP_wp_enqueue_scripts');

//	metabox
// Add meta boxes in page template

function page_title_background()
{
	add_meta_box(
	'_header_title_bg', // $id
	'Title Background', // $title
	'upload_title_bg_link', // $callback
	'page', // $page
	'normal', // $context
	'high' // $priority
	);
}

add_action('admin_init', 'page_title_background');


function upload_title_bg_link( $post ) {

	wp_nonce_field( 'header_bg_img', 'myplugin_meta_box_nonce' );

	$value = get_post_meta( $post->ID, '_header_title_bg', true );

	?>
	<div>
		<label for="header_bg_img"><?php _e( 'Paste the image link here. Image size must be greater than 1300px', 'bagbikikini' );?></label>
		<p>
			<input type="text" id="header-bg-img" name="header_bg_img" value="<?php echo esc_attr( $value ); ?>" class="widefat" />
		</p>
	</div>
	<?php
}

function save_upload_title_bg_link( $post_id ) {

	if (
			( ! isset( $_POST['myplugin_meta_box_nonce'] )	or ! wp_verify_nonce( $_POST['myplugin_meta_box_nonce'], 'header_bg_img' )	)	||		// verify nonce
			( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )	||																					// bypass autosave
			( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] and ! current_user_can( 'edit_page', $post_id ) )	||					// verefy permission
			! isset( $_POST['header_bg_img'] )																										// check data
		)
		return;


	$my_data = sanitize_text_field( $_POST['header_bg_img'] );
	update_post_meta( $post_id, '_header_title_bg', $my_data );
}

add_action( 'save_post', 'save_upload_title_bg_link' );

function hero_header_class(){

	global $bagmasterpiece;

	$class = 'hero';

	if(
			is_front_page() || is_page_template( 'template.membersip.php' ) || is_page( $bagmasterpiece['login-page-id'] ) ||
			is_page( $bagmasterpiece['register-page-id'] ) ||  is_page( $bagmasterpiece['lostpass-page-id'] ) ||
			is_page( $bagmasterpiece['resetpass-page-id'] ) || is_page( $bagmasterpiece['sms-verification-page-id'] )

			){
		$class.= '-home';
	}

	return $class;
}

function custom_background_image_block(){

	global $bagmasterpiece;

	$image = '';

	if( is_front_page() )
		$image = $bagmasterpiece['home-background']['url'];
	elseif(is_page_template( 'template.membersip.php' ))
		$image = $bagmasterpiece['membership-background']['url'];
	elseif( is_page( $bagmasterpiece['login-page-id'] ) )
		$image = $bagmasterpiece['login-background']['url'];
	elseif( is_page( $bagmasterpiece['register-page-id'] ) )
		$image = $bagmasterpiece['login-background']['url'];
	elseif( is_page( $bagmasterpiece['lostpass-page-id'] ) )
		$image = $bagmasterpiece['login-background']['url'];
	elseif( is_page( $bagmasterpiece['resetpass-page-id'] ) )
		$image = $bagmasterpiece['login-background']['url'];
	elseif( is_page( $bagmasterpiece['sms-verification-page-id'] ) )
		$image = $bagmasterpiece['login-background']['url'];

	if( $image == '' )
		return;

	?>
		<style>
			.hero-home{
				background:transparent url(<?php echo $image;?>) no-repeat fixed 50% 50% / cover;
			}
		</style>
	<?php
}


add_action('wp_head', 'custom_background_image_block');


//	filters

//Add login/logout link to naviagation menu
function add_login_out_item_to_menu( $items, $args ){

	$menu_locations = array('regular','visitor','special');

	//change theme location with your them location name
	if( is_admin() ||  ! in_array($args->theme_location, $menu_locations ) )
		return $items;

	if( is_user_logged_in( ) ){
		global $current_user;

		$name = get_user_meta( get_current_user_id(), 'first_name', true);

		$name = $name == '' ? $current_user->get('display_name') : $name;

		wp_get_current_user();

		$items_wrap = '<li id="menu-item-0" class="menu-item menu-item-greet"><a href="' . admin_url('profile.php') . '">Hi '. $name .' </a></li>';
		$items_wrap .= $items;
		$link = '<a href="' . wp_logout_url( home_url() ) . '" title="' .  __( 'Logout' ) .'">' . __( 'Logout' ) . '</a>';
		$items_wrap .= '<li id="menu-item-0" class="menu-item menu-item-logout">' .$link. '</li>';

		return $items_wrap;
	}

	return $items.= '<li id="log-in-out-link" class="menu-item menu-type-link">'. $link . '</li>';
}

add_filter( 'wp_nav_menu_items', 'add_login_out_item_to_menu', 50, 2 );

//	disable admin bar in front end
add_filter('show_admin_bar', '__return_false');

// helper

function get_current_url(){
	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );

	return $current_url;
}

function bootstrap_menu($location='primary'){

	$header_menu = array(
			'theme_location'  => $location,
			'menu'            => '',
			'container'       => 'div',
			'container_class' => '',
			'container_id'    => '',
			'menu_class'      => 'nav navbar-nav navbar-right',
			'menu_id'         => '',
			'echo'            => true,
			'fallback_cb'     => '',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul id="%1$s" class="nav navbar-nav navbar-right">%3$s</ul>',
			'depth'           => 0,
			'walker'          => ''
	);
	wp_nav_menu($header_menu);
}

function site_header_menu(){

	if( ! is_user_logged_in() ){
		bootstrap_menu('visitor');
	}
	else{

		if( current_user_can('Special') ){
			bootstrap_menu('special');
		}
		elseif( current_user_can('vip') ){
			bootstrap_menu('vip');
		}
		elseif( current_user_can('regular') ){
			bootstrap_menu('regular');
		}
		else{
			//	@todo default menu, currently set to regular user menu. need to be changed in future.
			bootstrap_menu('regular');
		}

	}
}

function facebook_share_box(){
	echo '<div class="fb-like" data-href="' . get_current_url() . '" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>';
}

add_action('wp_footer','facebook_sdk');

function facebook_sdk(){

	$fb_appID = '1580247948883122';

	?>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0&appId=<?php echo $fb_appID;?>";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

		<script type="text/javascript">
			jQuery(document).ready(function($){
				$( '.social-wrap' ).on('click', '.social-link', function(e){
					e.preventDefault();
					$this = $(this);
					window.open($this.data('href'), 'BMP_SOCIAL_SHARER', "width=670,height=340,resizable,scrollbars");
					return false;
				});
			});
		</script>
	<?php
}

function get_image_from_option($option, $alt = ''){
	global $bagmasterpiece;

	$image = array('url'=>'', 'height'=>0, 'width'=>0);

	$alt = $alt == '' ? $option : $alt;

	if( isset($bagmasterpiece[$option]) ){
		$image = $bagmasterpiece[$option];

		echo '<img class="'. $option .'" src="' . $image['url'] . '" alt="'. $alt .'" height="' . $image['height'] . '" width="' . $image['width'] . '">';

	}


}


function BMP_social_share($baseurl = '', $text = '', $media_url = ''){

	if( $baseurl == '' ){
		//if( is_single() || is_page() ){
			$baseurl = get_permalink(get_queried_object_id());
		//}
		//else{
		//	$baseurl = get_permalink( get_queried_object_id() );
		//}
	}

	$baseurl = urlencode( $baseurl );
	$referer = urlencode( home_url() );

	$data = array(
			'facebook' => "//www.facebook.com/sharer.php?u={$baseurl}&display=popup&ref=plugin",
			'twitter'  => "//twitter.com/intent/tweet?url={$baseurl}&original_referer={$referer}&text={$text}",
			'gplus'    => "//plus.google.com/share?url={$baseurl}",
			'pinterest'=> "//pinterest.com/pin/create/button/?url={$baseurl}&media={$media_url}&description={$text}"
		);

	?>
		<p class="social-wrap">
            <span class="social-link fb" data-href="<?php echo $data['facebook']?>" data-title="Share on Facebook">Facebook</span>
            <span class="social-link twitter" data-href="<?php echo $data['twitter']?>" data-title="Share on Twitter">Twitter</span>
            <span class="social-link gplus" data-href="<?php echo $data['gplus']?>" data-title="Share on Google+">Google Plus</span>
            <span class="social-link pinterest" data-href="<?php echo $data['pinterest']?>" data-title="Share on Pinterest">Pinterest</span>
			<span class="social-link instagram">Instagram</span>
    	</p>

	<?php
}

add_action('wp_head','BMP_social_share_og_meta');

function BMP_social_share_og_meta(){

	global $bagmasterpiece;

	$fb_appID = '1580247948883122';

	if( is_single() ){
		$data = array(
			'title' => get_the_title( get_queried_object_id() ),
			'type' => 'article',
			'url' => get_permalink( get_queried_object_id() ),
			'image' => wp_get_attachment_image_src(get_post_thumbnail_id( get_queried_object_id() ), 'full')
		);
	}
	else{
		$data = array(
			'title' => get_bloginfo('name'),
			'type' => 'article',
			'url' => get_permalink( get_queried_object_id() ),
			'image' => $bagmasterpiece['home-logo'],
			'description' => $bagmasterpiece['footer-notice']
		);
	}


	?>

	<!-- facebook og -->
	<meta property="fb:app_id" content="<?php echo $fb_appID;?>" />
	<meta property="og:site_name" content="<?php bloginfo('name');?>" />
	<meta property="og:title" content="<?php echo $data['title'];?>" />
	<meta property="og:type" content="<?php echo $data['type'];?>" />
	<meta property="og:url" content="<?php echo $data['url'];?>" />
	<meta property="og:image" content="<?php echo $data['image'][0];?>" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:description" content="<?php echo $data['description'];?>" />
	<meta name="twitter:site:id" content="63359297" />
	<meta name="twitter:card" content="summary" />

	<?php
}

// clean up

remove_action('wp_head', 'wp_generator');

if (!function_exists('remove_wp_open_sans')) :

function remove_wp_open_sans() {
	wp_deregister_style( 'open-sans' );
	wp_register_style( 'open-sans', false );
}

add_action('wp_enqueue_scripts', 'remove_wp_open_sans');

endif;


if( ! function_exists('prefy') ) :
	function prefy($thing){
		echo '<pre>';var_dump($thing);echo '</pre>';
	}
endif;


add_action('woocommerce_custom_before_shop_loop','BMP_custom_product_cat');
add_action('woocommerce_custom_before_shop_loop','BMP_custom_product_filter');

function BMP_custom_product_cat(){

	$cat_count = array();

	if( is_product() ){
		$terms = get_the_terms( $post->ID, 'product_cat' );
		if( $terms )
			$cat_count = wp_list_pluck( $terms, 'term_id' );
	}

	$cat = get_terms( 'product_cat', array('parent' => 0, 'hide_empty' => false ) );

	$cat_flat = array('<a href="' . get_permalink( wc_get_page_id('shop') ) . '">All</a>');

	foreach( $cat as $c ){

		$class = '';

		if( $cat_count and count( $cat_count ) > 0 and in_array($c->term_id, $cat_count) ){
			$class = 'class="current-product-cat"';
		}

		$cat_flat[]  = '<a href="' . get_term_link($c, 'product_cat') . '" ' . $class . '>' .$c->name. '</a>';
	}

	?>

	<div class="row">
		<div class="col-md-12">
			<div class="shop-header-terms"><?php echo implode('|', $cat_flat);?></div>
		</div>
	</div>

	<?php
}
function BMP_custom_product_filter(){

	$brands = get_terms('product_brand', array('hide_empty' => false, 'orderby' => 'id' ));
	$styles = get_terms('product_style', array('hide_empty' => false, 'orderby' => 'id' ));

	?>

	<div class="shop-filter">
		<div class="row">
			<form action="" id="shop-filter-form">
				<input type="hidden" name="post_type" value="product" />
				<div class="col-md-5">
					<div class="select-filter">
						<select name="brand">
							<option value="-1">Choose Brand</option>
							<?php foreach( $brands as $brand ):?>
							<option value="<?php echo $brand->slug?>" <?php selected(esc_attr($_REQUEST['brand']), $brand->slug)?>><?php echo $brand->name?></option>
							<?php endforeach;?>
						</select>
						<span></span>
					</div>
				</div>
				<div class="col-md-5">
					<div class="select-filter">
						<select name="style">
							<option value="-1">Select a Style</option>
							<?php foreach( $styles as $style ):?>
							<option value="<?php echo $style->slug?>" <?php selected(esc_attr($_REQUEST['style']), $style->slug)?>><?php echo $style->name?></option>
							<?php endforeach;?>
						</select>
						<span></span>
					</div>
				</div>
				<div class="pull-right col-md-2">
					<input type="hidden" name="sfilter" value="1">
					<button class="btn btn-default btn-filter" type="submit"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search</button>
				</div>
			</form>
		</div>
	</div>

	<?php
}

add_action('pre_get_posts','filter_brand_styles');

function filter_brand_styles($query){

	if ( !is_admin() && $query->is_main_query() ) {

		if( (is_shop() or is_product_category()) and isset( $_REQUEST['sfilter'] )){

			$tax = $query->get('tax_query');

			$has_brand = $has_style = false;

			if( isset($_REQUEST['brand']) and esc_attr($_REQUEST['brand']) != -1 ){
				$tax[] = array(
						'taxonomy' => 'product_brand',
						'field'    => 'slug',
						'terms'    => esc_attr($_REQUEST['brand']),
				);
				$has_brand = true;
			}

			if( isset($_REQUEST['style']) and esc_attr($_REQUEST['style']) != -1){
				$tax[] = array(
						'taxonomy' => 'product_style',
						'field'    => 'slug',
						'terms'    => esc_attr($_REQUEST['style']),
				);
				$has_style = true;
			}

			if( $has_brand and $has_style ){
				$tax['relation'] = 'AND';
			}

			if( $has_brand or $has_style ){
				$query->set('tax_query', $tax);
			}

		}

	}

	return $query;
}
//sfilter

function shop_page_header(){

	$value = get_post_meta( wc_get_page_id('shop'), '_header_title_bg', true );

	echo '<div class="page-header-image"><div class="phc"></div>';
	echo '<img src="' . $value . '">';
	echo '<h1>' .get_the_title(wc_get_page_id('shop')). '</h1>';
	echo '</div>';
}


add_action('wp_footer','make_offer_modal_box');

function make_offer_modal_box(){

	if( ! is_product() )
		return;

	Angelleye_Offers_For_Woocommerce::angelleye_ofwc_display_custom_woocommerce_product_tab_content();

	?>
	<script type="text/javascript">


		jQuery(document).ready(function($){

			$('#tab_custom_ofwc_offer_tab_alt_message_2').hide();
			$('#tab_custom_ofwc_offer_tab_alt_message_success').hide();
			$('#tab_custom_ofwc_offer_tab_inner fieldset').show();
            $('#tab_custom_ofwc_offer_tab_alt_message_custom').hide();
			$('#tab_custom_ofwc_offer_tab_inner').show();
			$('#tab_custom_ofwc_offer_tab_alt_message').hide();
			$('#offer-submit-loader').hide();

		});

	</script>

	<?php

}





add_filter('manage_concierge_post_posts_columns', 'manage_concierge_posts_columns' );
add_filter('manage_consignment_post_posts_columns', 'manage_consignment_posts_columns' );
add_action('manage_concierge_post_posts_custom_column', 'manage_concierge_posts_custom_column', 10, 2);
add_action('manage_consignment_post_posts_custom_column', 'manage_consignment_posts_custom_column', 10, 2);

function manage_concierge_posts_columns($columns){
	unset($columns['title']);

	$columns['c_date'] = 'Date';
	$columns['item'] = 'Item';
	$columns['brand'] = 'Brand';
	$columns['style'] = 'Style';
	$columns['color'] = 'Color';
	$columns['leather'] = 'Leather';
	$columns['hardware'] = 'Hardware';
	$columns['budget'] = 'Budget';
	$columns['offer'] = 'Offer';
	$columns['edit'] = '';

	unset($columns['date']);

	return $columns;
}

function manage_consignment_posts_columns($columns){
	unset($columns['title']);

	$columns['c_date'] = 'Date';
	$columns['item'] = 'Item';
	$columns['brand'] = 'Brand';
	$columns['style'] = 'Style';
	$columns['model'] = 'Style';
	$columns['other'] = 'Other Data';
	$columns['budget'] = 'Budget';
	$columns['consigner'] = 'Consigner';
	$columns['status'] = 'Status';
	$columns['edit'] = '';

	unset($columns['date']);

	return $columns;
}

function manage_concierge_posts_custom_column($column_name, $post_id){

	switch($column_name){
		case 'c_date' :
			echo get_the_date('Y-m-d',$post_id);
			break;
		case 'item' :
			$item = get_term_by('id',get_post_meta($post_id,'concierge_item', true),'concierge');
			echo $item->name;
			break;
		case 'brand' :
			$brand = get_term_by('id',get_post_meta($post_id,'concierge_brand', true),'concierge');
			echo $brand->name;
			break;
		case 'style' :
			$style = get_term_by('id',get_post_meta($post_id,'concierge_style', true),'concierge');
			echo $style->name;
			break;
		case 'model' :
			$style = get_term_by('id',get_post_meta($post_id,'concierge_model', true),'concierge');
			echo $style->model;
			break;
		case 'color' :
			echo get_post_meta($post_id,'concierge_color1', true) . ', ';
			echo get_post_meta($post_id,'concierge_color2', true) . ', ';
			echo get_post_meta($post_id,'concierge_color3', true);
			break;
		case 'leather' :
			echo get_post_meta($post_id,'concierge_leather1', true) . ', ';
			echo get_post_meta($post_id,'concierge_leather2', true);
			break;
		case 'hardware' :
			echo get_post_meta($post_id,'concierge_hardware', true);
			break;
		case 'budget' :
			echo get_post_meta($post_id,'concierge_budget', true);
			break;
		case 'offer' :
			$offer = (array) get_post_meta($post_id,'offer', true);
			echo count($offer) - 1;
			break;
		case 'edit' :
			echo '<a href="' .get_edit_post_link($post_id) .'" class="btn">Edit</a>';
			break;
	}
}
function manage_consignment_posts_custom_column($column_name, $post_id){

	switch($column_name){
		case 'c_date' :
			echo get_the_date('Y-m-d',$post_id);
			break;
		case 'item' :
			$item = get_term_by('id',get_post_meta($post_id,'consignment_item', true),'concierge');
			echo $item->name;
			break;
		case 'brand' :
			$brand = get_term_by('id',get_post_meta($post_id,'consignment_brand', true),'concierge');
			echo $brand->name;
			break;
		case 'style' :
			$style = get_term_by('id',get_post_meta($post_id,'consignment_style', true),'concierge');
			echo $style->name;
			break;
		case 'model' :
			$model = get_term_by('id',get_post_meta($post_id,'consignment_model', true),'concierge');
			echo $model->name;
			break;
		case 'other' :
			$raw = get_post_meta($post_id,'consignment_params', true);
			if( $raw == '' ){
				echo '<i>-</i>';
				break;
			}
			$param = explode(',', $raw );
			foreach($param as $p){
				echo '<span><b>' . $p . ':</b> <i>' . get_post_meta($post_id, 'consignment_'.$p, true) . '</i></span><br>';
			}
			break;
// 		case 'color' :
// 			echo get_post_meta($post_id,'consignment_color1', true) . ', ';
// 			echo get_post_meta($post_id,'consignment_color2', true) . ', ';
// 			echo get_post_meta($post_id,'consignment_color3', true);
// 			break;
// 		case 'leather' :
// 			echo get_post_meta($post_id,'consignment_leather1', true) . ', ';
// 			echo get_post_meta($post_id,'consignment_leather2', true);
// 			break;
// 		case 'hardware' :
// 			echo get_post_meta($post_id,'consignment_hardware', true);
// 			break;
		case 'budget' :
			echo get_post_meta($post_id,'consignment_budget', true);
			break;
		case 'consigner' :
			$user_id = get_post_meta($post_id,'consignment_consigner', true);
			$data = get_userdata($user_id);

			if( $data ){
			    echo $data->first_name .' ' .$data->last_name;
			}
			else{
			    echo '<i>&lt;invalid consigner&gt;</i>';
			}

			break;
		case 'status' :
			$status = get_post_meta($post_id,'_status', true);
			if( $status == '__published' )
				echo 'Published';
			elseif( $status == '__processing' )
				echo 'Processing';
			else
				echo 'Pending';
			break;
		case 'edit' :
			echo '<a href="' .get_edit_post_link($post_id) .'" class="btn">Edit</a>';
			break;
	}
}



function push_terms(&$terms, $elem){

	if( ! is_array($terms) or count($terms) == 0 )
		return;

	foreach( $terms as $key => $term ){

		if( $term['data']->term_id == $elem->parent ){

			$terms[$key]['child'][$elem->term_id] = array(
					'data' => $elem,
					'child' => array()
			);
		}
		else{
			push_terms($terms[$key]['child'], $elem);
		}
	}
}


// function push_terms(&$terms, $elem){

// 	if( ! is_array($terms) or count($terms) == 0 )
// 		return;

// 	foreach( $terms as $key => $term ){

// 		if( $key == 9999 )
// 			continue;

// 		if( $term['data']->term_id == $elem->parent ){

// 			$data = new stdClass();
// 			$data->name = 'Self Defined';
// 			$data->term_id = 9999;

// 			$terms[$key]['child'][$elem->term_id] = array(
// 					'data' => $elem,
// 					'child' => array(
// 							9999 => array(
// 									'data' => $data
// 							)
// 					)
// 			);
// 		}
// 		else{
// 			push_terms($terms[$key]['child'], $elem);
// 		}
// 	}
// }


// blog



add_filter('the_excerpt','custom_excerpt');

function custom_excerpt($text){
	return $text . '<a href=" ' . get_the_permalink() . '">(Read More)</a>';
}

function wp_bootstrap_pagination( $args = array() ) {

	$defaults = array(
			'range'           => 4,
			'custom_query'    => FALSE,
			'previous_string' => __( '<i class="glyphicon glyphicon-chevron-left"></i>', 'text-domain' ),
			'next_string'     => __( '<i class="glyphicon glyphicon-chevron-right"></i>', 'text-domain' ),
			'before_output'   => '<div class="post-nav"><ul class="pager">',
			'after_output'    => '</ul></div>'
	);

	$args = wp_parse_args(
			$args,
			apply_filters( 'wp_bootstrap_pagination_defaults', $defaults )
	);

	$args['range'] = (int) $args['range'] - 1;
	if ( !$args['custom_query'] )
		$args['custom_query'] = @$GLOBALS['wp_query'];
	$count = (int) $args['custom_query']->max_num_pages;
	$page  = intval( get_query_var( 'paged' ) );
	$ceil  = ceil( $args['range'] / 2 );

	if ( $count <= 1 )
		return FALSE;

	if ( !$page )
		$page = 1;

	if ( $count > $args['range'] ) {
		if ( $page <= $args['range'] ) {
			$min = 1;
			$max = $args['range'] + 1;
		} elseif ( $page >= ($count - $ceil) ) {
			$min = $count - $args['range'];
			$max = $count;
		} elseif ( $page >= $args['range'] && $page < ($count - $ceil) ) {
			$min = $page - $ceil;
			$max = $page + $ceil;
		}
	} else {
		$min = 1;
		$max = $count;
	}

	$echo = '';
	$previous = intval($page) - 1;
	$previous = esc_attr( get_pagenum_link($previous) );

	$firstpage = esc_attr( get_pagenum_link(1) );
	if ( $firstpage && (1 != $page) )
		$echo .= '<li class="previous"><a href="' . $firstpage . '">' . __( 'First', 'text-domain' ) . '</a></li>';
	if ( $previous && (1 != $page) )
		$echo .= '<li><a href="' . $previous . '" title="' . __( 'previous', 'text-domain') . '">' . $args['previous_string'] . '</a></li>';

	if ( !empty($min) && !empty($max) ) {
		for( $i = $min; $i <= $max; $i++ ) {
			if ($page == $i) {
				$echo .= '<li class="active"><span class="active">' . str_pad( (int)$i, 2, '0', STR_PAD_LEFT ) . '</span></li>';
			} else {
				$echo .= sprintf( '<li><a href="%s">%002d</a></li>', esc_attr( get_pagenum_link($i) ), $i );
			}
		}
	}

	$next = intval($page) + 1;
	$next = esc_attr( get_pagenum_link($next) );
	if ($next && ($count != $page) )
		$echo .= '<li><a href="' . $next . '" title="' . __( 'next', 'text-domain') . '">' . $args['next_string'] . '</a></li>';

	$lastpage = esc_attr( get_pagenum_link($count) );
	if ( $lastpage ) {
		$echo .= '<li class="next"><a href="' . $lastpage . '">' . __( 'Last', 'text-domain' ) . '</a></li>';
	}
	if ( isset($echo) )
		echo $args['before_output'] . $echo . $args['after_output'];
}

add_action('template_redirect','force_visitor_to_login');

function force_visitor_to_login(){

	if( is_user_logged_in() )
		return;

	global $bagmasterpiece;

	$needle = get_queried_object_id();

	$haystack = array_values($bagmasterpiece['restricted-pages']);

	if( in_array($needle, $haystack) ){
		wp_safe_redirect(wp_login_url(get_permalink($needle)));
	}


}

add_action( 'wp_ajax_handle_delete_media', 'BMP_handle_delete_media' );

function BMP_handle_delete_media(){

	if( isset($_REQUEST['media_id']) ){
		$post_id = absint( $_REQUEST['media_id'] );

		$status = wp_delete_attachment($post_id, true);

		if( $status )
			echo json_encode(array('status' => 'OK'));
		else
			echo json_encode(array('status' => 'FAILED'));
	}

	die();
}

add_action( 'wp_ajax_handle_dropped_media', 'BMP_handle_dropped_media' );

function BMP_handle_dropped_media() {
	status_header(200);

	$upload_dir = wp_upload_dir();
	$upload_path = $upload_dir['path'] . DIRECTORY_SEPARATOR;
	$num_files = count($_FILES['file']['tmp_name']);

	$newupload = 0;

	if ( !empty($_FILES) ) {
		$files = $_FILES;
		foreach($files as $file) {
			$newfile = array (
					'name' => $file['name'],
					'type' => $file['type'],
					'tmp_name' => $file['tmp_name'],
					'error' => $file['error'],
					'size' => $file['size']
			);

			$_FILES = array('upload'=>$newfile);
			foreach($_FILES as $file => $array) {
				$newupload = media_handle_upload( $file, 0 );
			}
		}
	}

	echo $newupload;
	die();
}


add_action('wp_ajax_save_concierge','BMP_save_concierge');

function BMP_save_concierge(){

	$data = file_get_contents("php://input");

	$obj = json_decode($data, true);

	$params = explode(',', $obj['formParam']);

	$form_data = $obj['formData'];
	$product_info = $form_data['productInfo'];

	$user_id = get_current_user_id();

	$posttype = $obj['type'];
	$type = $obj['type'];

	$post_id = wp_insert_post(
		array(
			'post_status' => 'publish',
			'post_type' => "{$posttype}_post",
			'post_author' => $user_id,
			'post_content' => '',
			'post_title' => '',
		)
	);

	if( is_wp_error($post_id) ){
		echo json_encode(array('status'=>400,'message'=>'Failed'));
		die();
	}

	$posted = array(
		"{$type}_item" 					=> $product_info['item'],
		"{$type}_brand" 				=> $product_info['brand'] == '9999' ? $product_info['brandCustom'] : $product_info['brand'],
		"{$type}_style" 				=> $product_info['style'] == '9999' ? $product_info['styleCustom'] : $product_info['style'],
		"{$type}_model" 				=> $product_info['model'] == '9999' ? $product_info['modelCustom'] : $product_info['model'],
		"{$type}_budget" 				=> get_converted_currency($product_info['budget'], true),
		"{$type}_params" 				=> $obj['formParam'],
		"{$type}_other_details" 		=> $product_info['otherNote']
	);

	foreach( $params as $param ){
		$posted[ $type .'_'. $param ] = $product_info['otherData'][$param];
	}

	foreach( $posted as $meta_key => $meta_value ){
		update_post_meta( $post_id, $meta_key, $meta_value );
	}

	if( $type == 'offer' ){

		$c_id =  $obj[ 'concierge_id' ];

		update_post_meta( $post_id, 'offer_pics', $form_data['offerPics'] );
		update_post_meta( $post_id, 'concierge_id', $c_id );

		$offer = get_post_meta($c_id,'offer', true);

		if( $offer === false )
			$offer = array($post_id);
		else
			$offer[] = $post_id;

		update_post_meta( $c_id, 'offer', $offer );
	}



	echo json_encode(array('status'=>200,'message'=>'Successfull', 'redirect_to' => $obj['return_url']));

	die();
}

add_action('wp_ajax_save_consignment','BMP_save_consignment');

function BMP_save_consignment(){

	$data = file_get_contents("php://input");

	$obj = json_decode($data, true);

	$params = explode(',', $obj['formParam']);

	$form_data = $obj['formData'];
	$product_info = $form_data['productInfo'];
	$financial = $form_data['financial'];

	$user_id = get_current_user_id();

	$type = 'consignment';
	$posttype = 'consignment';

	$post_id = wp_insert_post(
		array(
				'post_status' => 'publish',
				'post_type' => "{$posttype}_post",
				'post_author' => $user_id,
				'post_content' => '',
				'post_title' => '',
			)
	);

	if( is_wp_error($post_id) ){
		echo json_encode(array('status'=>400,'message'=>'Failed'));
		die();
	}

	$posted = array(
		"{$type}_item" 					=> $product_info['item'],
		"{$type}_brand" 				=> $product_info['brand'] == '9999' ? $product_info['brandCustom'] : $product_info['brand'],
		"{$type}_style" 				=> $product_info['style'] == '9999' ? $product_info['styleCustom'] : $product_info['style'],
		"{$type}_model" 				=> $product_info['model'] == '9999' ? $product_info['modelCustom'] : $product_info['model'],
		"{$type}_budget" 				=> $product_info['budget'],
		"{$type}_other_details" 		=> $product_info['otherNote'],
		"{$type}_included" 				=> $form_data['included'],
		"{$type}_bank_account_name" 	=> $financial['bankAccountName'],
		"{$type}_bank_account_number" 	=> $financial['bankAccountNumber'],
		"{$type}_bank_name" 			=> $financial['bankName'],
		"{$type}_swift_code" 			=> $financial['swiftCode'],
		"{$type}_bank_branch" 			=> $financial['bankBranch'],
		"{$type}_profile_pics" 			=> $form_data['profilePics'],
		"{$type}_auth_pics" 			=> $form_data['authPics'],
		"{$type}_receipt_pic" 			=> $form_data['receiptPic'],
		"{$type}_receipt" 				=> $form_data['hasReceipt'],
		"{$type}_params" 				=> $obj['formParam'],
		"{$type}_package_id" 			=> $obj['packageId']
	);

	foreach( $params as $param ){
		$posted[ $type .'_'. $param ] = $product_info['otherData'][$param];
	}

	foreach( $posted as $meta_key => $meta_value ){
		update_post_meta( $post_id, $meta_key, $meta_value );
	}

	global $bagmasterpiece;

	$product_id = $bagmasterpiece['consignment-product'];

	if($obj["formData"]["hasReceiptNot"]){
		WC()->cart->add_to_cart($product_id, 1, '', array(), array('_consignment_id' => $post_id));
		echo json_encode(array('status'=>200,'message'=>'Successfull', 'redirect_to' => WC()->cart->get_checkout_url()));
		die();
	}

	// @todo redirect to list

	echo json_encode(array('status'=>200,'message'=>'Successfull', 'redirect_to' => $obj['return_url']));
	die();
}


add_action('wp_ajax_save_pass','BMP_save_pass');

function BMP_save_pass(){

    $data = file_get_contents("php://input");

    $obj = json_decode($data, true);

    $user         = new stdClass();

    $user->ID     = (int) get_current_user_id();
    $current_user = get_user_by( 'id', $user->ID );

    if ( $user->ID <= 0 ) {
        echo json_encode(array('status'=>400, 'error' => array( array('id'=>'invalid_user', 'message' => 'Invalid user'))));
        die();
    }

    if ( ! empty( $obj['pass1'] ) && ! wp_check_password( $obj['current'], $current_user->user_pass, $current_user->ID ) ) {
        echo json_encode(array('status'=>400, 'error' => array( array('id'=> 'invalid_pass', 'message' => 'Current password is not matched.'))));
        die();
    }

    $pass_cur = $obj['current'];
    $pass1 = $obj['pass1'];
    $pass2 = $obj['pass2'];

    if ( ! empty( $pass_cur ) && empty( $pass1 ) && empty( $pass2 ) ) {
        echo json_encode(array('status'=>400, 'error' => array( array('id'=>'empty_pass', 'message' => 'Please fill out all password fields.'))));
        die();
	} elseif ( ! empty( $pass1 ) && empty( $pass_cur ) ) {
	    echo json_encode(array('status'=>400, 'error' => array( array('id'=>'empty_pass', 'message' => 'Please enter your current password.'))));
	    die();
	} elseif ( ! empty( $pass1 ) && empty( $pass2 ) ) {
	    echo json_encode(array('status'=>400, 'error' => array( array('id'=>'empty_pass', 'message' => 'Please re-enter your password.'))));
	    die();
	} elseif ( ! empty( $pass1 ) && $pass1 !== $pass2 ) {
	    echo json_encode(array('status'=>400, 'error' => array( array('id'=>'pass_missmatch', 'message' => 'Passwords do not match.'))));
	    die();
	}

    $user->user_pass = $pass1;

    wp_update_user( $user ) ;
    echo json_encode(array('status'=>200));
    die();
}

add_action('wp_ajax_save_profile','BMP_save_profile');

function BMP_save_profile(){

	$data = file_get_contents("php://input");

	$obj = json_decode($data, true);

	$error = array();

	$label = array(
		'first_name'	=> 'First Name',
		'last_name'		=> 'Last Name',
		'dob'			=> 'Date Of Birth',
		'gender'		=> 'Gender',
		'nos'			=> 'Name of Spouse',
		'address'		=> 'Address',
		'city'			=> 'City',
		'state'			=> 'State / Province',
		'country'		=> 'Country',
		'zip'			=> 'Zip',
//		'contact_number' => 'Contact Number',
//		'email'			=> 'Email',
		'whatsapp'		=> 'Whatsapp ID',
		'viber'			=> 'Viber ID',
		'currency'		=> 'Currency',
		'subscriptions'	=> 'Mailing list subscription'

);


	$posted = array(
		'first_name'	=> $obj['data']['firstName'],
		'last_name'		=> $obj['data']['lastName'],
		'dob'			=> $obj['data']['dob'],
		'gender'		=> $obj['data']['gender'],
		'nos'			=> $obj['data']['nos'],
		'address'		=> $obj['data']['address'],
		'city'			=> $obj['data']['city'],
		'state'			=> $obj['data']['state'],
		'country'		=> $obj['data']['country'],
		'zip'			=> $obj['data']['zip'],
//		'contact_number' => $obj['data']['contactNumber'],
//		'email'			=> $obj['data']['email'],
		'whatsapp'		=> $obj['data']['whatsapp'],
		'viber'			=> $obj['data']['viber'],
		'currency'		=> $obj['data']['currency'],
		'subscriptions'	=> $obj['data']['subscriptions']

	);


//	validation ???

// 	foreach( $posted as $key => $value ){

// 		if( $value == '' ){
// 			$error[] = array(
// 					'id' => 'invalid-' . $key,
// 					'message' => '<strong>' .$label[$key] . '</strong> is a required field.'
// 				);
// 		}

// 	}


	if( count($error) > 0 ){
		$message = array(
			'status' => 400,
			'error' => $error
		);
	}
	else{

		$user_id = get_current_user_id();

		foreach( $posted as $meta_key => $meta_value ){
			update_user_meta($user_id, $meta_key, $meta_value);
		}

// 		$args = array(
// 				'ID'         => $user_id,
// 				'user_email' => $posted['email']
// 		);
// 		wp_update_user( $args );

		$message = array(
				'status' => 200
		);

	}

	if(isset($_COOKIE['woocommerce_current_currency']) and $_COOKIE['woocommerce_current_currency'] != $posted['currency'] ){

	    setcookie('woocommerce_current_currency', $posted['currency'], time()-2000);

	}

	setcookie('woocommerce_current_currency', $posted['currency'], time()+3600*168,"/");

	echo json_encode($message);

	die();
}


add_action('wp_ajax_get_consignment_prices', 'BMP_get_consignment_prices');

function BMP_get_consignment_prices(){
	global $bagmasterpiece;

	$data = array(
			1 => array(
					'name' => 'All-in Package',
					'fee' => array(
								array(
									'id' => 'shipping',
									'name' => 'Shipping',
									'value' => $bagmasterpiece['a-shipping']
								),
								array(
									'id' => 'handling',
									'name' => 'Handling',
									'value' => $bagmasterpiece['a-handling']
								),
								array(
									'id' => 'photographer',
									'name' => 'Photograoher\'s Fee',
									'value' => $bagmasterpiece['a-photographer']
								),
								array(
									'id' => 'consignment',
									'name' => 'Consignment Fee',
									'value' => $bagmasterpiece['a-consignment']
								),
								array(
									'id' => 'authentication',
									'name' => 'Authentication',
									'value' => $bagmasterpiece['a-authentication']
								)
						)
				),
			2 => array(
					'name' => 'Home Kit Package',
					'fee' => array(
						array(
							'id' => 'shippingKit',
							'name' => 'Shipping Kit',
							'value' => $bagmasterpiece['h-shipping-kit']
						),
						array(
							'id' => 'deposit',
							'name'=> 'Deposit Received',
							'value' => get_deposit_amount()
						),
						array(
							'id' => 'escrow',
							'name' => 'Setup Escrow.com transection',
							'value' => $bagmasterpiece['h-setup-escrow']
						),
						array(
							'id' => 'shipping',
							'name' => 'Shipping',
							'value' => $bagmasterpiece['h-shipping']
						),
						array(
							'id' => 'inspect',
							'name' => 'Inspect Bag and Verify',
							'value' => $bagmasterpiece['h-inspect']
						)
					)
				)
		);

	echo json_encode($data);
	die();
}

function get_deposit_amount(){

	//	@todo fetch deposit amount by user

	return 0;
}

//add_action('woocommerce_before_calculate_totals', 'consignment_product_price_adjust' );

function consignment_product_price_adjust($cart_object ) {

	global $bagmasterpiece;

	$product_id = $bagmasterpiece['consignment-product'];

	foreach ( $cart_object->cart_contents as $key => $value ) {

		$_product = apply_filters( 'woocommerce_cart_item_product', $value['data'], $value, $key );

		if( $_product->is_visible() or $value['product_id'] != $product_id )
			continue;

		$package_id = get_post_meta( $value['_consignment_id'], 'consignment_package_id', true);

		if( $package_id == 1 ){

			$custom = 0;
			$custom += floatval( $bagmasterpiece['a-shipping'] );
			$custom += floatval( $bagmasterpiece['a-handling'] );
			$custom += floatval( $bagmasterpiece['a-photographer'] );
			$custom += floatval( $bagmasterpiece['a-consignment'] );
			$custom += floatval( $bagmasterpiece['a-authentication']);

			$cart_object->cart_contents[$key]['data']->price = $custom;
		}
		elseif( $package_id == 2 ){

			$custom = 0;
			$custom += floatval( $bagmasterpiece['h-shipping-kit'] );
			$custom += floatval( $bagmasterpiece['h-shipping'] );
			$custom += floatval( $bagmasterpiece['h-setup-escrow'] );
			$custom += floatval( $bagmasterpiece['h-inspect'] );
			$custom += floatval( get_deposit_amount() );

			$cart_object->cart_contents[$key]['data']->price = $custom;
		}


	}
}


add_action('woocommerce_checkout_order_processed', 'attach_consignment_order_id_with_order', 10, 2);

function attach_consignment_order_id_with_order($order_id, $posted){

global $bagmasterpiece;

	$product_id = $bagmasterpiece['consignment-product'];

	$consignment_id = array();

	foreach ( WC()->cart->get_cart() as $key => $value ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $value['data'], $value, $key );
		if( $_product->is_visible() or $value['product_id'] != $product_id )
			continue;

		$consignment_id[] = $value['_consignment_id'];
	}

	if( 0 < sizeof($consignment_id) )
		update_post_meta( $order_id, '_consignment_id', $consignment_id );
}

add_action( 'woocommerce_order_status_processing', 'processs_consignment_order_id_with_order' );
add_action( 'woocommerce_order_status_completed', 'processs_consignment_order_id_with_order' );

function processs_consignment_order_id_with_order($order_id){

	$consignment_id = get_post_meta( $order_id, '_consignment_id', true );

	if( is_array($consignment_id) and !empty($consignment_id) ){
		foreach( $consignment_id as $consignment ){
			update_post_meta($consignment,'_status', '__processing');
		}
	}
}


function publish_as_product($referer){

	if( defined('SAVING_AS_PRODUCT') ){
		return;
	}

	$post_id = get_post_meta( $referer ,'_product_id', true );

	if( $post_id == '' ){

		$user_id = get_current_user_id();

		$post = array(
			'post_author' => $user_id,
			'post_status' => "publish",
			'post_title' => 'New Product',
			'post_type' => "product",
		);

		define('SAVING_AS_PRODUCT', true);

		$post_id = wp_insert_post( $post, true );
	}

	if($post_id){

		$consignment = new Consignment_Data($referer);

		update_post_meta( $post_id, '_thumbnail_id', $consignment->get('thumbnail') );

		wp_set_object_terms( $post_id, $consignment->get('item'), 'product_item' );
		wp_set_object_terms( $post_id, $consignment->get('brand'), 'product_brand' );
		wp_set_object_terms( $post_id, $consignment->get('style'), 'product_style' );
		wp_set_object_terms( $post_id, $consignment->get('model'), 'product_model' );

		wp_set_object_terms( $post_id, 'simple', 'product_type');

		update_post_meta( $post_id, '_visibility', 'visible' );
		update_post_meta( $post_id, '_stock_status', 'instock');
		update_post_meta( $post_id, 'total_sales', '0');
	//	update_post_meta( $post_id, '_downloadable', 'yes');
	//	update_post_meta( $post_id, '_virtual', 'yes');
		update_post_meta( $post_id, '_regular_price', $consignment->get('budget') );
	// 	update_post_meta( $post_id, '_sale_price', "1" );
		update_post_meta( $post_id, '_purchase_note', "" );
		update_post_meta( $post_id, '_featured', "no" );
		update_post_meta( $post_id, '_weight', "" );
		update_post_meta( $post_id, '_length', "" );
		update_post_meta( $post_id, '_width', "" );
		update_post_meta( $post_id, '_height', "" );
		update_post_meta( $post_id, '_sku', "");
		update_post_meta( $post_id, '_product_attributes', array());
		update_post_meta( $post_id, '_sale_price_dates_from', "" );
		update_post_meta( $post_id, '_sale_price_dates_to', "" );
		update_post_meta( $post_id, '_price', $consignment->get('budget') );
		update_post_meta( $post_id, '_sold_individually', "" );
		update_post_meta( $post_id, '_manage_stock', "no" );
		update_post_meta( $post_id, '_backorders', "no" );
		update_post_meta( $post_id, '_stock', "" );
		update_post_meta( $post_id, '_consigner', $consignment->get('consigner') );
		update_post_meta( $post_id, '_product_image_gallery', $consignment->get('image_gallery') );
		update_post_meta( $referer, '_product_id', $post_id);
		update_post_meta( $referer, '_status', '__published');


		$raw_params = get_post_meta($referer, 'offer_params', true);
		update_post_meta( $post_id, 'offer_params', $raw_params );

		if( $raw_params != '' ){
			$params = explode(',', $raw_params );

			if( is_array($params) ){
				foreach( $params as $i => $p ){
					$meta = get_post_meta($referer,"offer_{$p}", true);
					update_post_meta( $post_id, "offer_{$p}" ,$meta );
				}
			}
		}
	}

}

class Consignment_Data{

	private $id = 0;
	private $type = '';

	public function __construct( $id ){
		$this->id = $id;
		$this->type = get_post_type($id);
	}

	private function get_meta( $key ){
		return get_post_meta($this->id, $key, true);
	}


	/**
	 *
	 * Get the meta value from the consignment post
	 *
	 * accepts params-
	 * item, brand, style, size, stamp, color1, color2, color3, leather1, leather2, hardware, consigner,
	 * budget, other_details, included, profile_pics. auth_pics, receipt_pic, receipt, thumbnail, image_gallery
	 *
	 * @param string $key
	 * @return string $meta_value
	 */
	public function get( $key ){

		if( $key == '' )
			return new WP_Error('400','No key provided for data', $key);

		switch( $key ){
			case 'item':
				$item = get_term_by('id', $this->get_meta('consignment_item'), 'concierge');
				return $item->name;
				break;
			case 'brand':
				$item = get_term_by('id', $this->get_meta('consignment_brand'), 'concierge');
				return $item->name;
				break;
			case 'style':
				$item = get_term_by('id', $this->get_meta('consignment_style'), 'concierge');
				return $item->name;
				break;
			case 'model':
				return $this->get_meta('consignment_model');
				break;
			case 'size':
				return $this->get_meta('consignment_size');
				break;
			case 'stamp':
				return $this->get_meta('consignment_stamp');
				break;
			case 'color1':
				return $this->get_meta('consignment_color1');
				break;
			case 'color2':
				return $this->get_meta('consignment_color2');
				break;
			case 'color3':
				return $this->get_meta('consignment_color3');
				break;
			case 'leather1':
				return $this->get_meta('consignment_leather1');
				break;
			case 'leather2':
				return $this->get_meta('consignment_leather2');
				break;
			case 'hardware':
				return $this->get_meta('consignment_hardware');
				break;
			case 'consigner':
				return $this->get_consigner();
				break;
			case 'budget':
				return $this->get_meta('consignment_budget');
				break;
			case 'other_details':
				return $this->get_meta('consignment_other_details');
				break;
			case 'included':
				return $this->get_meta('consignment_included');
				break;
			case 'profile_pics':
				return $this->get_meta('consignment_profile_pics');
				break;
			case 'auth_pics':
				return $this->get_meta('consignment_auth_pics');
				break;
			case 'receipt_pic':
				return $this->get_meta('consignment_receipt_pic');
				break;
			case 'receipt':
				return $this->get_meta('consignment_receipt');
				break;
			case 'thumbnail':
				$gal = $this->get_meta('_product_image_gallery');

				if( !is_array($gal) ){
					$gal = explode(',', $gal);
				}

				return array_shift( $gal );
				break;
			case 'image_gallery':
				return $this->get_meta('_product_image_gallery');
				break;
			default:
				return '';
				break;
		}

	}

	private function get_consigner(){
		$post = get_post($this->id);

		if( ! $post ){
			$thisblog = $current_blog->blog_id;
			return get_user_id_from_string( get_blog_option($thisblog, 'admin_email'));
		}

		return $post->post_author;
	}

	/////	end of class
}

add_action('woocommerce_add_order_item_meta', 'consigner_checkout_order_process', 10, 3);

function consigner_checkout_order_process( $item_id, $values, $cart_item_key ){

	$consigner = get_post_meta($values['product_id'], '_consigner', true);;

	if( !$consigner )
		return;

	$userdata = get_userdata( $consigner );

	$consigner = '<a href="'. get_edit_user_link( $consigner ) .'">'. esc_attr( $userdata->user_nicename ) .'</a>';

	wc_add_order_item_meta( $item_id, '_consigner', $consigner );
}

add_filter('woocommerce_attribute_label','consigner_woocommerce_attribute_label', 10, 2);

function consigner_woocommerce_attribute_label($meta_key, $meta_value){

	if( $meta_key == '_consigner' ){
		return 'Consigner';
	}
}


function get_comission_for_user( $user_id = 0){
	if( $user_id == 0 )
		$user_id = get_current_user_id();

	$comission = array(
			'type' => get_option( '_commision_global_type'),
			'amount' => get_option( '_commision_global_amount')
		);

	if( $type = get_user_meta($user_id, '_commision_type', true) ){
		$comission['type'] = $type;
	}

	if( $amount = get_user_meta($user_id, '_commision_amount', true) ){
		$comission['amount'] = $amount;
	}

	return $comission;
}

function commisize_budget( &$budget, $post_id = 0 ){

	global $post;

	$self_requested = false;

	if( $post_id == 0 ){
		$self_requested = $post->post_author == get_current_user_id();
	}
	else{

		$post = get_post($post_id);
		$self_requested = $post->post_author == get_current_user_id();
	}

	$amount = $budget;
	$com = false;
	$user_id = 0;

	if( !$self_requested ){
	    if( get_post_type($post) == 'concierge_post' ){
	        $user_id = get_current_user_id();
	    }
	    elseif( get_post_type($post) == 'offer_post' ){
	        $user_id = (int) $post->post_author;
	    }
	    else{
	        $user_id = (int) $post->post_author;
	    }
	}

	$com = get_comission_for_user($user_id);

	if( $com === false || $self_requested){
		$budget = $amount;
		return $budget;
	}

	if( get_post_type($post) == 'concierge_post' ){
	    if( $com['type'] == 'fixed' ){
	        $budget = $amount - (float) $com['amount'];
	    }
	    else{
	        $budget =  $amount - $amount *( (float) $com['amount']/100);
	    }
	}
	elseif( get_post_type($post) == 'offer_post' ){
	    if( $com['type'] == 'fixed' ){
	        $budget = $amount + (float) $com['amount'];
	    }
	    else{
	        $budget =  $amount + $amount *( (float) $com['amount']/100);
	    }
	}
	else{

	}

	return $budget;
}



//add_filter('login_redirect','login_redirect_to_first');

function login_redirect_to_first($url){
	return add_query_arg( array('message'=>1), $url );
}

function dr_email_login_authenticate( $user, $username, $password ) {
	if ( is_a( $user, 'WP_User' ) )
		return $user;

	if ( !empty( $username ) ) {
		$username = str_replace( '&', '&amp;', stripslashes( $username ) );
		$user = get_user_by( 'email', $username );
		if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status )
			$username = $user->user_login;
	}

	return wp_authenticate_username_password( null, $username, $password );
}
remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
add_filter( 'authenticate', 'dr_email_login_authenticate', 20, 3 );

//add_action('init','login_message_init');

function login_message_init(){

	if( isset( $_GET['message'] ) ){
		$x = $_COOKIE[md5('member-just-logged-in')];

		if( $x == '_O_' || $_SERVER['re'] ){
			 setcookie(md5('member-just-logged-in'), "", time()-3600);
		}
		else{
			setcookie(md5('member-just-logged-in'), "_O_", time()+3600);
		}

	}
	else{

	}

}

add_filter('registration_errors', 'remove_username_empty_error', 10, 3);

function remove_username_empty_error($wp_error, $sanitized_user_login, $user_email){

    if(isset($wp_error->errors['empty_username'])){
        $wp_error->remove('empty_username');
    }
    if(isset($wp_error->errors['username_exists'])){
        $wp_error->remove('username_exists');
    }
    return $wp_error;
}

add_action('login_form_register', 'BMP_custom_login_form_register');

function BMP_custom_login_form_register(){
	if( isset($_POST['user_email']) && !empty($_POST['user_email'])){
		$_POST['user_login'] = $_POST['user_email'];
	}
}

function is_offer_available( $offer_id ){

	global $bagmasterpiece;

	$date = get_the_date('Y-m-d H:i:s', $offer_id);
	$from = strtotime($date);

	$add = 0;

	if( bmp_is_offer_status('accepted') ){
		$add = (int) $bagmasterpiece['cart-timeout-day'];
	}
	else{
		$add = (int) $bagmasterpiece['offer-timeout-day'];
	}

	$advance = date('d', $from)  + $add;

	$to = mktime( date('H', $from), date('i', $from), date('s', $from), date('m', $from), $advance, date('y', $from) );

	return BMP_human_time_diff(time(), $to);
}

function get_bmp_data( $post_id = 0, $meta_key = '', $return = false ){

	if( $post_id == 0 or $meta_key == '')
		return;

	$meta_value = get_post_meta($post_id, $meta_key, true);
	$data = '';

	if( is_numeric($meta_value) ){
		$term = get_term_by('id', $meta_value, 'concierge');
	}
	elseif( is_string($meta_value) ){
		$term = get_term_by('slug', $meta_value, 'concierge');
	}

	if( $term ){
		$data =  $term->name;
	}
	else{
		$data = $meta_value;
	}

	if( $return ){
		return $data;
	}
	else{
		echo $data;
	}
}

function bmp_clean_array($data){
	$temp = array();

	if( !is_array($data) or empty( $data ) ){
		return $data;
	}

	foreach( $data as $key => $value ){
		if( !$value ){
			continue;
		}

		$temp[$key] = $value;
	}

	return $temp;
}

function bmp_is_offer_status($offer_id = 0, $status = ''){

	if( $offer_id == 0){
		return false;
	}

	return $status == get_post_meta($offer_id, '_offer_status', true);
}


add_action('template_redirect','bmp_offer_action');

function bmp_offer_action(){

	if( !isset( $_GET['_wpnonce'] ) or
			(	! wp_verify_nonce($_GET['_wpnonce'], 'really-cancel-the-offer') and
			 	! wp_verify_nonce($_GET['_wpnonce'], 'really-accept-the-offer') and
			 	! wp_verify_nonce($_GET['_wpnonce'], 'really-complete-the-offer')
			)
		){
		return;
	}

	$message = 0;

	if( esc_attr($_GET['action']) == 'cancel' ){

		if( isset( $_GET['offer_id'] ) and get_post_type( esc_attr($_GET['offer_id']) ) == 'offer_post' ){

			$offer_id = (int) esc_attr($_GET['offer_id']);

			$r = update_post_meta( $offer_id, '_offer_status', 'declined');

			if( $r ){
				$message = 1;
			}
			else{
				$message = 2;
			}
		}
		else{
			$message = 3;
		}

		$location = esc_url($_GET['return_url']);

		$location = add_query_arg( array('offer_id' => $offer_id, 'message' => $message), $location );

		wp_safe_redirect($location);
	}
	elseif( esc_attr($_GET['action']) == 'accept' ){

		if( isset( $_GET['offer_id'] ) and get_post_type( esc_attr($_GET['offer_id']) ) == 'offer_post' ){

			$offer_id = (int) esc_attr($_GET['offer_id']);

			if( is_offer_available($offer_id) ){
				$r = update_post_meta( $offer_id, '_offer_status', 'accepted');

				if( $r ){
					$message = 4;
				}
				else{
					$message = 2;
				}
			}
			else{
				$message = 6;
			}


		}
		else{
			$message = 3;
		}

		$location = esc_url($_GET['return_url']);

		$location = add_query_arg( array('offer_id' => $offer_id, 'message' => $message), $location );

		wp_safe_redirect($location);
	}
	elseif( esc_attr($_GET['action']) == 'complete' ){

		if( isset( $_GET['offer_id'] ) and get_post_type( esc_attr($_GET['offer_id']) ) == 'offer_post' ){

			$offer_id = (int) esc_attr($_GET['offer_id']);

			$product_id = get_post_meta($offer_id, '_concierge_product_id', true);

			if( ! $product_id or get_post_type( $product_id ) != 'product' ){

				$user_id = get_current_user_id();

				$post = array(
						'post_author' => $user_id,
						'post_status' => "publish",
						'post_title' => 'Product for offer #' . $offer_id,
						'post_type' => "product",
				);

				$post_id = wp_insert_post( $post, true );

				if($post_id){

					$offer_item  = get_bmp_data($offer_id, 'offer_item',  true);
					$offer_brand = get_bmp_data($offer_id, 'offer_brand', true);
					$offer_style = get_bmp_data($offer_id, 'offer_style', true);
					$offer_model = get_bmp_data($offer_id, 'offer_model', true);

					$thumbnail = '';
					$gallery = get_post_meta($offer_id, 'offer_pics', true);

					if( !is_array($gallery) ){
						$gallery = explode(',', $gallery);
					}
					if( count($gallery) >0 ){
						$thumbnail = array_shift( $gallery );
					}

					$concierge_from = get_post($offer_id);
					$concierge_from = $concierge_from->post_author;

					$offer_budget = get_post_meta($offer_id,'offer_budget', true);
					commisize_budget($offer_budget);

					update_post_meta( $offer_id, '_concierge_product_id', $post_id);

					update_post_meta( $post_id, '_thumbnail_id', $thumbnail );

					//	enable deposit
					update_post_meta( $post_id, '_wc_deposits_enable_deposit', 'yes' );
					update_post_meta( $post_id, '_wc_deposits_amount_type', 'percent' );
					update_post_meta( $post_id, '_wc_deposits_deposit_amount', '50' );

					wp_set_object_terms( $post_id, $offer_item,  'product_item' );
					wp_set_object_terms( $post_id, $offer_brand, 'product_brand' );
					wp_set_object_terms( $post_id, $offer_style, 'product_style' );
					wp_set_object_terms( $post_id, $offer_model, 'product_model' );

					wp_set_object_terms( $post_id, 'simple', 'product_type');

					update_post_meta( $post_id, '_visibility', 'hidden' );
					update_post_meta( $post_id, '_stock_status', 'instock');
					update_post_meta( $post_id, 'total_sales', '0');
					//	update_post_meta( $post_id, '_downloadable', 'yes');
					//	update_post_meta( $post_id, '_virtual', 'yes');
					update_post_meta( $post_id, '_regular_price', $offer_budget );
					// 	update_post_meta( $post_id, '_sale_price', "1" );
					update_post_meta( $post_id, '_purchase_note', "" );
					update_post_meta( $post_id, '_featured', "no" );
					update_post_meta( $post_id, '_weight', "" );
					update_post_meta( $post_id, '_length', "" );
					update_post_meta( $post_id, '_width', "" );
					update_post_meta( $post_id, '_height', "" );
					update_post_meta( $post_id, '_sku', "");
					update_post_meta( $post_id, '_product_attributes', array());
					update_post_meta( $post_id, '_sale_price_dates_from', "" );
					update_post_meta( $post_id, '_sale_price_dates_to', "" );
					update_post_meta( $post_id, '_price', $offer_budget );
					update_post_meta( $post_id, '_sold_individually', "" );
					update_post_meta( $post_id, '_manage_stock', "no" );
					update_post_meta( $post_id, '_backorders', "no" );
					update_post_meta( $post_id, '_stock', "1" );
					update_post_meta( $post_id, '_concierge_by', $user_id );
					update_post_meta( $post_id, '_concierge_from', $concierge_from );
					update_post_meta( $post_id, '_product_image_gallery', implode(',', $gallery) );

					// update meta data

					$raw_params = get_post_meta($offer_id, 'offer_params', true);
					update_post_meta( $post_id, 'offer_params', $raw_params );

					if( $raw_params != '' ){
						$params = explode(',', $raw_params );

						if( is_array($params) ){
							foreach( $params as $i => $p ){
								$meta = get_post_meta($offer_id,"offer_{$p}", true);
								update_post_meta( $post_id, "offer_{$p}" ,$meta );
							}
						}
					}
				}
				else{
					$message = 2;
					$location = esc_url($_GET['return_url']);

					$location = add_query_arg( array('offer_id' => $offer_id, 'message' => $message), $location );

					wp_safe_redirect($location);

					die();
				}

				$product_id = $post_id;
			}
            wc()->cart->empty_cart();
			WC()->cart->add_to_cart($product_id, 1, '', array(), array('_offer_id' => $offer_id));

			$location = WC()->cart->get_checkout_url();

			wp_safe_redirect($location);

		}
		else{
			$message = 3;
			$location = esc_url($_GET['return_url']);

			$location = add_query_arg( array('offer_id' => $offer_id, 'message' => $message), $location );

			wp_safe_redirect($location);
		}
		die();
	}


}

add_action('woocommerce_checkout_order_processed', 'attach_offer_order_id_with_order', 10, 2);

function attach_offer_order_id_with_order($order_id, $posted){

	$offer_id = array();

	foreach ( WC()->cart->get_cart() as $key => $value ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $value['data'], $value, $key );
		if( $_product->is_visible() or ! isset($value['_offer_id']) )
			continue;

		$offer_id = $value['_offer_id'];
		update_post_meta( $order_id, '_offer_id', $offer_id );
		update_post_meta( $offer_id, '_offer_on_hold', $order_id );
	}

}

add_action( 'woocommerce_order_status_processing', 'processs_offer_order_id_with_order' );
add_action( 'woocommerce_order_status_completed', 'processs_offer_order_id_with_order' );

function processs_offer_order_id_with_order($order_id){

	$offer_id = get_post_meta( $order_id, '_offer_id', true );

	if( is_array($offer_id) and !empty($offer_id) ){
		foreach( $offer_id as $offer ){
			update_post_meta($offer,'_offer_status', 'completed');
			update_post_meta($offer,'_offer_order', $order_id);
		}
	}
	else{
		update_post_meta($offer_id,'_offer_status', 'completed');
		update_post_meta($offer_id,'_offer_order', $order_id);
	}
}

function offer_action_message(){

	global $bagmasterpiece;

	if( isset( $_GET['message'] ) and is_page($bagmasterpiece['concierge-page-id']) ){

		$id = esc_attr($_GET['message']);

		$messages = array(
				0 => 'undefined',
				1 => 'You have successfully declined this offer.',
				2 => 'Unable to complete the operation, please try again.',
				3 => 'Invalid offer id.',
				4 => 'You have successfully accepted this offer.',
				5 => 'You have successfully completed this offer.',
				6 => 'Offer timeout, can not process this offer anymore.'
			);

		if( in_array($id, array(1,4,5) ) ){
			?>
			<div class="col-md-12 woocommerce">
				<ul class="woocommerce-message">
					<li><?php echo $messages[$id]?></li>
				</ul>
			</div>
			<?php
		}
		else{

			?>
			<div class="col-md-12 woocommerce">
				<ul class="woocommerce-error">
					<li><?php echo $messages[$id]?></li>
				</ul>
			</div>
			<?php

		}

	}
}

function BMP_human_time_diff( $from, $to = '' ) {
	if ( empty( $to ) ) {
		$to = time();
	}

	$date_diff = array(
			'min' => 0,
			'hour' => 0,
			'day' => 0
		);

	if( $to < $from ){
		return false;
	}

	$diff = (int) abs( $to - $from );

	if ( $diff >= YEAR_IN_SECONDS ) {
		$years = floor( $diff / YEAR_IN_SECONDS );
// 		if ( $years <= 1 )
// 			$years = 1;
		if( $years == 0 ){
			break;
		}

		$date_diff['year'] = $years;
		$diff = $diff % YEAR_IN_SECONDS;
		$since = sprintf( _n( '<span class="date-num">%s</span> year', '<span class="date-num">%s</span> years', $years ), BMP_lead_zero_it($years) );
		$date_since .= "<span class=\"date date-year\">$since</span>";
	}

	if ( $diff < YEAR_IN_SECONDS && $diff >= 30 * DAY_IN_SECONDS ) {
		$months = floor( $diff / ( 30 * DAY_IN_SECONDS ) );
// 		if ( $months <= 1 )
// 			$months = 1;

		if( $months == 0 ){
			break;
		}

		$date_diff['month'] = $months;
		$diff = $diff % ( 30 * DAY_IN_SECONDS );
		$since = sprintf( _n( '<span class="date-num">%s</span> month', '<span class="date-num">%s</span> months', $months ), BMP_lead_zero_it($months) );
		$date_since .= "<span class=\"date date-month\">$since</span>";
	}

	if ( $diff < 30 * DAY_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
		$days = floor( $diff / DAY_IN_SECONDS );
// 		if ( $days <= 1 )
// 			$days = 1;

		if( $days == 0 ){
			break;
		}

		$date_diff['day'] = $days;
		$diff = $diff % DAY_IN_SECONDS;
		$since = sprintf( _n( '<span class="date-num">%s</span> day', '<span class="date-num">%s</span> days', $days ), BMP_lead_zero_it($days) );
		$date_since .= "<span class=\"date date-day\">$since</span>";
	}

	if ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
		$hours = floor( $diff / HOUR_IN_SECONDS );
// 		if ( $hours <= 1 )
// 			$hours = 1;

		if( $hours == 0 ){
			break;
		}

		$date_diff['hour'] = $hours;
		$diff = $diff % HOUR_IN_SECONDS;
		$since = sprintf( _n( '<span class="date-num">%s</span> hour', '<span class="date-num">%s</span> hours', $hours ), BMP_lead_zero_it($hours) );
		$date_since .= "<span class=\"date date-hour\">$since</span>";
	}

	if ( $diff < HOUR_IN_SECONDS ) {
		$mins = round( $diff / MINUTE_IN_SECONDS );
		if ( $mins <= 1 )
			$mins = 1;
		$date_diff['min'] = $mins;
		$diff = $diff % MINUTE_IN_SECONDS;
		$since = sprintf( _n( '<span class="date-num">%s</span> min', '<span class="date-num">%s</span> mins', $mins ), BMP_lead_zero_it($mins) );
		$date_since .= "<span class=\"date date-mins\">$since</span>";
	}

	$date_diff = array_reverse($date_diff);

	return $date_since;
}

function BMP_lead_zero_it($number = 0){
	if( $number < 10 )
		return '0' . $number;
	return $number;
}

function BMP_count_active_offers($offers = array()){
	if( !is_array($offers) or empty( $offers ) )
		return 0;

	$total = 0;

	foreach( $offers as $offer ){
		if( bmp_is_offer_status($offer, '') ){
			$total++;
		}
	}

	return $total;
}


function BMP_customer_reserved_product( $customer_email, $user_id, $product_id ) {
    global $wpdb;

    $emails = array();

    if ( $user_id ) {
        $user = get_user_by( 'id', $user_id );

        if ( isset( $user->user_email ) ) {
            $emails[] = $user->user_email;
        }
    }

    if ( is_email( $customer_email ) ) {
        $emails[] = $customer_email;
    }

    if ( sizeof( $emails ) == 0 ) {
        return false;
    }

    return $wpdb->get_var(
        $wpdb->prepare( "
            SELECT COUNT( DISTINCT order_items.order_item_id )
            FROM {$wpdb->prefix}woocommerce_order_items as order_items
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS itemmeta ON order_items.order_item_id = itemmeta.order_item_id
            LEFT JOIN {$wpdb->postmeta} AS postmeta ON order_items.order_id = postmeta.post_id
            LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
            WHERE
            posts.post_status IN ( 'wc-partially-paid' ) AND
            itemmeta.meta_value  = %s AND
            itemmeta.meta_key    IN ( '_variation_id', '_product_id' ) AND
            postmeta.meta_key    IN ( '_billing_email', '_customer_user' ) AND
            (
            postmeta.meta_value  IN ( '" . implode( "','", array_unique( $emails ) ) . "' ) OR
					(
						postmeta.meta_value = %s AND
						postmeta.meta_value > 0
					)
				)
			", $product_id, $user_id
    )
    );
}

function number_exixts($number = ''){

    if( $number == '' )
        return false;

    global $wpdb;

    $query = $wpdb->prepare("SELECT DISTINCT `user_id` FROM `{$wpdb->prefix}usermeta` WHERE meta_key = 'contact_number' and meta_value = '%s'", $number);

    return $wpdb->get_results( $query, ARRAY_A );

}


add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {

    $screen = get_current_screen();

    if($screen->id !== 'edit-woocommerce_offer'){
        return;
    }

    echo '<style>
        th#title{
            width:32%;
        }
	    th#offer_actions{
	       width:10%;
	    text-align:center;
        }
  </style>';
}


function user_verified_by_email(){
    global $wpdb;

    $cap_key = $wpdb->prefix . 'capabilities';

    $user_id = get_current_user_id();

    if ( $userdata = get_userdata($user_id) ) {

        if ( array_key_exists( 'pending', (array) $userdata->$cap_key ) ) {
            return false;
        }
        else{
            return true;
        }
    }
    wp_die('You do not have permission to access this page.');
}

add_action('template_redirect', 'restrict_email_verification');
add_action('template_redirect', 'resend_email_verification', 10);

function restrict_email_verification(){

	if( !is_user_logged_in() ){
		return;
	}

    $current_id = get_queried_object_id();

    $page_id = Theme_My_Login::get_page_id( 'profile' );
    $login_page_id = Theme_My_Login::get_page_id( 'login' );

    if($current_id == $page_id || $current_id == $login_page_id){
        return;
    }

	global $bagmasterpiece;

	$haystack = array_values($bagmasterpiece['restricted-pages']);

	if( !in_array($needle, $haystack) ){
		//return;
	}

    if( !user_verified_by_email() ){

        $redirect_to = Theme_My_Login::get_page_link( 'profile' );

        wp_safe_redirect($redirect_to);

        die();
    }
}

function resend_email_verification(){

    if( isset($_REQUEST['resend_activation']) and wp_verify_nonce( $_REQUEST['resend_activation'], 'really-verify-resend-activation' ) ){

        remove_action('template_redirect', 'restrict_email_verification');

        $user = new WP_User( get_current_user_id() );

        if ( in_array( 'pending', (array) $user->roles ) ) {
            // Send activation e-mail
            Theme_My_Login_User_Moderation::new_user_activation_notification( $user->ID );
            // Now redirect them
            $redirect_to = Theme_My_Login::get_page_link( 'profile' );
            $redirect_to = add_query_arg( array( 'sendactivation' => 'sent' ), $redirect_to );
            wp_redirect( $redirect_to );
            exit;
        }

    }

}

//add_filter('woocommerce_get_price_html','woocommerce_get_price_html_convert', 10, 2);

function woocommerce_get_price_html_convert($price, $product){


    var_dump($_COOKIE);

    $rates = get_transient('woocommerce_currency_converter_rates');

    return $price;
}

add_action('init','set_currency_cookie');

function set_currency_cookie(){

    if( ! ( $user_id = get_current_user_id() ) ){
        return;
    }

    $value = get_user_meta($user_id, 'currency', true);

    if(isset($_COOKIE['woocommerce_current_currency']) and $_COOKIE['woocommerce_current_currency'] != $value ){

        setcookie('woocommerce_current_currency', $value, time()-2000);

    }

    setcookie('woocommerce_current_currency', $value, time()+3600*168,"/");

}


function tab_active_class($page = '', $tab = '', $classes=''){

    if( $page == '' )
        return false;

    global $bagmasterpiece;

    $current_page_id = get_queried_object_id();

    switch( $page ){
        case 'concierge' :
            if( $current_page_id == $bagmasterpiece['concierge-page-id'] ){
                echo 'class="active ' . $classes .'"';
            }
            break;
        case 'consignment' :
            if( $current_page_id == $bagmasterpiece['consignment-page-id'] ){
                echo 'class="active ' . $classes .'"';
            }
            break;
        case 'profile' :
            if( $current_page_id == $bagmasterpiece['profile-page-id'] ){
                if( ( $tab =='' and !isset($_GET['tab']) ) || ($tab !='' and isset($_GET['tab']) and esc_attr($_GET['tab']) == $tab) ){
                    echo 'class="active ' . $classes .'"';
                }
                else{
                    echo 'class="' . $classes .'"';
                }
            }
            break;
    }

}

function dashboard_tabs(){

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

	<div class="col-md-12">
	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" <?php echo tab_active_class('profile');?>><a href="<?php echo $profile;?>">Profile</a></li>
	    <li role="presentation" <?php echo tab_active_class('concierge');?>><a href="<?php echo $concierge;?>">My Concierge</a></li>
	    <li role="presentation" <?php echo tab_active_class('consignment');?>><a href="<?php echo $consignment;?>">My Consignment</a></li>
	    <li role="presentation" <?php echo tab_active_class('profile', 'subscriptions');?>><a href="<?php echo $subscriptions;?>">My Subscriptions</a></li>
	    <li role="presentation" <?php echo tab_active_class('profile', 'orders');?>><a href="<?php echo $orders;?>">Orders</a></li>
	    <li role="presentation" <?php echo tab_active_class('profile', 'accounts');?>><a href="<?php echo $accounts;?>">Billing &amp; Shipping</a></li>
	    <li role="presentation" <?php echo tab_active_class('profile', 'change-pass');?>><a href="<?php echo $change_pass;?>">Change Password</a></li>
	  </ul>
	</div>
	<?php

}

function is_user_phone_verified(){
    $user_id = get_current_user_id();

    // @todo check the phone number

    return !current_user_can('regular');
}

add_action('tml_user_activation_post','custom_tml_user_activation_post', 10, 2);

function custom_tml_user_activation_post( $username, $email ){
    if ( has_action( 'tml_new_user_registered', 'wp_new_user_notification' ) )
        remove_action( 'tml_new_user_registered', 'wp_new_user_notification', 10, 2 );
    if ( class_exists( 'Theme_My_Login_Custom_Email' ) ) {
        $custom_email = Theme_My_Login_Custom_Email::get_object();
        if ( has_action( 'tml_new_user_registered', array( &$custom_email, 'new_user_notification' ) ) )
            remove_action( 'tml_new_user_registered', array( &$custom_email, 'new_user_notification' ), 10, 2 );
    }

    // Moderate user upon registration
    add_action( 'tml_new_user_registered', 'welcome_moderate_user', 100, 2 );
}

function welcome_moderate_user($user_id, $pass){
    $user = get_userdata( $user_id );

    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $message  = sprintf(__('New user registration on your site %s:'), $blogname) . "\r\n\r\n";
    $message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
    $message .= sprintf(__('E-mail: %s'), $user->user_email) . "\r\n";

    @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $message);

    $info = array();
    $info['user_login'] = $user->user_login;
    $info['user_password'] = $pass;
    $info['remember'] = true;

    $user_signon = wp_signon( $info, false );

    if ( is_wp_error($user_signon) ){
        $location = wp_login_url();
    }
    else {

        wp_set_current_user($user_signon->ID);
        update_user_meta($user_signon->ID, '__just_registered', '__YES__');

        $id = wc_get_page_id('shop');

        if( function_exists('icl_object_id') ){
            $page_id = icl_object_id($id, 'page', false, ICL_LANGUAGE_CODE);
        }
        else{
            $page_id = $id;
        }

        global $bagmasterpiece;

        $location = get_permalink( $bagmasterpiece['profile-page-id'] );
    }

    wp_safe_redirect($location);

    die();
}

function get_converted_currency( $amount = 0, $reverse = false){

    if( $amount == 0 ){
        return 0;
    }

    global $woocommerce_currency_converter;

    $amount = number_format($amount, 2, '.', '');
    $new_amount = $amount;

    if( $reverse ){
        $store_currency = $_COOKIE['woocommerce_current_currency'];
        $target_currency = get_option('woocommerce_currency');
    }
    else{
        $store_currency = get_option('woocommerce_currency');
        $target_currency = $_COOKIE['woocommerce_current_currency'];
    }

    if ($store_currency && $target_currency && $woocommerce_currency_converter->rates->$target_currency && $woocommerce_currency_converter->rates->$store_currency) {

        $new_amount = ( $amount / $woocommerce_currency_converter->rates->$store_currency ) * $woocommerce_currency_converter->rates->$target_currency;

        $new_amount = round($new_amount, 2);

    }

    return $new_amount;
}

add_action('wp_ajax_reoffer', 'BMP_concierge_reoffer');

function BMP_concierge_reoffer(){

    $offer_id = intval( esc_attr($_REQUEST['offer_id']) );
    $amount = floatval( esc_attr($_REQUEST['re_offer_amount']) );

    if( !$offer_id or get_post_type($offer_id) != 'offer_post'){

        echo json_encode(array('status' => '400', 'message' => 'Invalid offer.'));
        die();
    }

    if( !$amount ){
        echo json_encode(array('status' => '400', 'message' => 'Invalid amount.'));
        die();
    }

    $amount = get_converted_currency($amount, true);


    $status = update_post_meta($offer_id, 'offer_reoffer', $amount);

    if( $status ){
        echo json_encode(array('status' => '200', 'message' => 'Successfully submitted.'));
        die();
    }

    echo json_encode(array('status' => '400', 'message' => 'System error! Pleas try again.'));
    die();

}