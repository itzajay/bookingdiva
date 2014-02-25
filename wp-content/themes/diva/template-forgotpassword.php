<?php
/*
@since Colonial 1.0
Template Name: Forgot Password Page
*/
?>
<?php get_header(); ?>
<!--page content container start-->
    <div class="content_warp">
      <div class="heading_warp">
        <h1>Reset Password</h1>
        <h2><a href="<?php echo get_bloginfo('url');?>">Home</a> &gt; Reset Password</h2>
      </div>
      <!-- body container start-->
      <div class="main_body_conainer">
        <!--body left div start-->
        <div class="body_conainer_lt">
          <div class="accout_detail_div">
				<div class="accout_detail_heading">
					<h1>Enter your new password below!</h1>
				</div>
				<form name = "add_info" action="<?php echo get_permalink(FORGOTPASS);?>" method="post">
					<input type="hidden" name="key" value="<?php echo $_GET['key']?>" />
					<input type="hidden" name="reset_pass" value=1 />
					<input type="hidden" name="email" value="<?php echo urldecode($_GET['email']);?>" />
					<ul class="account_edit">
					  <li class="short_fieldlt">
						<h2>Password</h2>
						<h3>
						  <input name="pass" type="password" class="s_feld" />
						</h3>
					  </li>
					  <li class="short_fieldrt">
						<h2>Confirm Password</h2>
						<h3>
						  <input name="confirm_pass" type="password" class="s_feld" />
						</h3>
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
<?php get_footer(); ?>