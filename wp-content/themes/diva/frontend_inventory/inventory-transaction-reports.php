<script type="text/javascript">
	jQuery.noConflict();
	jQuery(function($) {
		$(document).ready(function(){
			var divWidth = $('div.movable').width();
			var tableWidth = $('div.movable>table').width();
			if (tableWidth<divWidth){
				$('div.movable>table').width(divWidth);
			}
			$('.no_show_submit').click(function(){
				$('.no_show').val('true');
				$('.confirmed').val('false');
				$('.cancel').val('false');
			});
			$('.confirmed_submit').click(function(){
				$('.no_show').val('false');
				$('.confirmed').val('true');
				$('.cancel').val('false');
			});
			$('.cancel_submit').click(function(){
				$('.no_show').val('false');
				$('.confirmed').val('false');
				$('.cancel').val('true');
			});
			$('.date_range').change(function(){
				var $val = $(this).val();
				var $from = '';
				var $to = '';
				var myDate = new Date();
				
				if($val == 't'){
					$from = "<?php echo date(get_option('date_format'));?>";
					$to = "<?php echo date(get_option('date_format'));?>";
				}else if($val == 'wd'){
					$from = "<?php echo date(get_option('date_format'),mktime(1, 0, 0, date('m'), date('d')-date('w'), date('Y')));?>";
					$to = "<?php echo date(get_option('date_format'));?>";
				}else if($val == 'md'){
					$from = "<?php echo date(get_option('date_format'),mktime(1, 0, 0, date('m'), '01', date('Y')));?>";
					$to = "<?php echo date(get_option('date_format'));?>";
				}else if($val == 'qd'){
					$from = "<?php echo date(get_option('date_format'),mktime(1, 0, 0,get_quater_start(CurrentQuarter()) , '01', date('Y')));?>";
					$to = "<?php echo date(get_option('date_format'));?>";
				}else if($val == 'lw'){
					$from = "<?php echo date(get_option('date_format'),mktime(1, 0, 0, date('m'), date('d')-date('w')-7, date('Y')));?>";
					$to = "<?php echo date(get_option('date_format'),mktime(1, 0, 0, date('m'), date('d')-date('w')-1, date('Y')));?>";
				}else if($val == 'lm'){
					$from = "<?php echo date(get_option('date_format'),mktime(1, 0, 0, date('m')-1, '+01', date('Y')));?>";
					$to = "<?php echo date(get_option('date_format'),mktime(1, 0, 0, date('m'),'-00', date('Y')));?>";
				}else if($val == 'lq'){
					$from = "<?php echo date(get_option('date_format'),mktime(1, 0, 0,get_quater_start(CurrentQuarter()-1) , '01', date('Y')));?>";
					$to = "<?php echo date(get_option('date_format'),mktime(1, 0, 0,get_quater_start(CurrentQuarter()) , '-00', date('Y')));?>";
				}
				$('#from').val($from);
				$('#to').val($to);
			});
			// If you need to remove an already bound editor you can call
		
			// > $(selectorForEditors).unbind('.editInPlace')
		
			// Which will remove all events that this editor has bound. You need to make sure however that the editor is 'closed' when you call this.
			
		});
	});
