<?php
	if(!empty($_POST['check_inventory']) && $_POST['check_inventory'] == true){
		check_availability($_POST);
		exit;
	}
	if(!empty($_POST['update_buttons']) && $_POST['update_buttons'] == true){
		get_time_slots($_POST['post_id'],$_POST['table_value'],$_POST['date_value'],$_POST['time_value'],$_POST['discount'],$_POST['table']);
		exit;
	}
	if(!empty($_POST['add_booking']) && $_POST['add_booking'] == true){
		do_booking();
		exit;
	}
	if(!empty($_POST['change_booking']) && $_POST['change_booking'] == true){
		//change_booking();
		exit;
	}
?>
<!DOCTYPE html>

<html>
	<head>
		<meta charset = "<?php bloginfo('charset');?>" >
		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<?php if (is_search()) { ?>
		<meta name="robots" content="noindex, nofollow" /> 
		<?php } ?>
	
		<title>
			   <?php
				  if (function_exists('is_tag') && is_tag()) {
					 single_tag_title("Tag Archive for &quot;"); echo '&quot; - '; }
				  elseif (is_archive()) {
					 wp_title(''); echo ' Archive - '; }
				  elseif (is_search()) {
					 echo 'Search for &quot;'.wp_specialchars($s).'&quot; - '; }
				  elseif (!(is_404()) && (is_single()) || (is_page())) {
					 wp_title(''); echo ' - '; }
				  elseif (is_404()) {
					 echo 'Not Found - '; }
				  if (is_home()) {
					 bloginfo('name'); echo ' - '; bloginfo('description'); }
				  else {
					  bloginfo('name'); }
				  if ($paged>1) {
					 echo ' - page '. $paged; }
			   ?>
		</title>
		
		<meta name="title" content="<?php
				  if (function_exists('is_tag') && is_tag()) {
					 single_tag_title("Tag Archive for &quot;"); echo '&quot; - '; }
				  elseif (is_archive()) {
					 wp_title(''); echo ' Archive - '; }
				  elseif (is_search()) {
					 echo 'Search for &quot;'.wp_specialchars($s).'&quot; - '; }
				  elseif (!(is_404()) && (is_single()) || (is_page())) {
					 wp_title(''); echo ' - '; }
				  elseif (is_404()) {
					 echo 'Not Found - '; }
				  if (is_home()) {
					 bloginfo('name'); echo ' - '; bloginfo('description'); }
				  else {
					  bloginfo('name'); }
				  if ($paged>1) {
					 echo ' - page '. $paged; }
			   ?>">
		<meta name="description" content="<?php bloginfo('description'); ?>">
		
		<meta name="google-site-verification" content="">
		<!-- Speaking of Google, don't forget to set your site up: http://google.com/webmasters -->
		
		<meta name="author" content="Your Name Here">
		<meta name="Copyright" content="Copyright Booking Diva 2011. All Rights Reserved.">
	
		<!-- Dublin Core Metadata : http://dublincore.org/ -->
		<meta name="DC.title" content="Booking Diva">
		<meta name="DC.subject" content="Restaurants Bookings">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
		
		<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/_/img/favicon.ico">
		<!-- This is the traditional favicon.
			 - size: 16x16 or 32x32
			 - transparency is OK
			 - see wikipedia for info on browser support: http://mky.be/favicon/ -->
			 
		<link rel="apple-touch-icon" href="<?php bloginfo('template_directory'); ?>/_/img/apple-touch-icon.png">
		<!-- The is the icon for iOS's Web Clip.
			 - size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
			 - To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
			 - Transparency is not recommended (iOS will put a black BG behind the icon) -->
		
		<!-- CSS: screen, mobile & print are all in the same file -->
		<link rel="stylesheet" href="<?php echo DIVATEMPLATEPATH.'/css/jquery-ui-1.8.17.custom.css'?>">
		<link rel="stylesheet" href="<?php echo DIVATEMPLATEPATH."/css/diva.css"; ?>">
		<link rel="stylesheet" href="<?php echo DIVATEMPLATEPATH."/css/jquery.selectBox.css"; ?>">
		<link rel="stylesheet" href="<?php echo DIVATEMPLATEPATH."/css/style-custom.css"; ?>">
		<link rel="stylesheet" href="<?php echo DIVATEMPLATEPATH."/js/fancybox/jquery.fancybox-1.3.4.css"; ?>">
		
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
		<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id='help' style='position:fixed; top:50%; left:0; height:69px; width:37px;overflow:visible;z-index:1;'>
			<div style='position:absolute; margin-top:-50%; height:100%; width:100%;'>
				<a href='https://bookingdiva.zendesk.com' target='_blank'>
					<img src='<?php echo DIVATEMPLATEPATH."/".DIVAIMAGEPATH ?>/help.png'/>
				</a>
			</div>
		 </div>
		<div class = "body_bg">
			<script type="text/javascript">
				jQuery(function(){
					jQuery("SELECT").selectBox();
				});
			</script>
			<div id="main_div">
				<!--header div start-->
				<div id="header_div">
				  <!--header left div start-->
				  <div id="header_lt_div">
					<div class="logo_div">
						<a href='<?php echo site_url('/');?>'>
						<img src="<?php echo DIVATEMPLATEPATH."/".DIVAIMAGEPATH ?>/logo.png" />
						</a>
					</div>
					<div class="state_text">Delhi/NCR</div>
					<div class="share_us_div">
						<?php share_icon();?>
					</div>
				  </div>
				  <!--header left div end-->
				  <!--header right div start-->
				  <div id="header_rt_div">
					<div class="welcome_area" <?php if(is_user_logged_in()) { echo 'style="position:relative;"';} ?>>
					  <div class="help_number">
						<h1>Book Now : </h1>
						<h2>+91 99105 04824</h2>
					  </div>
					  <div class="welcome_div ndlogin">
							<div class="ndlogin">
								<?php nd_login_widget(array()); ?>
							</div>
					  </div>
					</div>
					<div style="width:579px; clear:both;">
					  <div class="tip_content">
						<span>Tip:</span>
						<?php showtip();?>
					</div>
					<div class="invite_area">
						<div class="invite_text" style = "height:16px">
							<?php if(is_general_user()){ ?>
								<a href="<?php echo get_friend_page_link(); ?>"><?php echo get_field('friend_invitation_text',THEMESETTINGS);?></a>
							<?php
								}elseif(is_rest_user()){
									$post = get_posts_by_author();
									echo "<a><span>".$post['post_title']."</span></a>";
								}
							?>
							
						</div>
						<div class="follow_div">
						  <ul>
							<li class="text">Follow us :</li>
							<li>
								<?php follow_icon();?>
							</li>
						  </ul>
						</div>
					  </div>
					</div>
				  </div>
				  <!--header right div end-->
				  <!--main link div start-->
				  <div class="main_link_div">
					<?php
						if(!is_user_logged_in()){
							wp_nav_menu(array( 'theme_location'=>'header_menu', 'container' => '','menu_class' =>'main_link' ) );
						}elseif(is_rest_user()){
							wp_nav_menu(array( 'theme_location'=>'rest_menu', 'container' => '','menu_class' =>'main_link' ) );
						}else{
							wp_nav_menu(array( 'theme_location'=>'user_menu', 'container' => '','menu_class' =>'main_link' ) );
						}
						if(!empty($_SESSION['admin'])){
							echo '<ul class = "main_link"><li><a href="'.get_bloginfo('url').'/?login_user=admin" target = "blank">Switch to Admin</a></li></ul>';
						}
					?>
				  </div>
				  <!--main link div end-->
				</div>
				<!--header div end-->
				<?php if (! (is_front_page() || is_rest_user()) ) { ?>
					<!--search div start-->
					<div class="search_div">
						<form action="<?php echo get_bloginfo('url'); ?>" method="get">
							<input type="hidden" value = 1 name="search">
							<div class="search_fileld">
								<?php if(trim(get_search_query()) != "") {?>
									<input name="s" type="text" class="field4" value="<?php the_search_query();?>" onclick="this.value=''" />			 	
								<?php }else{?> 
									<input name="s" type="text" class="field4" value="Restaurant Name / Location / Cuisine" onclick="this.value=''" onblur="if(this.value==''){this.value='Restaurant Name / Location / Cuisine'}"  />
								<?php }?>
							</div>
							<div class="search_button">
								<input name="" type="image" src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/search_img.jpg" />
							</div>
						</form>
					</div>
					<!--search div end-->
				<?php
					}
					if(is_home() || is_category() || is_search()){
						get_template_part( 'search', 'header' );
					}
				?>