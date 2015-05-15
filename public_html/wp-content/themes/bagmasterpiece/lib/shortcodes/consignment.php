<?php
function BMP_get_consignment($attr, $content = ''){

	if( isset( $_GET['new'] )  ):

		include_once dirname( __FILE__ ) .'/consignment-new.php';
	
	else:
	
		include_once dirname( __FILE__ ) .'/consignment-list.php';
	
	endif;
	
	return $form;
}

add_action('wp_ajax_get_conditions','BMP_get_conditions');

function BMP_get_conditions(){
	
	$data_raw = get_terms('concierge', array('hide_empty' => false, 'orderby' => 'id' ));
	$data = array();
	
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

	echo json_encode($data);
	die();
}