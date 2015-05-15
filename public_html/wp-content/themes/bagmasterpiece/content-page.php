<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			
			<?php $value = get_post_meta( get_the_ID(), '_header_title_bg', true );?>
			
			<div class="page-header-image">
				<img src="<?php echo $value;?>">
				<h1 class="entry-title"><?php the_title();?></h1>
			</div>
			
		</header>
		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->
		<footer class="entry-meta">
			
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
