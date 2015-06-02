<?php

/**
 * New concierge form
 * @var unknown_type
 */


$type = ( esc_attr($_GET['new']) == 'offer' and current_user_can('Special') ) ? 1 : 0;

$data_raw = get_terms('concierge', array('hide_empty' => false, 'orderby' => 'id' ));
$data = array();

if( $type ){
	wp_enqueue_style('dropzone');
	wp_enqueue_script('dropzone');
}

$posted = array(
		'concierge_item' => 0,
		'concierge_brand' => 0,
		'concierge_style' => 0,
		'concierge_model' => '',
		'concierge_size' => '',
		'concierge_stamp' => '',
		'concierge_color1' => '',
		'concierge_color2' => '',
		'concierge_color3' => '',
		'concierge_leather1' => '',
		'concierge_leather2' => '',
		'concierge_hardware' => '',
		'concierge_budget' => '',
		'concierge_other_details' => '',
		'concierge_params' => '',
		'check' => ''
);

if( isset( $_REQUEST['concierge'] ) ){

	$post_id = esc_attr( $_REQUEST['concierge'] );

	if ( 'publish' == get_post_status ( $post_id ) ) {

		foreach( $posted as $key => $val ){
			$posted[$key] = get_post_meta($post_id, $key, true);
		}

		// @todo fix the following to have current currecy :(

		$posted['concierge_budget'] = commisize_budget($posted['concierge_budget'], $post_id);

		$posted['concierge_budget'] = get_converted_currency($posted['concierge_budget']);

		if( isset($posted['concierge_params']) ){

			$params = explode(',',$posted['concierge_params']);

			foreach( $params as $param ){
				$posted[ 'concierge_' . $param ] = get_post_meta($post_id,'concierge_' . $param ,true);
			}

		}
	}


}

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

		var conciergeData = <?php echo json_encode($data);?>;
		var POSTED_DATA = <?php echo json_encode($posted);?>;

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
		var TYPE = "<?php echo $type ? 'offer' : 'concierge'; ?>";
		var concierge_id = "<?php echo isset($_REQUEST['concierge']) ? esc_attr($_REQUEST['concierge']) : 0; ?>";

	</script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/assets/js/app.js"></script>

	<?php /*?>

	<script type="text/javascript">

		var conciergeData = <?php echo json_encode($data);?>;
		var itemdata = {};
		var branddata = {};
		var styledata = {};
		var modeldata = {};
		var itemID = <?php echo $posted['concierge_item']?>,
			brandID = <?php echo $posted['concierge_brand']?>,
			styleID = <?php echo $posted['concierge_style']?>;

		console.log(conciergeData);


		jQuery( document ).ready( function($){

			var item = '<option value="0">Select Item</option>';

			for( var i in conciergeData ){
				if( conciergeData.hasOwnProperty(i) ){
					item += '<option value="' + conciergeData[i].data.term_id + '" '+ (itemID==conciergeData[i].data.term_id ? 'selected="selected"' : '') +'>' + conciergeData[i].data.name  + '</option>';
				}
			}

			$('#concierge_item').html(item);

			$('#concierge_item').on( 'change', function(){

				var brand = '<option value="0">Select Brand</option>';
				var data = itemdata = conciergeData[$(this).val()].child;
				console.log(data);

				if( data.length == 0 ){
					return;
				}

				for( var i in data ){
					if( data.hasOwnProperty(i) ){
						brand += '<option value="' + data[i].data.term_id + '" '+ (brandID==data[i].data.term_id ? 'selected="selected"' : '') +'>' + data[i].data.name  + '</option>';
					}
				}

				$('#concierge_brand').html(brand);

			} );

			$('#concierge_brand').on( 'change', function(){

				var style = '<option value="0">Select Style</option>';
				var data = branddata = itemdata[$(this).val()].child;
				console.log(data);

				for( var i in data ){
					if( data.hasOwnProperty(i) ){
						style += '<option value="' + data[i].data.term_id + '" '+ (styleID==data[i].data.term_id ? 'selected="selected"' : '') +'>' + data[i].data.name  + '</option>';
					}
				}

				$('#concierge_style').html(style);

			} );

			jQuery('.terms-handle').on('click',function(e){
				$(this).find('.glyphicon').toggleClass('glyphicon-chevron-down').toggleClass('glyphicon-chevron-up').end();
				$(this).parent().find('.tc-well').slideToggle();
			});

			if( brandID ){
				$('#concierge_item').trigger('change');
			}
			if( styleID ){
				$('#concierge_brand').trigger('change');
			}

			if( typeof jQuery.fn.dropzone == "function" && $("#offerPicDropzone").length > 0){
				$("#offerPicDropzone").dropzone({
					url: "/file/post",
					maxFilesize: 4,
					paramName: 'profilePic',
					clickable: true,
					acceptedFiles: 'image/*',
				});
			}

		} );

	</script>

	<?php */?>


