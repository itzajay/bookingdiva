<?php
	$values = array();
	function send_notifications($event,$booking_id,$booking_info){
		global $wpdb;
		global $current_user;
		$user_id = $current_user->ID;
		$booking = $wpdb->get_row("SELECT * from `".$wpdb->prefix."bookings` WHERE `id` =".$booking_id,ARRAY_A);
		$post = $wpdb->get_row("SELECT * from `".$wpdb->prefix."posts` WHERE `ID` = ".$booking['restaurant_id'],ARRAY_A);
		$users = get_users(array('meta_key'=>'restro','meta_value'=>$booking_info['restaurant_id']));
		foreach($users as $user){
			if(in_array($event,get_usermeta($user->ID, 'notification')) || $event == 'no_show' || $event == 'pending' ){
				if(in_array('email',get_usermeta( $user->ID, 'notification_method' ))){
					send_restaurant_owner_email($event,$user->ID,$booking_info);
				}
				if(in_array('sms',get_usermeta( $user->ID, 'notification_method' ))){
					if(get_usermeta($user->ID, 'mobile')  != NULL){
						send_restaurant_owner_sms($event,$user->ID,$booking_info);
					}
				}
			}
		}
		if(in_array('email',get_usermeta($user_id, 'notification_method' ))){
			send_user_email($event,$user_id,$booking_info);
		}
		if(in_array('sms',get_usermeta( $post['post_author'], 'notification_method' ))){
			if(get_usermeta($user_id, 'mobile') != ""){
				send_user_sms($event,$user_id,$booking_info);
			}
		}
		send_admin_email($event,$booking_info);
	}
	
	function send_restaurant_owner_email($event,$author,$booking_details){
		global $wpdb;
		$headers = 'From: Booking Diva <admin@bookingdiva.com>' . "\r\n";
		 
		$user = $wpdb->get_row("SELECT * from `".$wpdb->prefix."users` WHERE `ID` = ".$author,ARRAY_A);
		$to = $user['user_email'];
		$subject = $event;
		$str= get_mail_body($event,'restaurant_owner');
		$name = get_usermeta( $user['ID'], 'first_name');
		if(empty($name)){
			$name = $user['display_name'];
		}
		$values ['#restro_owner'] = $name;
		$values ['#name'] = $booking_details['user_name'];
		$values ['#booking_date'] = $booking_details['reserve_date'];
		$values ['#booking_time'] = $booking_details['reserve_time'];
		$values ['#restro_name'] = $booking_details['restro_name'];
		$values ['#no_of_people'] = $booking_details['reserve_table_type'];
		$values ['#booking_id'] = $booking_details['reserve_booking_id'];
		$values ['#address'] = $booking_details['address'];
		$body = strtr($str, $values);
		wp_mail($to, $subject, $body,$headers);
	}
	
	function send_restaurant_owner_sms($event,$author,$booking_details){
		global $wpdb;
		$user = $wpdb->get_row("SELECT * from `".$wpdb->prefix."users` WHERE `ID` = ".$author,ARRAY_A);
		$name = get_usermeta( $user['ID'], 'first_name');
		if(empty($name)){
			$name = $user['display_name'];
		}
		$str= get_sms_body($event,'restaurant_owner');
		$values ['#restro_owner'] = $name;
		$values ['#name'] = $booking_details['user_name'];
		$values ['#booking_date'] = $booking_details['reserve_date'];
		$values ['#booking_time'] = $booking_details['reserve_time'];
		$values ['#restro_name'] = $booking_details['restro_name'];
		$values ['#no_of_people'] = $booking_details['reserve_table_type'];
		$values ['#booking_id'] = $booking_details['reserve_booking_id'];
		$values ['#address'] = $booking_details['address'];
		$body = strtr($str, $values);
		$mobile = get_usermeta($author, 'mobile' );
		sendsms($mobile,$body);
	}
	
	function send_user_sms($event,$user_id,$booking_details){
		$str= get_sms_body($event,'user');
		$values ['#name'] = $booking_details['user_name'];
		$values ['#booking_date'] = $booking_details['reserve_date'];
		$values ['#booking_time'] = $booking_details['reserve_time'];
		$values ['#restro_name'] = $booking_details['restro_name'];
		$values ['#no_of_people'] = $booking_details['reserve_table_type'];
		$values ['#booking_id'] = $booking_details['reserve_booking_id'];
		$values ['#address'] = $booking_details['address'];
		$body = strtr($str, $values);
		$mobile = get_usermeta($user_id, 'mobile' );
		sendsms($mobile,$body);
	}
	
	function send_user_email($event,$user_id,$booking_details){
		$to = $booking_details['user_email'];
		$headers = 'From: Booking Diva <admin@bookingdiva.com>' . "\r\n";
		 
		$str= get_mail_body($event,'user');
		$values ['#name'] = $booking_details['user_name'];
		$values ['#booking_date'] = $booking_details['reserve_date'];
		$values ['#booking_time'] = $booking_details['reserve_time'];
		$values ['#restro_name'] = $booking_details['restro_name'];
		$values ['#no_of_people'] = $booking_details['reserve_table_type'];
		$values ['#booking_id'] = $booking_details['reserve_booking_id'];
		$values ['#address'] = $booking_details['address'];
		$body = strtr($str, $values);
		wp_mail($to, $subject, $body,$headers);
	}
	
	function send_admin_email($event,$booking_details){
		global $wpdb;
		$headers = 'From: Booking Diva <admin@bookingdiva.com>' . "\r\n";
		 
		$user = $wpdb->get_row("SELECT * from `".$wpdb->prefix."users` WHERE `ID` = 1",ARRAY_A);
		$to = $user['user_email'];
		$subject = $event;
		$str= get_mail_body($event,'admin');
		$values ['#admin'] = $user['display_name'];
		$values ['#name'] = $booking_details['user_name'];
		$values ['#booking_date'] = $booking_details['reserve_date'];
		$values ['#booking_time'] = $booking_details['reserve_time'];
		$values ['#restro_name'] = $booking_details['restro_name'];
		$values ['#no_of_people'] = $booking_details['reserve_table_type'];
		$values ['#booking_id'] = $booking_details['reserve_booking_id'];
		$values ['#address'] = $booking_details['address'];
		$body = strtr($str, $values);
		wp_mail($to, $subject, $body,$headers);
	}
	function get_mail_body($event,$role){
		if($role == 'restaurant_owner'){
			if($event == 'booking'){
				return get_field('restaurant_booking_confirmed',EMAILTEMPLATE);
			}elseif($event == 'cancellation'){
				return get_field('restaurant_booking_cancel',EMAILTEMPLATE);
			}elseif($event == 'changes'){
				return get_field('restaurant_booking_change',EMAILTEMPLATE);
			}elseif($event == 'no_show'){
				return get_field('restaurant_noshow',EMAILTEMPLATE);
			}elseif($event == 'pending'){
				return get_field('restaurant_booking_pending',EMAILTEMPLATE);
			}
		}elseif($role == 'user'){
			if($event == 'booking'){
				return get_field('user_booking_confirmed',EMAILTEMPLATE);
			}elseif($event == 'cancellation'){
				return get_field('user_booking_cancel',EMAILTEMPLATE);
			}elseif($event == 'changes'){
				return get_field('user_booking_change',EMAILTEMPLATE);
			}elseif($event == 'no_show'){
				return get_field('user_noshow',EMAILTEMPLATE);
			}elseif($event == 'pending'){
				return get_field('user_booking_pending',EMAILTEMPLATE);
			}elseif($event == 'mobile'){
				return get_field('user_change_mobile',EMAILTEMPLATE);
			}elseif($event == 'change_password'){
				return get_field('user_change_password',EMAILTEMPLATE);
			}
		}elseif($role == 'admin'){
			if($event == 'booking'){
				return get_field('admin_booking_confirmed',EMAILTEMPLATE);
			}elseif($event == 'cancellation'){
				return get_field('admin_booking_cancel',EMAILTEMPLATE);
			}elseif($event == 'changes'){
				return get_field('admin_booking_change',EMAILTEMPLATE);
			}elseif($event == 'no_show'){
				return get_field('admin_noshow',EMAILTEMPLATE);
			}elseif($event == 'pending'){
				return get_field('admin_booking_pending',EMAILTEMPLATE);
			}
		}
	}
	
	function get_sms_body($event,$role){
		if($role == 'restaurant_owner'){
			if($event == 'booking'){
				return get_field('restaurant_sms_booking_confirm',EMAILTEMPLATE);
			}elseif($event == 'cancellation'){
				return get_field('restaurant_sms_booking_cancel',EMAILTEMPLATE);
			}elseif($event == 'changes'){
				return get_field('restaurant_sms_booking_change',EMAILTEMPLATE);
			}elseif($event == 'pending'){
				return get_field('restaurant_sms_booking_pending',EMAILTEMPLATE);
			}
		}elseif($role == 'user'){
			if($event == 'booking'){
				return get_field('user_sms_booking_confirm',EMAILTEMPLATE);
			}elseif($event == 'cancellation'){
				return get_field('user_sms_booking_cancel',EMAILTEMPLATE);
			}elseif($event == 'changes'){
				return get_field('user_sms_booking_change',EMAILTEMPLATE);
			}elseif($event == 'pending'){
				return get_field('user_sms_booking_pending',EMAILTEMPLATE);
			}elseif($event == 'mobile_new'){
				return get_field('user_sms_change_mobile_new',EMAILTEMPLATE);
			}elseif($event == 'mobile_old'){
				return get_field('user_sms_change_mobile_old',EMAILTEMPLATE);
			}elseif($event == 'change_password'){
				return get_field('user_sms_change_password',EMAILTEMPLATE);
			}
		}
	}
	
	function change_mobile_sms($new_no,$old_no){
		$values['#new_mobile'] =  $new_no;
		$old_no_message = get_sms_body('mobile_old','user');
		$new_no_message = get_sms_body('mobile_new','user');
		$body = strtr($old_no_message, $values);
		sendsms($old_no,$body);
		$body = strtr($new_no_message, $values);
		sendsms($new_no,$body);
	}
	
	function change_mobile_mail($new_no,$current_user_info){
		$headers = 'From: Booking Diva <admin@bookingdiva.com>' . "\r\n";
		 
		$values['#new_mobile'] =  $new_no;
		$to = $current_user_info->user_email;
		$subject = "Mobile number changed";
		$message = get_mail_body('mobile','user');
		$body = strtr($message, $values);
		wp_mail($to, $subject, $body,$headers);
	}
	
	function change_password_sms($change_pass,$current_user_info){
		$values['#password'] =  $change_pass;
		$change_pass_message = get_sms_body('change_password','user');
		$body = strtr($change_pass_message, $values);
		$mobile = get_usermeta($current_user_info->ID, 'mobile' );
		sendsms($mobile,$body);
	}
	function change_password_mail($new_pass,$current_user_info){
		$headers = 'From: Booking Diva <admin@bookingdiva.com>' . "\r\n";
		 
		$values['#password'] =  $new_pass;
		$to = $current_user_info->user_email;
		$subject = "Password changed";
		$message = get_mail_body('change_password','user');
		$body = strtr($message, $values);
		wp_mail($to, $subject, $body,$headers);
	}
	
	function sendsms($no,$msg){
		
		//Please Enter Your Details
		$user="bookingdiva"; //your username
		$password="83840419"; //your password
		$mobilenumbers="91".$no; //enter Mobile numbers comma seperated
		$message = $msg; //enter Your Message 
		$senderid="BkDiva"; //Your senderid
		$messagetype="N"; //Type Of Your Message
		$DReports="Y"; //Delivery Reports
		$url="http://www.smscountry.com/SMSCwebservice.asp";
		$message = urlencode($message);
		$ch = curl_init(); 
		if (!$ch){die("Couldn't initialize a cURL handle");}
		$ret = curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);          
	   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, 
	   "User=$user&passwd=$password&mobilenumber=$mobilenumbers&message=$message&sid=$senderid&mtype=$messagetype&DR=$DReports");
		$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   
	   
	   //If you are behind proxy then please uncomment below line and provide your proxy ip with port.
	   // $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");
	   
	   
	   
		$curlresponse = curl_exec($ch); // execute
	   if(curl_errno($ch))
		   //echo 'curl error : '. curl_error($ch);
	   
		if (empty($ret)) {
		   // some kind of an error happened
		   
		   die(curl_error($ch));
		   curl_close($ch); // close cURL handler
		} else {
		   $info = curl_getinfo($ch);
		   curl_close($ch); // close cURL handler
		   //echo "<br>";
		  // echo $curlresponse;    //echo "Message Sent Succesfully" ;
		  
		}
	}
?>