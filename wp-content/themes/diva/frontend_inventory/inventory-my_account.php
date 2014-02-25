<?php
	$user_ids = get_restro_users();
	$owner = $user_ids[1];
	$manager = $user_ids[2];
	$reservenist = $user_ids[3];
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
						<h1>Restaurant Owner General Information :</h1>
						<div class="edit_div"><a href="<?php echo add_query_arg(array('inventory' => 'my_account_edit'), get_permalink(RESTAURANTOWNER)); ?>"><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/edit.png" /></a></div>
					</div>
					<dl class="accout_detail">
						<dt>First Name :</dt>
						<dd><?php echo get_usermeta( $owner, 'first_name' ); ?></dd>
						<dt>Last Name:</dt>
						<dd><?php echo get_usermeta( $owner, 'last_name' ); ?></dd>
						<dt>Email Notifications :</dt>
						<dd>
							<?php
								if(in_array('email',get_usermeta( $owner, 'notification_method' ))){
									echo implode(", ",get_usermeta( $owner, 'notification' ));
								}else{
									echo "No";
								}
							?>
						</dd>
						<dt>SMS Notifications :</dt>
						<dd>
							<?php
								if(in_array('sms',get_usermeta( $owner, 'notification_method' ))){
									echo implode(", ",get_usermeta( $owner, 'notification' ));
								}else{
									echo "No";
								}
							?>
						</dd>
						<dt>Newsletter :</dt>
						<dd>
							<?php
								if(get_usermeta( $owner, 'subscription' ) == 'services'){
									echo "Yes";
								}else{
									echo "No";
								}
							?>
						</dd>
						<dt>Mobile :</dt>
						<dd><?php echo get_usermeta( $owner, 'mobile' ); ?></dd>
					</dl>
				</div>
				<div class="accout_detail_div">
					<div class="accout_detail_heading">
						<h1>Restaurant Owner Account Information:</h1>
						<div class="edit_div"><a href="<?php echo add_query_arg(array('inventory' => 'my_account_edit'), get_permalink(RESTAURANTOWNER)); ?>"><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/edit.png" /></a></div>
					</div>
					<?php //debug($current_user);?>
					<dl class="accout_detail">
						<dt>Email :</dt>
						<dd>
							<?php
								$user_info = get_userdata($owner);
								echo $user_info->user_email;
							?>
						</dd>
						<dt>Password :</dt>
						<dd>********</dd>
					</dl>
				</div>
				<div class="accout_detail_div">
					<div class="accout_detail_heading">
						<h1>Restaurant Manager General Information :</h1>
						<div class="edit_div"><a href="<?php echo add_query_arg(array('inventory' => 'my_account_edit'), get_permalink(RESTAURANTOWNER)); ?>"><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/edit.png" /></a></div>
					</div>
					<dl class="accout_detail">
						<dt>First Name :</dt>
						<dd><?php echo get_usermeta( $manager, 'first_name' ); ?></dd>
						<dt>Last Name:</dt>
						<dd><?php echo get_usermeta( $manager, 'last_name' ); ?></dd>
						<dt>Email Notifications :</dt>
						<dd>
							<?php
								if(in_array('email',get_usermeta( $manager, 'notification_method' ))){
									echo implode(", ",get_usermeta( $manager, 'notification' ));
								}else{
									echo "No";
								}
							?>
						</dd>
						<dt>SMS Notifications :</dt>
						<dd>
							<?php
								if(in_array('sms',get_usermeta( $manager, 'notification_method' ))){
									echo implode(", ",get_usermeta( $manager, 'notification' ));
								}else{
									echo "No";
								}
							?>
						</dd>
						<dt>Newsletter :</dt>
						<dd>
							<?php
								if(get_usermeta( $manager, 'subscription' ) == 'services'){
									echo "Yes";
								}else{
									echo "No";
								}
							?>
						</dd>
						<dt>Mobile :</dt>
						<dd><?php echo get_usermeta( $manager, 'mobile' ); ?></dd>
					</dl>
				</div>
				<div class="accout_detail_div">
					<div class="accout_detail_heading">
						<h1>Restaurant Manager Account Information:</h1>
						<div class="edit_div"><a href="<?php echo add_query_arg(array('inventory' => 'my_account_edit'), get_permalink(RESTAURANTOWNER)); ?>"><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/edit.png" /></a></div>
					</div>
					<?php //debug($current_user);?>
					<dl class="accout_detail">
						<dt>Email :</dt>
						<dd>
							<?php
								$user_info = get_userdata($manager);
								echo $user_info->user_email;
							?>
						</dd>
						<dt>Password :</dt>
						<dd>********</dd>
					</dl>
				</div>
			<?php }?>
			<div class="accout_detail_div">
				<div class="accout_detail_heading">
					<h1>Restaurant Reservenist General Information :</h1>
					<div class="edit_div"><a href="<?php echo add_query_arg(array('inventory' => 'my_account_edit'), get_permalink(RESTAURANTOWNER)); ?>"><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/edit.png" /></a></div>
				</div>
				<dl class="accout_detail">
					<dt>First Name :</dt>
					<dd><?php echo get_usermeta( $reservenist, 'first_name' ); ?></dd>
					<dt>Last Name:</dt>
					<dd><?php echo get_usermeta( $reservenist, 'last_name' ); ?></dd>
					<dt>Email Notifications :</dt>
					<dd>
						<?php
							if(in_array('email',get_usermeta( $reservenist, 'notification_method' ))){
								echo implode(", ",get_usermeta( $reservenist, 'notification' ));
							}else{
								echo "No";
							}
						?>
					</dd>
					<dt>SMS Notifications :</dt>
					<dd>
						<?php
							if(in_array('sms',get_usermeta( $reservenist, 'notification_method' ))){
								echo implode(", ",get_usermeta( $reservenist, 'notification' ));
							}else{
								echo "No";
							}
						?>
					</dd>
					<dt>Newsletter :</dt>
					<dd>
						<?php
							if(get_usermeta( $reservenist, 'subscription' ) == 'services'){
								echo "Yes";
							}else{
								echo "No";
							}
						?>
					</dd>
					<dt>Mobile :</dt>
					<dd><?php echo get_usermeta( $reservenist, 'mobile' ); ?></dd>
				</dl>
            </div>
            <div class="accout_detail_div">
				<div class="accout_detail_heading">
					<h1>Restaurant Reservenist Account Information:</h1>
					<div class="edit_div"><a href="<?php echo add_query_arg(array('inventory' => 'my_account_edit'), get_permalink(RESTAURANTOWNER)); ?>"><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/edit.png" /></a></div>
				</div>
				<?php //debug($current_user);?>
				<dl class="accout_detail">
					<dt>Email :</dt>
					<dd>
						<?php
							$user_info = get_userdata($reservenist);
							echo $user_info->user_email;
						?>
					</dd>
					<dt>Password :</dt>
					<dd>********</dd>
				</dl>
            </div>
        </div>
        <!--body left div end-->
        <div class="body_conainer_rt">
          <ul class="rt_banner">
            <li><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/inner_body_banner.jpg" alt="" /></li>
            <li><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/inner_body_banner2.jpg" alt="" /></li>
          </ul>
        </div>
      </div>
      <!-- body container end -->
    </div>
    <!--page content container end-->