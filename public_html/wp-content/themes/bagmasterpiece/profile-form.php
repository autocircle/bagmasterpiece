<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/

global $bagmasterpiece;

$data = array(
		'first_name' => '',
		'last_name' =>'',
		'dob' =>'',
		'gender' =>'',
		'nos' =>'',
		'address' =>'',
		'city' =>'',
		'state' =>'',
		'country' =>'',
		'zip' =>'',
		'contact_number' =>'',
		'email' =>'',
		'whatsapp' =>'',
		'viber' =>'',
		'currency' =>'',
		'subscriptions' => array(),
        'notifications' => array()

	);

$user_id = get_current_user_id();

global $current_user;

wp_get_current_user();

foreach( $data as $key => $value ){
	$data[$key] = get_user_meta($user_id, $key, true);
}

//$arg = get_user_meta(get_current_user_id(), 'notifications', true) ;

//var_dump( sms_notification_enabled( get_current_user_id() ) );

$data['email'] = $current_user->data->user_email;

//var_dump($data);

?>

<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/assets/js/angular.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/assets/js/angular-animate.min.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/assets/js/ngDialog.min.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/assets/js/dropzone.js"></script>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/assets/css/dropzone.css">
	<script type="text/javascript">

		var profileData = <?php echo json_encode($data);?>;
		var admin_ajax = "<?php echo admin_url('admin-ajax.php');?>";
		var RETURN_URL = "<?php echo get_permalink( get_queried_object_id() ); ?>";
	</script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/assets/js/app.js"></script>

