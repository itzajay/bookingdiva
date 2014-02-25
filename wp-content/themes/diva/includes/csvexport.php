<?php
	function _valToCsvHelper($val, $separator, $trimFunction) {
		if ($trimFunction) $val = $trimFunction($val);
		//If there is a separator (;) or a quote (") or a linebreak in the string, we need to quote it.
		$needQuote = FALSE;
		do {
			if (strpos($val, '"') !== FALSE) {
				$val = str_replace('"', '""', $val);
				$needQuote = TRUE;
				break;
			}
			if (strpos($val, $separator) !== FALSE) {
				$needQuote = TRUE;
				break;
			}
			if ((strpos($val, "\n") !== FALSE) || (strpos($val, "\r") !== FALSE)) { // \r is for mac
				$needQuote = TRUE;
				break;
			}
		} 
		while (FALSE);
		if ($needQuote) {
			$val = '"' . $val . '"';
		}
		return $val;
	}
	
	function arrayToCsvString($array, $separator=',', $trim='both', $removeEmptyLines=TRUE) {
		if (!is_array($array) || empty($array)) return '';
		switch ($trim) {
			case 'none':
				$trimFunction = FALSE;
				break;
			case 'left':
				$trimFunction = 'ltrim';
				break;
			case 'right':
				$trimFunction = 'rtrim';
				break;
			default: //'both':
				$trimFunction = 'trim';
			break;
		}
		$ret = array();
		reset($array);
		if (is_array(current($array))) {
			while (list(,$lineArr) = each($array)) {
				if (!is_array($lineArr)) {
					//Could issue a warning ...
					$ret[] = array();
				} else {
					$subArr = array();
					while (list(,$val) = each($lineArr)) {
						$val      = _valToCsvHelper($val, $separator, $trimFunction);
						$subArr[] = $val;
					}
				}
				$ret[] = join($separator, $subArr);
			}
			$crlf = _define_newline();
			return join($crlf, $ret);
		} else {
			while (list(,$val) = each($array)) {
				$val   = _valToCsvHelper($val, $separator, $trimFunction);
				$ret[] = $val;
			}
			return join($separator, $ret);
		}
	}
	
	function _define_newline() {
		$unewline = "\r\n";
		if (strstr(strtolower($_SERVER["HTTP_USER_AGENT"]), 'win')) {
		   $unewline = "\r\n";
		} else if (strstr(strtolower($_SERVER["HTTP_USER_AGENT"]), 'mac')) {
		   $unewline = "\r";
		} else {
		   $unewline = "\n";
		}
		return $unewline;
	}
	
	function _get_browser_type() {
		$USER_BROWSER_AGENT="";
	
		if (ereg('OPERA(/| )([0-9].[0-9]{1,2})', strtoupper($_SERVER["HTTP_USER_AGENT"]), $log_version)) {
			$USER_BROWSER_AGENT='OPERA';
		} else if (ereg('MSIE ([0-9].[0-9]{1,2})',strtoupper($_SERVER["HTTP_USER_AGENT"]), $log_version)) {
			$USER_BROWSER_AGENT='IE';
		} else if (ereg('OMNIWEB/([0-9].[0-9]{1,2})', strtoupper($_SERVER["HTTP_USER_AGENT"]), $log_version)) {
			$USER_BROWSER_AGENT='OMNIWEB';
		} else if (ereg('MOZILLA/([0-9].[0-9]{1,2})', strtoupper($_SERVER["HTTP_USER_AGENT"]), $log_version)) {
			$USER_BROWSER_AGENT='MOZILLA';
		} else if (ereg('KONQUEROR/([0-9].[0-9]{1,2})', strtoupper($_SERVER["HTTP_USER_AGENT"]), $log_version)) {
			$USER_BROWSER_AGENT='KONQUEROR';
		} else {
			$USER_BROWSER_AGENT='OTHER';
		}
	
		return $USER_BROWSER_AGENT;
	}
	
	function _get_mime_type() {
		$USER_BROWSER_AGENT= _get_browser_type();
	
		$mime_type = ($USER_BROWSER_AGENT == 'IE' || $USER_BROWSER_AGENT == 'OPERA')
			? 'application/octetstream'
			: 'application/octet-stream';
		return $mime_type;
	}
	
	function createcsv($report = 'transaction',$sep) {
		global $wpdb;
		// Get the columns and create the first row of the CSV
		switch($report) {
			case 'transaction':
				$fields = array('Transaction Date','Transaction ID','Booking Date & Time','Day','Category','Seatings','User Name','Email','Mobile','Status');
				break;
			case 'booking':
			default:
				$fields = array('Transaction Date','Transaction ID','Booking Date & Time','Day','Category','Seatings','User Name','Email','Mobile','Status');
				break;
		}

		$csv = arrayToCsvString($fields, $sep);
		$csv .= _define_newline();
	
		// Query the entire contents from the Users table and put it into the CSV
		switch($report) {
			case 'transaction':
				$query = "SELECT b.booking_time, b.id as booking_id, b.booking_status, b.booking_date, b.`created` as`date`,b.`id`,a.`type_id`,bc.name,b.category_id,rc.start_time,rc.end_time,u.display_name,u.user_email,u.ID,tt.title FROM `wp_bookings` as b left join wp_transactions as a on a.ref_id = b.id AND a.type_id in (3,5) left join wp_users as u on u.ID = a.user_id left join wp_booking_categories as bc on bc.id = b.category_id left join wp_restaurant_categories as rc on rc.booking_category_id = b.category_id left join wp_table_types as tt on tt.id = b.table_type_id where b.`restaurant_id` =".$_GET['id'];
				break;
			case 'booking':
			default:
				$query = "SELECT b.booking_time, b.id as booking_id, b.booking_status, b.booking_date, b.`created` as`date`,b.`id`,a.`type_id`,bc.name,b.category_id,rc.start_time,rc.end_time,u.display_name,u.user_email,u.ID,tt.title FROM `wp_bookings` as b left join wp_transactions as a on a.ref_id = b.id AND a.type_id in (3,5) left join wp_users as u on u.ID = a.user_id left join wp_booking_categories as bc on bc.id = b.category_id left join wp_restaurant_categories as rc on rc.booking_category_id = b.category_id left join wp_table_types as tt on tt.id = b.table_type_id where b.`restaurant_id` =".$_GET['id'];
				break;
		}
		
		$results = $wpdb->get_results($query,ARRAY_A);

		$i=0;
		$new_array = array();
		global $booking_status;
		$i=0;
		switch($report) {
			case 'transaction':
				foreach($results as $result){
					$new_array[$i]['transaction_date'] = date(get_option('date_format'),strtotime($result['date']));
					$new_array[$i]['transaction_id'] = $result['id'];
					$new_array[$i]['booking_date_and_time'] = date(get_option('date_format'),strtotime($result['booking_date']))." ".date(DIVATIMEFORMAT,strtotime($result['booking_time']));
					$new_array[$i]['day'] = date('D',strtotime($result['booking_date']));
					$new_array[$i]['category'] = parent_category_name($result['category_id']);
					$new_array[$i]['seatings'] = $result['title'];
					$new_array[$i]['user_name'] = get_usermeta( $result['ID'], 'first_name')." ".get_usermeta( $result['ID'], 'last_name');
					$new_array[$i]['user_email'] = $result['user_email'];
					$new_array[$i]['mobile'] = get_usermeta($result['ID'],'mobile');
					$new_array[$i]['status'] = $booking_status[$result['booking_status']];
					$i++;
				}
				break;
			case 'booking':
			default:
				foreach($results as $result){
					$new_array[$i]['transaction_date'] = date(get_option('date_format'),strtotime($result['date']));
					$new_array[$i]['transaction_id'] = $result['id'];
					$new_array[$i]['booking_date_and_time'] = date(get_option('date_format'),strtotime($result['booking_date']))." ".date(DIVATIMEFORMAT,strtotime($result['booking_time']));
					$new_array[$i]['day'] = date('D',strtotime($result['booking_date']));
					$new_array[$i]['category'] = parent_category_name($result['category_id']);
					$new_array[$i]['seatings'] = $result['title'];
					$new_array[$i]['user_name'] = get_usermeta( $result['ID'], 'first_name')." ".get_usermeta( $result['ID'], 'last_name');
					$new_array[$i]['user_email'] = $result['user_email'];
					$new_array[$i]['mobile'] = get_usermeta($result['ID'],'mobile');
					$new_array[$i]['status'] = $booking_status[$result['booking_status']];
					$i++;
				}
				break;
		}
		
		$csv .= arrayToCsvString($new_array, $sep);
		$now = gmdate('D, d M Y H:i:s') . ' GMT';
	
		header('Content-Type: ' . _get_mime_type());
		header('Expires: ' . $now);
	
		header('Content-Disposition: attachment; filename="report.csv"');
		header('Pragma: no-cache');
	
		echo $csv;
	}
	if (isset($_GET['csv']) && $_GET['csv'] == "true") {
		//if ( !current_user_can('edit_users') )
			//wpdie('No, that won\'t be working, sorry.');
		$report = $_GET['report'];
		$sep = ",";
		if (isset($_GET['sep'])) {
			$sep = $_GET['sep'];
			if ($sep == "tab") {
				$sep = "\t";
			}
		}
		// echo $table;
		createcsv($report,$sep);
		exit;
	}
?>