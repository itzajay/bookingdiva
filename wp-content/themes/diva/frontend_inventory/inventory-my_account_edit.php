<?php
	$user_ids = get_restro_users();
	$owner = $user_ids[1];
	$manager = $user_ids[2];
	$reservenist = $user_ids[3];
	
	//owner data
	$owner_first_name = get_usermeta( $owner, 'first_name');
	$owner_last_name = get_usermeta( $owner, 'last_name');
	$owner_mobile = get_usermeta( $owner, 'mobile');
	$owner_notification = get_usermeta( $owner, 'notification');
	if(in_array('booking',$owner_notification))
	$owner_booking = "checked = 'checked'";
	if(in_array('changes',$owner_notification))
	$owner_changes = "checked = 'checked'";
	if(in_array('cancellation',$owner_notification))
	$owner_cancellation = "checked = 'checked'";
	$owner_subscription = get_usermeta( $owner, 'subscription');
	if($owner_subscription == 'services')
	$owner_service = "checked = 'checked'";
	$owner_notification_method = get_usermeta( $owner, 'notification_method');
	if(in_array('sms',$owner_notification_method))
	$owner_sms = "checked = 'checked'";
	if(in_array('email',$owner_notification_method))
	$owner_email = "checked = 'checked'";
	$owner_info = get_userdata($owner);
	
	//manager data
	$manager_first_name = get_usermeta( $manager, 'first_name');
	$manager_last_name = get_usermeta( $manager, 'last_name');
	$manager_mobile = get_usermeta( $manager, 'mobile');
	$manager_notification = get_usermeta( $manager, 'notification');
	if(in_array('booking',$manager_notification))
	$manager_booking = "checked = 'checked'";
	if(in_array('changes',$manager_notification))
	$manager_changes = "checked = 'checked'";
	if(in_array('cancellation',$manager_notification))
	$manager_cancellation = "checked = 'checked'";
	$manager_subscription = get_usermeta( $manager, 'subscription');
	if($manager_subscription == 'services')
	$manager_service = "checked = 'checked'";
	$manager_notification_method = get_usermeta( $manager, 'notification_method');
	if(in_array('sms',$manager_notification_method))
	$manager_sms = "checked = 'checked'";
	if(in_array('email',$manager_notification_method))
	$manager_email = "checked = 'checked'";
	$manager_info = get_userdata($manager);
	
	//reservenist data
	$reservenist_first_name = get_usermeta( $reservenist, 'first_name');
	$reservenist_last_name = get_usermeta( $reservenist, 'last_name');
	$reservenist_mobile = get_usermeta( $reservenist, 'mobile');
	$reservenist_notification = get_usermeta( $reservenist, 'notification');
	if(in_array('booking',$reservenist_notification))
	$reservenist_booking = "checked = 'checked'";
	if(in_array('changes',$reservenist_notification))
	$reservenist_changes = "checked = 'checked'";
	if(in_array('cancellation',$reservenist_notification))
	$reservenist_cancellation = "checked = 'checked'";
	$reservenist_subscription = get_usermeta( $reservenist, 'subscription');
	if($reservenist_subscription == 'services')
	$reservenist_service = "checked = 'checked'";
	$reservenist_notification_method = get_usermeta( $reservenist, 'notification_method');
	if(in_array('sms',$reservenist_notification_method))
	$reservenist_sms = "checked = 'checked'";
	if(in_array('email',$reservenist_notification_method))
	$reservenist_email = "checked = 'checked'";
	$reservenist_info = get_userdata($reservenist);
	
