<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="row">

		<?php

			$brands = get_the_terms( get_the_ID(), 'product_brand' );
			$brand = '';

			if( is_null($brands) or is_wp_error($brands) or empty($brands)){
				$brand = '-';
			}
			else{
				$brand_plunked = wp_list_pluck($brands, 'name');
				$brand = implode(', ', $brand_plunked);
			}

			$styles = get_the_terms( get_the_ID(), 'product_style' );
			$style = '';

			if( is_null($styles) or is_wp_error($styles) or empty($styles)){
				$style = '-';
			}
			else{
				$styles_plunked = wp_list_pluck($styles, 'name');
				$style = implode(', ', $styles_plunked);
			}

			$models = get_the_terms( get_the_ID(), 'product_model' );
			$model = '';

			if( is_null($models) or is_wp_error($models) or empty($models)){
				$model = '-';
			}
			else{
				$models_plunked = wp_list_pluck($models, 'name');
				$model = implode(', ', $models_plunked);
			}


		?>

		<div class="col-xs-12">
			<div class="brand-block">
				<h3><?php echo $brand?></h3>
			</div>
		</div>

		<div class="col-md-4">
			<div class="title-block">
				<?php woocommerce_template_single_title();?>
				<h3><?php echo $style;?></h3>
				<h3><?php echo $model;?></h3>
			</div>

			<div class="description-block">
				<h3>Product Specifications</h3>
				<?php the_content(); ?>
				<div class="specifications">
						<?php
							$post_id = get_the_ID();
							$raw_params = get_post_meta($post_id, 'offer_params', true);
							if( $raw_params != '' ){
								$params = explode(',', $raw_params );

								if( is_array($params) ){
									foreach( $params as $i => $p ){

										$term = get_term_by('slug', $p, 'concierge');
										$label = '';
										if($term){
											$label = $term->name;
										}
										else{
											$label = 'Description '.($i+1);
										}
										?>
										<p class="item">
											<strong><?php echo $label;?></strong>
											<i><?php echo esc_attr( get_post_meta($post_id,"offer_{$p}", true));?></i>
										</p>
										<?php
									}
								}
							}
						?>
					</div>
			</div>
		</div>

		<div class="col-md-4">

		  <?php

    		  $images = array();

    		  if ( has_post_thumbnail() ) {
    		      $images[] = get_post_thumbnail_id();
    		  }

    		  $attachment_ids = $product->get_gallery_attachment_ids();

    		  if ( $attachment_ids ) {
    		      $images = array_merge($images, $attachment_ids);
    		  }

    		  $loop 		= 0;
    		  $columns 	    = 4;

    		  $images = array_unique($images);

		  ?>

			<div class="thumbnail-block">
			     <?php if( ! empty( $images ) ):?>
                    <ul class="bxslider">
				        <?php foreach ($images as $attachment_id):?>
				            <?php
				                $classes = array( 'zoom' );

                    			if ( $loop == 0 || $loop % $columns == 0 )
                    				$classes[] = 'first';

                    			if ( ( $loop + 1 ) % $columns == 0 )
                    				$classes[] = 'last';

                    			$image_link = wp_get_attachment_url( $attachment_id );

                    			if ( ! $image_link )
                    				continue;

                    			$image       = wp_get_attachment_image( $attachment_id, 'shop_single');
                    			$image_class = esc_attr( implode( ' ', $classes ) );
                    			$image_title = esc_attr( get_the_title( $attachment_id ) );
                    			$loop++;
				            ?>
                            <li>
                                <?php echo sprintf( '<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[product-gallery]">%s</a>', $image_link, $image_class, $image_title, $image );?>
                            </li>
                        <?php endforeach;?>
                    </ul>
                    <div id="bx-pager">
                        <?php $index = 0;?>
                        <?php foreach ($images as $attachment_id):?>
                            <?php
				                $image_link  = wp_get_attachment_url( $attachment_id );
				                if( $image_link  == '' )
				                    continue;

				                $image       = wp_get_attachment_image( $attachment_id);
				            ?>
                            <a data-slide-index="<?php echo $index;?>" href=""><?php echo $image;?></a>
                            <?php $index++;?>
                        <?php endforeach;?>

                    </div>
                 <?php else:?>
                    <?php wc_get_template( 'single-product/product-image.php' );?>
                 <?php endif;?>
			</div>

		</div>

		<div class="col-md-4">
			<div class="action-block">
			    <?php
			         global $current_user;
			         wp_get_current_user();

			    ?>


				<?php if( $product->is_in_stock() ):?>

					<?php woocommerce_template_single_price();?>
					<div class="action-buttons">
						<form class="cart" method="post" enctype='multipart/form-data'>
						 	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />
						 	<button type="button" class="make-offer" data-toggle="modal" data-target="#makeOfferModal">Make Offer</button>
						 	<button type="submit" class="add_to_cart">Add to cart</button>
						</form>
					</div>
					<div class="product-status available">
						<h1>Available</h1>
					</div>
				<?php elseif (BMP_customer_reserved_product( $current_user->user_email, get_current_user_id(), $product->id )):?>
				    <div class="product-status sold">
						<h1>Reserved</h1>
					</div>
				<?php else:?>
                    <div class="product-status sold">
						<h1>Sold</h1>
					</div>
				<?php endif;?>

				<div class="notify">
				    <button type="button" class="notify-btn btn btn-primary" data-toggle="modal" data-target="#notifyMeModal">Notify</button>
				</div>
				<!-- Modal -->
                <div class="modal fade" id="notifyMeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-body">
                       <div class="row">
                        <div class="col-md-6">
                            <p class="form-row">
                                <label><input type="checkbox" name="options[]" value="brand">&nbsp;Brand</label>
                            </p>
                            <p class="form-row">
                                <label><input type="checkbox" name="options[]" value="style">&nbsp;Style</label>
                            </p>
                            <p class="form-row">
                                <label><input type="checkbox" name="options[]" value="color">&nbsp;Color</label>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="form-row">
                                <label><input type="checkbox" name="options[]" value="size">&nbsp;Size</label>
                            </p>
                            <p class="form-row">
                                <label><input type="checkbox" name="options[]" value="leather">&nbsp;Leather</label>
                            </p>
                            <p class="form-row">
                                <label><input type="checkbox" name="options[]" value="hardware">&nbsp;Hardware</label>
                            </p>
                        </div>

                        <div class="col-md-12">
                            <div class="hr"><hr></div>
                        </div>
                        <div class="col-md-12">
                            <h3>Delivery Method</h3>
                        </div>
                        <div class="col-md-6">
                            <p class="form-row">
                                <label><input type="checkbox" name="dm[]" value="email">&nbsp;Email</label>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="form-row">
                                <?php if( is_user_phone_verified() ):?>
                                <label><input type="checkbox" name="dm[]" value="sms">&nbsp;SMS</label>
                                <?php else:?>
                                <?php

                                    global $bagmasterpiece;

                                    $v = get_permalink($bagmasterpiece['sms-verification-page-id']);
                                ?>
                                <label><input type="checkbox" class="disabled" disabled readonly name="" value="">&nbsp;SMS&nbsp;(<a traget="_blank" href="<?php echo $v;?>">Verify phone number</a>)</label>
                                <?php endif;?>
                            </p>
                        </div>
                        <div class="col-md-12">
                            <div class="hr"><hr></div>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Okay</button>
                        </div>

                       </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $media = wp_get_attachment_url( get_post_thumbnail_id() );?>
				<?php BMP_social_share(get_permalink(get_the_ID()), get_the_title(get_the_ID()), $media);?>
			</div>
		</div>
	</div>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->


<?php
	$args = array(
		'posts_per_page' => 5,
		'columns' => 2,
		'orderby' => 'rand'
	);
	woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );
?>


<?php do_action( 'woocommerce_after_single_product' ); ?>
