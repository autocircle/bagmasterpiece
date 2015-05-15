<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/

global $bagmasterpiece;

?>
<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">
	<?php $template->the_action_template_message( 'login' ); ?>
	<?php $template->the_errors(); ?>
	<form name="loginform" id="loginform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'login' ); ?>" method="post">
		
		<div class="input-box">
			<p>
				<label for="user_login<?php $template->the_instance(); ?>"><?php _e( 'Email Address', 'theme-my-login' ); ?></label>
				<input type="text" name="log" id="user_login<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'log' ); ?>" size="20" placeholder="Email"/>
			</p>
			<p>
				<label for="user_pass<?php $template->the_instance(); ?>"><?php _e( 'Password', 'theme-my-login' ); ?></label>
				<input type="password" name="pwd" id="user_pass<?php $template->the_instance(); ?>" class="input" value="" size="20" placeholder="Password" />
			</p>
		</div>

		<?php do_action( 'login_form' ); ?>

		<p class="submit">
			<input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php esc_attr_e( 'Log In', 'theme-my-login' ); ?>" />
			<!-- <input type="hidden" name="redirect_to" value="<?php //$template->the_redirect_url( 'login' ); ?>" />-->
			<input type="hidden" name="redirect_to" value="<?php echo get_the_permalink( $bagmasterpiece['profile-page-id'] ); ?>" />
			<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
			<input type="hidden" name="action" value="login" />
		</p>
		
		<?php 
			$register = $this->get_action_url( 'register' );
			$lost_pass = $this->get_action_url( 'lostpassword' );		
		?>
		
		<p class="submit forgot">
			<a href="<?php echo $lost_pass;?>">Forgot my password</a>
		</p>
		<p class="submit register">
			<a href="<?php echo $register;?>">Not a member yet? Sign up now!</a>
		</p>
	</form>
	
	
</div>
