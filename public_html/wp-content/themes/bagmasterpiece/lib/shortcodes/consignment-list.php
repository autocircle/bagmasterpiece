<?php

	$arg = array(
		'post_type' => 'consignment_post'
	);

	$arg['author'] = get_current_user_id();

	$concierges = new wp_query($arg);

	ob_start();

	?>
	<div class="concierge-page">
		<div class="container-fluid">

			<div class="row">
				<div class="col-md-12">
					<h1>My Consignments</h1>
				</div>

				<?php dashboard_tabs();?>

				<div class="col-md-12">
					<div class="btn-group-container clearfix">
						<div class="btn-group pull-left">
						  <a class="btn btn-warning btn-view-offers" href="<?php echo add_query_arg(array('view'=>'offers'),get_permalink(get_queried_object_id()) ); ?>">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							View Offers</a>
						</div>
						<div class="btn-group pull-right">
							<a class="btn btn-warning btn-submit-new-request" href="<?php echo add_query_arg(array('new'=>'consignment'),get_permalink(get_queried_object_id()) ); ?>">
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
								<th scope="col" id="brand" class="manage-column column-style" style="">Style</th>
								<th scope="col" id="brand" class="manage-column column-model" style="">Model</th>
								<th scope="col" id="description" class="manage-column column-description" style="">Description</th>
								<th scope="col" id="budget" class="manage-column column-budget" style="">Budget</th>
								<th scope="col" id="status" class="manage-column column-status" style="">Status</th>
							</tr>
						</thead>
						<tbody id="the-list">
						<?php if( $concierges->have_posts() ):?>
							<?php while( $concierges->have_posts() ):?>
								<?php $concierges->the_post();?>

							<tr id="post-<?php the_ID();?>" <?php post_class();?>>
								<td class="c_date column-c_date"><?php manage_consignment_posts_custom_column('c_date', get_the_ID());?></td>
								<td class="item column-item"><?php manage_consignment_posts_custom_column('item', get_the_ID());?></td>
								<td class="brand column-brand"><?php manage_consignment_posts_custom_column('brand', get_the_ID());?></td>
								<td class="brand column-style"><?php manage_consignment_posts_custom_column('style', get_the_ID());?></td>
								<td class="brand column-model"><?php manage_consignment_posts_custom_column('model', get_the_ID());?></td>
								<td class="description column-description">
								    <button class="btn btn-default btn-view-details btn-mini" data-toggle="modal" data-target="#viewDetails<?php the_ID();?>">View Details</button>
								    <div class="modal fade" id="viewDetails<?php the_ID();?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									  <div class="modal-dialog">
									    <div class="modal-content">
									      <div class="modal-body">
									      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

									      	<div class="details_content">
									      		<p>
													<label for="title">Date</label>
													<span class="value"><?php manage_consignment_posts_custom_column('c_date', get_the_ID());?></span>
												</p>
										      	<p>
													<label for="title">Item</label>
													<span class="value"><?php manage_consignment_posts_custom_column('item', get_the_ID());?></span>
												</p>
												<p>
													<label for="title">Brand</label>
													<span class="value"><?php manage_consignment_posts_custom_column('brand', get_the_ID());?></span>
												</p>
												<p>
													<label for="title">Style</label>
													<span class="value"><?php manage_consignment_posts_custom_column('style', get_the_ID());?></span>
												</p>
												<p>
													<label for="title">Model</label>
													<span class="value"><?php manage_consignment_posts_custom_column('model', get_the_ID());?></span>
												</p>

												<?php
            										$raw_params = get_post_meta(get_the_ID(), 'consignment_params', true);

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
            											<i><?php echo esc_attr( get_post_meta(get_the_ID(),"consignment_{$p}", true));?></i>
            										</p>
            										<?php endforeach;?>

            										<?php endif;?>
            									<?php endif;?>

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

								</td>
								<td class="style column-budget"><?php echo wc_price(get_post_meta(get_the_ID(),'consignment_budget', true));?></td>
								<td class="style column-status"><?php manage_consignment_posts_custom_column('status', get_the_ID());?></td>
							</tr>
							<?php endwhile;?>
						<?php else:?>
							<tr>
								<td colspan="8">
									<p>No item found</p>
									<p><a class="btn btn-warning btn-submit-new-request" href="<?php echo add_query_arg(array('new'=>'consignment'),get_permalink(get_queried_object_id()) ); ?>">
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