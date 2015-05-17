<div class="col-md-12 spacing-style section-one border-style">
	<div class="section-heading">
            <h4><a href="<?php the_permalink(); ?>"><?php the_title()?></a></h4>
		<time><?php the_time('F j, Y');?></time>
	</div>
	
	<div class="post_thumb">
		<?php if( has_post_thumbnail() ):?>
			<?php the_post_thumbnail()?>
		<?php endif;?>
	</div>	
	<div class="post_content">
		<?php the_excerpt()?>
	</div>
	
	<p class="social-icon-style">						
		<a href="https://www.facebook.com"><i class="fa fa-facebook"></i></a>
		<a href="https://twitter.com/"><i class="fa fa-twitter"></i></a>
		<a href="https://www.pinterest.com/"><i class="fa fa-pinterest"></i></a>
		<a href="http://instagram.com/"><i class="fa fa-instagram"></i></a>
	</p>
	<p class="comments">  <?php comments_number( 'No comment', 'One comment', '% comments' ); ?></p>
</div>