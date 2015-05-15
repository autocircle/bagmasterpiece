/***
 * 
 * General functioning scripts for the website
 * 
 */

jQuery(document).ready(function ($){
	
	//	selectbox manipulation
	if($('.select-filter').length > 0 && typeof jQuery.fn.select2 !== 'undefined'){
		$('.select-filter select').select2({
			  minimumResultsForSearch: Infinity
		});
	}
	
	//	slider in single product page
	if($('.bxslider').length > 0 && typeof jQuery.fn.bxSlider !== 'undefined'){
		
		slider = $('.bxslider').bxSlider({
			pagerCustom: '#bx-pager'
		});
		$('.bxslider .bx-clone a').removeAttr('data-rel');
		//slider.reloadSlider();
	}
	
	$(function () {
	  $('[data-toggle="popover"]').popover()
	})
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})
	
	
	
});
  
