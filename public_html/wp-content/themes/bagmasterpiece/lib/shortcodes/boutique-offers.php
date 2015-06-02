<?php

/**
 * Boutique offers list shortcode callback
 * @param unknown $atts
 * @param string $content
 */

function BMP_get_boutique_offers($atts, $content = ""){

    $arg = array(
		'post_type' => 'consignment_offer',
	    'post_status' => 'any'
	);

	$arg['meta_query'] = array(
        array(
            'key'     => 'offer_user_id',
			'value'   => get_current_user_id(),
			'compare' => '='
        )
	);

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
                        		<th scope="col" id="offer_by" class="manage-column column-offer_by" style="">Item</th>
                        		<th scope="col" id="consignment_budget" class="manage-column column-consignment_budget" style="">Price</th>
                        		<th scope="col" id="offer_budget" class="manage-column column-offer_budget" style="">Offer</th>
                        		<th scope="col" id="counter_offer_budget" class="manage-column column-counter_offer_budget" style="">Counter Offer</th>
                        		<th scope="col" id="offer_status" class="manage-column column-offer_status" style="">Offer Status</th>
                        		<th scope="col" id="offer_status" class="manage-column column-offer_status" style="width: 25%;"></th>
                        	</tr>
                    	</thead>

                    	<tbody id="the-list">
                    	   <?php if( $concierges->have_posts() ):?>
						      <?php while( $concierges->have_posts() ):?>
								<?php $concierges->the_post();?>
                    	           <tr id="" class="type-consignment_offer status-publish hentry alternate iedit author-other level-0">
                						<td class="c_date column-c_date"><?php manage_consignment_offer_custom_column('c_date', get_the_ID());?></td>
                						<td class="c_date column-c_date">
                						  <?php
                						      $p_id = get_post_meta(get_the_ID(),'orig_offer_product_id', true);
                						  ?>
                						  <a class="button-secondary btn btn-default" href="<?php echo get_permalink($p_id);?>">View Item</a>
                						</td>
                						<td class="consignment_budget column-consignment_budget"><?php manage_consignment_offer_custom_column('consignment_budget', get_the_ID());?></td>
                						<td class="offer_budget column-offer_budget"><?php manage_consignment_offer_custom_column('offer_budget', get_the_ID());?></td>
                						<td class="counter_offer_budget column-counter_offer_budget"><?php manage_consignment_offer_custom_column('counter_offer_budget', get_the_ID());?></td>
                						<td class="offer_status column-offer_status"><?php manage_consignment_offer_custom_column('offer_status', get_the_ID());?></td>
                						<td class="offer_status column-offer_status">

                						<?php if(get_post_status( get_the_ID() ) == 'publish')    :?>

                						    <?php if( get_post_meta( get_the_ID(), 'offer_modifier', true) == get_current_user_id() ):?>

                						    <p><small>Your offer if awaiting review.</small></p>

                						    <?php else:?>

                						    <div class="btn-group" role="group">
                						      <?php
                						              $base_url = add_query_arg( array('view' => 'offers'), get_permalink( get_queried_object_id() ) );

                						              $arg = array(
                						                  'action' => 'accept',
                						                  'offer_id' => get_the_ID(),
                						                  'return_url' => $base_url
                						              );
                						              $arg2 = array(
                						                  'action' => 'cancel',
                						                  'offer_id' => get_the_ID(),
                						                  'return_url' => $base_url
                						              );


                						              $accept = wp_nonce_url(add_query_arg( $arg, $base_url ), 'really-accept-the-offer');
                						              $decline = wp_nonce_url(add_query_arg( $arg2, $base_url ), 'really-cancel-the-offer');
                						        ?>
                    						    <a class="btn btn-default" href="<?php echo $accept;?>">Accept</a>
                    						    <a class="btn btn-default" href="<?php echo $decline;?>">Decline</a>
                    						    <button class="btn btn-default" data-toggle="modal" data-target="#reofferModal<?php the_ID();?>">Counter</button>

                    				        </div>

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
                       									           			'action': 'reoffer_consignment',
                       									           			'offer_id': <?php echo get_the_ID()?>,
                       									           			're_offer_amount': $('#reofferamount').val()
                       									           	   };
                    									               $.post(woocommerce_params.ajax_url, data, function(response) {
                        									               console.log(response);
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
        									<?php elseif( get_post_status( get_the_ID() ) == 'accepted-offer'):?>
        									   <?php

        									       $the_who_accepted = '--';

        									       $accepttor_id = get_post_meta( get_the_ID(), 'offer_accepter', true );
        									       $offer = get_post( get_the_ID() );
        									       $consigner_id = $offer->post_author;
        									       $offerer_id = get_post_meta( get_the_ID(), 'offer_user_id', true );

        									       if( $accepttor_id  == get_current_user_id() ){
        									           $the_who_accepted = 'You';
        									       }
        									       elseif( $accepttor_id  == $consigner_id ){
        									           $the_who_accepted = 'Supplier';
        									       }
        									       elseif( $accepttor_id  == $offerer_id ){
        									           $the_who_accepted = 'Customer';
        									       }
        									       else{}


        									       $store_currency = isset( $_COOKIE['woocommerce_current_currency'] ) ? $_COOKIE['woocommerce_current_currency'] : null;
        									       $target_currency = get_option('woocommerce_currency');

        									       if( is_admin() || $store_currency == $target_currency || !$store_currency || !$target_currency){
        									           $decimal = 2;
        									       }
        									       else{
        									           $decimal = 6;
        									       }

        									   ?>
        									   <p>This offer is accepted by <i><?php echo $the_who_accepted;?></i> for <?php echo wc_price(get_post_meta( get_the_ID(), 'offer_budget', true ), $decimal);?></p>

        									   <?php if( is_offer_available(get_the_ID(), false) ):?>
        									       <p>Offer available for</p>
        									       <p><?php echo (is_offer_available(get_the_ID()));?></p>
        									       <?php
        									        $product_id = get_post_meta(get_the_ID(), 'offer_product_id', true);
                                                    $variant_id = get_post_meta(get_the_ID(), 'offer_variation_id', true);
                                                    $_pf = new WC_Product_Factory;
                                                    $product = ( $variant_id ) ? $_pf->get_product( $variant_id ) : $_pf->get_product( $product_id );
                                                    $offer_args = array(
                                                        'offer_id' => get_the_ID(),
                                                        'product_url' => $product->get_permalink(),
                                                    );

                                                    $pay_url = add_query_arg(array('__aewcoapi' => 1, 'woocommerce-offer-id' => $offer_args['offer_id']),$offer_args['product_url']);
        									   ?>
        									   <p><a href="<?php echo $pay_url;?>" class="btn btn-default">Deposit Now</a></p>
        									   <?php endif;?>

                    				    <?php endif;?>
                				        </td>
                            	   </tr>
                            	   <tr>

							</tr>
                    		  <?php endwhile;?>
						  <?php else:?>
							<tr>
								<td colspan="8">
									<p>No Offer yet</p>
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

    return $form;

}