<div class="login profile" id="theme-my-login<?php $template->the_instance(); ?>" data-ng-app="BMPAPP">




	<div class="container-fluid">
		<div class="row" data-ng-controller="profileController">

        <div class="col-md-12">
            <?php $template->the_action_template_message( 'profile' ); ?>
		      <?php $template->the_errors(); ?>
        </div>

		<noscript>
			<h3>You need to have Javascript enabled to acccess this page. Please upgrade your browser to have this resolved.</h3>
		</noscript>

		<?php if( current_user_can('regular') ):?>
		<div class="col-md-12 woocommerce">
			<ul class="woocommerce-error">
				<li>Learn how to be VIP <a class="button wc-forward" href="<?php echo get_permalink($bagmasterpiece['sms-verification-page-id']);?>">Learn More</a></li>
			</ul>
		</div>
		<?php endif;?>

		<?php if( !user_verified_by_email() ):?>
		<?php
		      $ra = get_permalink( Theme_My_Login::get_page_id( 'profile' ) );
		      $ra = wp_nonce_url($ra, 'really-verify-resend-activation', 'resend_activation');
		?>
		<div class="col-md-12 woocommerce">
			<ul class="woocommerce-error">
				<li>You have not yet confirmed your e-mail address. <a class="button wc-forward" href="<?php echo $ra;?>">Resend Activation Key</a></li>
			</ul>
		</div>
		<?php endif;?>

		<div role="tabpanel">


		<?php
		  global $bagmasterpiece;

		  $profile_page_id = $bagmasterpiece['profile-page-id'];

		  $concierge = $bagmasterpiece['concierge-page-id'] ? get_permalink( $bagmasterpiece['concierge-page-id'] ) : '';
		  $consignment = $bagmasterpiece['consignment-page-id'] ? get_permalink( $bagmasterpiece['consignment-page-id'] ) : '';

		  $profile = '#profile';
		  $subscriptions = '#subscriptions';
		  $orders = '#orders';
		  $accounts = '#accounts';
		  $change_pass = '#change-pass';

		  $current_page_id = get_queried_object_id();

		  if( $profile_page_id != $current_page_id ){

		      $profile = get_permalink( $bagmasterpiece['profile-page-id'] );
		      $subscriptions = add_query_arg( array('tab'=>'subscriptions'), $profile);
		      $orders = add_query_arg( array('tab'=>'orders'), $profile);
		      $accounts = add_query_arg( array('tab'=>'accounts'), $profile);
		      $change_pass = add_query_arg( array('tab'=>'change-pass'), $profile);
		  }

		?>

		<div class="col-md-12">
		  <!-- Nav tabs -->
		  <ul class="nav nav-tabs" role="tablist">
		    <li role="presentation" <?php echo tab_active_class('profile');?>><a href="<?php echo $profile;?>" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
		    <li role="presentation" <?php echo tab_active_class('concierge');?>><a href="<?php echo $concierge;?>">My Concierge</a></li>
		    <li role="presentation" <?php echo tab_active_class('consignment');?>><a href="<?php echo $consignment;?>">My Consignment</a></li>
		    <li role="presentation" <?php echo tab_active_class('profile', 'subscriptions');?>><a href="<?php echo $subscriptions;?>" aria-controls="subscriptions" role="tab" data-toggle="tab">My Subscriptions</a></li>
		    <li role="presentation" <?php echo tab_active_class('profile', 'orders');?>><a href="<?php echo $orders;?>" aria-controls="orders" role="tab" data-toggle="tab">Orders</a></li>
		    <li role="presentation" <?php echo tab_active_class('profile', 'accounts');?>><a href="<?php echo $accounts;?>" aria-controls="accounts" role="tab" data-toggle="tab">Billing &amp; Shipping</a></li>
		    <li role="presentation" <?php echo tab_active_class('profile', 'change-pass');?>><a href="<?php echo $change_pass;?>" aria-controls="change-pass" role="tab" data-toggle="tab">Change Password</a></li>
		  </ul>
		</div>
		<!-- Tab panes -->
		  <div class="tab-content">
		    <div role="tabpanel" <?php echo tab_active_class('profile', '', 'tab-pane');?> id="profile">

		<form id="your-profile" action="<?php $template->the_action_url( 'profile' ); ?>" method="post" class="ng-cloak">
		<?php wp_nonce_field( 'update-user_' . $current_user->ID ); ?>

			<input type="hidden" name="from" value="profile" />
			<input type="hidden" name="checkuser_id" value="<?php echo $current_user->ID; ?>" />

			<div class="col-md-12 woocommerce" data-ng-if="error.length>0">
				<ul class="woocommerce-error">
					<li data-ng-repeat="e in error" ng-bind-html="e.message"></li>
				</ul>
			</div>


			<div class="col-md-12">
				<div class="panel-group" role="tablist" aria-multiselectable="true">
					<div class="panel panel-area">
					    <div class="panel-heading panel-bg" role="tab" id="headingOne">
						    <h4 class="panel-title">MEMBER'S DETAILS</h4>
					    </div>
					    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne" aria-expanded="true">
							<div class="panel-body panel-body-style">
								<div class="row placeholder panel-pad">
									<div class="col-md-6 panel-img">
										<img src="<?php echo get_template_directory_uri()?>/images/pic-4.jpg" class="img-responsive" alt="Responsive image">
									</div>
									<div class="col-md-6 panel-right-form">
										<div class="form-group panel-form-style">
											<label for="firstname">First Name</label>
											<input type="text" class="form-control" id="firstname" placeholder="First Name" value="" data-ng-model="data.firstName">
										</div>
										<div class="form-group panel-form-style">
											<label for="lastname">Last Name</label>
											<input type="text" class="form-control" id="lastname" placeholder="Last Name" value="" data-ng-model="data.lastName">
										</div>
										<div class="form-group panel-form-style">
											<label for="dob">Date of Birth</label>
											<input type="text" class="form-control" id="dob" placeholder="Date of Birth" data-ng-model="data.dob">
										</div>
										<div class="form-group panel-form-style">
											<label for="gender">Gender</label>
											<select id="gender" name="gender" data-ng-model="data.gender" data-ng-options="i.value as i.label for i in param.gender">
												<option value="">Select Gender</option>
											</select>
										</div>
										<div class="form-group panel-form-style">
											<label for="nos">Name of Spouse</label>
											<input type="text" class="form-control" id="nos" name="nos" placeholder="Name of Spouse" data-ng-model="data.nos">
										</div>
									</div>
								</div>
							</div>
						</div>
				    </div>
					<div class="panel panel-area">
						<div class="panel-heading panel-bg" role="tab" id="headingTwo">
							<h4 class="panel-title">MAILING ADDRESS & CONTACT DETAILS</h4>
						</div>
					    <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false">
						    <div class="panel-body panel-body-style">
								<div class="row placeholder panel-pad">
									<div class="col-md-7 panel-right-form">
										<div class="form-horizontal">
											<div class="form-group panel-form-style">
												<label for="address" class="col-sm-12 control-label"><span>Address</span></label>
												<div class="col-sm-12">
												    <input type="text" class="form-control" id="address" placeholder="Address" data-ng-model="data.address">
												</div>
											</div>
											<div class="form-group panel-form-style label-style">
												<label for="city" class="col-sm-6 control-label"><span>City</span></label>
												<label for="state" class="col-sm-6 control-label"><span>State / Province</span></label>
											</div>
											<div class="form-group panel-form-style">
												<div class="col-sm-6">
												  <input type="text" class="form-control" id="city" placeholder="City" data-ng-model="data.city">
												</div>
												<div class="col-sm-6">
												  <input type="text" class="form-control" id="state" placeholder="State / Province" data-ng-model="data.state">
												</div>
											</div>
											<div class="form-group panel-form-style label-style">
												<label for="country" class="col-sm-6 control-label"><span>Country</span></label>
												<label for="zip" class="col-sm-6 control-label"><span>Zip / Postal Code</span></label>
											</div>
											<div class="form-group panel-form-style">
												<div class="col-sm-6">
												  <input type="text" class="form-control" id="country" placeholder="Country" data-ng-model="data.country">
												</div>
												<div class="col-sm-6">
												  <input type="text" class="form-control" id="zip" placeholder="Zip / Postal Code" data-ng-model="data.zip">
												</div>
											</div>
											<div class="form-group panel-form-style label-style">
												<label for="contact-number" class="col-sm-6 control-label disabled-input-container">
												    <span>Contact Number</span>
												    <a class="popover-trigger" tabindex="0" data-toggle="popover" data-trigger="focus" data-content="The confirmed phone number can only be changed with email confirmation.">
												        <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
												    </a>
												</label>
												<label for="email" class="col-sm-6 control-label disabled-input-container">
												    <span>Email</span>
												    <a class="popover-trigger" tabindex="0" data-toggle="popover" data-trigger="focus" data-content="The email can be changed only with SMS notification or email confirmation.">
												        <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
												    </a>
												</label>
											</div>
											<div class="form-group panel-form-style">
												<div class="col-sm-6">
												  <p class="disabled-input" id="contact-number">
												    <?php echo $data['contact_number'];?>
												  </p>
												</div>
												<div class="col-sm-6">
												  <p class="disabled-input" id="contact-number">
												    <?php echo $data['email'];?>
												  </p>
												</div>
											</div>
											<div class="form-group panel-form-style label-style">
												<label for="whatsapp" class="col-sm-6 control-label"><span>Whatsapp</span></label>
												<label for="viber" class="col-sm-6 control-label"><span>Viber</span></label>
											</div>
											<div class="form-group panel-form-style">
												<div class="col-sm-6">
												  <input type="text" class="form-control" id="whatsapp" placeholder="Whatsapp" data-ng-model="data.whatsapp">
												</div>
												<div class="col-sm-6">
												  <input type="text" class="form-control" id="viber" placeholder="Viber" data-ng-model="data.viber">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-5 panel-img">
										<img src="<?php echo get_template_directory_uri()?>/images/pic-1.jpg" class="img-responsive panel-img" alt="Responsive image">
									</div>

								</div>
						    </div>
					    </div>
					</div>

					<div class="panel panel-area">
						<div class="panel-heading panel-bg" role="tab" id="headingThree">
						    <h4 class="panel-title">CURRENCY & SUBSCRIPTIONS</h4>
					    </div>
					    <div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
						    <div class="panel-body panel-body-style">

								<div class="row placeholder panel-pad">
									<div class="col-md-4 col-lg-4 currency-left-bg">
										<div class="form-group panel-form-style currency-font-style">
											<label for="currency">Currency</label>
											<select class="form-control input-bg" id="currency" name="" data-ng-model="data.currency" data-ng-options="i as i for (i,j) in param.currency">
											     <option value="">Select Currency</option>
											</select>
										</div>
									</div>
									<div class="col-md-8 col-lg-8 currency-right-bg">
										<div class="form-group">
											<div class="checkbox">
												<p class="subscribed"><span>SUBSCRIBED TO:</span></p>
											</div>
										</div>
										<div class="form-group">
											<div class="subscripton-items">
												<label data-ng-repeat="a in param.subscriptions" data-ng-class="{'fat':$index==2}" >
													<input type="checkbox" value="{{a.value}}" data-check-list='data.subscriptions'> {{a.label}}
												</label>
											</div>
										</div>
									</div>
								</div>

						    </div>
					    </div>
					</div>


					<div class="panel panel-area">
						<div class="panel-heading panel-bg" role="tab" id="headingThree">
						    <h4 class="panel-title">Notification Preferences</h4>
					    </div>
					    <div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
						    <div class="panel-body panel-body-style">

								<div class="row placeholder panel-pad">
									<div class="col-md-12">
										<div class="form-group">
											<div class="checkbox">
												<p class="subscribed"><span>Receive Notificatins via:</span></p>
											</div>
										</div>
										<div class="form-group">
											<div class="subscripton-items">
												<label>
													<input type="checkbox" value="email" name="notifications-email" disabled="disabled" class="disabled" checked="checked"> Email
												</label>
												<label data-ng-repeat="a in param.notifications" data-ng-class="{'fat':$index==2}" >
													<input type="checkbox" value="{{a.value}}" data-check-list='data.notifications'> {{a.label}}
												</label>
											</div>
										</div>
									</div>
								</div>

						    </div>
					    </div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<p class="text-center">
					<button type="button" class="btn btn-default update-button update-button-disabled" data-ng-if="processing"></button>
					<button type="button" class="btn btn-default update-button" data-ng-click="submitForm()" data-ng-if="!processing"></button>
				</p>
			</div>

		</form>


		</div>
		    <div role="tabpanel" <?php echo tab_active_class('profile', 'subscriptions', 'tab-pane');?> id="subscriptions">
		    	<div class="col-md-12">
					<div class="panel panel-area">
						<div class="panel-heading panel-bg" role="tab" id="headingFour">
						    <h4 class="panel-title">My Subscription</h4>
					    </div>
					    <div id="collapseFour" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFour" aria-expanded="false">
						    <div class="panel-body panel-body-style">
						      <!--  -- >
						        <form action="<?php echo get_permalink( $bagmasterpiece['profile-page-id'] )?>" method="post">

                                	<div class="panel panel-area">
                						<div class="panel-heading panel-bg" role="tab" id="headingFour">
                						    <h4 class="panel-title">Notification Preference</h4>
                					    </div>
                					    <div id="collapseFour" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFour" aria-expanded="false">
                						    <div class="panel-body panel-body-style">
                								<div class="row placeholder">

                                                    <div class="col-md-12">
                										<div class="form-horizontal form-preference">
                											<div class="form-group panel-form-style">
                												<div class="col-sm-4">
                												    <label for="currentpass" class="control-label"><span>Notification Preference</span></label>
                												</div>
                												<div class="col-sm-8">
                												    <label><input type="checkbox" name="notification-email" checked="checked" disabled value="1"> EMAIL</label>
                												    <label><input type="checkbox" name="notification-sms" value="1"> SMS</label>
                												</div>
                											</div>
                										</div>
                									</div>

                								</div>
                						    </div>
                					    </div>
                					</div>
                					<div class="row">
                					   <div class="col-md-12">
                						    <p style="margin-top:15px;"></p>
                                        	<p class="text-center">
                                        		<?php wp_nonce_field( 'save_notification_preference_details', 'save_subscriptions_my_profile' ); ?>
                					            <button type="submit" class="btn btn-default update-button"></button>
                                        	</p>
                						</div>
                					</div>
                                </form> <!-- #chnage password form -->
						    </div>
					    </div>
					</div>
				</div>
		    </div>
		    <div role="tabpanel" <?php echo tab_active_class('profile', 'accounts', 'tab-pane');?> id="accounts">
		    	<div class="col-md-12">
					<?php wc_get_template( 'myaccount/my-address.php' ); ?>
				</div>
		    </div>
		    <div role="tabpanel" <?php echo tab_active_class('profile', 'orders', 'tab-pane');?> id="orders">
		    	<div class="col-md-12">
		    		<?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => -1 ) ); ?>
		    	</div>
		    </div>
		    <div role="tabpanel" <?php echo tab_active_class('profile', 'change-pass', 'tab-pane');?> id="change-pass">
		    	<div class="col-md-12">

		    	<form action="" method="post">

                	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

                	<div class="panel panel-area">
						<div class="panel-heading panel-bg" role="tab" id="headingFour">
						    <h4 class="panel-title">CHANGE PASSWORD</h4>
					    </div>
					    <div id="collapseFour" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFour" aria-expanded="false">
						    <div class="panel-body panel-body-style">
								<div class="row placeholder">

								    <div class="col-md-12 woocommerce" data-ng-if="error.length>0">
                        				<ul class="woocommerce-error">
                        					<li data-ng-repeat="e in error" ng-bind-html="e.message"></li>
                        				</ul>
                        			</div>

									<div class="col-md-12">
										<div class="form-horizontal">
											<div class="form-group panel-form-style label-style">
												<label for="currentpass" class="col-sm-4 control-label"><span>Current Password</span></label>
												<label for="newpass" class="col-sm-4 control-label"><span>New Password</span></label>
												<label for="cnewpass" class="col-sm-4 control-label"><span>Re-type New Password</span></label>
											</div>
											<div class="form-group panel-form-style">
												<div class="col-sm-4">
												     <input type="password" class="" name="" id="" style="display: none;"/>
                			                         <input type="password" class="form-control input-bg input-text" name="" id="password_current" data-ng-model="pass.current"/>
												</div>
												<div class="col-sm-4">
												    <input type="password" class="form-control input-bg input-text" name="" id="password_1" data-ng-model="pass.pass1"/>
												</div>
												<div class="col-sm-4">
												  <input type="password" data-ng-class="{invalid:( pass.pass1 != '' && pass.pass2 != '' && pass.pass1 != pass.pass2)}" class="form-control input-bg input-text" name="" id="password_2" data-ng-model="pass.pass2"/>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="woocommerce ng-invalid-holder" data-ng-if="data.newpass != '' && data.confirmnewpass != '' && data.newpass != data.confirmnewpass ">
											<ul class="woocommerce-error">
												<li><p class="text-center ng-invalid"><strong>New Password</strong> and <strong>Retype Password</strong> fields are not matched!</p></li>
											</ul>
										</div>
									</div>

								</div>
						    </div>
					    </div>
					</div>
					<div class="row">
					   <div class="col-md-12">
						   <?php do_action( 'woocommerce_edit_account_form' ); ?>
						    <p style="margin-top:15px;"></p>
                        	<p class="text-center">
                        		<?php wp_nonce_field( 'save_account_details' ); ?>

                        		<button type="button" class="btn btn-default update-button" data-ng-if="!validPass()" data-toggle="tooltip" data-placement="top" title="Please fill all the fields."></button>
                        		<button type="button" class="btn btn-default update-button update-button-disabled" data-ng-if="processing"></button>
					            <button type="button" class="btn btn-default update-button" data-ng-click="changePass()" data-ng-if="!processing && validPass()"></button>


                        	</p>

                        	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
						</div>
					</div>
                </form> <!-- #chnage password form -->
		    	</div>
		    </div>
		  </div>

		</div>
		</div>



	</div>

