

<form action="<?php echo nd_login_current_url(); ?>" method="post" class="nd_form" id="nd_login_form"><div class="nd_form_inner">
	<p style='text-align:center'><?php echo jfb_output_facebook_btn(); ?><p>
	<p class='or'>OR</p>	
	<?php
		global $nd_login_errors;
		if (isset($nd_login_errors) && sizeof($nd_login_errors)>0 && $nd_login_errors->get_error_code()) :
			echo '<ul class="errors">';
			foreach ($nd_login_errors->errors as $error) {
				echo '<li>'.$error[0].'</li>';
				break;
			}
			echo '</ul>';
		endif; 
	?>
	
	<p><input type="text" class="text" name="log" id="nd_username" placeholder="<?php _e('Email*', 'ninety'); ?>" /></p>
	<p><input type="password" class="text" name="pwd" id="nd_password" placeholder="<?php _e('Password*','ninety'); ?>" /></p>
	<p><input type="checkbox" checked='checked' name="rememberme" id="nd_rememberme" /><label for="nd_password" style='display:inline'><?php _e('Remember Me','ninety'); ?></label></p>
	<p>
		<a class="forgotten" href="#nd_lost_password_form"><?php _e('Forgot password?','ninety'); ?></a> <input type="submit" class="button" value="<?php _e('Login','ninety'); ?>" />
		<input name="nd_login" type="hidden" value="true"  />
		<input name="rememberme" type="hidden" id="rememberme" value="forever"  />
		<input name="redirect_to" type="hidden" id="redirect_to" value="<?php echo nd_login_current_url(); ?>"  />
	</p>
</div>
</form>