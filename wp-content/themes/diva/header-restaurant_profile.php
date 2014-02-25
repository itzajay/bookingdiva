<!DOCTYPE html>

<html>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		
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
		
		
		<!--  Mobile Viewport meta tag
		j.mp/mobileviewport & davidbcalhoun.com/2010/viewport-metatag 
		device-width : Occupy full width of the screen in its current orientation
		initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
		maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width -->
		<!-- Uncomment to use; use thoughtfully!-->
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
		<link rel="stylesheet" href="<?php echo DIVATEMPLATEPATH."/account-style.css"; ?>">
		<link rel="stylesheet" href="<?php echo DIVATEMPLATEPATH.'/jquery-ui-1.8.17.custom.css';?>">
		<link rel="stylesheet" href="<?php echo DIVATEMPLATEPATH."/style-custom.css"; ?>">
		
		<!-- all our JS is at the bottom of the page, except for Modernizr. 
		<script src="<?php bloginfo('template_directory'); ?>/_/js/modernizr-1.7.min.js"></script>-->
		
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
		<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	
		<?php wp_head(); ?>
		
	</head>
	
	<body <?php body_class(); ?>>
	<div id="container_div">
		<div id="main_div">
			<!--logo container div start-->
			<div class="logo_contianer">
				<div class="logo_div"><a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/logo.jpg" alt="" /></a></div>
				<div class="off_text"><?php echo get_field('header_text1',THEMESETTINGS);?><br/><?php echo get_field('header_text2',THEMESETTINGS);?></div>
				<div class="call_text"><?php echo get_field('header_text3',THEMESETTINGS);?><br/><?php echo get_field('header_text4',THEMESETTINGS);?></div>
			</div>
			<!--logo container div end-->
			<!--main link container div start-->
			<div class="main_link_container">
				<?php get_template_part('header','inventory_menu');?>
			</div>