</script>
<style type="text/css">
ul.overrider_from li h1 {
    width: 120px;
}
</style>
<?php
	$parameters = "";
	$sep="";
	foreach($_GET as $key => $val){
		if($key != 'page')
			$parameters.= $sep."$key=$val";
		$sep = '&';
	}
	$current_user = wp_get_current_user();
	$post = get_posts_by_author();
	
	$mail_content = array();
	$booking_details = array();
	
	if(!empty($_POST['booking_id'])){
		$booking_details = $wpdb->get_row("select * from wp_bookings where id = ".$_POST['booking_id'],ARRAY_A);
		$mail_content['restro_name'] = get_the_title($booking_details['restaurant_id']);
		$mail_content['address'] = get_field('address',$booking_details['restaurant_id']);
		$mail_content['reserve_booking_id'] = $booking_details['id'];
		$mail_content['reserve_date'] = $booking_details['booking_date'];
		$mail_content['reserve_time'] = $booking_details['booking_time'];
		$mail_content['reserve_table_type'] = $booking_details['seatings'];
		$mail_content['user_name'] =  get_usermeta( $current_user->ID, 'first_name')." ".get_usermeta( $current_user->ID, 'last_name');
	}
	if(!empty($_POST['no_show']) && $_POST['no_show'] == 'true'){
		$query1 = 'update wp_bookings set booking_status = 4 where id ='.$_POST['booking_id'];
		$wpdb->get_row($query1);
		send_notifications('no_show',$_POST['booking_id'],$mail_content);
	}
	if(!empty($_POST['confirmed']) && $_POST['confirmed'] == 'true'){
		$inventory_query = 'update wp_inventory set booked_tables = booked_tables + 1 where `restaurant_id` ='.$booking_details['restaurant_id'].' AND `booking_category_id` = '.$booking_details['category_id'].' AND `table_type_id` = '.$booking_details['table_type_id'].' AND `date` ="'.$booking_details['booking_date'].'"';
		$query1 = 'update wp_bookings set booking_status = 1 where id ='.$_POST['booking_id'];
		$wpdb->get_row($query1);
		$inventory_query = 'update wp_inventory set booked_tables = booked_tables + 1 where `restaurant_id` ='.$booking_details['restaurant_id'].' AND `booking_category_id` = '.$booking_details['category_id'].' AND `table_type_id` = '.$booking_details['table_type_id'].' AND `date` ="'.$booking_details['booking_date'].'"';
		$wpdb->get_row($inventory_query);
		send_notifications('booking',$_POST['booking_id'],$mail_content);
	}
	if(!empty($_POST['cancel']) && $_POST['cancel'] == 'true'){
		$query1 = 'update wp_bookings set booking_status = 3 where id ='.$_POST['booking_id'];
		$wpdb->get_row($query1);
		if($booking_details['booking_status']  == 1){
			$query2 = 'insert into wp_transactions (`user_id`,`ref_id`,`type_id`,`amount`,`date`) values ('.$booking_details['user_id'].','.$_POST['booking_id'].',5,10,curdate())';
			$wpdb->get_row($query2);
			$query3 = 'update wp_inventory set booked_tables = booked_tables - 1 where `restaurant_id` ='.$booking_details['restaurant_id'].' AND `booking_category_id` = '.$booking_details['category_id'].' AND `table_type_id` = '.$booking_details['table_type_id'].' AND `date` ="'.$booking_details['booking_date'].'"';
			$wpdb->get_row($query3);
		}
		send_notifications('cancellation',$_POST['booking_id'],$mail_content);
	}
	/*
		Place code to connect to your DB here.
	*/
	// include your code to connect to DB.
	
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	$from = date("Y-m-d");
	$nextweek  = mktime(0, 0, 0, date("m"), date("d")+7, date("Y"));
	$to = date("Y-m-d",$nextweek);
	if(!empty($_GET['search'])){
		$from = date('Y-m-d',strtotime($_GET['from']));
		$to = date('Y-m-d',strtotime($_GET['to']));
		if(!empty($_GET['category'])){
			$slot = "";
			$slots = $wpdb->get_results("select id from wp_booking_categories where parent_id=".$_GET['category'],ARRAY_N);
			$sep = "";
			foreach($slots as $key => $value){
				foreach ($value as $key1 => $value1){
					$slot.= $sep.$value1['id'];
					$sep = ", ";
				}
			}
			$rules_condition.= ' AND `booking_category_id` in('.$slot.') ';
		}
		if(!empty($_GET['id'])){
			$rules_condition.= ' AND a.`id` ='.$_GET['id'];
		}
		if(!empty($_GET['transaction_type'])){
			$rules_condition.= ' AND a.`type_id` ='.$_GET['transaction_type'];
		}
	}
	if(!empty($from) && !empty($to)){
		$condition = " b.`created` BETWEEN '".$from."' AND '".$to."'";
	}elseif(empty($from ) && !empty($to)){
		$condition = " b.`created` <= '".$to."'";
	}elseif(empty($to ) && !empty($from)){
		$condition = " b.`created` >= '".$from."'";
	}
	$query = "SELECT COUNT(*) as num FROM `wp_bookings` as b left join wp_transactions as a on a.ref_id = b.id AND a.type_id in (3,5) left join wp_users as u on u.ID = a.user_id left join wp_booking_categories as bc on bc.id = b.category_id left join wp_restaurant_categories as rc on rc.booking_category_id = b.category_id left join wp_table_types as tt on tt.id = b.table_type_id where b.`restaurant_id` =".$post['ID']." AND $condition $rules_condition";
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages[num];
	
	/* Setup vars for query. */
	//$targetpage = "filename.php"; 	//your file name  (the name of this file)
	$limit = 10; 								//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
	$sql = "SELECT b.booking_time, b.id as booking_id, b.booking_status, b.booking_date, b.`created` as`date`,b.`id`,a.`type_id`,bc.name,b.category_id,rc.start_time,rc.end_time,u.display_name,u.user_email,u.ID,tt.title FROM `wp_bookings` as b left join wp_transactions as a on a.ref_id = b.id AND a.type_id in (3,5) left join wp_users as u on u.ID = a.user_id left join wp_booking_categories as bc on bc.id = b.category_id left join wp_restaurant_categories as rc on rc.booking_category_id = b.category_id left join wp_table_types as tt on tt.id = b.table_type_id where b.`restaurant_id` =".$post['ID']." AND $condition $rules_condition LIMIT $start, $limit";
	$result = mysql_query($sql);
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class = 'trans_navigation'><ol class='wp-paginate'>";
		//previous button
		if ($page > 1) 
			$pagination.= "<li><a href=\"?$parameters&page=$prev\" class = 'prev'></a></li>";
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li><span class=\"current page\" >$counter</span></li>";
				else
					$pagination.= "<li><a href=\"?$parameters&page=$counter\" class = 'page'>$counter</a></li>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li><span class=\"current page\">$counter</span></li>";
					else
						$pagination.= "<li><a href=\"?$parameters&page=$counter\" class = 'page'>$counter</a></li>";					
				}
				$pagination.= "<li>...</li>";
				$pagination.= "<li><a href=\"?$parameters&page=$lpm1\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"?$parameters&page=$lastpage\">$lastpage</a></li>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<li><a href=\"?$parameters&page=1\">1</a></li>";
				$pagination.= "<li><a href=\"?$parameters&page=2\">2</a></li>";
				$pagination.= "<li>...</li>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li><span class=\"current page\">$counter</span></li>";
					else
						$pagination.= "<li><a href=\"?$parameters&page=$counter\" class = 'page'>$counter</a></li>";					
				}
				$pagination.= "...";
				$pagination.= "<li><a href=\"?$parameters&page=$lpm1\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"?$parameters&page=$lastpage\">$lastpage</a></li>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<li><a href=\"?$parameters&page=1\" class = 'page'>1</a></li>";
				$pagination.= "<li><a href=\"?$parameters&page=2\" class = 'page'>2</a></li>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li><span class=\"current page\">$counter</span></li>";
					else
						$pagination.= "<li><a href=\"?$parameters&page=$counter\" class = 'page'>$counter</a></li>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<li><a href=\"?$parameters&page=$next\" class = 'next'></a></li>";
		$pagination.= "</ol></div>\n";		
	}
	global $booking_status;
	//$content = $wpdb->get_results("SELECT a.`date`,a.`id`,a.`type_id`,bc.name,b.category_id,rc.start_time,rc.end_time,u.user_login,u.user_email,tt.title FROM `wp_transactions` as a left join wp_bookings as b on b.id = a.ref_id left join wp_users as u on u.ID = a.user_id left join wp_booking_categories as bc on bc.id = b.category_id left join wp_restaurant_categories as rc on rc.booking_category_id = b.category_id left join wp_table_types as tt on tt.id = b.table_type_id where b.`restaurant_id` =".$post['ID'],ARRAY_A );
	$seatings = $wpdb->get_results("SELECT a.*,b.title as table_type FROM `wp_restaurant_seatings` as a left join wp_table_types as b on a.table_type_id = b.id WHERE a.`restaurant_id`=".$post['ID'].' ORDER BY b.title ASC',ARRAY_A);
	$booking_categories = $wpdb->get_results("select * from wp_booking_categories where id in ( SELECT b.parent_id as category FROM `wp_restaurant_categories` as a left join wp_booking_categories as b on a.booking_category_id = b.id WHERE a.`restaurant_id`=".$post['ID'].") ORDER BY display ASC",ARRAY_A);
