<?php
	$conn = mysql_connect('localhost', 'root', '');
	mysql_select_db('diva_latest',$conn);
	// Booking Closed
	$query = "UPDATE `wp_bookings` set `booking_status` = 2 where `booking_status` = 1  AND (ADDDATE(CONCAT(`booking_date`,' ',`booking_time`), INTERVAL 24 HOUR)) <= NOW()";
	$results = mysql_query($query);
	
	$query = "SELECT id from `wp_bookings` set `booking_status` = 2 where `booking_status` = 1  AND (ADDDATE(CONCAT(`booking_date`,' ',`booking_time`), INTERVAL 24 HOUR)) <= NOW()";
	$results = mysql_query($query);
	while($row = mysql_fetch_assoc($results)){
		$query = "UPDATE `wp_transactions` set status` = 1 where `ref_id` = ".$row['id']."  AND type_id = 3";
	}
	
	
	// Transactions Active
	$query = "SELECT * FROM `wp_transactions` as a where status =0 AND type_id =6;";
	$results = mysql_query($query);
	$transactions = array();
	while($row = mysql_fetch_assoc($results)){
		$transactions[] = $row;
	}
	foreach($transactions as $transaction){
		$query = "select * from wp_bookings where booking_status = 2 AND user_id = ".$transaction['user_id']." group by user_id";
		$results = mysql_query($query);
		while($row = mysql_fetch_assoc($results)){
			$query = "UPDATE `wp_transactions` set status` = 1 where `user_id` = ".$row['user_id']."  AND type_id = 6";
			mysql_query($query);
			$query = "UPDATE `wp_transactions` set status` = 1 where `user_id` = ".$row['ref_id']."  AND `ref_id` = ".$row['user_id']." AND type_id = 2";
			mysql_query($query);
		}
	}
?>