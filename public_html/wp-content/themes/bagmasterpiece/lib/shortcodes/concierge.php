<?php
function concierge_form_cb($atts, $content = ""){



	if( isset( $_GET['view'] ) and esc_attr($_GET['view']) == 'offer' && isset( $_GET['offer_id'] ) and get_post_type( esc_attr($_GET['offer_id']) ) == 'offer_post'):

	ob_start();

	include_once dirname(__FILE__) .'/concierge-offer.php';

	$form = ob_get_clean();

	elseif( isset( $_GET['view'] ) and esc_attr($_GET['view']) == 'accepted' ):

	ob_start();

	?>

	<div class="concierge-page">
		<div class="concierge-container">

			<div class="row">
				<div class="col-md-12">
					<h1>Accepted Offers</h1>
				</div>

				<div class="col-md-12">
					<div class="btn-group-container clearfix">
						<div class="pull-right">

							<a class="btn btn-default btn-back-to-list" href="<?php echo get_permalink(get_queried_object_id()); ?>">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							Back to list</a>
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

			<?php $post_id = isset($_GET['offer_id']) ? esc_attr($_GET['offer_id']) : 0;?>

			<div class="row">

				<?php offer_action_message();?>

				<div class="col-md-12">


					<table class="wp-list-table widefat fixed posts">
						<thead>
							<tr>
								<th scope="col" id="c_date" class="manage-column column-c_date" style="">Date</th>
								<th scope="col" id="item" class="manage-column column-item" style="">Budget</th>
								<th scope="col" id="brand" class="manage-column column-brand" style="">Time left</th>
								<th scope="col" id="style" class="manage-column column-style" style="">Status</th>
								<th scope="col" id="style" class="manage-column column-style" style=""></th>
							</tr>
						</thead>
						<tbody id="the-list">


					<?php

					   // @todo how could I get the offers only to me!!!

						$args = array(
									'post_type' => 'offer_post',
									'post_per_page' => -1,
									'meta_query' => array(
									       'relation' => 'AND',
											array(
													'key' => '_offer_status',
													'value' => array('accepted', 'completed'),
													'comapre' => 'IN'
												),
											array(
													'key' => 'concierge_author',
													'value' => get_current_user_id(),
													'comapre' => '='
												)
										)
								);

						$accepted_offers = new WP_Query( $args );

						if( $accepted_offers->have_posts() ){
							while( $accepted_offers->have_posts() ){

								$accepted_offers->the_post();

								?>

								<tr>
									<td><?php echo get_the_date('Y-m-d',get_the_ID()); ?></td>
									<td>
										<?php
											$amount = get_post_meta(get_the_ID(),'offer_budget', true);
											//commisize_budget($amount);
											echo wc_price($amount, array('decimals' => 0));
										?>
									</td>
									<td>
										<?php
											if( ! bmp_is_offer_status(get_the_ID(), 'completed') ){
												echo is_offer_available(get_the_ID());
											}
										?>
									</td>
									<td>
										<p class="offer-action"><span><b>Offer Status:</b></span>
											<?php if( get_post_meta(get_the_ID(), '_offer_on_hold', true) and ! bmp_is_offer_status(get_the_ID(), 'completed')):?>
											<span><i>Reserved</i></span>
											<?php elseif( bmp_is_offer_status(get_the_ID(), 'declined') ):?>
											<span><i>Passed</i></span>
											<?php elseif( bmp_is_offer_status(get_the_ID(), 'accepted') ):?>
											<span><i>Accepted</i></span>
											<?php elseif( bmp_is_offer_status(get_the_ID(), 'completed') ):?>
										    <span><i>Completed</i></span>
											<?php else:?>
											<span><i>Active</i></span>
											<?php endif;?>
										</p>
									</td>
									<td>
										<?php
											if( bmp_is_offer_status(get_the_ID(), 'completed') ){
												$view_offer = add_query_arg(array('view'=>'offer','offer_id'=>get_the_ID()),get_permalink(get_queried_object_id()));
												?>
												<span><a target="_blank" href="<?php echo $view_offer;?>" class="btn btn-default"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View</a></span>
												<?php
											}
											elseif( get_post_meta(get_the_ID(), '_offer_on_hold', true) ){

												$order_id = get_post_meta(get_the_ID(), '_offer_on_hold', true);

												$order = wc_get_order($order_id);

												?>
										      	<span><a href="<?php echo $order->get_view_order_url();?>" class="btn btn-default"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View Order</a></span>
										      	<span><a href="<?php echo $order->get_checkout_payment_url();?>" class="btn btn-success"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Complete Order</a></span>
												<?php
											}
											elseif( is_offer_available(get_the_ID()) ){

												$arg = array(
														'view' => 'accepted',
														'action' => 'complete',
														'offer_id'=> get_the_ID(),
														'return_url' => add_query_arg(array('view'=>'offer','offer_id'=>get_the_ID()),get_permalink(get_queried_object_id()))
												);

												$base_url = get_permalink( get_queried_object_id() );

												$complete_offer = wp_nonce_url(add_query_arg( $arg, $base_url ), 'really-complete-the-offer');

												?>
										      	<span><a href="<?php echo $complete_offer;?>" class="btn btn-success"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Complete Now</a></span>
												<?php
											}
											else{
												echo '';
											}
										?>
									</td>
								</tr>

								<?php
							}
						}
						else{
						    echo '<tr><td colspan="5">No offer is accepted yet.</td></tr>';
						}
					?>
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div>

	<?php

	$form = ob_get_clean();

	elseif( empty( $_GET ) or ( isset( $_GET['view'] ) and (esc_attr($_GET['view']) == 'request_all' or (esc_attr($_GET['view']) == 'offer' and !isset( $_GET['offer_id'] ) )) ) ):

	$type = ( isset($_GET['view']) and esc_attr($_GET['view']) == 'request_all' and current_user_can('Special') ) ? 1 : 0;

	$arg = array(
			'post_type' => 'concierge_post'
		);

	if( !$type ){
		$arg['author'] = get_current_user_id();
	}

	$concierges = new wp_query($arg);

	ob_start();

	?>
	<div class="concierge-page">
		<div class="container-fluid">

			<div class="row">
				<div class="col-md-12">
					<?php if( $type ):?>
					<h1>Requests List</h1>
					<?php else:?>
					<h1>My Requests</h1>
					<?php endif;?>
				</div>


				<?php dashboard_tabs();?>

				<div class="col-md-12">
					<div class="btn-group-container clearfix">

					<div class="pull-left">
						<a class="btn btn-info btn-accepted-offers" href="<?php echo add_query_arg(array('view'=>'accepted'),get_permalink(get_queried_object_id()) ); ?>">
						<span class="glyphicon glyphicon-check" aria-hidden="true"></span>
						Accepted offers</a>
					</div>

					<div class="btn-group pull-right">
						<?php if( current_user_can('Special') ):?>

							<?php if( $type ):?>
							<a class="btn btn-default" style="margin-left:0px" href="<?php echo get_permalink(get_queried_object_id()); ?>">
							<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
							View My Request</a>
							<?php else:?>
							<a class="btn btn-default" style="margin-left:0px" href="<?php echo add_query_arg(array('view'=>'request_all'),get_permalink(get_queried_object_id()) ); ?>">
							<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
							View Request List</a>
							<?php endif;?>

						<?php endif;?>

						<a class="btn btn-warning btn-submit-new-request" href="<?php echo add_query_arg(array('new'=>'concierge'),get_permalink(get_queried_object_id()) ); ?>">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
						Submit a New Request</a>
					</div>

					</div>
				</div>

			</div>

			<div class="row">
				<div class="col-md-12">

					<table class="wp-list-table widefat fixed posts">
						<thead>
							<tr>
								<th scope="col" id="c_date" class="manage-column column-c_date" style="">Date</th>
								<th scope="col" id="item" class="manage-column column-item" style="">Item</th>
								<th scope="col" id="brand" class="manage-column column-brand" style="">Brand</th>
								<th scope="col" id="style" class="manage-column column-style" style="">Style</th>
								<th scope="col" id="model" class="manage-column column-model" style="">Model</th>
								<th scope="col" id="description" class="manage-column column-description" style="">Description</th>
								<th scope="col" id="budget" class="manage-column column-budget" style="">Budget</th>
								<th scope="col" id="offer" class="manage-column column-budget" style="">Offer</th>
							</tr>
						</thead>
						<tbody id="the-list">
						<?php if( $concierges->have_posts() ):?>
							<?php while( $concierges->have_posts() ):?>
								<?php $concierges->the_post();?>

								<?php
									global $post;
									$self_requested = $post->post_author == get_current_user_id();
								?>

							<tr id="post-<?php the_ID();?>" <?php post_class();?>>
								<td class="c_date column-c_date"><?php manage_concierge_posts_custom_column('c_date', get_the_ID());?></td>
								<td class="item column-item"><?php manage_concierge_posts_custom_column('item', get_the_ID());?></td>
								<td class="brand column-brand"><?php manage_concierge_posts_custom_column('brand', get_the_ID());?></td>
								<td class="style column-style"><?php manage_concierge_posts_custom_column('style', get_the_ID());?></td>
								<td class="style column-model"><?php manage_concierge_posts_custom_column('model', get_the_ID());?></td>
								<td class="description column-description">
								<?php /*?>
									<?php
										$raw_params = get_post_meta(get_the_ID(), 'concierge_params', true);

										if( $raw_params != '' ):
											$params = explode(',', $raw_params );

										?>
										<?php if( is_array($params) ):?>

										<?php foreach( $params as $i => $p ):?>
										<?php
											$term = get_term_by('slug', $p, 'concierge');
											$label = '';
											if($term)
												$label = $term->name;
											else
												$label = 'Description '.($i+1);
										?>
										<p class="item">
											<strong style="display:inline-block;width:100px; text-align:left"><?php echo $label;?></strong>
											<i style="display:inline-block;text-align:left"><?php echo esc_attr( get_post_meta(get_the_ID(),"concierge_{$p}", true));?></i>
										</p>
										<?php endforeach;?>

										<?php endif;?>
									<?php endif;?>
								<?php */?>
								<?php //if( $type ):?>

									<div>
									<button class="btn btn-default btn-view-details btn-mini" data-toggle="modal" data-target="#viewDetails<?php the_ID();?>">View Details</button>

									<div class="modal fade" id="viewDetails<?php the_ID();?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									  <div class="modal-dialog">
									    <div class="modal-content">
									      <div class="modal-body">
									      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

									      	<div class="details_content">
									      		<p>
													<label for="title">Date</label>
													<span class="value"><?php echo get_the_date('Y-m-d',get_the_ID());?></span>
												</p>
										      	<p>
													<label for="title">Item</label>
													<span class="value"><?php manage_concierge_posts_custom_column('item', get_the_ID());?></span>
												</p>
												<p>
													<label for="title">Brand</label>
													<span class="value"><?php manage_concierge_posts_custom_column('brand', get_the_ID());?></span>
												</p>
												<p>
													<label for="title">Style</label>
													<span class="value"><?php manage_concierge_posts_custom_column('style', get_the_ID());?></span>
												</p>
												<p>
													<label for="title">Model</label>
													<span class="value"><?php echo get_post_meta(get_the_ID(),'concierge_model', true);?></span>
												</p>

												<?php
													$raw_params = get_post_meta(get_the_ID(), 'concierge_params', true);

													if( $raw_params != '' ):
														$params = explode(',', $raw_params );

													?>
													<?php if( is_array($params) ):?>

													<?php foreach( $params as $i => $p ):?>
													<?php
														$term = get_term_by('slug', $p, 'concierge');
														$label = '';
														if($term)
															$label = $term->name;
														else
															$label = 'Description '.($i+1);
													?>
													<p class="item">
														<strong><?php echo $label;?></strong>
														<i><?php echo esc_attr( get_post_meta(get_the_ID(),"concierge_{$p}", true));?></i>
													</p>
													<?php endforeach;?>

													<?php endif;?>
												<?php endif;?>

												<p>
													<label for="title">Budget</label>
													<span class="value">
														<?php
														/*
															$com = get_comission_for_user();

															if( $com === false || $self_requested){
																manage_concierge_posts_custom_column('budget', get_the_ID());
															}
															else{

																$amount = get_post_meta(get_the_ID(),'concierge_budget', true);

																if( $com['type'] == 'fixed' ){
																	echo $amount = $amount + (float) $com['amount'];
																}
																else{
																	echo $amount =  $amount + $amount *( (float) $com['amount']/100);
																}

															}

															*/

														$amount = get_post_meta(get_the_ID(),'concierge_budget', true);

														commisize_budget($amount);

														echo wc_price($amount, array('decimals' => 0));

														?>

													</span>
												</p>
												<p>
													<label for="title">Other Details/ Requests</label>
													<span class="value"><?php echo get_post_meta(get_the_ID(),'concierge_other_details', true)?></span>
												</p>

									      	</div>

									      	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									      </div>
									    </div>
									  </div>
									</div>
									</div>

								</td>

								<?php //endif;?>
								<td class="style column-budget">
									<?php
										$amount = get_post_meta(get_the_ID(),'concierge_budget', true);

										commisize_budget($amount, get_the_ID());

										echo wc_price($amount, array('decimals' => 0));
									?>

								</td>
								<td class="offer column-offer">

								<?php if( $type ):?>
									<?php

										if($self_requested){
											echo '<i>Self requested</i>';
											break;
										}
									?>
									<?php $offers = get_post_meta(get_the_ID(),'offer',true);?>

									<?php
										$found = false;

										if( is_array($offers) ){

											bmp_clean_array($offers);
											foreach( $offers as $post_id ){
												if( get_post_field( 'post_author', $post_id ) == get_current_user_id() ){
													$offer_id = $post_id;
													$found = true;
													break;
												}
											}
										}
									?>
									<?php if( $found ):?>
										<a href="<?php echo add_query_arg(array( 'view'=>'offer','offer_id'=>$offer_id ),get_permalink(get_queried_object_id()) ); ?>" class="btn btn-default btn-view-offer">View your offer</a>
									<?php else:?>
										<a href="<?php echo add_query_arg(array( 'new'=>'offer','concierge'=>get_the_ID() ),get_permalink(get_queried_object_id()) ); ?>" class="btn btn-info btn-submit">Submit</a>
									<?php endif;?>

								<?php else:?>

									<?php $offers = get_post_meta(get_the_ID(),'offer',true);?>
									<?php if( $offers !== false and !empty($offers) and count($offers) >0 ):?>

									<button class="btn btn-default btn-offer btn-mini" data-toggle="modal" data-target="#offerModal<?php the_ID();?>">Offer <span class="badge"><?php echo BMP_count_active_offers($offers);?></span></button><span class="badge"> <?php echo BMP_count_active_offers($offers);?></span>

									<div class="modal fade" id="offerModal<?php the_ID();?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									  <div class="modal-dialog">
									    <div class="modal-content">
									      <div class="modal-header">
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									        <h4 class="modal-title" id="myModalLabel">Offers for this item</h4>
									      </div>
									      <div class="modal-body">
										      	<table>
										      		<thead>
										      			<tr>
										      				<th>Budget</th>
										      				<th>Your Offer</th>
										      				<th>Action</th>
										      			</tr>
										      		</thead>
										      		<tbody>
												      	<?php foreach($offers as $offer){
												      		if( $offer == '' )
												      			continue;



												      		$view_offer = add_query_arg(array('view'=>'offer','offer_id'=>$offer),get_permalink(get_queried_object_id()));

												      		$base_url = add_query_arg(array('view' => 'offer','offer_id' => $offer),get_permalink( get_queried_object_id() ));
												      		$accept_offer_list_url = add_query_arg(array('view' => 'accepted','offer_id' => $offer),get_permalink( get_queried_object_id() ));

												      		$arg = array(
												      				'action' => 'accept',
												      				'return_url' => $accept_offer_list_url
												      		);
												      		$arg2 = array(
												      				'action' => 'cancel',
												      				'return_url' => $base_url
												      		);
												      		$arg3 = array(
												      				'action' => 'complete',
												      				'return_url' => add_query_arg(array('view'=>'offer','offer_id'=>$offer),get_permalink(get_queried_object_id()))
												      		);
												      		$accept_offer = wp_nonce_url(add_query_arg( $arg, $base_url ), 'really-accept-the-offer');
												      		$decline_offer = wp_nonce_url(add_query_arg( $arg2, $base_url ), 'really-cancel-the-offer');
												      		$complete_offer = wp_nonce_url(add_query_arg( $arg3, $base_url ), 'really-complete-the-offer');
												      		?>
												      		<tr>
												      			<td class="budget">
												      				<?php
												      					$amount = get_post_meta($offer, 'offer_budget',true);
												      					$reoffer = get_post_meta($offer, 'offer_reoffer', true);
												      					$counter = get_post_meta($offer, 'offer_reoffer_counter', true);

												      					commisize_budget($amount, $offer);

												      					if( $counter ){
												      					    commisize_budget($counter, $offer);
												      					    echo '<small><del>' . wc_price($amount, array('decimals' => 0)) . '</del></small>&nbsp;';
												      					    echo wc_price($counter, array('decimals' => 0));
												      					}
												      					else{
												      					    echo wc_price($amount, array('decimals' => 0));
												      					}


												      				?>
												      			</td>
												      			<td>
												      			   <?php
												      			       if( $reoffer ){
												      			           echo wc_price($reoffer, array('decimals' => 0));
												      			       }

												      			   ?>
												      			</td>
												      			<td>
												      				<?php if( bmp_is_offer_status($offer, 'declined') ):?>
												      				<i>Passed</i>
												      				<?php elseif( bmp_is_offer_status($offer, 'accepted') ):?>
												      				<span><i>This offer is accepted.</i></span>&nbsp;
												      				<span><a href="<?php echo $complete_offer;?>" class="btn btn-success"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Complete Now</a></span>
												      				<?php elseif( bmp_is_offer_status($offer, 'completed') ):?>
												      				<i>Completed</i>
												      				<?php else:?>
												      				<a href="<?php echo $accept_offer;?>" class="btn btn-success"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Accept</a>
												      				<a href="<?php echo $decline_offer;?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Decline</a>
												      				<a target="_blank" href="<?php echo $view_offer;?>" class="btn btn-default"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View</a>
												      				<?php endif;?>

												      				<?php if( !$reoffer && !bmp_is_offer_status($offer, 'accepted') ):?>

												      				<button class="btn btn-default" data-toggle="modal" data-target="#reofferModal<?php the_ID();?>">Make an offer</button>


												      				<div class="modal fade" id="reofferModal<?php the_ID();?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                									  <div class="modal-dialog modal-sm">
                                									    <div class="modal-content">
                                									      <div class="modal-header">
                                									        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                									        <h4 class="modal-title" id="myModalLabel">Make an offer</h4>
                                									      </div>
                                									      <div class="modal-body">
                                									           <?php //if( !get_post_meta($offer, 'offer_reoffer', true)):?>
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
                                               									           			'offer_id': <?php echo $offer?>,
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
                                									           <?php //endif;?>
                                									      </div>
                                									    </div>
                                									  </div>
                                									</div>
                                									<?php endif;?>

                                							     </td>
												      		</tr>
												      		<?php
												      	}?>
										      	</tbody>
										      </table>
									      </div>
									      <div class="modal-footer">
									        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									      </div>
									    </div>
									  </div>
									</div>

									<?php else:?>
									<p>No offer yet</p>
									<?php endif;?>
								<?php endif;?>

								</td>
							</tr>
							<?php endwhile;?>
						<?php else:?>
							<tr>
								<td colspan="8">
									<p>No item found</p>
									<p><a class="btn btn-warning btn-submit-new-request" href="<?php echo add_query_arg(array('new'=>'concierge'),get_permalink(get_queried_object_id()) ); ?>">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					Submit a New Request</a></p>
								</td>
							</tr>
						<?php endif;?>
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div>
		<?php

	$form = ob_get_clean();

	//	 new concierge form

	elseif( isset($_GET['new']) and ( $_GET['new'] == 'concierge' || ($_GET['new'] == 'offer' && isset($_GET['concierge']) && get_post_type(esc_attr($_GET['concierge'])) == 'concierge_post' ) ) )	:

	include_once dirname(__FILE__) . '/concierge-new.php';

	$form = ob_get_clean();

	else:

	$link = get_permalink(get_queried_object_id());

	$form = '<h1>Oh snap! There must be an error!</h1>';
	$form .= '<p><a href="' . $link . '" class="btn btn-default">View My Requests</a>';
	$form .= '<span class="sep"Or</span> <a href="' . add_query_arg(array('new'=>'concierge'),$link) . '" class="btn btn-default btn-submit-new-request">Submit a new request</a></p>';


	endif;

	return $form;
}