?>
<!--body div start-->

	<div class="content_warp">
      	<div class="heading_warp">
        	<h1>Booking Report</h1>
        	<h2><a href="<?php echo get_bloginfo('url');?>">Home</a> &gt; Booking Report</h2>
      	</div>

      	<div class="transaction_search">
      		<form  method="get" action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
				<input type="hidden" name="inventory" value = "<?php echo $_GET['inventory'];?>"/>
				<input type="hidden" name="restaurant_id" value = "<?php echo $post['ID'];?>"/>
				<input type="hidden" name="search" value = "1"/>
				<h1>Search By</h1>
				<ul>
					<li>
						<select name = "transaction_type">
							<option value="">Select Type</option>
							<?php
								foreach($transaction_type as $key => $val){
									if(!empty($_GET['transaction_type']) && $_GET['transaction_type'] == $key){
										echo "<option selected='selected' value =".$key.">".$val."</option>";
									}else{
										echo "<option value =".$key.">".$val."</option>";
									}
								}
							?>
						</select>
					</li>
					<li>
						<select name = "category">
							<option value="">All Categories</option>
							<?php
								foreach($booking_categories as $booking_category){
									if(!empty($_GET['category']) && $_GET['category'] == $booking_category['id']){
										echo "<option selected = 'selected' value =".$booking_category['id'].">".$booking_category['name']."</option>";
									}else{
										echo "<option value =".$booking_category['id'].">".$booking_category['name']."</option>";
									}
								}
							?>
						</select>
					</li>
					<li class="long">
						<input type="text" name="from" id = 'from' class = "over_fileld datepicker field1" value = "<?php echo date(get_option('date_format'),strtotime($from));?>"/>
					</li>
					<li class="long">
						<input type="text" name="to" id = 'to' class = "datepicker over_fileld field1" value = "<?php echo date(get_option('date_format'),strtotime($to));?>"/>
					</li>
					<li class="long">
						<input type="text" name="id" class = "over_fileld field1" value = "<?php echo $_GET['id'];?>" placeholder = "Transaction ID"/>
					</li>
					<li>
						<input name="" type="image" src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH;?>/go.png" />
					</li>
				</ul>
				<ul>
					<li>
						<select name = "date_range" class = 'date_range'>
							<option value="">Choose Date Range</option>
							<?php
								$date_options = array(
													  't'=>'Today',
													  'wd'=>'Week to date',
													  'md'=>'Month to date',
													  'qd'=>'Quater to date',
													  'lw'=>'Last week',
													  'lm'=>'Last month',
													  'lq'=>'Last quater'
													  );
								foreach($date_options as $key=>$value){
									if(!empty($_GET['date_range']) && $_GET['date_range'] == $key){
										echo "<option selected = 'selected' value =".$key.">".$value."</option>";
									}else{
										echo "<option value =".$key.">".$value."</option>";
									}
								}
							?>
						</select>
					</li>
				</ul>
			</form>
      	</div>
		<div style = "float:left;margin-top:-3px;">
			<?php echo $pagination;?>
		</div>
		<div class="export_excel"><a href="<?php echo add_query_arg(array('inventory' => 'transaction'), get_rest_page_link()).'&report=booking&csv=true&id='.$post['ID'];?>">Export Report to Excel</a></div>
      	<div class="result">
      		<table border="0" cellspacing="0" cellpadding="0" class="rules_table pad_cell">
				<thead class = "result_heading">
					<tr>
						<th align="left" >S No.</th>
						<th align="left" >Transaction Date</th>
						<th align="left" >Transaction ID</th>
						<th align="left" >Booking Date & Time</th>
						<th align="left" >Day</th>
						<th align="left" >Category</th>
						<th align="center" >Seatings</th>
						<th align="left" >User Name</th>
						<th align="left" >Email</th>
						<th align="left" >Mobile</th>
						<th align="left" >Status</th>
						<th align="left" class = 'last'>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php $j= $start+1;;$i = 0; while($row = mysql_fetch_array($result)){?>    
						<tr <?php if($i%2 == 0):?>class="alternate"<?php endif;?>>
							<td align="left">
								<?php echo $j; $j++;?>
							</td>
							<td align="left" >
								<?php echo date(get_option('date_format'),strtotime($row['date']));?>
							</td>
							<td align="left" >
								<?php echo $row['id'];?>
							</td>
							<td align="left" >
								<?php echo date(get_option('date_format'),strtotime($row['booking_date']))." ".date(DIVATIMEFORMAT,strtotime($row['booking_time']));?>
							</td>
							<td align="left" >
								<?php echo date('D',strtotime($row['booking_date']));?>
							</td>
							<td align="left">
								<?php echo parent_category_name($row['category_id']);?>
							</td>
							<td align="center" >
								<?php echo $row['title'];?>
							</td>
							<td align="center">
								<?php echo get_usermeta( $current_user->ID, 'first_name')." ".get_usermeta( $current_user->ID, 'last_name');?>
							</td>
							<td align="center">
								<?php echo $row['user_email'];?>
							</td>
							<td align="center">
								<?php echo get_usermeta($row['ID'],'mobile');?>
							</td>
							<td align="left">
								<?php echo $booking_status[$row['booking_status']];?>
							</td>
							<td align="left" >
								<form method="post" action="<?php echo add_query_arg(array('inventory' => 'transaction'), get_rest_page_link()); ?>" onsubmit= "confirm('Are you sure you want to do this?')" >
									<input type = "hidden" name = "booking_id" value="<?php echo $row['booking_id'];?>">
									<input type = "hidden" name="no_show" class="no_show" value="false">
									<input type = "hidden" name="cancel" class="cancel" value="false">
									<input type = "hidden" name="confirmed" class="confirmed" value="false">
									<?php
										$time_diff = $wpdb->get_row("select time_to_sec(timediff(NOW(), '".$row['booking_date']." ".$row['booking_time']."' )) / 3600 as time",ARRAY_A);
										if($row['booking_status'] == 1 && $time_diff['time'] < 24 && $time_diff['time'] > 0){
											echo '<input name="no_show_submit" class="no_show_submit" type="image" src="'.DIVATEMPLATEPATH.'/images/no.gif" title = "No Show" alt=""/>';
										}
										if($row['booking_status']==1 || $row['booking_status']==5){
											echo '<input name="cancel_submit" class="cancel_submit" type="image" src="'.DIVATEMPLATEPATH.'/images/wrong.png" title = "Cancel" alt=""/>';
										}
										if($row['booking_status']==5){
											echo '<input name="confirmed_submit" class="confirmed_submit" type="image" src="'.DIVATEMPLATEPATH.'/images/tick.png" title = "Confirmed" alt=""/>';
										}
									?>
								</form>
							</td>
						</tr>
					<?php $i++; }?>
				</tbody>
			</table>
      	</div>
    </div>