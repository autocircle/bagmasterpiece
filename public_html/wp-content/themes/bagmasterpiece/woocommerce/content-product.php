<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';
?>
<li <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<div class="product-loop">
		<a href="<?php the_permalink(); ?>">

			<?php
				/**
				 * woocommerce_before_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_show_product_loop_sale_flash - 10
				 * @hooked woocommerce_template_loop_product_thumbnail - 10
				 */
				do_action( 'woocommerce_before_shop_loop_item_title' );
			?>
		</a>
		<h3>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

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


		?>

		<div class="loop-product-section">
			<p class="cat"><?php echo $brand;?></p>
			<p class="brand"><?php echo $style;?></p>

			<?php $media = wp_get_attachment_url( get_post_thumbnail_id() );?>
			<?php BMP_social_share(get_permalink(get_the_ID()), get_the_title(get_the_ID()), $media);?>
		</div>


	<?php

		/**
		 * woocommerce_after_shop_loop_item hook
		 *
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		//do_action( 'woocommerce_after_shop_loop_item' );

	?>
	</div>
</li>
