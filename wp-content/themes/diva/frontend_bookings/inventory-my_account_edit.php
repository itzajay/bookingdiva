<?php
	global $email_alert;
	$first_name = get_usermeta( $user_id, 'first_name');
	$last_name = get_usermeta( $user_id, 'last_name');
	$mobile = get_usermeta( $user_id, 'mobile');
	$city = get_usermeta( $user_id, 'city');
	$zipcode = get_usermeta( $user_id, 'zipcode');
	$gender = get_usermeta( $user_id, 'gender');
	$dob = get_usermeta( $user_id, 'dob');
	$gender = get_usermeta( $user_id, 'gender');
	$alert = get_usermeta( $user_id, 'email_alert');
	if(in_array('alert1',$alert))
	$alert1 = "checked = 'checked'";
	if(in_array('alert2',$alert))
	$alert2 = "checked = 'checked'";
	if(in_array('alert3',$alert))
	$alert3 = "checked = 'checked'";
	if($gender == 'male')
	$male = "checked = 'checked'";
	if($gender == 'female')
	$female = "checked = 'checked'";
	$notification_method = get_usermeta( $user_id, 'notification_method');
	if(in_array('sms',$notification_method))
	$sms = "checked = 'checked'";
	if(in_array('email',$notification_method))
	$email = "checked = 'checked'";
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
          <div class="accout_detail_div">
				<div class="accout_detail_heading">
					<h1>General Information :</h1>
				</div>
				
				<form  method="post" action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
					<input type="hidden" name="inventory" value = "<?php echo $_GET['inventory'];?>"/>
					<input type="hidden" name="info" value = "general"/>
					<ul class="account_edit">
						<li class="short_fieldlt">
							<h2>
								First Name
							</h2>
							<h3>
								<input name="first_name" type="text" class="s_feld" value="<?php echo $first_name?>" />
							</h3>
						</li>
						<li class="short_fieldrt">
							<h2>
								Last Name
							</h2>
							<h3>
								<input name="last_name" type="text" class="s_feld" value="<?php echo $last_name?>" />
							</h3>
						</li>
						<li class="short_fieldlt">
							<h2>
								City
							</h2>
							<h3>
								<input name="city" type="text" class="s_feld" value="<?php echo $city?>" />
							</h3>
						</li>
						<li class="short_fieldrt">
							<h2>
								Mobile
							</h2>
							<h3>
								<input name="mobile" type="text" class="s_feld" value = "<?php echo $mobile?>" maxlength = 10/>
								<input name="old_mobile" type="hidden" class="s_feld" value = "<?php echo $mobile?>"/>
							</h3>
						</li>
						<li class="short_fieldlt">
							<h2>
								Zipcode
							</h2>
							<h3>
								<input name="zipcode" type="text" class="s_feld" value="<?php echo $zipcode?>" />
							</h3>
						</li>
						<li class="short_fieldrt">
							<h2>
								Date of Birth
							</h2>
							<h3>
								<input name="dob" type="text" class="s_feld datepicker" value = "<?php echo $dob?>"/>
							</h3>
						</li>
						<li class="short_fieldlt">
							<h2>
								Gender
							</h2>
							<span>
								<input name="gender" type="radio" value="male" <?php echo $male;?> />
								Male &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input name="gender" type="radio" value="female" <?php echo $female;?> />
								Female
							</span>
						</li>
						<li class="short_fieldrt">
							<h2>
								Email Alerts
							</h2>
							<span>
								<input name="email_alert[0]" type="checkbox" value="alert1" <?php echo $alert1;?> />
								<?php echo $email_alert['alert1'];?><br/>
								<input name="email_alert[1]" type="checkbox" value="alert2" <?php echo $alert2;?> />
								<?php echo $email_alert['alert2'];?><br/>
								<input name="email_alert[2]" type="checkbox" value="alert3" <?php echo $alert3;?> />
								<?php echo $email_alert['alert3'];?>
							</span>
						</li>
						<li class="short_fieldlt">
							<h2>
								Notification By
							</h2>
							<span>
								<input name="notification_method[0]" type="checkbox" value="email" <?php echo $email;?>  />
								Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input name="notification_method[1]" type="checkbox" value="sms" <?php echo $sms;?> />
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
					<h1>Account Information :</h1>
				</div>
				<?php $current_user = wp_get_current_user();?>
				<form  method="post" action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
					<input type="hidden" name="inventory" value = "<?php echo $_GET['inventory'];?>"/>
					<input type="hidden" name="info" value = 'account'/>
					<input type="hidden" name="user_login" value = "<?php echo $current_user->user_login; ?>"/>
					<ul class="account_edit">
						<li class="short_fieldlt">
							<h2>
								Email
							</h2>
							<h3 style = "background:none;">
								<input name="email" type="text" class="s_feld" value = "<?php echo $current_user->user_email; ?>" disabled = "disabled"/>
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
            <li><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/inner_body_banner.jpg" alt="" /></li>
            <li><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/images/inner_body_banner2.jpg" alt="" /></li>
          </ul>
        </div>
      </div>
      <!-- body container end -->
    </div>
    <!--page content container end-->