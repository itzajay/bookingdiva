<?php
	global $current_user;
	global $wpdb;
	session_start();
	$m_acct = $wpdb->get_row("select * from pocket where user_id =".$current_user->ID,ARRAY_A);
?>
<?php if (is_user_logged_in()) : 
	
	$current_user = wp_get_current_user();
	?>
	<h1>
		<ul class="nd_tabs">
			<li class="tab_right"><a href="<?php echo wp_logout_url( nd_login_current_url() ); ?>"><?php _e('Log out','ninety'); ?></a></li>
			<!--
			<li class="active"><a href="#nd_user"><?php echo $current_user->user_login; ?></a></li>
			<li><a href="#nd_recently_viewed"><?php _e('Recent Activity', 'ninety'); ?></a></li>
			-->
		</ul>
	</h1>
	
<?php else : ?>
	<h1>
		<ul class="nd_tabs">
			<li><a href="#nd_login_form"><?php _e('Login', 'ninety'); ?></a></li>
			<?php if (get_option('users_can_register')) : ?>
			<li class="<?php if(isset($_GET['ref'])) echo 'active'; ?>"><a href="#nd_register_form"><?php _e('Sign up', 'ninety'); ?></a></li><?php endif; ?>
		</ul>
	</h1>

<?php endif;
	if (is_user_logged_in()) {
		$first_name = get_usermeta($current_user->ID,'first_name');
		$last_name = get_usermeta($current_user->ID,'last_name');
		$name = $first_name." ".$last_name;
		if($name == " "){
			$name = $current_user->user_email;
		}
		if(is_general_user()){
			echo "<h2 style = 'margin-top:3px;'>";
			echo "<span>Welcome ".$name."</span>";
			if($m_acct){
				//echo "<span> ( <label>".$m_acct['loyalty_points']." loyalty points ".$m_acct['standby_points']." stand by points</label>)</span>";
			}
			echo "</h2>";
		}elseif(is_rest_user()){
			echo "<h2 style = 'margin-top:3px;'><span>Welcome ".$name."</span></h2>";
		}
	}
?>