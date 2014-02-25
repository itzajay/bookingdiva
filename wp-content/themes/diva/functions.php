<?php
	//global $wpdb;
	//$posts = $wpdb->get_results("SELECT `ID`, `post_title` from `".$wpdb->prefix."posts` WHERE `post_type` = 'post' AND `post_status` = 'publish' ORDER BY `post_title` ASC;",ARRAY_A);
		//foreach($posts as $post){
			//$wpdb->get_row("INSERT INTO `wp_inventory_rules` (`restaurant_id`, `booking_category_id`, `table_type_id`, `sun`,`mon`,`tue`,`wed`,`thu`,`fri`,`sat`) VALUES ('".$post['ID']."','4','7','110','10','10','10','10','10','10'), ('".$post['ID']."','5','7','10','10','10','10','10','10','10'), ('".$post['ID']."','6','7','10','10','10','10','10','10','10');");
		//}
	/* Constants Declaration */
	//defining Template Path
	define("DIVATEMPLATEPATH",get_bloginfo('template_directory'));
	
	//defining folder for including Files
	define("INCLUDEPATH","includes");
	
	//defining folder for images
	define("IMAGEPATH","images");
	
	//defining folder for new images
	define("DIVAIMAGEPATH","images");
	
	//defining folder for Javascript files
	define("JSPATH","js");
	
	//defining folder for Frontend Restaurant operations files
	define("FRONTENDINVENTORY","frontend_inventory");
	
	//defining folder for Frontend Booking operations files
	define("FRONTENDBOOKING","frontend_bookings");
	
	//including register sidebars
	include(TEMPLATEPATH."/".INCLUDEPATH."/register-sidebars.php");

	//getting id of admin settings page
	$slug = "admin-theme-settings";
	$page = get_page_by_path($slug);
	if($page){
		define("THEMESETTINGS",$page->ID);
	}
	
	//getting id of email template page
	$slug = "email-templates";
	$page = get_page_by_path($slug);
	if($page){
		define("EMAILTEMPLATE",$page->ID);
	}
	
	//getting id of Forgot Page template page
	$slug = "forgot-password";
	$page = get_page_by_path($slug);
	if($page){
		define("FORGOTPASS",$page->ID);
	}
	
	//defining time format
	define("DIVATIMEFORMAT","g:i a");
	
	//defining date format
	define("DIVADATEFORMAT","%d-%m-%Y");
	
	//definin Restaurant Owner Page ID
	define("RESTAURANTOWNER",240);
	
	// End Of  Constants Declaration
	
	// Email Alert Types for users
	$email_alert = array(
						"alert1"=>'Alerts: First to hear about all offers',
						"alert2"=>"Weekly: Highlights of existing offers",
						"alert3"=>"No Emails: Just don't forget about us"
						 );
	
	// Transactions Types
	$transaction_type = array(
							 1=>'Recharge',
							 2=>'Referal',
							 3=>'Booking',
							 4=>'Sign Up',
							 5=>'Cancel',
							 6=>'invitee'
							 );
	// Booking Status
	$booking_status = array(
							 1=>'Confirmed',
							 2=>'Closed',
							 3=>'Cancel',
							 4=>'No Show',
							 5=>'Pending'
							 );
	// All Table Types
	$all_table_types = array(
							 1=>'2',
							 2=>'4',
							 3=>'6',
							 4=>'8',
							 5=>'10',
							 6=>'10+'
							 );
	
	// User Types
	$user_types = array(
						1=>'Restaurant Owner',
						2=>'Restaurant Manager',
						3=>'Restaurant Reservationist',
						);
	
	// Invoice Status
	$invoice_status = array(
							 0=>'Un-Paid',
							 1=>'Paid'
							);
	
	// Booking confirmation
	$booking_confirmation = array(
							 '1'=>'Automatic',
							 '2'=>'Manual'
							);
	
	// Booking confirmation
	$discount_percantage = array(
							'20' => '20',
							'25' => '25',
							'30' => '30',
							'35' => '35',
							'40' => '40',
							'45' => '45',
							'50' => '50',
							'55' => '55',
							'60' => '60',
							'65' => '65',
							'70' => '70',
							'75' => '75',
							'80' => '80',
							'85' => '85',
							'90' => '90',
							'95' => '95',
							'100' => '100'
							);
	//Time Array for Start Time & End Time
	
	$diva_time = array('00:00'=>'00:00','00:30'=>'00:30',
					   '01:00'=>'01:00','01:30'=>'01:30',
					   '02:00'=>'02:00','02:30'=>'02:30',
					   '03:00'=>'03:00','03:30'=>'03:30',
					   '04:00'=>'04:00','04:30'=>'04:30',
					   '05:00'=>'05:00','05:30'=>'05:30',
					   '06:00'=>'06:00','06:30'=>'06:30',
					   '07:00'=>'07:00','07:30'=>'07:30',
					   '08:00'=>'08:00','08:30'=>'08:30',
					   '09:00'=>'09:00','09:30'=>'09:30',
					   '10:00'=>'10:00','10:30'=>'10:30',
					   '11:00'=>'11:00','11:30'=>'11:30',
					   '12:00'=>'12:00','12:30'=>'12:30',
					   '13:00'=>'13:00','13:30'=>'13:30',
					   '14:00'=>'14:00','14:30'=>'14:30',
					   '15:00'=>'15:00','15:30'=>'15:30',
					   '16:00'=>'16:00','16:30'=>'16:30',
					   '17:00'=>'17:00','17:30'=>'17:30',
					   '18:00'=>'18:00','18:30'=>'18:30',
					   '19:00'=>'19:00','19:30'=>'19:30',
					   '20:00'=>'20:00','20:30'=>'20:30',
					   '21:00'=>'21:00','21:30'=>'21:30',
					   '22:00'=>'22:00','22:30'=>'22:30',
					   '23:00'=>'23:00','23:30'=>'23:30'
					   );
	
	
	//time range
	function getTimeRange($start,$end) {
		// takes two times formatted as 00:00:00and creates an
		// inclusive array of the times between the from and to times.
	  
		// could test validity of time here but I'm already doing
		// that in the main script
		
		$aryRange=array();
		$final_array = array();
		$iTimeFrom = mktime(substr($start,0,2),substr($start,3,2),substr($start,6,2),1,0,0);
		$iTimeTo = mktime(substr($end,0,2),substr($end,3,2),substr($end,6,2),1,0,0);
		//debug($iTimeFrom);
		//debug($iTimeTo);
		//debug(date(DIVATIMEFORMAT,strtotime($iTimeFrom)));
		if ($iTimeTo>=$iTimeFrom) {
			array_push($aryRange,date(DIVATIMEFORMAT,strtotime($iTimeFrom))); // first entry
			while ($iTimeFrom<$iTimeTo) {
			  $iTimeFrom+=1800; // add ½ hour
			  array_push($aryRange,date(DIVATIMEFORMAT,strtotime($iTimeFrom)));
			}
		}
		foreach($aryRange as $key=>$value){
			$final_array[$value] = $value;
		}
		return $final_array;
	}
	
	// THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
	
	add_action('admin_head', 'custom_css');
	function custom_css() {
	   echo '<link rel="stylesheet" href="'.DIVATEMPLATEPATH.'/css/jquery-ui-1.8.17.custom.css">';
	}
	//sending emails in html format
	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
	
	function createDateRangeArray($strDateFrom,$strDateTo) {
		// takes two dates formatted as YYYY-MM-DD and creates an
		// inclusive array of the dates between the from and to dates.
	  
		// could test validity of dates here but I'm already doing
		// that in the main script
		
		$aryRange=array();
		$final_array = array();
		$iDateFrom = mktime(1,0,0,substr($strDateFrom,5,2), substr($strDateFrom,8,2),substr($strDateFrom,0,4));
		$iDateTo = mktime(1,0,0,substr($strDateTo,5,2), substr($strDateTo,8,2),substr($strDateTo,0,4));
	  
		if ($iDateTo>=$iDateFrom) {
			array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
			while ($iDateFrom<$iDateTo) {
			  $iDateFrom+=86400; // add 24 hours
			  array_push($aryRange,date('Y-m-d',$iDateFrom));
			}
		}
		foreach($aryRange as $key=>$value){
			$final_array[$value] = $value;
		}
		return $final_array;
	}
	
	function get_posts_by_author(){
		$current_user = wp_get_current_user();
		global $wpdb;
		$restro_id = get_usermeta($current_user->ID,'restro');
		
		return $wpdb->get_row("SELECT `ID`, `post_title` from `".$wpdb->prefix."posts` WHERE `post_type` = 'post' AND `post_status` = 'publish' AND ID = '".$restro_id."'  ORDER BY `ID` DESC;",ARRAY_A);
	}
	
	// Load jQuery
	
	if ( !function_exists(core_mods) ) {
		function core_mods() {
				//wp_deregister_script('jquery');
					wp_enqueue_script('jquery');
					wp_register_script('jqueryUI', (DIVATEMPLATEPATH."/".JSPATH."/jquery-ui.js"), false);
					wp_register_script('jqueryEditor', (DIVATEMPLATEPATH."/".JSPATH."/jquery.editinplace.js"), false);
					wp_register_script('jqueryDatepicker', (DIVATEMPLATEPATH."/".JSPATH."/datePicker.js"), false);
					wp_register_script('jqueryfade', (DIVATEMPLATEPATH."/".JSPATH."/jquery.innerfade.js"), false);
					wp_register_script('jqueryselect', (DIVATEMPLATEPATH."/".JSPATH."/jquery.selectBox.js"), false);
					wp_register_script('jqueryphotofy', (DIVATEMPLATEPATH."/".JSPATH."/jquery-photofy-2.0.43.js"), false);
					wp_register_script('jqueryfancybox', (DIVATEMPLATEPATH."/".JSPATH."/fancybox/jquery.fancybox-1.3.4.js"), false);
					
					//wp_register_script('jqueryEditorScript', (DIVATEMPLATEPATH."/".JSPATH."/inlineEditorScript.js"), false);
					wp_enqueue_script('jqueryUI');
					wp_enqueue_script('jqueryEditor');
					wp_enqueue_script('jqueryDatepicker');
					wp_enqueue_script('jqueryfade');
					wp_enqueue_script('jqueryselect');
					wp_enqueue_script('jqueryphotofy');
					wp_enqueue_script('jqueryfancybox');
					wp_localize_script( 'jqueryDatepicker', 'divadatepicker', array( 'image_url' => DIVATEMPLATEPATH."/".IMAGEPATH ) );

		}
		core_mods();
	}

	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');
    
	//For getting all id
	function get_all_id(){
		global $wpdb;
		$table_id = $wpdb->get_row("SELECT `id` from `".$wpdb->prefix."table_types` WHERE LOWER(`title`) = 'all' ",ARRAY_A);
		return $table_id['id'];
	}
	
    //Implementing custom searching

	add_filter( 'pre_get_posts' , 'add_meta_to_search' );
	function add_meta_to_search( &$query ) {
		global $wpdb;
		if(!is_admin() && !isset($_GET['abc']) && is_home()){
			$metaQuery = array();
			$postin = array();
			$condition = "";
			$search_date = date('Y-m-d');
			if($_GET['date'] != false) {
				$search_date = date('Y-m-d',strtotime($_GET['date']));
			}
			$condition.=' where `date` ="'.$search_date.'"';
			$parent_id = get_current_parent_booking_id();
			if($_GET['time'] != false) {
				$parent_id = $_GET['time'];
			}
			$booking_ids = get_booking_ids($parent_id);
			if(!empty($booking_ids)){
				$condition.=' AND i.`booking_category_id` IN('.$booking_ids.')';
			}
			
			if(!empty($_GET['search'])){
				if($_GET['seating'] != false){
					if(!empty($_GET['seating'])) {
						$all_id = get_all_id();
						$condition.=' AND `table_type_id` in ('.$_GET['seating'].','.$all_id.')';
					}
				}
				$tax_condition=array();
				if(!empty($_GET['cuisine_type'])) {
					$tax_condition[] = array(
											'taxonomy'=>'cuisine',
											'field'=>'id',
											'terms'=>array($_GET['cuisine_type'])
										);
				}
				if(!empty($_GET['area'])) {
					$tax_condition[] = array(
											'taxonomy'=>'area',
											'field'=>'id',
											'terms'=>array($_GET['area'])
										);
				}
				$query->set('tax_query',$tax_condition);
				$query->set('meta_query', $metaQuery);
			}
			$condition.=' AND no_of_tables > booked_tables';
			$seatings = $wpdb->get_results('SELECT i.*, rc.start_time, rc.end_time FROM `'.$wpdb->prefix.'inventory` as i left join wp_restaurant_categories as rc ON rc.restaurant_id = i.restaurant_id AND rc.booking_category_id = i.booking_category_id'.$condition.' GROUP BY `restaurant_id`',ARRAY_A);
			foreach($seatings as $seating){
				$postin[] = $seating['restaurant_id'];
			}
			if(empty($postin)){
				$postin[] = 0;
			}
			$query->set('post__in', $postin);
			$_GET['abc'] = 1;
			return $query;
		}
	}
	

	add_action('init','register_my_menus');
	function register_my_menus() {
		register_nav_menus( array( 'header_menu' => __( 'Header Menu' ), 'rest_menu' => __( 'Restaurant Owner Menu' ),'user_menu' => __( 'User Menu' ), 'footer_menu' => __( 'Footer Menu' ), 'footer_menu_2' => __( 'Footer Menu 2' ),'static_page_menu' => __( 'STATIC PAGE MENU' )) );
	}
	
	//debug function
	function debug($var){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}
	
	//for getting logo's posts for sample restaurants
	
	function get_logo_posts(){
		global $wpdb;
		$posts = $wpdb->get_results("SELECT `ID`, `post_title` from `".$wpdb->prefix."posts` WHERE `post_type` = 'post' AND `post_status` = 'publish' ORDER BY `post_title` ASC;",ARRAY_A);
		$result_array = array();
		foreach($posts as $logo_post){
			$logo = get_field('logo',$logo_post['ID']);
			$status = get_field('show_logo',$logo_post['ID']);
			if(!empty($logo) && $status == true){
				$result_array[] = $logo_post;
			}
		}
		return $result_array;
	}
	function get_small_logo_posts(){
		global $wpdb;
		$posts = $wpdb->get_results("SELECT `ID`, `post_title` from `".$wpdb->prefix."posts` WHERE `post_type` = 'post' AND `post_status` = 'publish' ORDER BY `post_title` ASC;",ARRAY_A);
		$result_array = array();
		foreach($posts as $logo_post){
			$logo = get_field('small_logo',$logo_post['ID']);
			$status = get_field('show_logo',$logo_post['ID']);
			if(!empty($logo) && $status == true){
				$result_array[] = $logo_post;
			}
		}
		return $result_array;
	}
	if(!empty($_POST['add_meta'])){
		$frontpage_id = get_option('page_for_posts');
		$user_id = get_current_user_id( );
		update_user_meta( $user_id, 'first_name', $_POST['first_name']);
		update_user_meta( $user_id, 'last_name', $_POST['last_name']);
		update_user_meta( $user_id, 'gender', $_POST['gender']);
		update_user_meta( $user_id, 'zipcode', $_POST['zipcode']);
		update_user_meta( $user_id, 'dob', $_POST['dob']);
		update_user_meta( $user_id, 'email_alert', $_POST['email_alert']);
		wp_redirect( get_permalink($frontpage_id) );
	}
	
	function is_rest_user(){
		global $current_user;
		if($current_user->roles[0] == 'contributor'){
			return true;
		}else{
			return false;
		}
	}
	
	function is_general_user(){
		global $current_user;
		if($current_user->roles[0] == 'subscriber'){
			return true;
		}else{
			return false;
		}
	}
	
	function get_ID_by_slug($page_slug) {
		$page = get_page_by_path($page_slug);
		if ($page) {
			return $page->ID;
		} else {
			return null;
		}
	}
	
	function get_rest_page_link(){
		$slug = "restaurant-owner";
		$id = get_ID_by_slug($slug);
		return get_permalink($id);
	}
	
	function get_friend_page_link(){
		$slug = "friend-invitation";
		$id = get_ID_by_slug($slug);
		return get_permalink($id);
	}
	
	function parent_category_name($id){
		global $wpdb;
		$parent = $wpdb->get_row("Select name from wp_booking_categories where id = (select parent_id from wp_booking_categories where id = ".$id.")",ARRAY_A);
		return $parent['name'];
	}
	
	//check time overlap
	
	function check_time_overlap($restaurant,$start,$end){
		global $wpdb;
		$time_overlap = $wpdb->get_row("select count(*) as record from wp_restaurant_categories as a where `restaurant_id` =".$restaurant." AND ((a.start_time > '".$start."' AND a.start_time < '".$end."') OR (a.end_time < '".$end."' AND a.end_time > '".$start."') OR (a.start_time <= '".$start."' AND a.end_time >= '".$end."') )",ARRAY_A);
		if($time_overlap['record'] > 0){
			return 0;
		}else{
			return 1;
		}
	}
	
	//Check Availibilty For Booking
	
	function check_availability($post){
		global $wpdb;
		//debug($post);
		$time_diff = $wpdb->get_row("select time_to_sec(timediff('".date('Y-m-d',strtotime($_POST['reserve_date']))." ".$_POST['reserve_time']."',NOW() )) / 3600 as time",ARRAY_A);
		if($time_diff['time']>2){
			$results = $wpdb->get_row('select * from wp_restaurant_categories where `restaurant_id`='.$post['reserve_restaurant_id'].' AND `start_time`<="'.$post['reserve_time'].'" AND `end_time` >="'.$post['reserve_time'].'"',ARRAY_A);
			if(!empty($results) && count($results) > 0){
				$seating_val = "";
				$query.= "select count(*) as num from wp_inventory where";
				$query.= ' `restaurant_id`='.$post['reserve_restaurant_id'];
				$query.= ' AND `booking_category_id`='.$_POST['reserve_slot'];
				
				if(!empty($_POST['table_type_id'])){
					$seating_val = $post['table_type_id'];
				}else{
					$table_value = explode('_',$post['reserve_table_type']);
					$seating_val = $table_value[1];
				}
				
				$query.= ' AND `table_type_id`='.$seating_val;
				$query.= ' AND `date`="'.date('Y-m-d',strtotime($_POST['reserve_date'])).'"';
				$query.= ' AND `no_of_tables` > `booked_tables`';
				$record = $wpdb->get_row($query,ARRAY_A);
				
				
				if($post['change']){
					if($record['num'] > 0){
						return "true,ok";
					}else{
						return "false,Booking are not available";
					}
				}else{
					if($record['num'] > 0){
						echo "true,ok";
					}else{
						echo "false,Booking are not available";
					}
				}
			}else{
				if($post['change']){
					return "false,this restaurant is not having bookings on this time";
				}else{
					echo "false,this restaurant is not having bookings on this time";
				}
			}
		}else{
			if($post['change']){
				return "false,You can book tables only before two hours.";
			}else{
				echo "false,You can book tables only before two hours.";
			}
		}
	}
	
	//send invitaion
	
	if(!empty($_POST['invite_friend']) && $_POST['invite_friend']==true){
		$current_user = wp_get_current_user();
		$user_email = $current_user->user_email;
		$to = $_POST['recipient_email'];
		$subject = "Invitation";
		$body = "Hi, \n\n".$_POST['recipient_message'];
		$body.= "\n\n For registration click here \n";
		$body.= get_bloginfo('url')."?ref=".$user_email;
		if (wp_mail($to, $subject, $body)) {
			echo("<p>Message successfully sent!</p>");
		} else {
			echo("<p>Message delivery failed...</p>");
		}
	}
	
	
	/**
	 * 
	 * Social functions
	 */
	
	function share_icon($url = null){ 
		if($url == null) $url = site_url('/');
	?>
		<!-- AddThis Button BEGIN -->
          <div class="addthis_toolbox addthis_default_style" style="float:left;">
          <a class="addthis_button_tweet" g:plusone:size="small" addthis:url="<?php echo $url?>"></a> 
          <a class="addthis_button_google_plusone"  style="margin-left:-30px;" g:plusone:size="medium" addthis:url="<?php echo $url;?>"></a> 
          <a class="addthis_button_facebook_like" style='margin-left:-30px;' fb:like:layout="button_count" g:plusone:size="small" addthis:url="<?php echo $url?>"></a> 
          </div>
          <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f55c3da1874ece2"></script>
          <!-- AddThis Button END -->
	<?php }
	
	function share_icon_restaurant($url = null){ 
		if($url == null) $url = site_url('/');
	?>
		<!-- AddThis Button BEGIN -->
          <div class="addthis_toolbox addthis_default_style" style="float:left;">
          <a style = "width:100%;margin-bottom:10px;"class="addthis_button_tweet" g:plusone:size="small" addthis:url="<?php echo $url?>"></a> 
		  <a style = "width:100%;margin-bottom:5px;"class="addthis_button_facebook_like" fb:like:layout="button_count" g:plusone:size="small" addthis:url="<?php echo $url?>"></a>
		  <a style = "width:100%;margin-bottom:10px;"class="addthis_button_google_plusone"  style="margin-left:-30px;" g:plusone:size="medium" addthis:url="<?php echo $url;?>"></a> 
         </div>
          <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f55c3da1874ece2"></script>
          <!-- AddThis Button END -->
	<?php }
	
	function share_icon_confirmation($url = null){ 
		if($url == null) $url = site_url('/');
	?>
		<!-- AddThis Button BEGIN -->
        <div class="addthis_toolbox addthis_default_style" style="float:left;">
			<ul class="rt_link">
				<li>Add to calender </li>
				<li><a style='text-decoration:none' href="<?php echo $url?>" class="addthis_button_email">&nbsp;&nbsp;Share your booking</a></li>
				<li><a style='width:80px' class="addthis_button_facebook_like" fb:like:layout="button_count" g:plusone:size="small" addthis:url="<?php echo $url?>"></a> </li>
				<li style = "margin-top:-7px;"><a style='width:80px' class="addthis_button_tweet" g:plusone:size="small" addthis:url="<?php echo $url?>"></a></li>
			</ul>
		</div>
        <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f55c3da1874ece2"></script>
          <!-- AddThis Button END -->
	<?php }
	
	function follow_icon(){?>
		<!-- AddThis Button BEGIN -->
		<div>
			<a href="http://www.facebook.com/bookingdiva" target = 'blank' ><img src="<?php echo DIVATEMPLATEPATH."/".DIVAIMAGEPATH ?>/facebook_follow.png" /></a>
			<a href="http://www.twitter.com/#!/BookingDiva" target = 'blank' ><img src="<?php echo DIVATEMPLATEPATH."/".DIVAIMAGEPATH ?>/twitter_follow.png" /></a>
			<a href="http://www.linkedin.com/pub/booking-diva/49/482/9b6" target = 'blank' ><img src="<?php echo DIVATEMPLATEPATH."/".DIVAIMAGEPATH ?>/linkedin_follow.png" /></a>
			<a href="https://plus.google.com/104349481553317417993/posts"target = 'blank' ><img src="<?php echo DIVATEMPLATEPATH."/".DIVAIMAGEPATH ?>/google_follow.png" /></a>
			
		</div>
		<!-- AddThis Button END -->
	<?php }
	
	function showtip(){
		for($i=1;$i<=4;$i++){
			if(get_field('tip_'.$i,THEMESETTINGS) != ""){
				$tips[] = "<p>".get_field('tip_'.$i,THEMESETTINGS)."</p><img src = '".get_field('tip_'.$i.'_image',THEMESETTINGS)."'>";
			}
		}
		shuffle($tips);
		echo $tips[0];
	}
	
	function show_restaurant_terms($term, $post){
		$terms = get_the_terms($post,$term);
		if ($terms){
			$sep = "";
			foreach ( $terms as $term ) {
				echo $sep.$term->name;
				$sep = ", ";
			}
		}
	}
	
	add_filter('do_viral', 'process_viral');
	function process_viral($options){
		global $wpdb;
		$user_id = $options['user'];
		$user_ref = urldecode($options['ref']);
		$user_info = $wpdb->get_row("select * from wp_users where user_email = '".$user_ref."'");
		$amount = get_field('registeration_amount',THEMESETTINGS);
		$query = "INSERT INTO `".$wpdb->prefix."transactions` (`user_id`,`amount`,`type_id`,`date`) VALUES(".$user_id.",".$amount.",4,CURDATE());";
		if($user_ref != ""){
			$wpdb->get_row("INSERT INTO `".$wpdb->prefix."transactions` (`user_id`,`ref_id`,`amount`,`type_id`,`status`,`date`) VALUES(".$user_id.",".$user_info->ID.",".$amount.",6,0,CURDATE());");
			$wpdb->get_row("INSERT INTO `".$wpdb->prefix."transactions` (`user_id`,`ref_id`,`amount`,`type_id`,`status`,`date`) VALUES(".$user_info->ID.",".$user_id.",".$amount.",2,0,CURDATE());");
		}
		$wpdb->get_row($query);
	}
	
	add_filter('get_register_url', 'regiser_url');
	function regiser_url($args=array()){
		return get_permalink(get_field('second_registration_page_id',THEMESETTINGS));
	}
	
	//include('pagination/ps_pagination.php');
	function get_location_info($id){
		global $wpdb;
		$map = get_field('google_map_code',$id);
		$map_id = $wpdb->get_row('select mapid from wp_mappress_posts where postid ='.$id,ARRAY_A);
		?>
		<h1>Other Details:</h1>
		<p><?php echo get_field('rest_telephone',$id);?></p>
		<p><?php echo get_field('address',$id);?></p>
		<p class="link">
			<span>
				<?php if(get_field('address',$id)):?>
					<a target = 'blank' href="http://maps.google.com/?daddr=<?php echo strip_tags(get_field('address',$id));?>">Directions &nbsp;</a>
				<?php endif; ?>
				<?php
					if(trim(get_field('menu',$id)) != ""){
				?>
					|&nbsp;
					<a href = "<?php echo get_field('menu',$id);?>" target = 'blank'> Menu</a> &nbsp;
				<?php
				}
					if(trim(get_field('website',$id)) != ""){
				?>
					|&nbsp; <a href = "<?php echo get_field('website',$id);?>" target = 'blank'>Website</a>
				<?php }?>
			</span>
		</p>
		<?php if(isset($map_id['mapid']) || !empty($map)):?>
			<a id="map" href="#map_popup" title=""><img src="<?php echo DIVATEMPLATEPATH."/".DIVAIMAGEPATH ?>/map.jpg" /></a>
			<div class = 'map_none'>
				<div id = "map_popup" class="popup_warp" style = 'width:auto;'>
					<div class="popup_heading">Restaurant Map</div>
					<div class="popup_content_div">
						<?php
							if(!empty($map)){
								echo $map;
							}elseif(isset($map_id['mapid'])){
								echo do_shortcode('[mappress mapid="'.$map_id['mapid'].'"]');
							}
						?>
					</div>
				</div>
			</div>
		<?php endif;?>
<?php }
	//including Notifications Functions
	include(TEMPLATEPATH."/".INCLUDEPATH."/notifications.php");
	//including exportcsv Functions
	include(TEMPLATEPATH."/".INCLUDEPATH."/csvexport.php");
	
	function fb_add_custom_user_profile_fields( $user ) {
		global $wpdb;
		global $user_types;
		$user_options = "<option value = 0>Select User Type</option>";
		$options = "<option value = 0>Select Restaurant</option>";
		$posts = $wpdb->get_results("SELECT `ID`, `post_title` from `".$wpdb->prefix."posts` WHERE `post_type` = 'post' AND `post_status` = 'publish' ORDER BY `post_title` ASC;",ARRAY_A);
		foreach($posts as $post){
			if($post['ID'] == get_the_author_meta( 'restro', $user->ID )){
				$options.= "<option selected = selected value = ".$post['ID'].">".$post['post_title']."</option>";
			}else{
				$options.= "<option value = ".$post['ID'].">".$post['post_title']."</option>";
			}
		}
		foreach($user_types as $key=>$val){
			if($key ==get_the_author_meta( 'user_type', $user->ID  )){
				$user_options.= "<option selected = selected value = ".$key.">".$val."</option>";
			}else{
				$user_options.= "<option value = ".$key.">".$val."</option>";
			}
		}
	?>
		<h3><?php _e('Extra Profile Information', 'your_textdomain'); ?></h3>
		<table class="form-table">
			<tr>
				<th>
					<label for="restro"><?php _e('Restaurant', 'your_textdomain'); ?>
				</label></th>
				<td>
					<select name= "restro" id = "restro">
						<?php echo $options;?>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<label for="user_type"><?php _e('User Type', 'your_textdomain'); ?>
				</label></th>
				<td>
					<select name= "user_type" id = "user_type">
						<?php echo $user_options;?>
					</select>
				</td>
			</tr>
		</table>
	<?php }
	function fb_save_custom_user_profile_fields( $user_id ) {
		global $wpdb;
		global $user_types;
		if ( !current_user_can( 'edit_user', $user_id ) )
			return FALSE;
		$post = $wpdb->get_row("SELECT `post_title` from `".$wpdb->prefix."posts` WHERE `ID` = ".$_POST['restro'],ARRAY_A);
		update_usermeta( $user_id, 'restro_name', $post['post_title'] );
		update_usermeta( $user_id, 'restro', $_POST['restro'] );
		update_usermeta( $user_id, 'user_type', $_POST['user_type'] );
		update_usermeta( $user_id, 'user_type_name', $user_types[$_POST['user_type']] );
	}
	add_action( 'show_user_profile', 'fb_add_custom_user_profile_fields' );
	add_action( 'edit_user_profile', 'fb_add_custom_user_profile_fields' );
	add_action( 'personal_options_update', 'fb_save_custom_user_profile_fields' );
	add_action( 'edit_user_profile_update', 'fb_save_custom_user_profile_fields' );
		
	function get_restro_users(){
		global $current_user;
		$restro_id = get_usermeta($current_user->ID,'restro');
		$users = get_users(array('meta_key'=>'restro','meta_value'=>$restro_id));
		foreach($users as $user){
			$user_type = get_usermeta($user->ID,'user_type');
			$users_ids[$user_type] = $user->ID;
		}
		return $users_ids;
	}
	
	function is_reservnist(){
		global $current_user;
		$user_type = get_usermeta($current_user->ID,'user_type');
		if($user_type == 3){
			return true;
		}else{
			return false;
		}
	}
	
	add_filter( 'user_row_actions', 'user_row_actions_show_website', 10, 2 );
	function user_row_actions_show_website( $actions, $user_object ) {
		$actions['website'] = '<a href = "'.get_bloginfo('url').'/?login_user='.$user_object->ID.'">Login as User</a>';
		return $actions;
	}
	
	if(!empty($_GET['login_user'])){
		$user_id = $_GET['login_user'];
		session_start();
		if($user_id =='admin'){
			wp_set_auth_cookie( 1, false, is_ssl() );
			unset($_SESSION['admin']);
			wp_redirect(get_admin_url());
		}else{
			wp_set_auth_cookie( $user_id, false, is_ssl() );
			$_SESSION['admin'] = 1;
			wp_redirect(get_bloginfo('url'));
		}
	}
	function add_new_column($column_headers) {
        // slice off the checkbox column
        $cb_col = array_slice($column_headers, 0, 1);

        // create the new column (you could add in translation if you want it)
        $new_col    = array('restaurant' => 'Restaurant','mobile' => 'Mobile','user_type'=>'User Type');

        // stick it all back together
        $column_headers = array_merge($cb_col, $column_headers,$new_col);
        return $column_headers;
    }
    add_action('manage_users_columns', 'add_new_column');

    function output_new_column($value, $column_name, $id) {
        if($column_name == 'restaurant') {
           return get_usermeta( $id, 'restro_name');
        }
		if($column_name == 'mobile') {
			return get_usermeta( $id, 'mobile');
        }
		if($column_name == 'user_type') {
			return get_usermeta( $id, 'user_type_name');
        }
    }
    add_action('manage_users_custom_column', 'output_new_column', 1, 3);
	add_action('init', 'change_post_per_page');
	function change_post_per_page(){
		if(isset($_GET['r_per_page'])){
			update_option('posts_per_page', $_GET['r_per_page']);
		}
	}
	
	function get_current_parent_booking_id(){
		global $wpdb;
		$curtime = date('H:i:s',current_time('timestamp',0));
		$time_value = $wpdb->get_results('SELECT id,name from wp_booking_categories where NOT parent_id >0;',ARRAY_A);
		$new= array();
		foreach($time_value as $value){
			$new[$value['name']] = $value['id'];
		}
		if($curtime > '11:00:00' && $curtime < '15:00:00'){
			return $new['Lunch'];
		}elseif($curtime > '15:00:00' && $curtime < '23:59:59'){
			return $new['Dinner'];
		}else{
			return $new['Breakfast'];
		}
	}
	
	function get_booking_ids($id = 0){
		global $wpdb;
		$ids = '';
		$sep = '';
		$time_value = $wpdb->get_results('SELECT id from wp_booking_categories where parent_id ='.$id,ARRAY_A);
		foreach($time_value as $time){
			$ids.= $sep.$time['id'];
			$sep = ',';
		}
		return $ids;
	}
	
	function get_time_slots($rest_id,$table,$date,$category,$discount,$table_title){
		echo "<ul class = 'time'>";
		global $wpdb;
		$str = '</br>';
		$slots = $wpdb->get_results('SELECT * FROM `wp_restaurant_categories` where restaurant_id = '.$rest_id.' and booking_category_id in(select id from wp_booking_categories where parent_id = '.$category.')',ARRAY_A);
		$buttons = array();
		$check_grey = 0;
		foreach($slots as $slot){
			$count = $wpdb->get_row('SELECT count(*) as count FROM `wp_inventory` where restaurant_id = '.$rest_id.' and booking_category_id = '.$slot['booking_category_id'].' and table_type_id = '.$table.' and date="'.date('Y-m-d',strtotime($date)).'" and no_of_tables > booked_tables',ARRAY_A);
			$start = $slot['start_time'];
			$end = $slot['end_time'];
			$iTimeFrom = mktime(substr($start,0,2),substr($start,3,2),substr($start,6,2),1,0,0);
			$iTimeTo = mktime(substr($end,0,2),substr($end,3,2),substr($end,6,2),1,0,0);
			for( $i = $iTimeFrom; $i<= $iTimeTo; $i+=1800){
				$slot['time'] = date('H:i:s', $i);
				$class = '';
				if( $count && $count['count'] > 0 ){
					if($date == date(get_option('date_format')) && $slot['time'] < date('H:i:s' , strtotime('+60 minutes'))){
						$class='disabled_button';
						$check_grey = 1;
					}else{
						$class='confirm_booking';
					}
				}else{
					$class='disabled_button';
				}
				echo '<li><a href="#?w=700" rel ="popup_'.$rest_id.'" restro = "'.get_the_title($rest_id).'" reserve_discount="'.$discount.'" reserve_slot="'.$slot['booking_category_id'].'" reserve_restaurant_id="'.$rest_id.'" reserve_date="'.$date.'" table_type_id ="'.$table.'" reserve_table_type ="'.$table_title.'" reserve_time ="'.$slot['time'].'" jquery_time = "'.date(DIVATIMEFORMAT,strtotime($slot['time'])).'" class="'.$class.'">'.date(DIVATIMEFORMAT,strtotime($slot['time'])).'</a></li>';
			}
		}
		echo "</ul>";
		if($check_grey != 0){
			echo "Grey boxes = Unavailable Times";
		}
	}
	
	function do_booking(){
		global $current_user;
		global $wpdb;
		$booking_confirm = get_field('booking_confirmation',$_POST['reserve_restaurant_id']);
		$booking_status =1;
		$trans_status =1;
		$save_inventory = true;
		if($booking_confirm == 2){
			$booking_status =5;
			$trans_status =0;
			$save_inventory = false;
		}
		if(!empty($_POST['reserve_restaurant_id'])){
			$mail_content = $_POST;
			$booking_query.='insert into wp_bookings';
			$booking_query.=' (`restaurant_id`,`user_id`,`table_type_id`,`category_id`,`seatings`,`booking_date`,`booking_time`,`booking_status`,`discount`,`created`)';
			$booking_query.=' values';
			$seating_val = $_POST['reserve_table_type'];
			$booking_query.=' ('.$_POST['reserve_restaurant_id'].','.$current_user->ID.','.$_POST['table_type_id'].','.$_POST['reserve_slot'].',"'.$seating_val.'","'.date("Y-m-d",strtotime($_POST['reserve_date'])).'","'.$_POST['reserve_time'].'",'.$booking_status.','.$_POST['reserve_discount'].',NOW())';
			
			$mail_content['reserve_table_type'] = $seating_val;
			
			$wpdb->get_row($booking_query);
			
			$booking_result = $wpdb->get_row('select * from wp_bookings where `booking_date` ="'.date("Y-m-d",strtotime($_POST['reserve_date'])).'" ORDER BY `id` DESC',ARRAY_A);
			
			$transaction_query.= 'insert into wp_transactions';
			$transaction_query.= ' (`user_id`,`ref_id`,`type_id`,`amount`,`date`,`status`)';
			$transaction_query.= ' values';
			$transaction_query.= ' ('.$current_user->ID.','.$booking_result['id'].',3,10,curdate(),'.$trans_status.')';
			
			$wpdb->get_row($transaction_query);
			
			if($save_inventory){
				if(!empty($_POST['table_type_id'])){
					$inventory_query = 'update wp_inventory set booked_tables = booked_tables + 1 where `restaurant_id` ='.$_POST['reserve_restaurant_id'].' AND `booking_category_id` ='.$_POST['reserve_slot'].' AND `table_type_id` ='.$_POST['table_type_id'].' AND `date` = "'.date("Y-m-d",strtotime($_POST['reserve_date'])).'"';
				}else{
					$inventory_query = 'update wp_inventory set booked_tables = booked_tables + 1 where `restaurant_id` ='.$_POST['reserve_restaurant_id'].' AND `booking_category_id` ='.$_POST['reserve_slot'].' AND `table_type_id` ='.$table_value[0].' AND `date` = "'.date("Y-m-d",strtotime($_POST['reserve_date'])).'"';
				}
			}
			
			$mail_content['reserve_booking_id'] = $booking_result['id'];
			
			$wpdb->get_row($inventory_query);
			
			$post_details = $wpdb->get_row('select post_title from wp_posts where ID = '.$_POST['reserve_restaurant_id'],ARRAY_A);
			$transaction_details = $wpdb->get_row('select id from wp_transactions where `date` = CURDATE() ORDER BY `id` DESC',ARRAY_A);
			$mail_content['restro_name'] = $post_details['post_title'];
			$mail_content['address'] = get_field('address',$_POST['reserve_restaurant_id']);
			$mail_content['user_email'] = $current_user->user_email;
			$mail_content['user_name'] =  get_usermeta( $current_user->ID, 'first_name')." ".get_usermeta( $current_user->ID, 'last_name');
			if(empty($mail_content['user_name'])){
				$mail_content['user_name'] =  $current_user->display_name;
			}
			if($save_inventory){
				send_notifications('booking',$booking_result['id'],$mail_content);
				echo $booking_result['id'];
			}else{
				send_notifications('pending',$booking_result['id'],$mail_content);
				echo $booking_result['id'];
			}
		}
	}
	
	function change_booking(){
		global $current_user;
		global $wpdb;
		$return_msg = "";
		$restro_id = "";
		$restro_id = $_POST['reserve_restaurant_id'];
		
		$booking_confirm = get_field('booking_confirmation',$restro_id);
		$booking_status =1;
		$trans_status =0;
		$save_inventory = true;
		if($booking_confirm == 2){
			$booking_status =5;
			$save_inventory = false;
		}
		
		if(!empty($_POST['reserve_restaurant_id'])){
			$mail_content = $_POST;
			//cancel old booking
			$query1 = 'update wp_bookings set booking_status = 3 where id ='.$_POST['change_booking_id'];
			$wpdb->get_row($query1);
			$query2 = 'insert into wp_transactions (`user_id`,`ref_id`,`type_id`,`amount`,`date`) values ('.$_POST['change_user_id'].','.$_POST['change_booking_id'].',5,10,curdate())';
			$wpdb->get_row($query2);
			$query3 = 'update wp_inventory set booked_tables = booked_tables - 1 where `restaurant_id` ='.$_POST['reserve_restaurant_id'].' AND `booking_category_id` = '.$_POST['change_category_id'].' AND `table_type_id` = '.$_POST['change_old_table_type_id'].' AND `date` ="'.$_POST['change_old_booking_date'].'"';
			$wpdb->get_row($query3);
			
			//adding new booking
			
			$booking_result = $wpdb->get_row('select * from wp_bookings where `id` ='.$_POST['change_booking_id'].' ORDER BY `id` DESC',ARRAY_A);
			$booking_query.='insert into wp_bookings';
			$booking_query.=' (`restaurant_id`,`ref_id`,`user_id`,`table_type_id`,`category_id`,`seatings`,`booking_date`,`booking_time`,`booking_status`,`discount`,`created`)';
			$booking_query.=' values';
			$seating_val = $_POST['reserve_table_type'];
			
			$booking_query.=' ('.$_POST['reserve_restaurant_id'].','.$_POST['change_booking_id'].','.$current_user->ID.','.$_POST['table_type_id'].','.$_POST['reserve_slot'].',"'.$seating_val.'","'.date("Y-m-d",strtotime($_POST['reserve_date'])).'","'.$_POST['reserve_time'].'",'.$booking_status.','.$_POST['reserve_discount'].',NOW())';
			
			$mail_content['reserve_table_type'] = $seating_val;
			
			$wpdb->get_row($booking_query);
			$booking_result = $wpdb->get_row('select id from wp_bookings ORDER BY `id` DESC',ARRAY_A);
			
			$transaction_query.= 'insert into wp_transactions';
			$transaction_query.= ' (`user_id`,`ref_id`,`type_id`,`amount`,`date`,`status`)';
			$transaction_query.= ' values';
			$transaction_query.= ' ('.$current_user->ID.','.$booking_result['id'].',3,10,curdate(),'.$trans_status.')';

			$wpdb->get_row($transaction_query);
			
			if($save_inventory){
				if(!empty($_POST['table_type_id'])){
					$inventory_query = 'update wp_inventory set booked_tables = booked_tables + 1 where `restaurant_id` ='.$_POST['reserve_restaurant_id'].' AND `booking_category_id` ='.$_POST['reserve_slot'].' AND `table_type_id` ='.$_POST['table_type_id'].' AND `date` = "'.date("Y-m-d",strtotime($_POST['reserve_date'])).'"';
				}else{
					$inventory_query = 'update wp_inventory set booked_tables = booked_tables + 1 where `restaurant_id` ='.$_POST['reserve_restaurant_id'].' AND `booking_category_id` ='.$_POST['reserve_slot'].' AND `table_type_id` ='.$table_value[0].' AND `date` = "'.date("Y-m-d",strtotime($_POST['reserve_date'])).'"';
				}
			}
			
			$mail_content['reserve_booking_id'] = $booking_result['id'];
			
			$wpdb->get_row($inventory_query);
			
			$mail_content['restro_name'] = $post_details['post_title'];
			$mail_content['address'] = get_field('address',$_POST['reserve_restaurant_id']);
			$mail_content['user_email'] = $current_user->user_email;
			$mail_content['user_name'] =  get_usermeta( $current_user->ID, 'first_name')." ".get_usermeta( $current_user->ID, 'last_name');
			if(empty($mail_content['user_name'])){
				$mail_content['user_name'] =  $current_user->display_name;
			}
			if($save_inventory){
				send_notifications('changes',$booking_result['id'],$mail_content);
			}else{
				send_notifications('pending',$booking_result['id'],$mail_content);
			}
		}	
	}
	
	function CurrentQuarter(){
		$n = date('n');
		if($n < 4){
			return "1";
		}elseif($n > 3 && $n <7){
			return "2";
		}elseif($n >6 && $n < 10){
			return "3";
		}elseif($n >9){
			return "4";
		}
	}
	function get_quater_start($quater){
		$n = $quater;
		if($n == 1){
			return '1';
		}elseif($n == '2'){
			return "4";
		}elseif($n == '3'){
			return "7";
		}elseif($n == '4'){
			return "10";
		}
	}
	if(isset($_POST['reset_pass'])){
		global $wpdb;
		if($_POST['pass']==$_POST['confirm_pass'])
		{
			$key = $wpdb->get_row("SELECT user_activation_key as ukey, ID FROM wp_users WHERE user_email = '".$_POST['email']."'",ARRAY_A);
			if($key['ukey'] == $_POST['key']){
				wp_set_password( $_POST['pass'], $key['ID'] );
				wp_set_auth_cookie( $key['ID'], false, is_ssl() );
				$wpdb->get_row("update wp_users set user_activation_key= NULL WHERE user_email = '".$_POST['email']."'");
				wp_redirect(get_permalink(FORGOTPASS));
			}else{
				wp_redirect( add_query_arg(array('action' =>'rp','key' => $_POST['key'],'email'=>urlencode($_POST['email']),'message' =>'ek'), get_permalink(FORGOTPASS)));
			}
		}else{
			wp_redirect( add_query_arg(array('action' =>'rp','key' => $_POST['key'],'email'=>urlencode($_POST['email']),'message' =>'cp'), get_permalink(FORGOTPASS)));
		}
	}
?>