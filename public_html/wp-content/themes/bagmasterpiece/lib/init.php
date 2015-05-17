<?php

//	 theme supports

add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support('woocommerce');

set_post_thumbnail_size( 825, 510, true );

register_nav_menu('blog', 'The secondary level navigation in blog page.');

// enqueue scritps and styles

function bag_bikikini_scripts() {
	wp_enqueue_style( 'font', 'http://fonts.googleapis.com/css?family=Didact+Gothic' );
	wp_enqueue_style( 'bootstrap-theme', get_template_directory_uri() . '/assets/css/bootstrap-theme.min.css' );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );
	wp_enqueue_style( 'fa', get_template_directory_uri() . '/assets/css/font-awesome.min.css' );
	wp_enqueue_style( 'main-stylesheet', get_stylesheet_uri());
	wp_enqueue_style( 'responsive', get_template_directory_uri() . '/assets/css/responsive.css' );

	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'bag_bikikini_scripts' );


// widgets

function BMP_page_sidebar(){

	register_sidebar( array(
	'name'          => __( 'Home Category Boxes', 'bikikini' ),
	'id'            => 'home-category-widget',
	'description'   => __( 'Widgets in this area will be shown on only home page box sections', 'bikikini' ),
	'before_widget' => '<div  id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<h1>',
	'after_title'   => '</h1>',
	) );

	register_sidebar( array(
	'name'          => __( 'Membership Page Boxes', 'bikikini' ),
	'id'            => 'membership-page-widget',
	'description'   => __( 'Widgets in this area will be shown on only membership landing page box sections', 'bikikini' ),
	'before_widget' => '<div  id="%1$s-o" class="col-md-4 widget %2$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<h1>',
	'after_title'   => '</h1>',
	) );

	register_sidebar( array(
	'name'          => __( 'BLog Page sidebar', 'bikikini' ),
	'id'            => 'blog',
	'description'   => __( 'Blog page sidebar', 'bikikini' ),
	'before_widget' => '<div  id="%1$s-o" class="widget %2$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<h2 class="right-heading">',
	'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'BMP_page_sidebar' );


add_action( 'init', 'create_concierge_posts' );

function create_concierge_posts() {

	// post type

	$labels = array(
			'name'              => __( 'Concierges', 'taxonomy general name' ),
			'singular_name'     => __( 'Concierge', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Concierges' ),
			'all_items'         => __( 'All Concierges' ),
			'parent_item'       => __( 'Parent Concierge' ),
			'parent_item_colon' => __( 'Parent Concierge:' ),
			'edit_item'         => __( 'Edit Concierge' ),
			'update_item'       => __( 'Update Concierge' ),
			'add_new_item'      => __( 'Add New Concierge' ),
			'new_item_name'     => __( 'New Concierge' ),
			'menu_name'         => __( 'Concierge' ),
	);

	$args = array(
			'public' => false,
			'label'  => 'Concierge',
			'labels' => $labels,
			'exclude_from_search'  => true,
			'publicly_queryable'   => false,
			'show_ui'              => true,
			'show_in_menu'         => true,
			'show_in_nav_menus'    => false,
			'show_in_admin_bar'    => false,
			'supports'             => array('concierge'),
			'register_meta_box_cb' => 'concierge_post_meta_box',
			'rewrite'              => false,
			'menu_position'			=> 57
	);
	register_post_type( 'concierge_post', $args );


	$labels = array(
			'name'              => __( 'Consignments', 'taxonomy general name' ),
			'singular_name'     => __( 'Consignment', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Consignments' ),
			'all_items'         => __( 'All Consignments' ),
			'parent_item'       => __( 'Parent Consignment' ),
			'parent_item_colon' => __( 'Parent Consignment:' ),
			'edit_item'         => __( 'Edit Consignment' ),
			'update_item'       => __( 'Update Consignment' ),
			'add_new_item'      => __( 'Add New Consignment' ),
			'new_item_name'     => __( 'New Consignment' ),
			'menu_name'         => __( 'Consignment' ),
	);

	$args2 = array(
			'public' => false,
			'label'  => 'Consignment',
			'labels' => $labels,
			'exclude_from_search'  => true,
			'publicly_queryable'   => false,
			'show_ui'              => true,
			'show_in_menu'         => true,
			'show_in_nav_menus'    => false,
			'show_in_admin_bar'    => false,
			'supports'             => array('consignment'),
			'register_meta_box_cb' => 'concierge_post_meta_box',
			'rewrite'              => false,
			'menu_position'			=> 57
	);
	register_post_type( 'consignment_post', $args2 );


	$args3 = array(
			'public' => false,
			'label'  => 'Offer',
			'exclude_from_search'  => true,
			'publicly_queryable'   => false,
			'show_ui'              => true,
			'show_in_menu'         => true,
			'show_in_nav_menus'    => false,
			'show_in_admin_bar'    => false,
			'supports'             => array('offer'),
			'rewrite'              => false
	);
	register_post_type( 'offer_post', $args3 );

	//	taxonomy

	$labels = array(
			'name'              => __( 'Conditions', 'taxonomy general name' ),
			'singular_name'     => __( 'Condition', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Conditions' ),
			'all_items'         => __( 'All Conditions' ),
			'parent_item'       => __( 'Parent Condition' ),
			'parent_item_colon' => __( 'Parent Condition:' ),
			'edit_item'         => __( 'Edit Condition' ),
			'update_item'       => __( 'Update Condition' ),
			'add_new_item'      => __( 'Add New Condition' ),
			'new_item_name'     => __( 'New Condition' ),
			'menu_name'         => __( 'Condition' ),
	);

	register_taxonomy(
		'concierge',
		array( 'concierge_post', 'consignment_post'),
		array(
			'labels'            => $labels,
			'label' => __( 'Concierge' ),
			'public' => false,
			'rewrite' => false,
			'hierarchical' => true,
			'show_ui' => true
		)
	);

	register_taxonomy(
		'product_item',
		'product',
		array(
			'label' => __( 'Item' ),
			'rewrite' => array( 'slug' => 'product_item' ),
			'hierarchical' => true,
		)
	);
	register_taxonomy(
		'product_brand',
		'product',
		array(
			'label' => __( 'Brand' ),
			'rewrite' => array( 'slug' => 'product_brand' ),
			'hierarchical' => true,
		)
	);
	register_taxonomy(
		'product_style',
		'product',
		array(
			'label' => __( 'Style' ),
			'rewrite' => array( 'slug' => 'product_style' ),
			'hierarchical' => true,
		)
	);
	register_taxonomy(
		'product_model',
		'product',
		array(
			'label' => __( 'Model' ),
			'rewrite' => array( 'slug' => 'product_model' ),
			'hierarchical' => true,
		)
	);
}

function concierge_post_meta_box($post){
	add_meta_box( 'concierge_data', 'Concierge Data', 'concierge_meta_box_callback', 'concierge_post', 'normal', 'high' );

	add_meta_box( 'consignment_data', 'Consignment Data', 'consignment_meta_box_callback', 'consignment_post', 'normal', 'high' );
	add_meta_box( 'woocommerce-product-images', 'Consignment Images', 'consignment_images_meta_box_callback', 'consignment_post', 'side');
	add_meta_box( 'consignment_publish', 'Publish', 'consignment_publish_meta_box_callback', 'consignment_post', 'side' );
}

function consignment_images_meta_box_callback($post){
	wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
	wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.css', array(), WC_VERSION );
	wp_enqueue_media();
	?>

	<script>
		jQuery(document).ready(function($){
			// Product gallery file uploads
			var product_gallery_frame;
			var $image_gallery_ids = $('#product_image_gallery');
			var $product_images = $('#product_images_container ul.product_images');

			jQuery('.add_product_images').on( 'click', 'a', function( event ) {
				var $el = $(this);
				var attachment_ids = $image_gallery_ids.val();

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( product_gallery_frame ) {
					product_gallery_frame.open();
					return;
				}

				// Create the media frame.
				product_gallery_frame = wp.media.frames.product_gallery = wp.media({
					// Set the title of the modal.
					title: $el.data('choose'),
					button: {
						text: $el.data('update'),
					},
					states : [
						new wp.media.controller.Library({
							title: $el.data('choose'),
							filterable :	'all',
							multiple: true,
						})
					]
				});

				// When an image is selected, run a callback.
				product_gallery_frame.on( 'select', function() {

					var selection = product_gallery_frame.state().get('selection');

					selection.map( function( attachment ) {

						attachment = attachment.toJSON();

						if ( attachment.id ) {
							attachment_ids   = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;
							attachment_image = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

							$product_images.append('\
								<li class="image" data-attachment_id="' + attachment.id + '">\
									<img src="' + attachment_image + '" />\
									<ul class="actions">\
										<li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li>\
									</ul>\
								</li>');
						}

					});

					$image_gallery_ids.val( attachment_ids );
				});

				// Finally, open the modal.
				product_gallery_frame.open();
			});

			// Image ordering
			$product_images.sortable({
				items: 'li.image',
				cursor: 'move',
				scrollSensitivity:40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'wc-metabox-sortable-placeholder',
				start:function(event,ui){
					ui.item.css('background-color','#f6f6f6');
				},
				stop:function(event,ui){
					ui.item.removeAttr('style');
				},
				update: function(event, ui) {
					var attachment_ids = '';

					$('#product_images_container ul li.image').css('cursor','default').each(function() {
						var attachment_id = jQuery(this).attr( 'data-attachment_id' );
						attachment_ids = attachment_ids + attachment_id + ',';
					});

					$image_gallery_ids.val( attachment_ids );
				}
			});

			// Remove images
			$('#product_images_container').on( 'click', 'a.delete', function() {
				$(this).closest('li.image').remove();

				var attachment_ids = '';

				$('#product_images_container ul li.image').css('cursor','default').each(function() {
					var attachment_id = jQuery(this).attr( 'data-attachment_id' );
					attachment_ids = attachment_ids + attachment_id + ',';
				});

				$image_gallery_ids.val( attachment_ids );

				// remove any lingering tooltips
				$( '#tiptip_holder' ).removeAttr( 'style' );
				$( '#tiptip_arrow' ).removeAttr( 'style' );

				return false;
			});
		});
	</script>

	<div class="woocommerce-product-images">

		<div id="product_images_container">
			<ul class="product_images">
				<?php
					if ( metadata_exists( 'post', $post->ID, '_product_image_gallery' ) ) {
						$product_image_gallery = get_post_meta( $post->ID, '_product_image_gallery', true );
					} else {
						// Backwards compat
						$attachment_ids = get_posts( 'post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&meta_key=_woocommerce_exclude_image&meta_value=0' );
						$attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
						$product_image_gallery = implode( ',', $attachment_ids );
					}

					$attachments = array_filter( explode( ',', $product_image_gallery ) );

					if ( $attachments ) {
						foreach ( $attachments as $attachment_id ) {
							echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
								' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '
								<ul class="actions">
									<li><a href="#" class="delete tips" data-tip="' . __( 'Delete image', 'woocommerce' ) . '">' . __( 'Delete', 'woocommerce' ) . '</a></li>
								</ul>
							</li>';
						}
					}
				?>
			</ul>

			<input type="hidden" id="product_image_gallery" name="product_image_gallery" value="<?php echo esc_attr( $product_image_gallery ); ?>" />

		</div>
		<p class="add_product_images hide-if-no-js">
			<a href="#" data-choose="<?php _e( 'Add Images to Product Gallery', 'woocommerce' ); ?>" data-update="<?php _e( 'Add to gallery', 'woocommerce' ); ?>" data-delete="<?php _e( 'Delete image', 'woocommerce' ); ?>" data-text="<?php _e( 'Delete', 'woocommerce' ); ?>"><?php _e( 'Add product gallery images', 'woocommerce' ); ?></a>
		</p>

	</div>
	<?php

}

function consignment_publish_meta_box_callback($post){

	wp_nonce_field('consignment_meta_box_verify', 'consignment_meta_box_nonce');

	$status = get_post_meta( $post->ID ,'_product_id', true );

	$value = $status == '' ? 'Publish as a Product' : 'Update product';

	?>

	<div id="">
		<div id="_publishing-action" style="text-align:center">
			<span class="spinner"></span>
			<input type="submit" name="save" class="button button-primary button-large" id="original_publish" accesskey="p" value="<?php echo $value;?>">
		</div>
		<div class="clear"></div>
	</div>

	<?php
}

function concierge_meta_box_callback($post){

    wp_nonce_field('concierge_meta_box_verify', 'concierge_meta_box_nonce');

	$type = 'concierge';

	$post_id = $post->ID;

	$posted = array(
		"{$type}_item" => '',
		"{$type}_brand" => '',
		"{$type}_style" => '',
		"{$type}_model" => '',
		"{$type}_size" => '',
// 		"{$type}_stamp" => '',
// 		"{$type}_color1" => '',
// 		"{$type}_color2" => '',
// 		"{$type}_color3" => '',
// 		"{$type}_leather1" => '',
// 		"{$type}_leather2" => '',
// 		"{$type}_hardware" => '',
		"{$type}_budget" => '',
		"{$type}_other_details" => '',
		"{$type}check" => ''
	);


	$raw_params = get_post_meta($post_id, 'concierge_params', true);

	if( $raw_params != '' ){

		$params = explode(',', $raw_params );
		foreach( $params as $param ){
			$posted[ $type .'_'. $param ] = '';
		}
	}

	foreach( $posted as $key => $value ){
		$posted[$key] = get_post_meta($post_id, $key, true);
	}


	$item = get_term_by('id', $posted["{$type}_item"], 'concierge');
	$brand = get_term_by('id', $posted["{$type}_brand"], 'concierge');
	$style = get_term_by('id', $posted["{$type}_style"], 'concierge');
	$model = get_term_by('id', $posted["{$type}_model"], 'concierge');

	?>
		<style>
			.concierge_meta_box .item{
				background: #f2f2f2;
				padding: 2px 10px;
			}
			.concierge_meta_box .item:nth-child(2n){
				background: #fff;
			}
			.concierge_meta_box label{
				width:120px;
				text-align:left;
				display:inline-block;
				verticle-align:middle;
			}
			.concierge_meta_box select, .concierge_meta_box input, .concierge_meta_box textarea{
				width: 245px;
			}
		</style>
		<ul class="concierge_meta_box">
			<li class="item">
				<label for="concierge_item">Item</label>
				<select name="concierge_item" id="concierge_item">
					<option value="<?php echo $item->term_id?>"><?php echo $item->name?></option>
				</select>
			</li>
			<li class="item">
				<label for="concierge_brand">Brand</label>
				<?php if( $brand ):?>
				<select name="concierge_brand" id="concierge_brand">
					<option value="<?php echo $brand->term_id?>"><?php echo $brand->name?></option>
				</select>
				<?php else:?>
				<input type="text" name="concierge_brand" id="concierge_brand" value="<?php echo esc_attr($posted["{$type}_brand"])?>">
				<?php endif;?>
			</li>
			<li class="item">
				<label for="concierge_style">Style</label>
				<?php if( $style ):?>
				<select name="concierge_style" id="concierge_style">
					<option value="<?php echo $style->term_id?>"><?php echo $style->name?></option>
				</select>
				<?php else:?>
				<input type="text" name="concierge_brand" id="concierge_brand" value="<?php echo esc_attr($posted["{$type}_style"])?>">
				<?php endif;?>
			</li>
			<li class="item">
				<label for="concierge_model">Model</label>
				<?php if( $model ):?>
				<select name="concierge_model" id="concierge_model">
					<option value="<?php echo $model->term_id?>"><?php echo $model->name?></option>
				</select>
				<?php else:?>
				<input type="text" name="concierge_brand" id="concierge_brand" value="<?php echo esc_attr($posted["{$type}_model"])?>">
				<?php endif;?>
			</li>

			<?php if( $raw_params != '' and is_array($params) ):?>

			<?php foreach( $params as $i => $p ):?>
			<?php
				$term = get_term_by('slug', $p, 'concierge');
				$label = '';
				if($term)
					$label = $term->name;
				else
					$label = 'Description ' . ($i+1);
			?>
			<li class="item">
				<label for="concierge_<?php echo $p?>"><?php echo $label;?></label>
				<input type="text" name="concierge_<?php echo $p?>" id="concierge_<?php echo $p?>" value="<?php echo esc_attr($posted["{$type}_{$p}"])?>">
			</li>
			<?php endforeach;?>

			<?php endif;?>
			<!--
			<li class="item">
				<label for="concierge_size">Size</label>
				<input type="text" name="concierge_size" id="concierge_size" value="<?php echo esc_attr($posted["{$type}_size"])?>">
			</li>
			<li class="item">
				<label for="concierge_stamp">Stamp</label>
				<input type="text" name="concierge_stamp" id="concierge_stamp" value="<?php echo esc_attr($posted["{$type}_stamp"])?>">
			</li>
			<li class="item">
				<label for="concierge_color1">Color 1</label>
				<input type="text" name="concierge_color1" id="concierge_color1" value="<?php echo esc_attr($posted["{$type}_color1"])?>">
			</li>
			<li class="item">
				<label for="concierge_color2">Color 2</label>
				<input type="text" name="concierge_color2" id="concierge_color2" value="<?php echo esc_attr($posted["{$type}_color1"])?>">
			</li>
			<li class="item">
				<label for="concierge_color3">Color 3</label>
				<input type="text" name="concierge_color3" id="concierge_color3" value="<?php echo esc_attr($posted["{$type}_color2"])?>">
			</li>
			<li class="item">
				<label for="concierge_leather1">Leather 1</label>
				<input type="text" name="concierge_leather1" id="concierge_leather1" value="<?php echo esc_attr($posted["{$type}_leather1"])?>">
			</li>
			<li class="item">
				<label for="concierge_leather2">Leather 2</label>
				<input type="text" name="concierge_leather2" id="concierge_leather2" value="<?php echo esc_attr($posted["{$type}_leather2"])?>">
			</li>
			<li class="item">
				<label for="concierge_hardware">Hardware</label>
				<input type="text" name="concierge_hardware" id="concierge_hardware" value="<?php echo esc_attr($posted["{$type}_hardware"])?>">
			</li>
			-->
			<li class="item">
				<label for="concierge_budget">Budget (<?php echo get_woocommerce_currency()?>)</label>
				<input type="text" name="concierge_budget" id="concierge_budget" value="<?php echo esc_attr($posted["{$type}_budget"])?>">
			</li>
			<li class="item">
				<label for="concierge_other_details">Other Details/ Requests</label>
				<textarea name="concierge_other_details" id="concierge_other_details"><?php echo esc_attr($posted["{$type}_other_details"])?></textarea>
			</li>
		</ul>
	<?php


}

function consignment_meta_box_callback($post){

	$type = 'consignment';

	$post_id = $post->ID;

	$posted = array(
		"{$type}_item" => '',
		"{$type}_brand" => '',
		"{$type}_style" => '',
		"{$type}_model" => '',
// 		"{$type}_size" => '',
// 		"{$type}_stamp" => '',
// 		"{$type}_color1" => '',
// 		"{$type}_color2" => '',
// 		"{$type}_color3" => '',
// 		"{$type}_leather1" => '',
// 		"{$type}_leather2" => '',
// 		"{$type}_hardware" => '',
		"{$type}_budget" => '',
		"{$type}_other_details" => '',
		"{$type}_included" 	=> '',
		"{$type}_bank_account_name" 	=> '',
		"{$type}_bank_account_number" 	=> '',
		"{$type}_bank_name" 	=> '',
		"{$type}_swift_code" 	=> '',
		"{$type}_bank_branch" 	=> '',
		"{$type}_profile_pics" 	=> '',
		"{$type}_auth_pics" 	=> '',
		"{$type}_receipt_pic" 	=> '',
		"{$type}_receipt" 	=> '',
		"{$type}_package_id" 	=> ''
	);


	$raw_params = get_post_meta($post_id, 'consignment_params', true);

	if( $raw_params != '' ){

		$params = explode(',', $raw_params );
		foreach( $params as $param ){
			$posted[ $type .'_'. $param ] = '';
		}
	}

	foreach( $posted as $key => $value ){
		$posted[$key] = get_post_meta($post_id, $key, true);
	}

	$item = get_term_by('id', $posted["{$type}_item"], 'concierge');
	$brand = get_term_by('id', $posted["{$type}_brand"], 'concierge');
	$style = get_term_by('id', $posted["{$type}_style"], 'concierge');
	$model = get_term_by('id', $posted["{$type}_model"], 'concierge');

	?>
		<style>
			.consignment_meta_box .item{
				background: #f2f2f2;
				padding: 2px 10px;
				clear:both;
			}
			.consignment_meta_box .item:nth-child(2n){
				background: #fff;
			}
			.consignment_meta_box label{
				width:120px;
				text-align:left;
				display:inline-block;
				verticle-align:middle;
			}
			.consignment_meta_box select, .consignment_meta_box input, .consignment_meta_box textarea{
				width: 245px;
			}
			.image-block p{
				width: 150px;
				height: 180px;
				display: inline-block;
				position: relative;
				border: 5px solid #fff;
				border-radius: 5px;
				box-shadow: 2px 2px 5px -3px #333;
				vertical-align:top;
			}
			.image-block p img{
				width:100%;
				height: auto;
			}
			.image-block p a{
				display: block;
				background: rgb(0, 0, 0) none repeat scroll 0% 0%;
				color: rgb(255, 255, 255);
				text-decoration: none;
				text-align: center;
				border-radius: 3px;
				margin: 0px;
				padding: 3px;
				bottom: 0;
				position: absolute;
				width: 96%;
			}
			.image-block p a:hover{
				background:#333;
			}
		</style>
		<ul class="consignment_meta_box">
			<li class="item">
				<label for="consignment_item">Item</label>
				<select name="consignment_item" id="consignment_item">
					<option value="<?php echo $item->term_id?>"><?php echo $item->name?></option>
				</select>
			</li>
			<li class="item">
				<label for="concierge_brand">Brand</label>
				<?php if( $brand ):?>
				<select name="concierge_brand" id="concierge_brand">
					<option value="<?php echo $brand->term_id?>"><?php echo $brand->name?></option>
				</select>
				<?php else:?>
				<input type="text" name="concierge_brand" id="concierge_brand" value="<?php echo esc_attr($posted["{$type}_brand"])?>">
				<?php endif;?>
			</li>
			<li class="item">
				<label for="concierge_style">Style</label>
				<?php if( $style ):?>
				<select name="concierge_style" id="concierge_style">
					<option value="<?php echo $style->term_id?>"><?php echo $style->name?></option>
				</select>
				<?php else:?>
				<input type="text" name="concierge_brand" id="concierge_brand" value="<?php echo esc_attr($posted["{$type}_style"])?>">
				<?php endif;?>
			</li>
			<li class="item">
				<label for="concierge_model">Model</label>
				<?php if( $model ):?>
				<select name="concierge_model" id="concierge_model">
					<option value="<?php echo $model->term_id?>"><?php echo $model->name?></option>
				</select>
				<?php else:?>
				<input type="text" name="concierge_brand" id="concierge_brand" value="<?php echo esc_attr($posted["{$type}_model"])?>">
				<?php endif;?>
			</li>

			<?php if( $raw_params != '' and is_array($params) ):?>

			<?php foreach( $params as $i => $p ):?>
			<?php
				$term = get_term_by('slug', $p, 'concierge');
				$label = '';
				if($term)
					$label = $term->name;
				else
					$label = 'Description ' . ($i+1);
			?>
			<li class="item">
				<label for="consignment_<?php echo $p?>"><?php echo $label;?></label>
				<input type="text" name="consignment_<?php echo $p?>" id="consignment_<?php echo $p?>" value="<?php echo esc_attr($posted["{$type}_{$p}"])?>">
			</li>
			<?php endforeach;?>

			<?php endif;?>

			<li class="item">
				<label for="consignment_budget">Budget</label>
				<input type="text" name="consignment_budget" id="consignment_budget" value="<?php echo esc_attr($posted["{$type}_budget"])?>">
			</li>
			<li class="item">
				<label for="consignment_other_details">Other Details/ Requests</label>
				<textarea name="consignment_other_details" id="consignment_other_details"><?php echo esc_attr($posted["{$type}_other_details"])?></textarea>
			</li>
			<li class="item">
				<label for="consignment_included">Included</label>
				<textarea name="consignment_included" id="consignment_included"><?php echo implode(', ',$posted["{$type}_included"])?></textarea>
			</li>
			<li class="item">
				<label for="">Bank Account Name</label>
				<strong><?php echo esc_attr($posted["{$type}_bank_account_name"])?></strong>
			</li>
			<li class="item">
				<label for="">Bank Account Number</label>
				<strong><?php echo esc_attr($posted["{$type}_bank_account_number"])?></strong>
			</li>
			<li class="item">
				<label for="">Bank Name</label>
				<strong><?php echo esc_attr($posted["{$type}_bank_name"])?></strong>
			</li>
			<li class="item">
				<label for="">Bank Branch</label>
				<strong><?php echo esc_attr($posted["{$type}_bank_branch"])?></strong>
			</li>
			<li class="item">
				<label for="">Swift Code</label>
				<strong><?php echo esc_attr($posted["{$type}_swift_code"])?></strong>
			</li>
			<li class="item image-block">
				<label for="">Profile Pictures</label>
				<?php $pps = $posted["{$type}_profile_pics"];?>

				<?php foreach( $pps as $p ):?>


					<?php $a = wp_get_attachment_image_src($p,'full');?>
					<?php if(! $a) continue;?>
				<p>
				    <?php echo wp_get_attachment_image($p);?>
					<a target="_blank" href="<?php echo $a[0];?>">Original</a>
				</p>
				<?php endforeach;?>
			</li>
			<li class="item image-block">
				<label for="">Receipt Picture</label>

				<?php $p = $posted["{$type}_receipt_pic"];?>
				<?php if( $p ):?>
					<p><?php echo wp_get_attachment_image($p);?>
					<?php $a = wp_get_attachment_image_src($p,'full');?>
					<a target="_blank" href="<?php echo $a[0];?>">Original</a></p>
				<?php else:?>
				<i>No receipt image given, requested for authentication</i>
				<?php endif;?>

			</li>
			<li class="item image-block">
				<label for="">Authentication Pictures</label>

				<?php $pps = $posted["{$type}_auth_pics"];?>

				<?php if( count($pps) > 0 ):?>
					<?php foreach( $pps as $p ):?>
					<p>
						<?php echo wp_get_attachment_image($p);?>
						<?php $a = wp_get_attachment_image_src($p,'full');?>
						<a target="_blank" href="<?php echo $a[0];?>">Original</a>
					</p>
					<?php endforeach;?>
				<?php else:?>
					<i>Original receipt provided, authentication is not required.</i>
				<?php endif;?>
			</li>
			<li class="item">
				<label for="">Package ID</label>
				<strong><?php echo esc_attr($posted["{$type}_package_id"]) == 2 ? 'Home Kit Package' : 'All-in Package'?></strong>
			</li>

		</ul>
	<?php

	// @todo Need to give admin the privilige to edit the options above.

}


function save_concierge_metabox_data( $post_id ){

    if (
        ! isset( $_POST['concierge_meta_box_nonce'] ) ||
        ! wp_verify_nonce( $_POST['concierge_meta_box_nonce'], 'concierge_meta_box_verify' ) ||
        (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) ||
        ! current_user_can( 'edit_post', $post_id ) ) {

            return;
        }


        $fields = array(
            'concierge_item' => '',
            'concierge_brand' => '',
            'concierge_style' => '',
            'concierge_model' => ''	,
            'concierge_budget' => '',
            'concierge_other_details' => '',
            'concierge_included' => ''
        );

        $raw_params = get_post_meta($post_id, 'concierge_params', true);

        if( $raw_params != '' ){
            $params = explode(',', $raw_params );
            foreach( $params as $param ){
                $fields[ 'concierge_'. $param ] = '';
            }
        }

        foreach( $fields as $f => $val ){
            update_post_meta( $post_id, $f, sanitize_text_field( esc_attr($_REQUEST[$f]) ) );
        }
}


function save_consignment_metabox_data( $post_id ){

	if (
			! isset( $_POST['consignment_meta_box_nonce'] ) ||
			! wp_verify_nonce( $_POST['consignment_meta_box_nonce'], 'consignment_meta_box_verify' ) ||
			  defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||
			! current_user_can( 'edit_post', $post_id ) ) {

		return;
	}

	//die();

	$fields = array(
		'consignment_item' => '',
		'consignment_brand' => '',
		'consignment_style' => '',
		'consignment_model' => ''	,
		'consignment_budget' => '',
		'consignment_other_details' => '',
		'consignment_included' => ''
	);

	$raw_params = get_post_meta($post_id, 'consignment_params', true);

	if( $raw_params != '' ){
		$params = explode(',', $raw_params );
		foreach( $params as $param ){
			$fields[ 'consignment_'. $param ] = '';
		}
	}

	foreach( $fields as $f => $val ){
		update_post_meta( $post_id, $f, sanitize_text_field( esc_attr($_REQUEST[$f]) ) );
	}

	$attachment_ids = isset( $_POST['product_image_gallery'] ) ? array_filter( explode( ',', wc_clean( $_POST['product_image_gallery'] ) ) ) : array();

	update_post_meta( $post_id, '_product_image_gallery', implode( ',', $attachment_ids ) );

	if( isset( $_REQUEST['save'] ) and ( esc_attr( $_REQUEST['save']) == 'Publish as a Product' or  esc_attr( $_REQUEST['save']) == 'Update product' ))
		publish_as_product($post_id);
}

add_action( 'save_post', 'save_consignment_metabox_data' );
add_action( 'save_post', 'save_concierge_metabox_data' );


add_action('admin_menu','BMP_commision_rates');

function BMP_commision_rates(){
	add_submenu_page('users.php', 'Commision Rates', 'Comission Rates', 'manage_options', 'commision-rates', 'BMP_commision_rates_cb');
}

add_action('current_screen','BMP_save_comission_data');

function BMP_save_comission_data(){

	$screen = get_current_screen();

	if( $screen->id != 'users_page_commision-rates' )
		return;

	$location = '';

	if( !current_user_can('manage_options') )
		wp_die('You do not have sufficient permission to access this page.');

	if( isset( $_POST['nonce'] ) and wp_verify_nonce($_POST['nonce'], 'verify_commision_page') ){

		if( isset( $_POST['global_type'] ) ){
			update_option( '_commision_global_type', esc_attr($_POST['global_type']) );
		}
		if( isset( $_POST['global_amount'] ) ){
			update_option( '_commision_global_amount', esc_attr($_POST['global_amount']) );
		}

		$location = admin_url('users.php?page=commision-rates&message=1');
	}

	if( isset( $_POST['usernonce'] ) and wp_verify_nonce($_POST['usernonce'], 'verify_commision_by_user') ){
		$user_id = absint( esc_attr( $_POST['user_select'] ) );
		if( isset( $_POST['user_type'] ) ){
			update_user_meta( $user_id, '_commision_type', esc_attr($_POST['user_type']) );
		}
		if( isset( $_POST['user_amount'] ) ){
			update_user_meta( $user_id, '_commision_amount', esc_attr($_POST['user_amount']) );
		}
		$location = admin_url('users.php?page=commision-rates&message=1');
	}


	if( isset( $_GET['resetnonce'] ) and wp_verify_nonce($_GET['resetnonce'], 'verify_reset_user') ){

		if( isset( $_GET['reset'] ) ){
			$user_id = absint( esc_attr( $_GET['reset'] ) );
			delete_user_meta($user_id, '_commision_type');
			delete_user_meta($user_id, '_commision_amount');
		}
		$location = admin_url('users.php?page=commision-rates&message=1');
	}

	if( $location !='' )
		wp_safe_redirect($location);

}

function BMP_commision_rates_cb(){

	if( !current_user_can('manage_options') )
		wp_die('You do not have sufficient permission to access this page.');


	global $wpdb;
	$blog_id = get_current_blog_id();

	$user_query = new WP_User_Query( array(
			'meta_query' => array(
					'relation' => 'OR',
					array(
							'key' => $wpdb->get_blog_prefix( $blog_id ) . 'capabilities',
							'value' => 'vip',
							'compare' => 'like'
					),
					array(
							'key' => $wpdb->get_blog_prefix( $blog_id ) . 'capabilities',
							'value' => 'Special',
							'compare' => 'like'
					)
			)
	) );

	$current = 0;

	?>

	<div class="wrap">
		<h2>Commision Rates</h2>
		<p>Specify comission rates for your customers. Note: only <b>VIP</b> and <b>Special</b> customers are listed here.</p>


		<?php if( isset( $_REQUEST['message'] ) ):?>
			<div id="setting-error-settings_updated" class="updated settings-error">
				<p><strong>Settings saved.</strong></p>
			</div>
		<?php endif;?>

			<form action="<?php echo admin_url('users.php?page=commision-rates')?>" method="post">

				<?php wp_nonce_field('verify_commision_page','nonce');?>

				<div class="stuffbox">
					<?php
						$ct = get_option( '_commision_global_type');
						$ca = get_option( '_commision_global_amount');
					?>

					<div class="stuffbox-inner" style="padding: 12px 20px 0px;">
						<span><b>Global comission rate:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span>
							<label for="global_type">Type</label>
							<select name="global_type" id="global_type">
								<option value="">Select Type</option>
								<option value="percent" <?php selected($ct, 'percent', true);?>>Percent (%)</option>
								<option value="fixed" <?php selected($ct, 'fixed', true);?>>Fixed</option>
							</select>
						</span>
						<span>
							<label for="global_amount">Amount</label>
							<input type="text" name="global_amount" value="<?php echo $ca;?>" placeholder="Amount" id="global_amount">
						</span>
						<span>
							<input type="submit" name="button button-primary" value="Save">
						</span>
					</div>
					<br class="clear">
				</div>
			</form>

			<?php if ( ! empty( $user_query->results ) ) :?>

			<form action="<?php echo admin_url('users.php?page=commision-rates')?>" method="post">

				<?php wp_nonce_field('verify_commision_by_user','usernonce');?>

				<div class="stuffbox">
					<?php
						$ct = get_option( '_commision_global_type');
						$ca = get_option( '_commision_global_amount');
					?>

					<div class="stuffbox-inner" style="padding: 12px 20px 0px;">
						<span><b>User comission rate:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span>
							<label for="user_select"></label>
							<select name="user_select" id="user_select">
								<option value="">Select User</option>
								<?php foreach ( $user_query->results as $user ) :?>
								<option value="<?php echo $user->ID;?>"><?php echo $user->data->display_name;?></option>
								<?php endforeach;?>
							</select>
						</span>
						<span>
							<label for="user_type">Type</label>
							<select name="user_type" id="user_type">
								<option value="">Select Type</option>
								<option value="percent">Percent (%)</option>
								<option value="fixed">Fixed</option>
							</select>
						</span>
						<span>
							<label for="user_amount">Amount </label>
							<input type="text" name="user_amount" value="" placeholder="Amount" id="user_amount">
						</span>
						<span>
							<input type="submit" name="button button-primary" value="Save">
						</span>
					</div>
					<br class="clear">
				</div>
			</form>

			<?php endif;?>

			<table class="wp-list-table widefat fixed users">
				<thead>
					<tr>
						<th scope="col" id="username" class="manage-column column-username">
							<a href="#"><span>Username</span></a>
						</th>
						<th scope="col" id="name" class="manage-column column-name">
							<a href="#"><span>Name</span></a>
						</th>
						<th scope="col" id="email" class="manage-column column-email">
							<a href="#"><span>E-mail</span></a>
						</th>
						<th scope="col" id="email" class="manage-column column-role">
							<a href="#"><span>Role</span></a>
						</th>
						<th scope="col" id="email" class="manage-column column-c-type">
							<a href="#"><span>Commision Type</span></a>
						</th>
						<th scope="col" id="email" class="manage-column column-email">
							<a href="#"><span>Amount</span></a>
						</th>
						<th scope="col" id="email" class="manage-column column-email">
						</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<th scope="col" id="username" class="manage-column column-username">
							<a href="#"><span>Username</span></a>
						</th>
						<th scope="col" id="name" class="manage-column column-name">
							<a href="#"><span>Name</span></a>
						</th>
						<th scope="col" id="email" class="manage-column column-email">
							<a href="#"><span>E-mail</span></a>
						</th>
						<th scope="col" id="email" class="manage-column column-role">
							<a href="#"><span>Role</span></a>
						</th>
						<th scope="col" id="email" class="manage-column column-c-type">
							<a href="#"><span>Commision Type</span></a>
						</th>
						<th scope="col" id="email" class="manage-column column-email">
							<a href="#"><span>Amount</span></a>
						</th>
						<th scope="col" id="email" class="manage-column column-email">
						</th>
					</tr>
				</tfoot>

				<tbody id="the-list" data-wp-lists="list:user">

					<?php if ( ! empty( $user_query->results ) ) :?>
						<?php foreach ( $user_query->results as $user ) :?>

							<tr id="user-1" class="<?php echo $current%2 == 0 ? 'alternate' : ''?>">

								<td class="username column-username">
									<strong><a href="<?php echo  get_edit_user_link($user->ID)?>"><?php echo $user->data->user_login;?></a></strong>
									<br>
								</td>
								<td class="name column-name"><?php echo $user->data->display_name;?></td>
								<td class="email column-email"><a href="mailto:<?php echo $user->data->user_email;?>" title="E-mail: <?php echo $user->data->user_email;?>"><?php echo $user->data->user_email;?></a></td>
								<td class="role column-role">
									<?php
										if( in_array('vip', $user->roles) )
											echo '<b>VIP</b>';
										elseif( in_array('Special', $user->roles) )
											echo '<b>Special</b>';
										else
											echo '<b>-</b>';
									?>
								</td>
								<td class="email column-email">
									<?php
										$user_id = $user->ID;
										$type = '';
										$isp = false;
										$gtts = false;

										if( $type = get_user_meta($user_id, '_commision_type', true) ){
											if( $type == 'percent'){
												echo 'Percent (%)';
												$isp = true;
											}
											elseif( $type == 'fixed'){
												echo 'Fixed';
											}
											else
												echo '-';

											$gtts = true;
										}
										else{
											$type = get_option( '_commision_global_type');
											if( $type == 'percent'){
												echo 'Percent (%) <small><i>(global)<i></small>';
												$isp = true;
											}
											elseif( $type == 'fixed'){
												echo 'Fixed <small><i>(global)<i></small>';
											}
											else
												echo '-';
										}
									?>
								</td>
								<td class="email column-email">
									<?php
										$user_id = $user->ID;
										$type = '';
										$gtta = false;

										if( $type = get_user_meta($user_id, '_commision_amount', true) ){
											if(!$isp)
												echo wc_price($type);
											else
												echo $type;
											$gtta = true;
										}
										else{
											$typ = get_option( '_commision_global_amount');
											if(!$isp)
												echo wc_price($typ);
											else
												echo $typ;
											echo '&nbsp;<small><i>(global)<i></small>';
										}
									?>
								</td>
								<td class="email column-email">
									<?php if( $gtta and $gtts ):?>
									<a href="<?php echo wp_nonce_url(admin_url('users.php?page=commision-rates&reset=' . $user->ID),'verify_reset_user','resetnonce');?>">Reset</a>
									<?php else:?>
									<a href="#">Reset</a>
									<?php endif;?>
								</td>
							</tr>
							<?php $current++;?>
						<?php endforeach;?>
					<?php else:?>
						<tr>
							<td colspan="7">No users found.</td>
						</tr>
					<?php endif;?>


				</tbody>
			</table>

		<br class="clear">
	</div>

	<?php

}