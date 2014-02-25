<form action="<?php echo nd_login_current_url(); ?>" method="post" class="nd_form" autocomplete="off" id="nd_lost_password_form" style="display:none"><div class="nd_form_inner">
	
	<?php
		global $nd_lost_pass_errors;
		if (isset($nd_lost_pass_errors) && sizeof($nd_lost_pass_errors)>0 && $nd_lost_pass_errors->get_error_code()) :
			echo '<ul class="errors">';
			foreach ($nd_lost_pass_errors->errors as $error) {
				echo '<li>'.$error[0].'</li>';
				break;
			}
			echo '</ul>';
		endif; 
	?>
	
	<p><?php _e('Please enter your e-mail address.You will receive a new password via e-mail.', 'ninety'); ?></p>
	
	<p><input type="text" class="text" name="username_or_email" id="nd_lost_username" placeholder="<?php _e('Email*', 'ninety'); ?>" /></p>
	
	<p><input type="submit" class="button" value="<?php _e('Get New Password','ninety'); ?>" /><input name="nd_lostpass" type="hidden" value="true"  /></p>

</div></form>