?>
<!--page content container start-->
    <div class="content_warp">
      <div class="heading_warp">
        <h1>My Account</h1>
        <h2><a href="<?php echo get_bloginfo('url');?>">Home</a> &gt; My Account</h2>
      </div>
      <!-- body container start-->
      <div class="main_body_conainer">
        <!--body left div start-->
        <div class="body_conainer_lt">
			<?php if(!is_reservnist()){?>
				<div class="accout_detail_div">
					<div class="accout_detail_heading">
						<h1>Restraunt Owner General Information :</h1>
					</div>
					<form  method="post" action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
						<input type="hidden" name="inventory" value = "<?php echo $_GET['inventory'];?>"/>
						<input type="hidden" name="info" value = "general"/>
						<input type="hidden" name="user_id" value = "<?php echo $owner?>"/>
						<ul class="account_edit">
							<li class="short_fieldlt">
								<h2>
									First Name
								</h2>
								<h3>
									<input name="first_name" type="text" class="s_feld" value="<?php echo $owner_first_name?>" />
								</h3>
							</li>
							<li class="short_fieldrt">
								<h2>
									Last Name
								</h2>
								<h3>
									<input name="last_name" type="text" class="s_feld" value="<?php echo $owner_last_name?>" />
								</h3>
							</li>
							<li class="short_fieldlt">
								<h2>
									Mobile
								</h2>
								<h3>
									<input name="mobile" type="text" class="s_feld" value = "<?php echo $owner_mobile?>" maxlength = 10/>
								</h3>
							</li>
							<li class="short_fieldrt">
								<h2>
									Notifications
								</h2>
								<span>
									<input name="notification[0]" type="checkbox" value="booking" <?php echo $owner_booking; ?> />
									Bookings&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input name="notification[1]" type="checkbox" value="changes" <?php echo $owner_changes; ?> />
									Changes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input name="notification[2]" type="checkbox" value="cancellation" <?php echo $owner_cancellation;?> />
									Cancellation
								</span>
							</li>
							<li class="short_fieldlt">
								<h2>
									Subscription
								</h2>
								<span>
									<input name="subscription" type="checkbox" value= "services" <?php echo $owner_service?>/>
									Newsletter, New Services, and Offers
								</span>
							</li>
							<li class="short_fieldrt">
								<h2>
									Notification By
								</h2>
								<span>
									<input name="notification_method[0]" type="checkbox" value="email" <?php echo $owner_email;?>  />
									Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input name="notification_method[1]" type="checkbox" value="sms" <?php echo $owner_sms;?> />
									SMS
								</span>
							</li>
							<li class="save">
								<input name="" type="image" src="<?php echo DIVATEMPLATEPATH; ?>/images/save.png" />
							</li>
						</ul>
					</form>
				</div>
				<div class="accout_detail_div">
					<div class="accout_detail_heading">
						<h1>Restraunt Owner Account Information :</h1>
					</div>
					<?php $current_user = wp_get_current_user();?>
					<form  method="post" action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
						<input type="hidden" name="inventory" value = "<?php echo $_GET['inventory'];?>"/>
						<input type="hidden" name="info" value = 'account'/>
						<input type="hidden" name="user_login" value = "<?php echo $current_user->user_login; ?>"/>
						<input type="hidden" name="user_id" value = "<?php echo $owner?>"/>
						<ul class="account_edit">
							<li class="short_fieldlt">
								<h2>
									Email
								</h2>
								<h3 style = "background:none;">
									<input name="email" type="text" class="s_feld" value = "<?php echo $owner_info->user_email; ?>" disabled="disabled"/>
								</h3>
							</li>
							<li class="short_fieldrt">
								<h2>
									Password
								</h2>
								<h3>
									<input name="password" type="password" class="s_feld" />
								</h3>
							</li>
							<li class="short_fieldrt">
								&nbsp;
							</li>
							<li class="save">
								<input name="" type="image" src="<?php echo DIVATEMPLATEPATH; ?>/images/save.png" />
							</li>
						</ul>
					</form>
				</div>
				<br/>
				<br/>
				<div class="accout_detail_div">
					<div class="accout_detail_heading">
						<h1>Restraunt Manager General Information :</h1>
					</div>
					<form  method="post" action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
						<input type="hidden" name="inventory" value = "<?php echo $_GET['inventory'];?>"/>
						<input type="hidden" name="info" value = "general"/>
						<input type="hidden" name="user_id" value = "<?php echo $manager?>"/>
						<ul class="account_edit">
							<li class="short_fieldlt">
								<h2>
									First Name
								</h2>
								<h3>
									<input name="first_name" type="text" class="s_feld" value="<?php echo $manager_first_name?>" />
								</h3>
							</li>
							<li class="short_fieldrt">
								<h2>
									Last Name
								</h2>
								<h3>
									<input name="last_name" type="text" class="s_feld" value="<?php echo $manager_last_name?>" />
								</h3>
							</li>
							<li class="short_fieldlt">
								<h2>
									Mobile
								</h2>
								<h3>
									<input name="mobile" type="text" class="s_feld" value = "<?php echo $manager_mobile?>" maxlength = 10/>
								</h3>
							</li>
							<li class="short_fieldrt">
								<h2>
									Notifications
								</h2>
								<span>
									<input name="notification[0]" type="checkbox" value="booking" <?php echo $manager_booking; ?> />
									Bookings&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input name="notification[1]" type="checkbox" value="changes" <?php echo $manager_changes; ?> />
									Changes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input name="notification[2]" type="checkbox" value="cancellation" <?php echo $manager_cancellation;?> />
									Cancellation
								</span>
							</li>
							<li class="short_fieldlt">
								<h2>
									Subscription
								</h2>
								<span>
									<input name="subscription" type="checkbox" value= "services" <?php echo $manager_service?>/>
									Newsletter, New Services, and Offers
								</span>
							</li>
							<li class="short_fieldrt">
								<h2>
									Notification By
								</h2>
								<span>
									<input name="notification_method[0]" type="checkbox" value="email" <?php echo $manager_email;?>  />
									Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input name="notification_method[1]" type="checkbox" value="sms" <?php echo $manager_sms;?> />
									SMS
								</span>
							</li>
							<li class="save">
								<input name="" type="image" src="<?php echo DIVATEMPLATEPATH; ?>/images/save.png" />
							</li>
						</ul>
					</form>
				</div>
				<div class="accout_detail_div">
					<div class="accout_detail_heading">
						<h1>Restraunt Manager Account Information :</h1>
					</div>
					<?php $current_user = wp_get_current_user();?>
					<form  method="post" action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
						<input type="hidden" name="inventory" value = "<?php echo $_GET['inventory'];?>"/>
						<input type="hidden" name="info" value = 'account'/>
						<input type="hidden" name="user_login" value = "<?php echo $current_user->user_login; ?>"/>
						<input type="hidden" name="user_id" value = "<?php echo $manager?>"/>
						<ul class="account_edit">
							<li class="short_fieldlt">
								<h2>
									Email
								</h2>
								<h3 style = "background:none;">
									<input name="email" type="text" class="s_feld" value = "<?php echo $manager_info->user_email; ?>" disabled="disabled"/>
								</h3>
							</li>
							<li class="short_fieldrt">
								<h2>
									Password
								</h2>
								<h3>
									<input name="password" type="password" class="s_feld" />
								</h3>
							</li>
							<li class="short_fieldrt">
								&nbsp;
							</li>
							<li class="save">
								<input name="" type="image" src="<?php echo DIVATEMPLATEPATH; ?>/images/save.png" />
							</li>
						</ul>
					</form>
				</div>
				<br/>
				<br/>
			<?php }?>
			<div class="accout_detail_div">
				<div class="accout_detail_heading">
					<h1>Restaurant Reservenist General Information :</h1>
				</div>
				<form  method="post" action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
					<input type="hidden" name="inventory" value = "<?php echo $_GET['inventory'];?>"/>
					<input type="hidden" name="info" value = "general"/>
					<input type="hidden" name="user_id" value = "<?php echo $reservenist?>"/>
					<ul class="account_edit">
						<li class="short_fieldlt">
							<h2>
								First Name
							</h2>
							<h3>
								<input name="first_name" type="text" class="s_feld" value="<?php echo $reservenist_first_name?>" />
							</h3>
						</li>
						<li class="short_fieldrt">
							<h2>
								Last Name
							</h2>
							<h3>
								<input name="last_name" type="text" class="s_feld" value="<?php echo $reservenist_last_name?>" />
							</h3>
						</li>
						<li class="short_fieldlt">
							<h2>
								Mobile
							</h2>
							<h3>
								<input name="mobile" type="text" class="s_feld" value = "<?php echo $reservenist_mobile?>" maxlength = 10/>
							</h3>
						</li>
						<li class="short_fieldrt">
							<h2>
								Notifications
							</h2>
							<span>
								<input name="notification[0]" type="checkbox" value="booking" <?php echo $reservenist_booking; ?> />
								Bookings&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input name="notification[1]" type="checkbox" value="changes" <?php echo $reservenist_changes; ?> />
								Changes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input name="notification[2]" type="checkbox" value="cancellation" <?php echo $reservenist_cancellation;?> />
								Cancellation
							</span>
						</li>
						<li class="short_fieldlt">
							<h2>
								Subscription
							</h2>
							<span>
								<input name="subscription" type="checkbox" value= "services" <?php echo $reservenist_service?>/>
								Newsletter, New Services, and Offers
							</span>
						</li>
						<li class="short_fieldrt">
							<h2>
								Notification By
							</h2>
							<span>
								<input name="notification_method[0]" type="checkbox" value="email" <?php echo $reservenist_email;?>  />
								Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input name="notification_method[1]" type="checkbox" value="sms" <?php echo $reservenist_sms;?> />
								SMS
							</span>
						</li>
						<li class="save">
							<input name="" type="image" src="<?php echo DIVATEMPLATEPATH; ?>/images/save.png" />
						</li>
					</ul>
				</form>
            </div>
            <div class="accout_detail_div">
				<div class="accout_detail_heading">
					<h1>Restaurant Reservenist Account Information :</h1>
				</div>
				<?php $current_user = wp_get_current_user();?>
				<form  method="post" action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
					<input type="hidden" name="inventory" value = "<?php echo $_GET['inventory'];?>"/>
					<input type="hidden" name="info" value = 'account'/>
					<input type="hidden" name="user_login" value = "<?php echo $current_user->user_login; ?>"/>
					<input type="hidden" name="user_id" value = "<?php echo $reservenist?>"/>
					<ul class="account_edit">
						<li class="short_fieldlt">
							<h2>
								Email
							</h2>
							<h3 style = "background:none;">
								<input name="email" type="text" class="s_feld" value = "<?php echo $reservenist_info->user_email; ?>" disabled="disabled"/>
							</h3>
						</li>
						<li class="short_fieldrt">
							<h2>
								Password
							</h2>
							<h3>
								<input name="password" type="password" class="s_feld" />
							</h3>
						</li>
						<li class="short_fieldrt">
							&nbsp;
						</li>
						<li class="save">
							<input name="" type="image" src="<?php echo DIVATEMPLATEPATH; ?>/images/save.png" />
						</li>
					</ul>
				</form>
            </div>
        </div>
        <!--body left div end-->
        <div class="body_conainer_rt">
          <ul class="rt_banner">
            <li><img src="<?php echo DIVATEMPLATEPATH; ?>/images/inner_body_banner.jpg" alt="" /></li>
            <li><img src="<?php echo DIVATEMPLATEPATH; ?>/images/inner_body_banner2.jpg" alt="" /></li>
          </ul>
        </div>
      </div>
      <!-- body container end -->
    </div>
    <!--page content container end-->