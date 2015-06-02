(function(){
	"use strict";

	var app = angular.module('BMPAPP',['ngAnimate', 'ngDialog']);

	app.controller('profileController', ['$scope', '$timeout', '$http', '$sce', 'ngDialog', function($scope, $timeout, $http, $sce, ngDialog){
		
		$scope.error = [];
		$scope.processing = false;
		$scope.param = {
			gender: [				
				{
					label : 'Male',
					value : 'male'
				},
				{
					label : 'Female',
					value : 'female'
				}
			],
			subscriptions:[
				{
					label:'Bags',
					value: 'bags'
				},
				{
					label:'Accessories',
					value: 'accessories'
				},
				{
					label:'BagMasPiece Newsletter',
					value: 'bmpnewsletter'
				},
				{
					label:'Jewelry',
					value: 'juwelry'
				},
				{
					label:'Watches',
					value: 'watches'
				}
			],
			notifications: [			               
			               {
			            	   label: 'SMS',
			            	   value: 'sms'
			               },
			],
			currency : {}
		};
		$scope.data = {			
			firstName: '',
			lastName:'',
			dob:'',
			gender:'',
			nos:'',						
			address:'',
			city:'',
			state:'',
			country:'',
			zip:'',
			contactNumber:'',
			email:'',
			whatsapp:'',
			viber:'',							
			currency:'',			
			subscriptions:[],
			notifications:[]
		};

		$scope.pass = {
				current: '',
				pass1 : '',
				pass2 : ''
		}
		
		$scope.flush = function(){

			var data = profileData;
			
			$scope.data.firstName = data.first_name;
			$scope.data.lastName = data.last_name;
			$scope.data.dob = data.dob;
			$scope.data.gender = data.gender;
			$scope.data.nos = data.nos;				
			$scope.data.address = data.address;
			$scope.data.city = data.city;
			$scope.data.state = data.state;
			$scope.data.country = data.country;
			$scope.data.zip = data.zip;
			$scope.data.contactNumber = data.contact_number;
			$scope.data.email = data.email;
			$scope.data.whatsapp = data.whatsapp;
			$scope.data.viber = data.viber;					
			$scope.data.currency = data.currency;

			$http.get('http://openexchangerates.org/api/currencies.json').
				success(function(data, status, headers, config) {
					$scope.param.currency = data;
				}).
				error(function(data, status, headers, config) {
					console.log('[failed to load currency list]');
				});
			
			
			
			if( typeof data.subscriptions != "string" && data.subscriptions.length > 0 ){
				$scope.data.subscriptions = data.subscriptions;
			}
			else{
				$scope.data.subscriptions = [];
			}
			
			if( typeof data.notifications != "string" && data.notifications.length > 0 ){
				$scope.data.notifications = data.notifications;
			}
			else{
				$scope.data.notifications = [];
			}
		};

		$scope.scrollToTop = function(){
			var SCT = jQuery('.profile').offset().top - 100;
			jQuery( 'html, body' ).animate( {
				scrollTop: SCT
			});
		};

		$scope.submitForm = function(){console.log($scope.data);
			$scope.processing = true;
			$scope.error = [];
			$http({
				method: 'POST',
				url: admin_ajax + '?action=save_profile',
				data:{
					data:$scope.data,
					return_url: RETURN_URL
				}
	        })
			.success(function(data) {
				
				$scope.processing = false;
				if(data.status == 200 ){					
					var dialog = ngDialog.open({
						template: '<p class="success-message"><span class="glyphicon glyphicon-saved" aria-hidden="true"></span></p><p class="success-message">Saved Successfully!</p>',
						plain: true,
						closeByDocument: false,
						closeByEscape: false
					});
					setTimeout(function () {
						dialog.close();
					}, 2000);
				}
				else if(data.status == 400 ){
					for( var i in data.error ){
						if( data.error.hasOwnProperty(i) ){
							var t = {
								id : data.error[i]['id'],
								message : $sce.trustAsHtml(data.error[i]['message'])
							}
							
							$scope.error.push(t);
						}
					}
					$scope.scrollToTop();
				}
					
			});
		}
		
		$scope.emptyPass = function(){
			return $scope.pass.current == '' || $scope.pass.pass1 == '' || $scope.pass.pass2 == '';
		};
		$scope.validPass = function(){
			return $scope.pass.current != '' && $scope.pass.pass1 != '' && $scope.pass.pass2 != '' && $scope.pass.pass1 == $scope.pass.pass2;
		};
		
		$scope.changePass = function(){
			$scope.processing = true;
			$scope.error = [];
			$http({
				method: 'POST',
				url: admin_ajax + '?action=save_pass',
				data:$scope.pass				
	        })
			.success(function(data) {
				console.log(data);
				$scope.processing = false;
				
				if(data.status == 200 ){
					
					$scope.pass = {
							current: '',
							pass1 : '',
							pass2 : ''
					}
					
					var dialog = ngDialog.open({
						template: '<p class="success-message"><span class="glyphicon glyphicon-saved" aria-hidden="true"></span></p><p class="success-message">Saved Successfully!</p>',
						plain: true,
						closeByDocument: false,
						closeByEscape: false
					});
					setTimeout(function () {
						dialog.close();
					}, 2000);
				}
				else if(data.status == 400 ){
					for( var i in data.error ){
						if( data.error.hasOwnProperty(i) ){
							var t = {
								id : data.error[i]['id'],
								message : $sce.trustAsHtml(data.error[i]['message'])
							}
							
							$scope.error.push(t);
						}
					}
					$scope.scrollToTop();
				}
				
				
					
			});
		}

		$scope.flush();

	}])

	app.controller('consignmentController', ['$scope', '$timeout', '$http', 'ngDialog', function($scope, $timeout, $http, ngDialog){
		
		/* $scope declaration starts */
		
		//	Store all scope variables and params
		$scope.param = {
			packageID : 0,
			data:consignmentData,
			formParam: {				
				item: {},
				brand: {},
				style: {},
				model: {},
				otherData :[],				
				includes: ['Box', 'Dustbag', 'Pillows', 'Paperbag', 'Raincoat', 'Clochette']
			},
			formData: {
				productInfo: {
					item : '',
					brand: '',					
					style: '',
					model: '',
					otherData: {},
					budget: '',
					otherNote: ''
				},
				included: [],
				financial: {
					type:'',					
					banckAccountName: '',
					banckAccountNumber: '',
					bankName: '',
					swiftCode: '',
					bankBranch: ''
				},
				profilePics : [],
				authPics : [],
				receiptPic:0,
				hasReceipt:0,
				hasReceiptNot:0,
				packageID:0,
				packageData: {},
				packageDataTotal: 0
			},
			packageData: {}
		};		
		
		$scope.emptyOtherData = [
			{
				data : {
					name: 'Description 1',
					slug: 'desc1'
				}
			},
			{
				data : {
					name: 'Description 2',
					slug: 'desc2'
				}
			},
			{
				data : {
					name: 'Description 3',
					slug: 'desc3'
				}
			},
			{
				data : {
					name: 'Description 4',
					slug: 'desc4'
				}
			},
			{
				data : {
					name: 'Description 5',
					slug: 'desc5'
				}
			}
			
		];

		$scope.empty = {
			data : {
				name: 'Self Defined',
				term_id: "9999", 
			}
		};

		$scope.pushEmpty = function(elem){
			if( typeof elem === "object"){
				elem[9999] = $scope.empty;
			}
			else{
				elem = {
					9999 : $scope.empty
				}
			}
			return elem;
		};

		$scope.itemSelected = function(){
			$scope.param.formParam.brand = $scope.param.data[$scope.param.formData.productInfo.item].child;
			$scope.param.formParam.brand = $scope.pushEmpty($scope.param.formParam.brand);
			$scope.param.formData.productInfo.brand = '';
			$scope.param.formData.productInfo.style = '';
			$scope.param.formData.productInfo.model = '';
			$scope.param.formParam.otherData = [];
		};
		
		$scope.brandSelected = function(){
			if($scope.param.formData.productInfo.brand != null){
				$scope.param.formParam.style = $scope.param.formParam.brand[$scope.param.formData.productInfo.brand].child;
			}			
			$scope.param.formParam.style = $scope.pushEmpty($scope.param.formParam.style);
			$scope.param.formData.productInfo.style = '';	
			$scope.param.formData.productInfo.model = '';
			$scope.param.formParam.otherData = [];

			if( $scope.param.formData.productInfo.brand == "9999" ){
				$scope.param.formData.productInfo.style = "9999";
				$scope.styleSelected();
			}
			if( $scope.param.formData.productInfo.brand == "" ){
				$scope.param.formData.productInfo.style = "";											
				$scope.styleSelected();
			}
			
		};

		$scope.styleSelected = function(){
			if($scope.param.formData.productInfo.style != null ){
				$scope.param.formParam.model = $scope.param.formParam.style[$scope.param.formData.productInfo.style].child;
			}			
			$scope.param.formParam.model = $scope.pushEmpty($scope.param.formParam.model);
			$scope.param.formData.productInfo.model = '';
			$scope.param.formParam.otherData = [];

			if( $scope.param.formData.productInfo.style == "9999" ){
				$scope.param.formData.productInfo.model = "9999";											
				$scope.modelSelected();
			}
			if( $scope.param.formData.productInfo.style == "" ){
				$scope.param.formData.productInfo.model = "";											
				$scope.modelSelected();
			}
			
		}
		
		$scope.modelSelected = function(){
			if($scope.param.formData.productInfo.model != null){
				$scope.param.formParam.otherData = $scope.param.formParam.model[$scope.param.formData.productInfo.model].child;			
			}			
			if( typeof $scope.param.formParam.otherData !== "object" ){
				$scope.param.formParam.otherData = $scope.emptyOtherData;
			}

		}

		$scope.breakDown = [];
		//	Holds scope step identifier
		$scope.step = 1;

		//	Selects the user choosen package on step-1
		$scope.selectPackage = function(packageId){
			$scope.param.packageID = packageId;
			$scope.breakDown = $scope.param.packageData[$scope.param.packageID];
			
			for( var i in $scope.breakDown.fee){

				if( $scope.breakDown.fee.hasOwnProperty(i) ){
					
					if( $scope.breakDown.fee[i].id == 'authentication' ){
						if( $scope.param.formData.hasReceipt ){
							$scope.breakDown.fee[i].set = 0;
						}
						else if( $scope.param.formData.hasReceiptNot ){
							$scope.breakDown.fee[i].set = 1;
						}
					}
					
				}
			}
			
			$scope.param.formData.packageID = packageId; 
			$scope.param.formData.packageData = $scope.breakDown;
			console.log($scope.param.formData.packageData);
			$scope.next();
		};

		//	Advance to next step functionality
		$scope.next = function(){
			$scope.step++;		
			$scope.scrollToTop();
		};

		//	Recede to the previous step functionality
		$scope.prev = function(){
			$scope.step--;
			$scope.scrollToTop();
		};
		
		$scope.scrollToTop = function(){
			var SCT = jQuery('.consignment-form-block').offset().top - 100;
			jQuery( 'html, body' ).animate( {
				scrollTop: SCT
			});
		};

		//	Flush the DOM based on $timeout. (from previous experience...)
		$scope.flush = function(){
			$timeout(function(){
				jQuery(".profilePicDropzone").dropzone({
					url: drophandler,
					maxFiles: 1,
					thumbnailWidth:160,
					thumbnailHeight:160,
					paramName: 'profilePic',
					clickable: true,
					acceptedFiles: 'image/*',
					success: function (file, response) {						
						file.previewElement.classList.add("dz-success");
						$scope.param.formData.profilePics.push(response);
						file['attachment_id'] = response;						
					},
					error: function (file, response) {
						file.previewElement.classList.add("dz-error");
					},
					addRemoveLinks: true,
					removedfile: function(file) {
					    var attachment_id = file.attachment_id;        
					    jQuery.ajax({
					        type: 'POST',
					        url: deletehandler,
					        data: {
					        	media_id : attachment_id
					        }
					    });
					    var _ref;
					    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;        
					}
				});
				jQuery(".authPicDropzone").dropzone({
					url: drophandler,
					maxFiles: 1,
					thumbnailWidth:160,
					thumbnailHeight:160,
					paramName: 'authPic',
					clickable: true,
					acceptedFiles: 'image/*',
					success: function (file, response) {						
						file.previewElement.classList.add("dz-success");
						$scope.param.formData.authPics.push(response);			
						file['attachment_id'] = response;										
					},
					error: function (file, response) {
						file.previewElement.classList.add("dz-error");
					},
					addRemoveLinks: true,
					removedfile: function(file) {
					    var attachment_id = file.attachment_id;        
					    jQuery.ajax({
					        type: 'POST',
					        url: deletehandler,
					        data: {
					        	media_id : attachment_id
					        }
					    });
					    var _ref;
					    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;        
					}
				});
				jQuery(".receiptDropzone").dropzone({ 
					url: drophandler,
					maxFiles: 1,
					paramName: 'receiptPic',
					clickable: true,
					acceptedFiles: 'image/*',
					success: function (file, response) {						
						file.previewElement.classList.add("dz-success");
						$scope.param.formData.receiptPic = response;	
						file['attachment_id'] = response;												
					},
					error: function (file, response) {
						file.previewElement.classList.add("dz-error");
					},
					addRemoveLinks: true,
					removedfile: function(file) {
					    var attachment_id = file.attachment_id;        
					    jQuery.ajax({
					        type: 'POST',
					        url: deletehandler,
					        data: {
					        	media_id : attachment_id
					        }
					    });
					    var _ref;
					    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;        
					}
				});					
			});			

			$scope.getPackages();			
		};

		//	get total amount from the scope.
		$scope.getTotal = function(){
			var d = $scope.breakDown.fee;
			var amount = 0;
			
			for( var i in d){

				if( d.hasOwnProperty(i) ){
					
					if( d[i].set == '1' ){
						if( d[i].type == 'percent' ){
							var p = parseInt(d[i].value);
							var val = $scope.param.formData.productInfo.budget * p / 100;
							amount += val;
						}
						else if( d[i].type == 'fixed' ){
							amount += parseInt(d[i].value);
						}
						else{}						
					}
					
				}
			}
			$scope.param.formData.packageDataTotal = amount;
			return amount;
		};
		
		$scope.init = function(){
			
			$http({
				method: 'POST',
				url: admin_ajax + '?action=get_conditions'				
	        })
			.success(function(data) {
				$scope.param.data = data;
			});
		};
		
		$scope.submitForm = function(){
			var fromparam = [];
			var P = $scope.param.formParam.otherData;

			console.log($scope.param.formData);
						
			for( var i in P ){
				if( P.hasOwnProperty(i) ){
					fromparam.push(P[i].data.slug);
				}
			}
			
			var saving = ngDialog.open({
				template: '<p class="processing-message"><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></p><p class="processing-message">Saving...</p>',
				plain: true,
				closeByDocument: false,
				closeByEscape: false
			});
					

			$http({
				method: 'POST',
				url: admin_ajax + '?action=save_consignment',
				data:{
					packageId : $scope.param.packageID,
					formData : $scope.param.formData,
					formParam: fromparam.join(','),
					return_url: RETURN_URL
				}
	        })
			.success(function(data) {
				console.log(data);
				
				saving.close();
				
				var dialog = ngDialog.open({
					template: '<p class="success-message"><span class="glyphicon glyphicon-saved" aria-hidden="true"></span></p><p class="success-message">Saved Successfully!</p>',
					plain: true,
					closeByDocument: false,
					closeByEscape: false
				});
				
				setTimeout(function () {
					dialog.close();
					if(data.status == 200 && data.redirect_to != ''){
						location.href = data.redirect_to;
					}
						
				}, 2000);
				
				
			});
		};

		$scope.authReceipt = function(){
			if($scope.param.formData.hasReceipt){
				$scope.param.formData.hasReceiptNot = false;
			}
			else{
				$scope.param.formData.hasReceiptNot = true;
			}
		};

		$scope.authReceiptNot = function(){
			if($scope.param.formData.hasReceiptNot){
				$scope.param.formData.hasReceipt = false;
			}
			else{
				$scope.param.formData.hasReceipt = true;
			}
		};

		$scope.getPackages = function(){
			$http.get(admin_ajax + '?action=get_consignment_prices')
			.success(function(data) {			
				console.log(data);	
				$scope.param.packageData = data;				
			});
		};

		$scope.init();
		$scope.flush();


		/*	$scope decalration ends...	*/
	}]);
	
	app.controller('conciergeController', ['$scope', '$timeout', '$http', 'ngDialog', function($scope, $timeout, $http, ngDialog){
		
		/* $scope declaration starts */
		
		//	Store all scope variables and params
		$scope.param = {
			data:conciergeData,
			formParam: {				
				item: {},
				brand: {},
				style: {},
				model: {},
				otherData :[]
			},
			formData: {
				productInfo: {
					item : '',
					brand: '',					
					style: '',
					model: '',
					otherData: {},
					budget: '',
					otherNote: ''
				},					
				offerPics : []
			}
		};		
		
		$scope.valid = false;
		$scope.tc = 0;
		$scope.TYPE = TYPE;
		
		$scope.emptyOtherData = [
             {
            	 data : {
            		 name: 'Description 1',
            		 slug: 'desc1'
            	 }
             },
             {
            	 data : {
            		 name: 'Description 2',
            		 slug: 'desc2'
            	 }
             },
             {
            	 data : {
            		 name: 'Description 3',
            		 slug: 'desc3'
            	 }
             },
             {
            	 data : {
            		 name: 'Description 4',
            		 slug: 'desc4'
            	 }
             },
             {
            	 data : {
            		 name: 'Description 5',
            		 slug: 'desc5'
            	 }
             }
             
        ];
		
		$scope.empty = {
				data : {
					name: 'Self Defined',
					term_id: "9999", 
				}
		};
		
		$scope.pushEmpty = function(elem){
			if( typeof elem === "object"){
				elem[9999] = $scope.empty;
			}
			else{
				elem = {
					9999 : $scope.empty
				}
			}
			return elem;
		};
		
		$scope.itemSelected = function(){
			$scope.param.formParam.brand = $scope.param.data[$scope.param.formData.productInfo.item].child;
			$scope.param.formParam.brand = $scope.pushEmpty($scope.param.formParam.brand);
			$scope.param.formData.productInfo.brand = '';
			$scope.param.formData.productInfo.style = '';
			$scope.param.formData.productInfo.model = '';
			$scope.param.formParam.otherData = [];
		};
		
		$scope.brandSelected = function(){
			if($scope.param.formData.productInfo.brand != null){
				$scope.param.formParam.style = $scope.param.formParam.brand[$scope.param.formData.productInfo.brand].child;
			}			
			$scope.param.formParam.style = $scope.pushEmpty($scope.param.formParam.style);
			$scope.param.formData.productInfo.style = '';	
			$scope.param.formData.productInfo.model = '';
			$scope.param.formParam.otherData = [];
			
			if( $scope.param.formData.productInfo.brand == "9999" ){
				$scope.param.formData.productInfo.style = "9999";
				$scope.styleSelected();
			}
			if( $scope.param.formData.productInfo.brand == "" ){
				$scope.param.formData.productInfo.style = "";											
				$scope.styleSelected();
			}
			
		};
		
		$scope.styleSelected = function(){
			if($scope.param.formData.productInfo.style != null ){
				$scope.param.formParam.model = $scope.param.formParam.style[$scope.param.formData.productInfo.style].child;
			}			
			$scope.param.formParam.model = $scope.pushEmpty($scope.param.formParam.model);
			$scope.param.formData.productInfo.model = '';
			$scope.param.formParam.otherData = [];
			
			if( $scope.param.formData.productInfo.style == "9999" ){
				$scope.param.formData.productInfo.model = "9999";											
				$scope.modelSelected();
			}
			if( $scope.param.formData.productInfo.style == "" ){
				$scope.param.formData.productInfo.model = "";											
				$scope.modelSelected();
			}
			
		}
		
		$scope.modelSelected = function(){
			if($scope.param.formData.productInfo.model != null){
				$scope.param.formParam.otherData = $scope.param.formParam.model[$scope.param.formData.productInfo.model].child;			
			}			
			if( typeof $scope.param.formParam.otherData !== "object" ){
				$scope.param.formParam.otherData = $scope.emptyOtherData;
			}
			
		}
		
		//	Holds scope step identifier
		$scope.step = 1;
		
		
		//	Flush the DOM based on $timeout. (from previous experience...)
		$scope.flush = function(){
			$timeout(function(){

				if(jQuery(".profilePicDropzone").length == 0 || typeof jQuery.fn.dropzone != "function"){
					return;
				}

				jQuery(".profilePicDropzone").dropzone({
					url: drophandler,
					paramName: 'offerPic',
					clickable: true,
					acceptedFiles: 'image/*',
					success: function (file, response) {			
						file.previewElement.classList.add("dz-success");
						$scope.param.formData.offerPics.push(response);
						file['attachment_id'] = response;						
					},
					error: function (file, response) {
						file.previewElement.classList.add("dz-error");
					},
					addRemoveLinks: true,
					removedfile: function(file) {
					    var attachment_id = file.attachment_id;
					    var index = $scope.param.formData.offerPics.indexOf(attachment_id);
					    if (index > -1) {
							$scope.param.formData.offerPics.splice(index, 1);
						}
					    jQuery.ajax({
					        type: 'POST',
					        url: deletehandler,
					        data: {
					        	media_id : attachment_id
					        }
					    });
					    var _ref;
					    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;        
					}
				});				
				
				$scope.setupPostdata();
			});					
		};
		
		$scope.setupPostdata = function(){
			
			var pd = POSTED_DATA;
						
			$scope.param.formData.productInfo.item = pd.concierge_item;
			$scope.itemSelected();
			
			if( typeof $scope.param.formParam.brand[pd.concierge_brand] === "undefined" ){
				$scope.param.formData.productInfo.brand = '9999';
				$scope.param.formData.productInfo.brandCustom = pd.concierge_brand
			}
			else{
				$scope.param.formData.productInfo.brand = pd.concierge_brand;
			}
			$scope.brandSelected();

			if( typeof $scope.param.formParam.style[pd.concierge_style] === "undefined" ){
				$scope.param.formData.productInfo.style = '9999';
				$scope.param.formData.productInfo.styleCustom = pd.concierge_style
			}
			else{
				$scope.param.formData.productInfo.style = pd.concierge_style;
			}
			$scope.styleSelected();

			if( typeof $scope.param.formParam.model[pd.concierge_model] === "undefined" ){
				$scope.param.formData.productInfo.model = '9999';
				$scope.param.formData.productInfo.modelCustom = pd.concierge_model
			}
			else{
				$scope.param.formData.productInfo.model = pd.concierge_model;
			}
			$scope.modelSelected();
			
			var others = pd.concierge_params.split(',');

			for ( var i in others ){
				if( others.hasOwnProperty(i) ){		
					var oo = others[i];			
					var ee = 'concierge_'+ oo;								
					$scope.param.formData.productInfo.otherData[oo] = pd[ee];
				}
			}

			$scope.param.formData.productInfo.budget = pd.concierge_budget;
			$scope.param.formData.productInfo.otherNote = pd.concierge_other_details;
			
		};
		
		$scope.termsChecked = function (){
			$scope.valid = false;
			
			if( $scope.param.formData.productInfo.budget !== '' && $scope.tc == 1 ){
				$scope.valid = true;
			}
			
		}
		
		$scope.submitForm = function(){
			
			$scope.processing = true;
			
			var fromparam = [];
			var P = $scope.param.formParam.otherData;
			
			for( var i in P ){
				if( P.hasOwnProperty(i) ){
					fromparam.push(P[i].data.slug);
				}
			}
			
			var saving = ngDialog.open({
				template: '<p class="processing-message"><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></p><p class="processing-message">Saving...</p>',
				plain: true,
				closeByDocument: false,
				closeByEscape: false
			});
			
			$http({
				method: 'POST',
				url: admin_ajax + '?action=save_concierge',
				data:{
					formData : $scope.param.formData,
					formParam: fromparam.join(','),
					return_url: RETURN_URL,
					type:TYPE,
					concierge_id:concierge_id
				}
			})
			.success(function(data) {console.log(data);
				if(data.status == 200 && data.redirect_to != ''){
					
					saving.close();
					
					var dialog = ngDialog.open({
						template: '<p class="success-message"><span class="glyphicon glyphicon-saved" aria-hidden="true"></span></p><p class="success-message">Saved Successfully!</p>',
						plain: true,
						closeByDocument: false,
						closeByEscape: false
					});
					
					setTimeout(function () {
						dialog.close();
						location.href = data.redirect_to;
					}, 2000);
					
				}
			}).error(function(data){
				console.error(data);
			});
		};
		
		$scope.flush();		
		
		/*	$scope decalration ends...	*/

	}]);

	app.directive('checkList', function() {
	  return {
	    scope: {
	      list: '=checkList',
	      value: '@'
	    },
	    link: function(scope, elem, attrs) {
	      var handler = function(setup) {
	        var checked = elem.prop('checked');
	        var index = scope.list.indexOf(scope.value);

	        if (checked && index == -1) {
	          if (setup) elem.prop('checked', false);
	          else scope.list.push(scope.value);
	        } else if (!checked && index != -1) {
	          if (setup) elem.prop('checked', true);
	          else scope.list.splice(index, 1);
	        }
	      };

	      var setupHandler = handler.bind(null, true);
	      var changeHandler = handler.bind(null, false);

	      elem.bind('change', function() {
	        scope.$apply(changeHandler);
	      });
	      scope.$watch('list', setupHandler, true);
	    }
	  };
	});
	
	app.directive('uiCurrency', function ($filter, $parse) {
	    return {
	        require: 'ngModel',
	        restrict: 'A',
	        link: function (scope, element, attrs, ngModel) {

	            function parse(viewValue, noRender) {
	                if (!viewValue)
	                    return viewValue;

	                // strips all non digits leaving periods.
	                var clean = viewValue.toString().replace(/[^0-9.]+/g, '').replace(/\.{2,}/, '.');

	                // case for users entering multiple periods throughout the number
	                var dotSplit = clean.split('.');
	                if (dotSplit.length > 2) {
	                    clean = dotSplit[0] + '.' + dotSplit[1].slice(0, 2);
	                } else if (dotSplit.length == 2) {
	                    clean = dotSplit[0] + '.' + dotSplit[1].slice(0, 2);
	                }

	                if (!noRender)
	                    ngModel.$render();
	                return clean;
	            }

	            ngModel.$parsers.unshift(parse);

	            ngModel.$render = function() {
	                var clean = parse(ngModel.$viewValue, true);
//	                if (!clean){
//	                	element.val(clean);
//	                }
	                    

	                var currencyValue,
	                    dotSplit = clean.split('.');

	                // todo: refactor, this is ugly
	                if (clean[clean.length-1] === '.') {
	                     currencyValue = $filter('number')(parseFloat(clean)) + '.';

	                } else if (clean.indexOf('.') != -1 && dotSplit[dotSplit.length - 1].length == 1) {
	                    currencyValue = $filter('number')(parseFloat(clean), 1);
	                } else if (clean.indexOf('.') != -1 && dotSplit[dotSplit.length - 1].length == 1) {
	                    currencyValue = $filter('number')(parseFloat(clean), 2);
	                } else {
	                     currencyValue = $filter('number')(parseFloat(clean));
	                }

	                element.val(currencyValue);
	            };

	        }
	    };
	})

})();