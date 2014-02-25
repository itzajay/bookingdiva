<?php $style = 'display:none;'; if(isset($_GET['ref'])) $style='display:block;' ?>
<form action="<?php echo nd_login_current_url(); ?>" method="post" class="nd_form" autocomplete="off" id="nd_register_form" style="<?php echo $style; ?>"><div class="nd_form_inner">
	<p style='text-align:center'><?php echo jfb_output_facebook_btn(); ?><p>
	<p class='or'>OR</p>	
	<?php
		global $nd_reg_errors;
		if (isset($nd_reg_errors) && sizeof($nd_reg_errors)>0 && $nd_reg_errors->get_error_code()) :
			echo '<ul class="errors">';
			foreach ($nd_reg_errors->errors as $error) {
				echo '<li>'.$error[0].'</li>';
				break;
			}
			echo '</ul>';
		endif; 
	?>
	
	<!--
	<p><label for="nd_reg_username"><?php _e('Username','ninety'); ?>:</label> <input type="text" class="text" name="username" id="nd_reg_username" placeholder="<?php _e('Username', 'ninety'); ?>" /></p>
-->
	<input type="hidden" class="text" name="username" id="nd_reg_username" placeholder="<?php _e('Username', 'ninety'); ?>" />
	<?php if(isset($_GET['ref'])){?>
		<input type="hidden" name="ref" value = "<?php echo $_GET['ref'];?>" />
	<?php }?>
	<p> <input type="text" class="text" name="email" id="nd_reg_email" placeholder="<?php _e('Email*', 'ninety'); ?>" /></p>
	<p> <input type="password" class="text" name="password" id="nd_reg_password" placeholder="<?php _e('Password*','ninety'); ?>" /></p>
	<p> <input type="password" class="text" name="password2" id="nd_reg_password_2" placeholder="<?php _e('Repeat Password*','ninety'); ?>" /></p>
	<p> <input type="text" class="text" name="mobile" placeholder="<?php _e('Mobile'); ?>" /></p>
	<p><input type="submit" class="button" value="<?php _e('Sign up','ninety'); ?>" /><input name="nd_register" type="hidden" value="true"  /></p>
</div></form>