add_action( 'wp', 'save_concierge_req' );

function save_concierge_req(){

	if(
			( ! isset( $_REQUEST['from_concierge_submit_form'] ) or ! wp_verify_nonce($_REQUEST['from_concierge_submit_form'], 'action_concierge_submit_form') )  	&&
			( ! isset( $_REQUEST['from_consignment_submit_form'] ) or ! wp_verify_nonce($_REQUEST['from_consignment_submit_form'], 'action_consignment_submit_form') ) &&
			( ! isset( $_REQUEST['from_concierge_offer_submit_form'] ) or ! wp_verify_nonce($_REQUEST['from_concierge_offer_submit_form'], 'action_concierge_offer_submit_form') )
		)
		return;

	$user_id = get_current_user_id();

	$posttype = '';
	$type = '';

	if( isset( $_REQUEST['from_concierge_submit_form'] ) ){
		$type = 'concierge';
		$posttype = 'concierge';
	}
	elseif( isset( $_REQUEST['from_consignment_submit_form'] ) ){
		$type = 'consignment';
		$posttype = 'consignment';
	}
	elseif( isset( $_REQUEST['from_concierge_offer_submit_form'] ) ){
		$type = 'concierge';
		$posttype = 'offer';
	}
	else
		return;

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
		set_transient('__concierge_status_' . $user_id, '__FAILED__', 60);
		return;
	}

	delete_transient('__concierge_status_' . $user_id);

	$posted = array(
			"{$type}_item" => '',
			"{$type}_brand" => '',
			"{$type}_style" => '',
			"{$type}_model" => '',
			"{$type}_size" => '',
			"{$type}_stamp" => '',
			"{$type}_color1" => '',
			"{$type}_color2" => '',
			"{$type}_color3" => '',
			"{$type}_leather1" => '',
			"{$type}_leather2" => '',
			"{$type}_hardware" => '',
			"{$type}_budget" => '',
			"{$type}_other_details" => ''
	);

	foreach( $posted as $meta_key => $value ){

		$meta_value = esc_attr( $_REQUEST[ $meta_key ] );

		update_post_meta( $post_id, $meta_key, $meta_value );
	}

	if( $type ){

		$c_id = esc_attr( $_REQUEST[ 'concierge_id' ] );

		update_post_meta( $post_id, 'concierge_id', $c_id );

		$offer = (array) get_post_meta($c_id,'offer', true);

		if( empty($offer) )
			$offer = array($post_id);
		else
			$offer[] = $post_id;

		update_post_meta( $c_id, 'offer', $offer );
	}

	$location = $_REQUEST['r'];

	if( is_page($location) )
		wp_safe_redirect( get_permalink($location) );
	else
		wp_safe_redirect(home_url());
}