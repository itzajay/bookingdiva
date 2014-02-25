<?php
/**
 * @since Colonial 1.0
   Template Name: Restaurant_owner
 */
?>
<?php
	global $wpdb;
	global $current_user;
	if(is_rest_user()){
		if(isset($_POST['update_date_inventory']) && $_POST['update_date_inventory'] == true){
			$record_query = "select `booked_tables` from `".$wpdb->prefix."inventory` WHERE `restaurant_id`=".trim($_POST['restaurant_id'])." && `table_type_id`=".trim($_POST['table_id'])." && `booking_category_id`=".trim($_POST['cat_id'])." && `date`='".trim($_POST['cur_date'])."';";
			$record = $wpdb->get_row($record_query,ARRAY_A);
			if($record['booked_tables']<= trim($_POST['no_of_tables'])){
				$query = "UPDATE `".$wpdb->prefix."inventory` SET `no_of_tables` = ".trim($_POST['no_of_tables']).",`flag` = 1 WHERE `restaurant_id`=".trim($_POST['restaurant_id'])." && `table_type_id`=".trim($_POST['table_id'])." && `booking_category_id`=".trim($_POST['cat_id'])." && `date`='".trim($_POST['cur_date'])."';";
				$wpdb->get_row($query);
				echo "true,<div class = 'updated'><p> Record Updated</p></div>";
			}else{
				echo "false,<div class = 'error'><p>Record is not updated becouse booked tables are ".$record['booked_tables']."</p></div>";
			}
			exit;
		}
		if(isset($_POST['update_day_inventory']) && $_POST['update_day_inventory'] == true){
			$day = array('sun'=>'Sunday','mon'=>'Monday','tue'=>'tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday');
			$inventory_query = "select * from wp_inventory where `date` between curdate() AND date_add( curdate(), INTERVAL 1 MONTH) AND  dayname(`date`) = '".$day[$_POST['field']]."'AND NOT `flag` = 1 AND `restaurant_id` = ".$_POST['restaurant_id']."  AND `booking_category_id` = ".$_POST['booking_category_id']." AND `table_type_id` = ".$_POST['table_type_id'];
			$inventory_records = $wpdb->get_results($inventory_query,ARRAY_A);
	
			$can_update = array();
			$can_update_id = array();
			$cant_update = array();
			foreach($inventory_records as $inventory_record){
				if($inventory_record['booked_tables'] > $_POST['no_of_tables']){
					$cant_update[]= $inventory_record;
				}
				else{
					$can_update_id[] = $inventory_record['id'];
					$can_update[]= $inventory_record;
				}
			}
			if(!empty($cant_update)){
				echo "<div class = 'error'><p>Inventory for following dates are not modified</p>";
				foreach($cant_update as $data){
					echo "<p>Date: ".date(get_option('date_format'),strtotime($data['date']));
					echo "&nbsp;&nbsp;&nbsp;Booked Tables: ".$data['booked_tables']."</p>";
				}
				echo "</div>";
			}
			if(!empty($can_update)){
				echo "<div class = 'updated'><p>Inventory for following dates are modified</p>";
				foreach($can_update as $data){
					echo "<p>Date: ".date(get_option('date_format'),strtotime($data['date']));
					echo "&nbsp;&nbsp;&nbsp;Booked Tables: ".$data['booked_tables']."</p>";
				}
				echo "</div>";
			}
			$string = implode(",",$can_update_id);
			if(!empty($string)){
				$update_inventory = "update wp_inventory set `no_of_tables` = ".$_POST['no_of_tables']." where id in (".$string.");";
				$wpdb->get_row($update_inventory);
			}
			$query = "UPDATE `".$wpdb->prefix."inventory_rules` SET `".$_POST['field']."` = ".trim($_POST['no_of_tables'])." WHERE `id`=".trim($_POST['record_id']).";";
			$wpdb->get_row($query);
			exit;
		}
		if(!empty($_POST['info']) && $_POST['info'] == 'general'){
			$user_id = $_POST['user_id'];
			update_usermeta( $user_id, 'first_name', $_POST['first_name']);
			update_usermeta( $user_id, 'last_name', $_POST['last_name']);
			update_usermeta( $user_id, 'mobile', $_POST['mobile']);
			update_user_meta( $user_id, 'notification', $_POST['notification']);
			update_user_meta( $user_id, 'subscription', $_POST['subscription']);
			update_user_meta( $user_id, 'notification_method', $_POST['notification_method']);
			wp_redirect( add_query_arg(array('inventory' => 'my_account'), get_permalink()));
		}
		if(!empty($_POST['info']) && $_POST['info'] == 'account'){
			$user_id = $_POST['user_id'];
			if(!empty($_POST['password']))
			{
				wp_set_password( $_POST['password'], $user_id );
				wp_set_auth_cookie( $user_id, false, is_ssl() );
			}
			wp_redirect( add_query_arg(array('inventory' => 'my_account'), get_permalink()));
		}
	}else{
		$user_id = get_current_user_id( );
		if(!empty($_POST['info']) && $_POST['info'] == 'general'){
			update_usermeta( $user_id, 'first_name', $_POST['first_name']);
			update_usermeta( $user_id, 'last_name', $_POST['last_name']);
			update_usermeta( $user_id, 'city', $_POST['city']);
			update_usermeta( $user_id, 'zipcode', $_POST['zipcode']);
			update_usermeta( $user_id, 'dob', $_POST['dob']);
			update_usermeta( $user_id, 'gender', $_POST['gender']);
			update_usermeta( $user_id, 'email_alert', $_POST['email_alert']);
			update_user_meta( $user_id, 'notification_method', $_POST['notification_method']);
			update_usermeta( $user_id, 'mobile', $_POST['mobile']);
			
			if($_POST['mobile'] != $_POST['old_mobile']){
				$new = $_POST['mobile'];
				$old = $_POST['old_mobile'];
				change_mobile_sms($new,$old);
				change_mobile_mail($new,$current_user);
			}
			wp_redirect( add_query_arg(array('inventory' => 'my_account'), get_permalink()));
		}
		if(!empty($_POST['info']) && $_POST['info'] == 'account'){
			if(!empty($_POST['password']))
			{
				wp_set_password( $_POST['password'], $user_id );
				wp_set_auth_cookie( $user_id, false, is_ssl() );
				change_password_sms($_POST['password'],$current_user);
				change_password_mail($_POST['password'],$current_user);
			}
			wp_redirect( add_query_arg(array('inventory' => 'my_account'), get_permalink()));
		}
	}