</div>


<!-- Modal -->
<div class="modal fade" id="membershipPage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
    		<div class="modal-body">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      		<div class="row">
	      			<div class="col-xs-6">
	      				<img src="<?php echo get_template_directory_uri()?>/images/after_ogin_image.png">
	      			</div>
	      			<div class="col-xs-6">
	      				<div class="text-center">
		      				<h3>Welcome to</h3>
		      				<h1>BagMasterPiece</h1>
		      				<p class="sub">Become a VIP member by completing your profile</p>
		      				<p><a class="btn btn-primary btn-custom-profile" href="<?php echo get_permalink($bagmasterpiece['profile-page-id']);?>">Member's Profile</a></p>
		      				<p><a class="text-muted" href="#" data-dismiss="modal">Fill up Profile Later</a></p>
		      			</div>
	      			</div>
	      		</div>
      		</div>
    	</div>
  	</div>
</div>
	<!-- Modal -->
<div class="modal fade" id="LoginMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
    		<div class="modal-body">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      		<div class="row">
	      			<div class="col-xs-6">
	      				<img src="<?php echo get_template_directory_uri()?>/images/after_ogin_image.png">
	      			</div>
	      			<div class="col-xs-6">
	      				<h3>Welcome to</h3>
	      				<h1>BagMasterPiece</h1>
	      				<button type="button" class="btn btn-default" data-dismiss="modal">Fill up Profile Later</button>
	      			</div>
	      		</div>
      		</div>
    	</div>
  	</div>
</div>


<script type="text/javascript">
	jQuery(document).ready(function($){

		<?php if( isset($_GET['message']) ):?>
			$('#LoginMessage').modal('show');
		<?php elseif( current_user_can('regular') ):?>
			$('#membershipPage').modal('show');
		<?php endif;?>
	});

</script>