<div class="concierge-form-block">

	<?php
		$user_id = get_current_user_id();
		$status = get_transient('__concierge_status_' . $user_id);

		if( $status and $status == '__FAILED__' ):
	?>
	<div class="alert alert-danger" role="alert">
		<p>ERROR! Failed to save your concierge. Please try again.</p>
	</div>
	<?php endif;?>

	<div class="row">
		<div class="col-md-12">
			<div class="btn-group-container clearfix">
				<div class="pull-left">
					<a class="btn btn-info btn-accepted-offers" href="<?php echo add_query_arg(array('view'=>'accepted'),get_permalink(get_queried_object_id()) ); ?>">
					<span class="glyphicon glyphicon-check" aria-hidden="true"></span>
					Accepted offers</a>
				</div>
				<div class="btn-group pull-right">
					<a class="btn btn-default btn-back-to-list" href="<?php echo get_permalink(get_queried_object_id()); ?>">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					Back to list</a>
				</div>
			</div>
		</div>
	</div>

	<form action="" method="post" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-12">

				<?php if( $type ):?>
					<h1>Offer</h1>
				<?php endif;?>

				<div class="concierge-form-block" data-ng-app="BMPAPP">
					<div class="block-content" data-ng-controller="conciergeController">
						<div class="concierge-form-element <?php echo $type ? ' concierge-offer' : '';?>">
							<div class="product-info-block">
								<h2>Product information <span>(fill in the fields)</span> </h2>

								<div class="tc-well">

									<div class="row">
										<div class="col-md-7">

											<ul>

												<li class="item">
													<label for="concierge_item">Item</label>
													<select name="concierge_item" id="concierge_item"
														data-ng-model="param.formData.productInfo.item"
														data-ng-options="value.data.term_id as value.data.name for (key , value) in param.data"
														data-ng-change="itemSelected()">
														<option value="">Select Item</option>
													</select>
												</li>
												<li class="item">
													<label for="concierge_brand">Brand</label>
													<select name="concierge_brand" id="concierge_brand"
														data-ng-model="param.formData.productInfo.brand"
														data-ng-options="value.data.term_id as value.data.name for (key , value) in param.formParam.brand"
														data-ng-change="brandSelected()">
														<option value="">Select Brand</option>
													</select>
													<input data-ng-if="param.formData.productInfo.brand==9999" type="text"
														name="concierge_size_custom" id="concierge_size_custom" value="" data-ng-model="param.formData.productInfo.brandCustom"
														placeholder="Insert your custom brand here.">
												</li>
												<li class="item">
													<label for="concierge_style">Style</label>
													<select name="concierge_style" id="concierge_style"
														data-ng-model="param.formData.productInfo.style"
														data-ng-options="value.data.term_id as value.data.name for (key , value) in param.formParam.style"
														data-ng-change="styleSelected()">
														<option value="">Select Style</option>
													</select>
													<input data-ng-if="param.formData.productInfo.style==9999" type="text"
														name="concierge_style_custom" id="concierge_style_custom" value="" data-ng-model="param.formData.productInfo.styleCustom"
														placeholder="Insert your custom style here.">
												</li>
												<li class="item">
													<label for="concierge_model">Model</label>
													<select name="concierge_model" id="concierge_model"
														data-ng-model="param.formData.productInfo.model"
														data-ng-options="value.data.term_id as value.data.name for (key , value) in param.formParam.model"
														data-ng-change="modelSelected()">
														<option value="">Select Model</option>
													</select>
													<input data-ng-if="param.formData.productInfo.model==9999" type="text"
														name="concierge_model_custom" id="concierge_model_custom" value="" data-ng-model="param.formData.productInfo.modelCustom"
														placeholder="Insert your custom model here.">
												</li>

												<li class="item" data-ng-repeat="D in param.formParam.otherData">
													<label for="concierge_{{D.data.slug}}">{{D.data.name}}</label>
													<input type="text" name="concierge_{{D.data.slug}}" id="concierge_{{D.data.slug}}" value="" data-ng-model="param.formData.productInfo.otherData[D.data.slug]">
												</li>

												<li class="item clearfix budget-item">
													<label for="concierge_budget">Budget</label>
													<span class="pull-right"><?php echo get_user_meta( get_current_user_id(), 'currency', true );?></span>
													<input type="text" name="concierge_budget" ui-currency id="concierge_budget" value="" data-ng-model="param.formData.productInfo.budget">

												</li>
												<li class="item">
													<label for="concierge_other_details">Other Details/ Requests</label>
													<textarea name="concierge_other_details" id="concierge_other_details" data-ng-model="param.formData.productInfo.otherNote"></textarea>
												</li>

											</ul>
											<!--
											<ul>
												<li class="item">
													<label for="concierge_item">Item</label>
													<select name="concierge_item" id="concierge_item">
														<option value="-1">Select Item</option>
													</select>
												</li>
												<li class="item">
													<label for="concierge_brand">Brand</label>
													<select name="concierge_brand" id="concierge_brand">
														<option value="-1">Select Brand</option>
													</select>
												</li>
												<li class="item">
													<label for="concierge_style">Style</label>
													<select name="concierge_style" id="concierge_style">
														<option value="-1">Select Style</option>
													</select>
												</li>
												<li class="item">
													<label for="concierge_model">Model</label>
													<select name="concierge_model" id="concierge_model">
														<option value="-1">Select Model</option>
													</select>
												</li>
												<li class="item">
													<label for="concierge_size">Size</label>
													<input type="text" name="concierge_size" id="concierge_size" value="<?php echo esc_attr($posted['concierge_size'])?>">
												</li>
												<li class="item">
													<label for="concierge_stamp">Stamp</label>
													<input type="text" name="concierge_stamp" id="concierge_stamp" value="<?php echo esc_attr($posted['concierge_stamp'])?>">
												</li>
												<li class="item">
													<label for="concierge_color1">Color 1</label>
													<input type="text" name="concierge_color1" id="concierge_color1" value="<?php echo esc_attr($posted['concierge_color1'])?>">
												</li>
												<li class="item">
													<label for="concierge_color2">Color 2</label>
													<input type="text" name="concierge_color2" id="concierge_color2" value="<?php echo esc_attr($posted['concierge_color2'])?>">
												</li>
												<li class="item">
													<label for="concierge_color3">Color 3</label>
													<input type="text" name="concierge_color3" id="concierge_color3" value="<?php echo esc_attr($posted['concierge_color3'])?>">
												</li>
												<li class="item">
													<label for="concierge_leather1">Leather 1</label>
													<input type="text" name="concierge_leather1" id="concierge_leather1" value="<?php echo esc_attr($posted['concierge_leather1'])?>">
												</li>
												<li class="item">
													<label for="concierge_leather2">Leather 2</label>
													<input type="text" name="concierge_leather2" id="concierge_leather2" value="<?php echo esc_attr($posted['concierge_leather2'])?>">
												</li>
												<li class="item">
													<label for="concierge_hardware">Hardware</label>
													<input type="text" name="concierge_hardware" id="concierge_hardware" value="<?php echo esc_attr($posted['concierge_hardware'])?>">
												</li>
												<li class="item">
													<label for="concierge_budget">Budget</label>
													<input type="text" name="concierge_budget" id="concierge_budget" value="<?php echo esc_attr($posted['concierge_budget'])?>">
												</li>
												<li class="item">
													<label for="concierge_other_details">Other Details/ Requests</label>
													<textarea name="concierge_other_details" id="concierge_other_details"><?php echo esc_attr($posted['concierge_other_details'])?></textarea>
												</li>
											</ul>	 -->
										 </div>
										 <div class="col-md-5">
										 	<img class="responsive-image" src="<?php echo get_template_directory_uri()?>/images/form_right_img.png">
										 </div>
									</div>

								</div>
							</div>

							<?php if( $type ):?>

							<div class="upload-images product-info-block">
								<h2>Upload your item's pictures here</h2>

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

							</div>

							<input type="hidden" name="concierge_id" value="<?php echo esc_attr($_GET['concierge'])?>">
							<?php wp_nonce_field('action_concierge_offer_submit_form','from_concierge_offer_submit_form')?>

							<?php else:?>

							<div class="terms-condition product-info-block">
								<h2 class="terms-handle clearfix">TERMS AND CONDITIONS <span class="badge pull-right"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></span></h2>
								<div class="tc-well well-inverse ">
									<?php echo $content;?>
								</div>
								<ul class="f_nav">
									<li>
										<label for="check">
											<input type="checkbox" data-ng-change="termsChecked()" data-ng-model="tc" name="check" value="1" id="check" <?php echo esc_attr($posted['check']) ? 'checked="checked"' : '';?>>
											I agree to the Terms and Conditions of Bagmasterpiece&apos;s Authentication Service
										</label>
									</li>
								</ul>

							</div>

							<?php wp_nonce_field('action_concierge_submit_form','from_concierge_submit_form')?>

							<?php endif;?>


							<input type="hidden" name="new" value="concierge">
							<input type="hidden" name="r" value="<?php echo get_queried_object_id()?>">
							<div class="span4 mid-button">
								<a class="previous" href="<?php echo get_permalink( get_queried_object_id() ); ?>">Previous</a>
								<button class="done disabled" type="button" data-ng-if="!valid && TYPE != 'offer'">Done</button>
								<button class="done" type="button" data-ng-click="submitForm()" data-ng-if="valid || TYPE == 'offer'">Done</button>
							</div>

						</div>
					</div>	<!-- controller block -->

				</div>	<!-- app block -->
			</div>
		</div>
	</form>
</div>