?>
<?php get_header();?>
<?php
	if(is_rest_user()){
		if(isset($_GET['inventory']) && $_GET['inventory'] == 'summary'){
			include(FRONTENDINVENTORY."/inventory-summary.php");
		}elseif(isset($_GET['inventory']) && $_GET['inventory'] == 'rules'){
			include(FRONTENDINVENTORY."/inventory-rules.php");
		}elseif(isset($_GET['inventory']) && $_GET['inventory'] == 'my_account'){
			include(FRONTENDINVENTORY."/inventory-my_account.php");
		}elseif(isset($_GET['inventory']) && $_GET['inventory'] == 'my_account_edit'){
			include(FRONTENDINVENTORY."/inventory-my_account_edit.php");
		}elseif(isset($_GET['inventory']) && $_GET['inventory'] == 'reports'){
			include(FRONTENDINVENTORY."/inventory-reports.php");
		}elseif(isset($_GET['inventory']) && $_GET['inventory'] == 'transaction'){
			include(FRONTENDINVENTORY."/inventory-transaction-reports.php");
		}elseif(isset($_GET['inventory']) && $_GET['inventory'] == 'invoices'){
			include(FRONTENDINVENTORY."/inventory-invoice-reports.php");
		}elseif(isset($_GET['inventory']) && $_GET['inventory'] == 'settings'){
			include(FRONTENDINVENTORY."/inventory-settings.php");
		}
	}else{
		if(isset($_GET['inventory']) && $_GET['inventory'] == 'my_account'){
			include(FRONTENDBOOKING."/inventory-my_account.php");
		}elseif(isset($_GET['inventory']) && $_GET['inventory'] == 'my_account_edit'){
			include(FRONTENDBOOKING."/inventory-my_account_edit.php");
		}elseif(isset($_GET['inventory']) && $_GET['inventory'] == 'booking_history'){
			include(FRONTENDBOOKING."/inventory-booking_history.php");
		}elseif(isset($_GET['inventory']) && $_GET['inventory'] == 'current_bookings'){
			include(FRONTENDBOOKING."/inventory-current_bookings.php");
		}elseif(isset($_GET['inventory']) && $_GET['inventory'] == 'transaction'){
			include(FRONTENDBOOKING."/inventory-transaction_report.php");
		}elseif(isset($_GET['inventory']) && $_GET['inventory'] == 'booking_details'){
			include(FRONTENDBOOKING."/inventory-booking_details.php");
		}
	}
	
?>
<?php get_footer(); ?>