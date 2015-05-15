<?php

require_once dirname(__FILE__) .'/concierge.php';
require_once dirname(__FILE__) .'/consignment.php';


add_shortcode('concierge-form', 'concierge_form_cb');
add_shortcode('consignment-form', 'BMP_get_consignment');
add_shortcode('sms-verification', 'BMP_sms_verify');

function pre_process_shortcode() {

    if (!is_page())
        return;

	if( current_user_can('manage_options') or current_user_can('vip') or current_user_can('Special') )
	   return;


	global $post;

	if (!empty($post->post_content)) {

		$regex = get_shortcode_regex();

		preg_match_all('/'.$regex.'/',$post->post_content,$matches);

		if ( !empty($matches[2]) && ( in_array('concierge-form',$matches[2]) or in_array('consignment-form',$matches[2]) ) ) {

			$parent = wp_get_post_parent_id($post->ID);

			if( $parent ){
				$location = get_permalink($parent);
			}
			else{
				$location = wp_login_url(get_permalink($post->ID));
			}

			wp_safe_redirect( $location );

			die();
		}
	}

}
add_action('template_redirect','pre_process_shortcode',1);

add_action('init','sms_verification_steps');
add_action('init','sms_verification_steps_code');

function sms_verification_steps_code(){

	if( ! isset($_POST['verify_code']) or !wp_verify_nonce($_POST['verify_code'], 'really-verify-sms-verification-code-verify') )
		return;

	$code = strtolower( trim(esc_attr($_POST['user_code'])) );

	$meta = strtolower( get_user_meta( get_current_user_id(), '__verification_code', true ) );

	if( $code === $meta ){

		$u = new WP_User( get_current_user_id() );

		$u->remove_role( 'regular' );
		$u->add_role( 'vip' );

		$location = add_query_arg( array('success'=>81), get_permalink(get_queried_object_id()) );
	}
	else{
		$location = add_query_arg(array('verify'=>1, 'error'=>2),get_permalink(get_queried_object_id()));
	}

	wp_safe_redirect($location);

}

function sms_verification_steps(){


	if( ! isset($_POST['verification']) or !wp_verify_nonce($_POST['verification'], 'really-verify-sms-verification-code') )
		return;

	//+8801674137365

	$number = esc_attr($_POST['user_phone']);



	if( $number == '' ){
		$location = add_query_arg(array('error'=>3),get_permalink(get_queried_object_id()));

		wp_safe_redirect($location);
		die();
	}

	if( number_exixts($number) ){
	    $location = add_query_arg(array('error'=>4),get_permalink(get_queried_object_id()));

	    wp_safe_redirect($location);
	    die();
	}


	/* Send an SMS using Twilio. You can run this file 3 different ways:
	 *
	* - Save it as sendnotifications.php and at the command line, run
	*        php sendnotifications.php
	*
	* - Upload it to a web host and load mywebhost.com/sendnotifications.php
	*   in a web browser.
	* - Download a local server like WAMP, MAMP or XAMPP. Point the web root
	*   directory to the folder containing this file, and load
	*   localhost:8888/sendnotifications.php in a web browser.
	*/
	// Include the PHP Twilio library. You need to download the library from
	// twilio.com/docs/libraries, and move it into the folder containing this
	// file.
	require_once LIBPATH . 'TwilioServices/Twilio.php';

	// Set our AccountSid and AuthToken from twilio.com/user/account

	// test
// 	$AccountSid = "AC62b269ee6c114fe044cbbb552974a22c";
// 	$AuthToken = "ac980c66627bd585ae8503edf3b73e54";

	// live
	$AccountSid = "AC73609d2123081f0ecb66987b6bd9bd12";
	$AuthToken = "8add8ab81b1b754d28ff01718bb1187e";

	// Instantiate a new Twilio Rest Client
	$client = new Services_Twilio($AccountSid, $AuthToken);

	/* Your Twilio Number or Outgoing Caller ID */
	$from = '+18555330188';

// 	$from = '+15005550006';
//  $number = '+15005550001';
	$length = 6;

	$code = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

	// Send a new outgoing SMS */
	$body = "Your Verification Code is: $code";

	try {
	    $client->account->sms_messages->create($from, $number, $body);

	    update_user_meta( get_current_user_id(), '__verification_code', $code );
	    update_user_meta( get_current_user_id(), 'contact_number', $number );

	    $location = add_query_arg(array('verify'=>22),get_permalink(get_queried_object_id()));

	    wp_safe_redirect($location);

	} catch (Exception $e) {

	    global $exception;

	    $exception = $e->getMessage();

	}


}

function BMP_sms_verify(){

	global $bagmasterpiece, $exception;

	$location = get_permalink($bagmasterpiece['profile-page-id']);




	$error_messages = array(
			1 => 'Unable to send sms to your phone number.',
			2 => 'Verification code mismatch.',
			3 => 'Invalid Phone number.',
	        4 => 'Number already exists.'
		);

	ob_start();

	?>

	<div class="sms-verify" id="theme-my-login">

		<?php if( isset($_GET['success']) ):?>

		<p>Thank you for verifying your phone number. You are now a VIP member and you will now enjoy all the special privileges only for our VIP members.</p>

		<?php elseif( current_user_can('vip') or current_user_can('Special') ):?>

		<p>You are already SMS verified.</p>

		<?php else:?>

			<?php if( isset($_REQUEST['error']) or !is_null($exception) ):?>
			<div class="col-md-12 woocommerce">
				<ul class="woocommerce-error">

				<?php if( isset($_REQUEST['error']) ):?>
					<li><?php echo $error_messages[ esc_attr($_REQUEST['error']) ]?></li>
				<?php endif;?>
				<?php if( !is_null($exception) ):?>
					<li><?php echo $exception;?></li>
				<?php endif;?>

				</ul>
			</div>
			<?php endif;?>

		<form name="sms-verification-form" id="sms-verification-form" action="<?php echo get_permalink(get_queried_object_id())?>" method="post">

			<?php if( isset($_REQUEST['verify']) ):?>
				<p>Please enter the verification code sent to your phone via SMS.</p>
				<p>
					<label for="user_login"><?php _e( 'Verification Code:', 'theme-my-login' ); ?></label>
					<input type="text" name="user_code" id="user_login" class="input" value=""/>
				</p>
				<?php wp_nonce_field('really-verify-sms-verification-code-verify','verify_code')?>
				<p class="submit">
					<input type="submit" name="send-code" id="wp-submit" value="<?php esc_attr_e( 'Verify Code', 'theme-my-login' ); ?>" />
				</p>
			<?php else:?>
				<p>Please enter a valid phone number below to send you a verification code via SMS.</p>
				<p>
					<label for="user_login"><?php _e( 'Phone number:', 'theme-my-login' ); ?></label>
					<input type="text" name="user_phone" id="user_login" class="input" value=""/>
				</p>
				<?php wp_nonce_field('really-verify-sms-verification-code','verification')?>
				<p class="submit">
					<input type="submit" name="send-code" id="wp-submit" value="<?php esc_attr_e( 'Send Verification Code', 'theme-my-login' ); ?>" />
				</p>
			<?php endif;?>
		</form>

		<?php endif;?>


	</div>


	<?php

	$form = ob_get_clean();
	return $form;
}