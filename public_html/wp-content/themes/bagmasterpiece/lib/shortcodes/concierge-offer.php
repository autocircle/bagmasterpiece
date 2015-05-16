<?php

$assets_path = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';
wp_enqueue_script( 'prettyPhoto', $assets_path . 'js/prettyPhoto/jquery.prettyPhoto.min.js', array( 'jquery' ), '3.1.5', true );
wp_enqueue_script( 'prettyPhoto-init', $assets_path . 'js/prettyPhoto/jquery.prettyPhoto.init.min.js', array( 'jquery','prettyPhoto' ) );
wp_enqueue_style( 'woocommerce_prettyPhoto_css', $assets_path . 'css/prettyPhoto.css' );

?>

<div class="concierge-offer-page">
		<div class="concierge-container">

			<div class="row">
					<div class="col-md-12">
						<h1>Offer</h1>
					</div>

					<div class="col-md-12">
						<div class="btn-group-container clearfix">
							<div class="pull-left">
								<a class="btn btn-info btn-accepted-offers" href="<?php echo add_query_arg(array('view'=>'accepted'),get_permalink(get_queried_object_id()) ); ?>">
								<span class="glyphicon glyphicon-check" aria-hidden="true"></span>
								Accepted offers</a>
							</div>
							<div class="pull-right">

								<a class="btn btn-default btn-view-my-requests" style="margin-left:0px" href="<?php echo get_permalink(get_queried_object_id()); ?>">
								<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
								View My Request</a>
								<?php if( current_user_can('Special') ):?>
								<a class="btn btn-default" style="margin-left:0px" href="<?php echo add_query_arg(array('view'=>'request_all'),get_permalink(get_queried_object_id()) ); ?>">
								<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
								View Request List</a>
								<?php endif;?>

								<a class="btn btn-warning btn-submit-new-request" href="<?php echo add_query_arg(array('new'=>'concierge'),get_permalink(get_queried_object_id()) ); ?>">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
								Submit a New Request</a>

							</div>
						</div>
					</div>
			</div>

			<?php $post_id = esc_attr($_GET['offer_id']);?>

			<div class="row">

				<?php offer_action_message();?>

				<div class="col-md-12">
					<div class="offer-main">

						<div id="product-<?php echo $post_id; ?>" <?php post_class(array('product', 'offer')); ?>>

							<div class="row">

								<div class="col-xs-12">
									<div class="brand-block">
										<h3><?php echo get_bmp_data($post_id, 'offer_brand', true);?></h3>
									</div>
								</div>

								<div class="col-md-4">
									<div class="title-block">
										<h3><?php echo get_bmp_data($post_id,'offer_style', true);?></h3>
										<h3><?php echo get_bmp_data($post_id,'offer_model', true);?></h3>
									</div>

									<div class="description-block">
										<h3>Product Specifications</h3>

										<div class="specifications">
											<?php
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
								<?php

									$pics = bmp_clean_array(get_post_meta($post_id, 'offer_pics', true));

									if( is_array($pics) and !empty($pics) ){

										$first = array_shift($pics);

										$thumnail = wp_get_attachment_image_src($first, 'full');

										$count = 0;
									}

								?>
								<div class="col-md-4">
									<div class="thumbnail-block">
										<div class="thumbnail-block">
											<div class="images">
												<?php if( $thumnail ):?>
												<a href="<?php echo $thumnail[0];?>" itemprop="image" class="woocommerce-main-image zoom" title="title" data-rel="prettyPhoto[product-gallery]">
													<?php echo wp_get_attachment_image($first, 'shop_single');?>
												</a>
												<?php else:?>
													<img src="<?php echo wc_placeholder_img_src();?>" alt="Placeholder" />
												<?php endif;?>
												<?php
													if( !empty($pics) ){
														echo '<div class="thumbnails columns-3">';
														foreach( $pics as $pic ){

															$count++;

															if( $count == 1 ){
																$class = 'first';
															}
															elseif( $count%3 == 0 ){
																$class = 'last';
															}

															$url =  wp_get_attachment_image_src($pic, 'full');
															$img = wp_get_attachment_image($pic);
															?>
															<a href="<?php echo $url[0];?>" class="zoom <?php echo $class;?>" title="title" data-rel="prettyPhoto[product-gallery]">
																<?php echo $img;?>
															</a>
															<?php
														}
														echo '</div>';
													}
												?>

											</div>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="action-block">
										<?php if( is_offer_available($post_id) ):?>

											<div class="timer">
												<p class="text-center">Time left</p>

												<h1 class="date-block">
												    <span class="timer" id="cart-countdown"><?php echo is_offer_available($post_id);?></span>
												</h1>

												<script>
                                                	function startTimer(duration, display) {
                                                		var start = Date.now(),
                                                			diff,
                                                			minutes,
                                                			seconds,
                                                			interval;
                                                		function timer() {
                                                			// get the number of seconds that have elapsed since
                                                			// startTimer() was called
                                                			diff = duration - (((Date.now() - start) / 1000) | 0);

                                                			// does the same job as parseInt truncates the float
                                                			hours   = ( diff / 3600 ) | 0;
                                                			diff2   =  diff % 3600;
                                                			minutes = (diff2 / 60) | 0;
                                                			seconds = (diff2 % 60) | 0;

                                                			hours 	= hours   < 10 ? "0" + hours   : hours;
                                                			minutes = minutes < 10 ? "0" + minutes : minutes;
                                                			seconds = seconds < 10 ? "0" + seconds : seconds;

                                                			display.textContent = hours + ":" + minutes + ":" + seconds;

                                                			if (diff <= 0) {
                                                				// add one second so that the count down starts at the full duration
                                                				// example 05:00 not 04:59
                                                				start = Date.now() + 1000;
                                                				clearInterval(interval);
                                                				window.location.reload();
                                                			}

                                                		};
                                                		// we don't want to wait a full second before the timer starts
                                                		timer();
                                                		interval = setInterval(timer, 1000);
                                                	}

                                                	jQuery(document).ready(function($){
                                                		var fiveMinutes = <?php echo is_offer_available($post_id, false);?>,
                                                		display = document.querySelector('#cart-countdown');

                                                		if( fiveMinutes > 0 ){
                                                			startTimer(fiveMinutes, display);
                                                		}
                                                	});

                                                </script>

											</div>

											<?php
												$post = get_post($post_id);
												$self_requested = $post->post_author == get_current_user_id();

											?>

											<div class="budget">
												<?php
													$amount = get_post_meta($post_id,'offer_budget', true);

													if( ! $self_requested ){
													    $amount = commisize_budget($amount, $post_id);
													}

													echo wc_price($amount, array('decimals' => 0));
												?>
											</div>

											<?php if( ! bmp_is_offer_status($post_id, 'declined') and !$self_requested ):?>

											<?php
												$accept_offer_url = '';
												$accept_offer_list_url = add_query_arg(array('view' => 'accepted','offer_id' => $offer),get_permalink( get_queried_object_id() ));
												$base_url = add_query_arg(array('view' => 'offer','offer_id' => $post_id),get_permalink( get_queried_object_id() ));

												$arg = array(
														'action' => 'accept',
														'return_url' => $accept_offer_list_url
												);
												$arg2 = array(
															'action' => 'cancel',
															'return_url' => $base_url
														);

												$accept_offer_url = wp_nonce_url(add_query_arg( $arg, $base_url ), 'really-accept-the-offer');
												$cancel_offer_url = wp_nonce_url(add_query_arg( $arg2, $base_url ), 'really-cancel-the-offer');

												$reoffer = get_post_meta($post_id, 'offer_reoffer', true);

											?>

											<?php if( $reoffer ):?>
											<div class="reoffer">
											     <p class="text-center"><strong>Your Offer:</strong>&nbsp;<span><?php echo wc_price($reoffer, array('decimals' => 0));?></span></p>
											</div>
											<?php endif;?>

											<div class="action-buttons">
												<p>
													<a class="btn btn-default btn-action btn-accept" href="<?php echo $accept_offer_url;?>">Accept</a>
													<button class="btn btn-default btn-action btn-pass" data-toggle="modal" data-target="#cancelOffer">Pass</button>
													<?php if( !$reoffer ):?>
													<button class="btn btn-default btn-reoffer" data-toggle="modal" data-target="#reOffer">Make an offer</button>
													<?php endif;?>
												</p>

												<div class="modal fade" id="cancelOffer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
												  <div class="modal-dialog">
												    <div class="modal-content">
												      <div class="modal-body">
												      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													      <p>Are you sure that you want to decline this offer? You will not be able to accept this offer anymore.</p>

												      	  <button type="button" class="btn btn-default btn-previous" data-dismiss="modal">Cancel</button>
												      	  <a href="<?php echo $cancel_offer_url;?>" class="btn btn-default btn-continue">Confirm</a>
												      </div>
												    </div>
												  </div>
												</div>

												<?php if( !$reoffer ):?>

												<div class="modal fade" id="reOffer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            									  <div class="modal-dialog modal-sm">
            									    <div class="modal-content">
            									      <div class="modal-header">
            									        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            									        <h4 class="modal-title" id="myModalLabel">Make an offer</h4>
            									      </div>
            									      <div class="modal-body">
            									               <script type="text/javascript">

            									               jQuery(document).ready(function($){
                									               $('#reoffersubmit').on('click', function(e){
                    									               if($(this).hasClass('disabled')){
                        									               return;
                        									           }
                    									               $('#res').text('');
                    									               $(this).addClass('disabled');
                    									               e.preventDefault();
                    									               var data = {
                       									           			'action': 'reoffer',
                       									           			'offer_id': <?php echo $post_id?>,
                       									           			're_offer_amount': $('#reofferamount').val()
                       									           	   };
                    									               $.post(woocommerce_params.ajax_url, data, function(response) {
                        									               response = JSON.parse(response);
                        									               if(response.status == 200){
                            									               $('#reofferamount').val('');
                            									           }
                    									            	   $('#reoffersubmit').removeClass('disabled');
                    									            	   $('#res').text(response.message);
                               									       });

                    									               return false;
                									               });
            									               });


            									               </script>
            									               <div id="res"></div>
            									               <form action="" method="post" id="reofferform">
            									                   <input type="text" name="reofferamount" id="reofferamount" value="" placeholder="Your amount">
            									                   <button type="button" id="reoffersubmit" class="btn btn-default">Confirm</button>
            									               </form>
            									      </div>
            									    </div>
            									  </div>
            									</div>

            									<?php endif;?>
											</div>
											<?php elseif( $self_requested ):?>

											    <?php $reoffer = get_post_meta($post_id, 'offer_reoffer', true);?>

												<?php if( $reoffer ):?>
												<div class="reoffer">
												     <p class="text-center"><strong>Offered:</strong>&nbsp;<span><?php echo wc_price($reoffer, array('decimals' => 0));?></span></p>
												</div>

												<div class="action-buttons">
    												<p>
    													<a class="btn btn-default" href="<?php //echo $accept_offer_url;?>">Accept</a>
    													<button class="btn btn-default" data-toggle="modal" data-target="#cancelOffer">Decline</button>
    													<button class="btn btn-default btn-reoffer" data-toggle="modal" data-target="#reOffer">Make an offer</button>
    												</p>
												</div>

												<?php endif;?>

												<p class="offer-action"><span><b>Offer Status:</b></span>
													<?php if( bmp_is_offer_status($post_id, 'declined') ):?>
													<span><i>Passed</i></span>
													<?php elseif( bmp_is_offer_status($post_id, 'accepted') ):?>
													<span><i>Accepted</i></span>
													<?php elseif( bmp_is_offer_status($post_id, 'completed') ):?>
												    <span><i>Completed</i></span>
													<?php else:?>
													<span><i>Active</i></span>
													<?php endif;?>
												</p>
											<?php else:?>
											<p class="offer-action offer-passed">You have passed this offer.</p>
											<?php endif;?>
										<?php else:?>
											<p class="offer-action offer-time-out">Offer timed out.</p>
										<?php endif;?>


									</div>
								</div>
							</div>

						</div>

					</div>
				</div>
			</div>
		</div>
	</div>