<?php 

if( !defined('ABSPATH') )	exit;

global $bagmasterpiece;

?>
</div>
	</div>
	
		<footer class="main-footer">
			<div class="footer container">
                            <div class="row">
                                <div class="col-md-8">
                                    <?php echo wpautop($bagmasterpiece['footer-notice']);?>
                                </div>
                                <div class="col-md-4">
                                    <?php get_image_from_option('footer-logo','BagMasterPiece');?>
                                    <ul class="social-wrap">
                                        <li class="fb"><a href="https://www.facebook.com/BagMasterpiece?ref=hl" target="_blank">Facebook</a></li>
                                        <li class="twitter"><a href="https://twitter.com/bagmasterpiece" target="_blank">Twitter</a></li>
                                        <li class="gplus"><a href="https://plus.google.com/105415189426342344652/about" target="_blank">Google Plus</a></li>
                                        <li class="pinterest"><a href="http://www.pinterest.com/bagmasterpiece/" target="_blank">Pinterest</a></li>
                                                    <li class="instagram"><a href="http://instagram.com/bagmasterpiece" target="_blank">Instagram</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
				<div class="col-md-12">
					<div class="copyright-text"><?php echo wpautop($bagmasterpiece['footer-copyright']);?></div>
				</div>
                            </div>
			</div>
		</footer>
</div>	
<!-- main page ends -->

	<?php wp_footer();?>
</body>
</html>