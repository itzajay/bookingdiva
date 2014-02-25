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
					<div class="edit_div"><a href="<?php echo add_query_arg(array('inventory' => 'my_account_edit'), get_permalink(RESTAURANTOWNER)); ?>"><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/edit.png" /></a></div>
				</div>
				<dl class="accout_detail">
					<dt>First Name :</dt>
					<dd>
						<?php
							if(get_usermeta( $current_user->ID, 'first_name') != NULL){
								echo get_usermeta( $current_user->ID, 'first_name' );
							}else{
								echo "Not Given";
							}
						?>
					</dd>
					<dt>Last Name :</dt>
					<dd>
						<?php
							if(get_usermeta( $current_user->ID, 'last_name') != NULL){
								echo get_usermeta( $current_user->ID, 'last_name' );
							}else{
								echo "Not Given";
							}
						?>
					</dd>
					<dt>City :</dt>
					<dd>
						<?php
							if(get_usermeta( $current_user->ID, 'city') != NULL){
								echo get_usermeta( $current_user->ID, 'city' );
							}else{
								echo "Not Given";
							}
						?>
					</dd>
					<dt>Zipcode :</dt>
					<dd>
						<?php
							if(get_usermeta( $current_user->ID, 'zipcode') != NULL){
								echo get_usermeta( $current_user->ID, 'zipcode' );
							}else{
								echo "Not Given";
							}
						?>
					</dd>
					<dt>Mobile :</dt>
					<dd>
						<?php
							if(get_usermeta( $current_user->ID, 'mobile') != NULL){
								echo get_usermeta( $current_user->ID, 'mobile' );
							}else{
								echo "Not Given";
							}
						?>
					</dd>
					<dt>Gender :</dt>
					<dd>
						<?php
							if(get_usermeta( $current_user->ID, 'gender') != NULL){
								echo strtoupper(get_usermeta( $current_user->ID, 'gender' ));
							}else{
								echo "None Selected";
							}
						?>
					</dd>
					<dt>Date of Birth :</dt>
					<dd>
						<?php
							if(get_usermeta( $current_user->ID, 'dob') != NULL){
								echo get_usermeta( $current_user->ID, 'dob' );
							}else{
								echo "Not Given";
							}
						?>
					</dd>
					<dt>Email Notifications :</dt>
					<dd>
						<?php
							if(in_array('email',get_usermeta( $current_user->ID, 'notification_method' ))){
								echo "Yes";
							}else{
								echo "No";
							}
						?>
					</dd>
					<dt>SMS Notifications :</dt>
					<dd>
						<?php
							if(in_array('sms',get_usermeta( $current_user->ID, 'notification_method' ))){
								echo "Yes";
							}else{
								echo "No";
							}
						?>
					</dd>
					<dt>Notifications :</dt>
					<dd>
						<?php
							global $email_alert;
							$alerts = get_usermeta( $current_user->ID, 'email_alert');
							$sep = '';
							if($alerts != "" ){
								foreach($alerts as $key=>$value){
									echo $sep.$email_alert[$value];
									$sep = ', ';
								}
							}else{
								echo "No";
							}
						?>
					</dd>
				</dl>
            </div>
            <div class="accout_detail_div">
				<div class="accout_detail_heading">
					<h1>Account Information:</h1>
					<div class="edit_div"><a href="<?php echo add_query_arg(array('inventory' => 'my_account_edit'), get_permalink(RESTAURANTOWNER)); ?>"><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/edit.png" /></a></div>
				</div>
				<?php //debug($current_user);?>
				<dl class="accout_detail">
					<dt>Email :</dt>
					<dd><?php echo $current_user->user_email; ?></dd>
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