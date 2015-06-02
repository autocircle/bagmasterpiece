<?php

/**
 * new consignment page
 * @var unknown_type
 */


$data_raw = get_terms('concierge', array('hide_empty' => false, 'orderby' => 'id' ));

$data = array();


$posted = array(
		'consignment_item' => 0,
		'consignment_brand' => 0,
		'consignment_style' => 0,
		'consignment_model' => '',
		'consignment_size' => '',
		'consignment_stamp' => '',
		'consignment_color1' => '',
		'consignment_color2' => '',
		'consignment_color3' => '',
		'consignment_leather1' => '',
		'consignment_leather2' => '',
		'consignment_hardware' => '',
		'consignment_budget' => '',
		'consignment_other_details' => '',
		'check' => ''
);

if( isset( $_POST ) ){
	$posted = wp_parse_args($_POST, $posted);
}

foreach ($data_raw as $d){
	if( $d->parent == 0 ){

		$data[$d->term_id] = array(
				'data' => $d,
				'child' => array()
		);
	}
}

foreach ($data_raw as $d){
	push_terms($data, $d);
}

foreach ($data_raw as $d){
	push_terms($data, $d);
}

ob_start();

?>
	<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/assets/js/angular.min.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/assets/js/angular-animate.min.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/assets/js/ngDialog.min.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/assets/js/dropzone.js"></script>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/assets/css/dropzone.css">
	<script type="text/javascript">

		var consignmentData = <?php echo json_encode($data);?>;

		jQuery( document ).ready( function($){

			jQuery('.terms-handle').on('click',function(e){
				//$(this).parent('.terms-condition').toggleClass('open');
				$(this).find('.glyphicon').toggleClass('glyphicon-chevron-down').toggleClass('glyphicon-chevron-up').end();
				$(this).closest('.product-info-block').find('.tc-well').slideToggle();

			});

		} );
		var admin_ajax = "<?php echo admin_url('admin-ajax.php');?>";
		var drophandler = "<?php echo admin_url( 'admin-ajax.php?action=handle_dropped_media' ); ?>";
		var deletehandler = "<?php echo admin_url( 'admin-ajax.php?action=handle_delete_media' ); ?>";
		var RETURN_URL = "<?php echo get_permalink( get_queried_object_id() ); ?>";
	</script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/assets/js/app.js"></script>
	<div class="consignment-form-block">

	<form action="" method="post" enctype="multipart/form-data">

		<div class="consignment-form-block" data-ng-app="BMPAPP">

			<div class="block-content" data-ng-controller="consignmentController">

				<div class="step step-1" data-ng-show="step==99">
					<div class="intro-title">
						<h2>Welcome to the Bagmasterpiece Consignment Service!</h2>
					</div>
					<div class="intro-text">
						<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages.</p>
						<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages.</p>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="package-select" data-ng-click="selectPackage(1)">
								<img data-ng-src="<?php echo get_template_directory_uri()?>/images/allinone-package.png">
							</div>
						</div>
						<div class="col-md-6">
							<div class="package-select" data-ng-click="selectPackage(2)">
								<img data-ng-src="<?php echo get_template_directory_uri()?>/images/homekit.png">
							</div>
						</div>
					</div>

				</div><!-- #step-1 -->

				<div class="step step-1" data-ng-show="step==1">

				    <div class="intro-title">
						<h2>Welcome to the Bagmasterpiece Consignment Service!</h2>
					</div>


					<div class="product-data">

						<div class="consignment-form-element">
							<div class="product-info-block">
								<h2>Product information <span>(fill in the fields)</span></h2>

								<div class="tc-well">
									<div class="row">
										<div class="col-md-7">

											<ul>
												<li class="item">
													<label for="consignment_item">Item</label>
													<select name="consignment_item" id="consignment_item"
														data-ng-model="param.formData.productInfo.item"
														data-ng-options="value.data.term_id as value.data.name for (key , value) in param.data"
														data-ng-change="itemSelected()">
														<option value="">Select Item</option>
													</select>
												</li>
												<li class="item">
													<label for="consignment_brand">Brand</label>
													<select name="consignment_brand" id="consignment_brand"
														data-ng-model="param.formData.productInfo.brand"
														data-ng-options="value.data.term_id as value.data.name for (key , value) in param.formParam.brand"
														data-ng-change="brandSelected()">
														<option value="">Select Brand</option>
													</select>
													<input data-ng-if="param.formData.productInfo.brand==9999" type="text"
														name="consignment_size_custom" id="consignment_size_custom" value="" data-ng-model="param.formData.productInfo.brandCustom"
														placeholder="Insert your custom brand here.">
												</li>
												<li class="item">
													<label for="consignment_style">Style</label>
													<select name="consignment_style" id="consignment_style"
														data-ng-model="param.formData.productInfo.style"
														data-ng-options="value.data.term_id as value.data.name for (key , value) in param.formParam.style"
														data-ng-change="styleSelected()">
														<option value="">Select Style</option>
													</select>
													<input data-ng-if="param.formData.productInfo.style==9999" type="text"
														name="consignment_style_custom" id="consignment_style_custom" value="" data-ng-model="param.formData.productInfo.styleCustom"
														placeholder="Insert your custom style here.">
												</li>
												<li class="item">
													<label for="consignment_model">Model</label>
													<select name="consignment_model" id="consignment_model"
														data-ng-model="param.formData.productInfo.model"
														data-ng-options="value.data.term_id as value.data.name for (key , value) in param.formParam.model"
														data-ng-change="modelSelected()">
														<option value="">Select Model</option>
													</select>
													<input data-ng-if="param.formData.productInfo.model==9999" type="text"
														name="consignment_model_custom" id="consignment_model_custom" value="" data-ng-model="param.formData.productInfo.modelCustom"
														placeholder="Insert your custom model here.">
												</li>

												<li class="item" data-ng-repeat="D in param.formParam.otherData">
													<label for="consignment_{{D.data.slug}}">{{D.data.name}}</label>
													<input type="text" name="consignment_{{D.data.slug}}" id="consignment_{{D.data.slug}}" value="" data-ng-model="param.formData.productInfo.otherData[D.data.slug]">
												</li>

												<!-- //

												<li class="item">
													<label for="consignment_size">Size</label>
													<input type="text" name="consignment_size" id="consignment_size" value="" data-ng-model="param.formData.productInfo.size">
												</li>
												<li class="item">
													<label for="consignment_stamp">Stamp</label>
													<input type="text" name="consignment_stamp" id="consignment_stamp" value="" data-ng-model="param.formData.productInfo.stamp">
												</li>
												<li class="item">
													<label for="consignment_color1">Color 1</label>
													<input type="text" name="consignment_color1" id="consignment_color1" value="" data-ng-model="param.formData.productInfo.color1">
												</li>
												<li class="item">
													<label for="consignment_color2">Color 2</label>
													<input type="text" name="consignment_color2" id="consignment_color2" value="" data-ng-model="param.formData.productInfo.color2">
												</li>
												<li class="item">
													<label for="consignment_color3">Color 3</label>
													<input type="text" name="consignment_color3" id="consignment_color3" value="" data-ng-model="param.formData.productInfo.color3">
												</li>
												<li class="item">
													<label for="consignment_leather1">Leather 1</label>
													<input type="text" name="consignment_leather1" id="consignment_leather1" value="" data-ng-model="param.formData.productInfo.leather1">
												</li>
												<li class="item">
													<label for="consignment_leather2">Leather 2</label>
													<input type="text" name="consignment_leather2" id="consignment_leather2" value="" data-ng-model="param.formData.productInfo.leather2">
												</li>
												<li class="item">
													<label for="consignment_hardware">Hardware</label>
													<input type="text" name="consignment_hardware" id="consignment_hardware" value="" data-ng-model="param.formData.productInfo.hardware">
												</li>

												//-->

												<li class="item clearfix budget-item">
													<label for="consignment_budget">Budget</label>
													<span class="pull-right"><?php echo get_user_meta( get_current_user_id(), 'currency', true );?></span>
													<input type="text" name="consignment_budget" ui-currency id="consignment_budget" value="" data-ng-model="param.formData.productInfo.budget">
												</li>
												<li class="item">
													<label for="consignment_other_details">Other Details/ Requests</label>
													<textarea name="consignment_other_details" id="consignment_other_details" data-ng-model="param.formData.productInfo.otherNote"></textarea>
												</li>
											</ul>
										</div>
										<div class="col-md-5">
											<div class="side-block-image"><img data-ng-src="<?php echo get_template_directory_uri()?>/images/ladywithbag.png"></div>
										</div>
									</div>
								</div>
							</div>

							<div class="product-info-block included">
								<h2 class="terms-handle">Included <span class="badge pull-right"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></span></h2>
								<div class="tc-well well-inverse">
									<label data-ng-repeat="a in param.formParam.includes">
										<input type="checkbox" value="{{a}}" data-check-list='param.formData.included'> {{a}}
									</label>
								</div>
							</div>

							<div class="product-info-block financial">
								<h2 class="terms-handle">Finalcial Information <span class="badge pull-right"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></span></h2>
								<div class="tc-well well-inverse well-bg" style="background-image: url(<?php echo get_template_directory_uri()?>/images/abovebankaccount.png)">
									<div class="row">
										<div class="finnance-switcher pull-right">
											<div class="switcehr-btn-container">
												<button type="button" class="active">Bank Transfer</button>
												<button type="button" class="">Escrow.com</button>
											</div>
										</div>
									</div>
									<div class="row">

										<div class="col-md-6">
											<label for="bankAccountName">Bank Account Name</label>
											<input type="text" name="bankAccountName" id="bankAccountName" data-ng-model="param.formData.financial.bankAccountName">
										</div>
										<div class="col-md-6">
											<label for="bankAccountNumber">Bank Account Number</label>
											<input type="text" name="bankAccountNumber" id="bankAccountNumber" data-ng-model="param.formData.financial.bankAccountNumber">
										</div>
										<div class="col-md-6">
											<label for="bankName">Bank Name</label>
											<input type="text" name="bankName" id="bankName" data-ng-model="param.formData.financial.bankName">
										</div>
										<div class="col-md-6">
											<label for="swiftCode">Swift Code</label>
											<input type="text" name="swiftCode" id="swiftCode" data-ng-model="param.formData.financial.swiftCode">
										</div>
										<div class="col-md-6">
											<label for="bankBranch">Bank Branch</label>
											<input type="text" name="bankBranch" id="bankBranch" data-ng-model="param.formData.financial.bankBranch">
										</div>
									</div>
								</div>
							</div>
							<div class="span4 mid-button">
								<button type="button" data-ng-show="step>1" data-ng-click="prev()" class="previous">Previous</button>
								<button type="button" class="done" data-ng-click="next()">Continue</button>
							</div> <!-- button group -->
						</div>
					</div>
				</div>	<!-- #step-2 -->

				<div class="step step2" data-ng-show="step==2">
					<div class="product-info-block image-uploader-block">
						<h2>Upload your profile pictures here</h2>

						<div class="profile-pic-thumb">
							<ul class="clearfix">
								<li class="first" style="background-image: url(<?php echo get_template_directory_uri()?>/images/consignment-profile/1.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/consignment-profile/2.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/consignment-profile/3.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/consignment-profile/4.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/consignment-profile/5.png)"></li>
							</ul>
						</div>
						<div class="profile-pic-thumb-dropzone profile-pic-thumb">
							<ul class="clearfix">
								<li class="first">
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="profilePicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="profilePicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="profilePicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="profilePicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="profilePicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
							</ul>
						</div>
						<!--
						<div class="profilePicDropzone dropzoneContainer">
							<div class="fallback">
								<input type="file" name="product-image" >
							</div>
							<div class="dropzone-tip">Click to select or drag and drop your images</div>
						</div>
						 -->

					</div>
					<div class="product-info-block reciept-uploader-block">
						<h2>Original Receipt</h2>

						<div class="tc-well well-inverse">

							<div class="row">
								<div class="col-md-4 col-md-offset-2">
									<div class="has-recipt-block">
										<label for="i-have-receipt">
											<input id="i-have-receipt" type="checkbox" name="have-reciept" value="1" data-ng-model="param.formData.hasReceipt" data-ng-change="authReceipt()">
											I have
										</label>
										<p class="tip">(Drag clear scan or photo of O.R. on the box below)</p>
									</div>

									<div class="nop-recipt-block">
										<label for="i-dont-have-receipt">
											<input id="i-dont-have-receipt" type="checkbox" name="have-reciept" value="1" data-ng-model="param.formData.hasReceiptNot"  data-ng-change="authReceiptNot()">
											I do not have
										</label>
										<p class="tip">(Proceed to Authentication)</p>
									</div>
								</div>
								<div class="col-md-5">
									<div class="receiptDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="receipt-image">
										</div>
									</div>
									<div class="dropzone-tip">Drag and Drop Image</div>
								</div>
							</div>
						</div>
					</div><!--  receipt upload -->

					<div class="product-info-block auht-instruction-block" data-ng-show="param.formData.hasReceiptNot">
						<h2 class="terms-handle">Instruction on how to shoot for bagmasterpiece <span class="badge pull-right"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></span></h2>

						<div class="tc-well well-inverse ">
							<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages.</p>
							<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages.</p>
						</div>
					</div>

					<div class="product-info-block auth-uploader-block" data-ng-show="param.formData.hasReceiptNot">
						<h2 class="text-center">Upload these images to authenticate</h2>

						<!-- auth uploader starts -->



						<div class="profile-pic-thumb">
							<ul class="clearfix">
								<li class="first" style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/1.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/2.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/3.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/4.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/5.png)"></li>
							</ul>
						</div>
						<div class="profile-pic-thumb-dropzone profile-pic-thumb">
							<ul class="clearfix">
								<li class="first">
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image">
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div class="profile-pic-thumb">
							<ul class="clearfix">
								<li class="first" style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/6.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/7.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/8.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/9.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/10.png)"></li>
							</ul>
						</div>
						<div class="profile-pic-thumb-dropzone profile-pic-thumb">
							<ul class="clearfix">
								<li class="first">
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div class="profile-pic-thumb">
							<ul class="clearfix">
								<li class="first" style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/11.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/12.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/13.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/14.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/15.png)"></li>
							</ul>
						</div>
						<div class="profile-pic-thumb-dropzone profile-pic-thumb">
							<ul class="clearfix">
								<li class="first">
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div class="profile-pic-thumb">
							<ul class="clearfix">
								<li class="first" style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/16.png)"></li>
								<li style="background-image: url(<?php echo get_template_directory_uri()?>/images/authentication/17.png)"></li>
							</ul>
						</div>
						<div class="profile-pic-thumb-dropzone profile-pic-thumb">
							<ul class="clearfix">
								<li class="first">
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
								<li>
									<h3 class="profile-pic-thumb-dropzone-title">Add Photo Here</h3>
									<div class="authPicDropzone dropzoneContainer">
										<div class="fallback">
											<input type="file" name="product-image" >
										</div>
									</div>
								</li>
							</ul>
						</div>

						<!-- auth uploader ends -->
						<!--
						<div class="tc-well well-inverse">
							<div class="authPicDropzone dropzoneContainer">
								<div class="fallback">
									<input type="file" name="product-image" >
								</div>
								<div class="dropzone-tip">Click to select or drag and drop your images</div>
							</div>
						</div>
						 -->
					</div><!--  auth block-->

					<div class="span4 mid-button">
						<button type="button" data-ng-show="step>1" data-ng-click="prev()" class="previous">Previous</button>
						<button type="button" class="done" data-ng-click="next()">Continue</button>
					</div> <!-- button group -->

				</div>
				<div class="step step-3" data-ng-show="step==3">
					<div class="row">
						<div class="col-md-6">
							<div class="package-select" data-ng-click="selectPackage(1)">
								<img data-ng-src="<?php echo get_template_directory_uri()?>/images/allinone-package.png">
							</div>
						</div>
						<div class="col-md-6">
							<div class="package-select" data-ng-click="selectPackage(2)">
								<img data-ng-src="<?php echo get_template_directory_uri()?>/images/homekit.png">
							</div>
						</div>
					</div>
					<div class="span4 mid-button">
						<button type="button" data-ng-show="step>1" data-ng-click="prev()" class="previous">Previous</button>
					</div> <!-- button group -->

				</div><!-- #step-1 -->
				<div class="step step3" data-ng-show="step==4">
					<div class="product-info-block breakdown">
						<h2>Consignment Free Breakdown <span class="pull-right">{{breakDown.name}}</span></h2>
						<div class="tc-well well-inverse">
							<div class="row">
								<div class="col-md-6">
									<div class="side-block-image">
										<img data-ng-src="<?php echo get_template_directory_uri()?>/images/breakdown.png">
									</div>
								</div>
								<div class="col-md-6">
									<table class="">
										<tbody>
											<tr data-ng-repeat="i in breakDown.fee">
												<th>{{i.name}}</th>
												<td>{{i.type=='percent' ? param.formData.productInfo.budget * i.value/100 : i.value}}</td>
												<td>
												    <label data-ng-repeat="m in i.options"><input type="radio" data-ng-model="param.formData.packageData.fee[$parent.$index].set" value="{{m.value}}"> {{m.name}}</label>
												</td>
											</tr>
											<tr>
												<th>Total</th>
												<td>{{getTotal()}}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="product-info-block terms-condition">
						<h2 class="terms-handle">TERMS AND CONDITIONS <span class="badge pull-right"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></span></h2>
						<div class="tc-well well-inverse ">
							<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages.</p>
							<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages.</p>
						</div>
						<ul class="f_nav">
							<li>
								<label for="check">
									<input type="checkbox" name="check" id="check">
									I agree to the Terms and Conditions of Bagmasterpiece&apos;s Authentication Service
								</label>
							</li>
						</ul>

					</div>
					<div class="span4 mid-button">
						<button type="button" data-ng-click="prev()" class="previous">Previous</button>
						<button type="button" class="done" data-ng-click="submitForm()">Complete</button>
					</div> <!-- button group -->
				</div>

			</div>

		</div>

		<?php /* no more required this block?>

		<div class="row">
			<div class="col-md-12">
				<h2 class="info products">Welcome to the BagMasterPiece consignment Service !</h2>
				<div class="consignment-form-element">
				<div class="form-step form-step1 step-open">
					<div class="row">
						<div class="col-md-6">

							<ul>
								<li class="item">
									<label for="consignment_item">Item</label>
									<select name="consignment_item" id="consignment_item">
										<option value="-1">Select Item</option>
									</select>
								</li>
								<li class="item">
									<label for="consignment_brand">Brand</label>
									<select name="consignment_brand" id="consignment_brand">
										<option value="-1">Select Brand</option>
									</select>
								</li>
								<li class="item">
									<label for="consignment_style">Style</label>
									<select name="consignment_style" id="consignment_style">
										<option value="-1">Select Style</option>
									</select>
								</li>
								<li class="item">
									<label for="consignment_model">Model</label>
									<select name="consignment_model" id="consignment_model">
										<option value="-1">Select Model</option>
									</select>
								</li>
								<li class="item">
									<label for="consignment_size">Size</label>
									<input type="text" name="consignment_size" id="consignment_size" value="<?php echo esc_attr($posted['consignment_size'])?>">
								</li>
								<li class="item">
									<label for="consignment_stamp">Stamp</label>
									<input type="text" name="consignment_stamp" id="consignment_stamp" value="<?php echo esc_attr($posted['consignment_stamp'])?>">
								</li>
								<li class="item">
									<label for="consignment_color1">Color 1</label>
									<input type="text" name="consignment_color1" id="consignment_color1" value="<?php echo esc_attr($posted['consignment_color1'])?>">
								</li>
								<li class="item">
									<label for="consignment_color2">Color 2</label>
									<input type="text" name="consignment_color2" id="consignment_color2" value="<?php echo esc_attr($posted['consignment_color2'])?>">
								</li>
								<li class="item">
									<label for="consignment_color3">Color 3</label>
									<input type="text" name="consignment_color3" id="consignment_color3" value="<?php echo esc_attr($posted['consignment_color3'])?>">
								</li>
								<li class="item">
									<label for="consignment_leather1">Leather 1</label>
									<input type="text" name="consignment_leather1" id="consignment_leather1" value="<?php echo esc_attr($posted['consignment_leather1'])?>">
								</li>
								<li class="item">
									<label for="consignment_leather2">Leather 2</label>
									<input type="text" name="consignment_leather2" id="consignment_leather2" value="<?php echo esc_attr($posted['consignment_leather2'])?>">
								</li>
								<li class="item">
									<label for="consignment_hardware">Hardware</label>
									<input type="text" name="consignment_hardware" id="consignment_hardware" value="<?php echo esc_attr($posted['consignment_hardware'])?>">
								</li>
								<li class="item">
									<label for="consignment_budget">Budget</label>
									<input type="text" name="consignment_budget" id="consignment_budget" value="<?php echo esc_attr($posted['consignment_budget'])?>">
								</li>
								<li class="item">
									<label for="consignment_other_details">Other Details/ Requests</label>
									<textarea name="consignment_other_details" id="consignment_other_details"><?php echo esc_attr($posted['consignment_other_details'])?></textarea>
								</li>
							</ul>
						  </div>
					</div>
				</div>
				<div class="terms-condition">
					<h2 class="terms-handle">Inclued</h2>
					<div class="tc-well well-inverse ">
						<label><input type="checkbox" name="inclued[]" value="">&nbsp;Box</label>
						<label><input type="checkbox" name="inclued[]" value="">&nbsp;Dustbag</label>
						<label><input type="checkbox" name="inclued[]" value="">&nbsp;Pillows</label>
						<label><input type="checkbox" name="inclued[]" value="">&nbsp;Paperbag</label>
						<label><input type="checkbox" name="inclued[]" value="">&nbsp;Raincoat</label>
						<label><input type="checkbox" name="inclued[]" value="">&nbsp;Clochette</label>
					</div>
					<?php wp_nonce_field('action_consignment_submit_form','from_consignment_submit_form')?>
				</div>
				<div class="financial-information">
					<h2 class="">Financial Information</h2>

					<div class="row">
						<div class="col-lg-6">
							<div>
								<label for="bank-account-name">Bank Account Name</label>
								<input type="text" name="bank-account-name" id="bank-account-name" value="">
							</div>
							<div>
								<label for="bank-name">Bank Name</label>
								<input type="text" name="bank-name" id="bank-name" value="">
							</div>
							<div>
								<label for="bank-branch">Bank Branch</label>
								<input type="text" name="bank-branch" id="bank-branch" value="">
							</div>
						</div>
						<div class="col-lg-6">
							<div>
								<label for="bank-account-number">Bank Account Number</label>
								<input type="text" name="bank-account-number" id="bank-account-number" value="">
							</div>
							<div>
								<label for="swift-code">Swift Code</label>
								<input type="text" name="swift-code" id="swift-code" value="">
							</div>
						</div>
					</div>
				</div>
				<!-- <div class="span4 mid-button">
					<button type="button" class="previous">Previous</button>
					<button type="button" class="done">Next</button>
				</div> -->
			</div>
			<div class="form-step form-step2 closed">
				<div class="row">
					<div class="col-lg-12">
						<div class="image-uploader-block">
							<h2>Upload your profile pictures here</h2>

							<div class="profilePicDropzone dropzoneContainer">
								<div class="fallback">
									<input type="file" name="product-image" >
								</div>
							</div>

						</div>
						<div class="reciept-uploader-block">
							<h2>Original Receipt</h2>

							<label>
								<input type="checkbox" name="have-reciept" value="1">
								I have
							</label>
							<p>(Drag clear scan or photo of O.R. on the box below)</p>

							<div class="receiptDropzone dropzoneContainer">
								<div class="fallback">
								<input type="file" name="receipt-image">
							</div>
							</div>

							<label>
								<input type="checkbox" name="have-reciept" value="0">
								I do not have
							</label>
							<p>(Proceed to Authentication)</p>
						</div>

					</div>
				</div>
			</div>
				<div class="col-lg-12">
					<div class="terms-condition">
						<h2 class="terms-handle">TERMS AND CONDITIONS</h2>
						<div class="tc-well well-inverse ">
							<?php echo $content == '' ? 'Dummy terms and condition' : $content;?>
						</div>
							<ul class="f_nav">
								<li>
									<label for="check">
										<input type="checkbox" name="check" id="check" <?php echo esc_attr($posted['check']) ? 'checked="checked"' : '';?>>
										I agree to the Terms and Conditions of Bagmasterpiece&apos;s Authentication Service
									</label>
								</li>
							</ul>
						<?php wp_nonce_field('action_consignment_submit_form','from_consignment_submit_form')?>
						<div class="span4 mid-button">
							<button class="previous">Previous</button>
							<button class="done" type="submit">Done</button>
						</div>

					</div>
				</div>
			</div>

		</div>

		<?php no more required &*/?>

	</form>
	</div>

	<?php

	$form = ob_get_clean();