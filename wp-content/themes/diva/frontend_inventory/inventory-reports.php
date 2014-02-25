<script type="text/javascript">
	jQuery.noConflict();
	jQuery(function($) {
		$(document).ready(function(){
			var divWidth = $('div.movable').width();
			var tableWidth = $('div.movable>table').width();
			if (tableWidth<divWidth){
				$('div.movable>table').width(divWidth);
			}
			// If you need to remove an already bound editor you can call
		
			// > $(selectorForEditors).unbind('.editInPlace')
		
			// Which will remove all events that this editor has bound. You need to make sure however that the editor is 'closed' when you call this.
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
		});
	});
</script>
<?php
	$current_user = wp_get_current_user();
	$post = get_posts_by_author();
	$sum ="";
	
	$from = date("Y-m-d");
	$nextweek  = mktime(0, 0, 0, date("m")  , date("d")+7, date("Y"));
	$to = date("Y-m-d",$nextweek);
	if(!empty($_GET['search'])){
		$from = date('Y-m-d',strtotime($_GET['from']));
		$to = date('Y-m-d',strtotime($_GET['to']));
		if(!empty($_GET['inventory_seating'])){
			$date_rules_condition.= ' AND `table_type_id`='.$_GET['inventory_seating']." ";
			$content_rules_condition.= ' AND a.`table_type_id`='.$_GET['inventory_seating']." ";
		}
		if(!empty($_GET['category'])){
			$slot = "";
			$slots = $wpdb->get_results("select id from wp_booking_categories where parent_id=".$_GET['category'],ARRAY_N);
			$sep = "";
			foreach($slots as $key => $value){
				foreach ($value as $key1 => $value1){
					$slot.= $sep.$value1;
					$sep = ", ";
				}
			}
			$date_rules_condition.= ' AND `booking_category_id` in('.$slot.') ';
			$content_rules_condition.= ' AND a.`booking_category_id` in('.$slot.') ';
		}
	}
	if(!empty($from) && !empty($to)){
		$condition = " `date` BETWEEN '".$from."' AND '".$to."'";
	}elseif(empty($from ) && !empty($to)){
		$condition = " `date` <= '".$to."'";
	}elseif(empty($to ) && !empty($from)){
		$condition = " `date` >= '".$from."'";
	}
	$date_query = "SELECT `date` from `".$wpdb->prefix."inventory` WHERE `restaurant_id` = ".$post['ID']." AND ".$condition.$date_rules_condition." GROUP BY `date` ORDER BY `date` ASC;";
	$dates = $wpdb->get_results($date_query,ARRAY_A);
	$sep = '';
	foreach($dates as $date):
		$sum.= $sep."sum(`".$date['date']."`)as `".$date['date']."`";
		$string.= $sep."if ( date = '".$date['date']."', no_of_tables, 0) as '".$date['date']."'";
		$sep=', ';
	endforeach;
	$query = "SELECT ".$sum." from ( select ".$string.",`booking_category_id`, `table_type_id`, `restaurant_id` from `".$wpdb->prefix."inventory` WHERE `restaurant_id` = ".$post['ID']." AND ".$condition.$date_rules_condition.") as temp GROUP BY `booking_category_id`,`table_type_id` ORDER BY `booking_category_id`,`table_type_id`;";
	//echo $query."<br/><br/>";
	$date_content = $wpdb->get_results($query,ARRAY_A);
	//debug($date_content);
	$sum = "";
	$string = "";
	$sep="";
	foreach($dates as $date):
		$sum.= $sep."sum(`".$date['date']."`)as `".$date['date']."`";
		$string.= $sep."if ( date = '".$date['date']."', booked_tables, 0) as '".$date['date']."'";
		$sep=', ';
	endforeach;
	$content_query = "SELECT `table_type_id`, tt.title as table_name,a.`booking_category_id`, c.name as parent_category_name, f.name as category_name, rc.start_time, rc.end_time,  p.post_title from `".$wpdb->prefix."inventory` as a left join wp_booking_categories as c on c.id in ( select parent_id from wp_booking_categories where id = a.booking_category_id) left join wp_restaurant_categories as rc on rc.booking_category_id = a.booking_category_id  left join wp_booking_categories as f on f.id = a.booking_category_id  left join wp_table_types as tt on tt.id = a.table_type_id left join wp_posts as p on p.ID = a.restaurant_id  WHERE a.`restaurant_id` = ".$post['ID']." AND ".$condition.$content_rules_condition." GROUP BY a.`booking_category_id`,`table_type_id` ORDER BY a.`booking_category_id`,`table_type_id`;";
	$content = $wpdb->get_results($content_query,ARRAY_A);
	$query1 = "SELECT ".$sum." from ( select ".$string.",`booking_category_id`, `table_type_id`, `restaurant_id` from `".$wpdb->prefix."inventory` WHERE `restaurant_id` = ".$post['ID']." AND ".$condition.$date_rules_condition.") as temp GROUP BY `booking_category_id`,`table_type_id` ORDER BY `booking_category_id`,`table_type_id`;";
	//echo $query1;
	$date_content1 = $wpdb->get_results($query1,ARRAY_A);
	//debug(count($date_content1));
	$seatings = $wpdb->get_results("SELECT a.*,b.title as table_type FROM `wp_restaurant_seatings` as a left join wp_table_types as b on a.table_type_id = b.id WHERE a.`restaurant_id`=".$post['ID'].' ORDER BY b.title ASC',ARRAY_A);
	$booking_categories = $wpdb->get_results("select * from wp_booking_categories where id in ( SELECT b.parent_id as category FROM `wp_restaurant_categories` as a left join wp_booking_categories as b on a.booking_category_id = b.id WHERE a.`restaurant_id`=".$post['ID'].") ORDER BY display ASC",ARRAY_A);
?>
<!--body div start-->

	<div class="content_warp">
      <div class="heading_warp">
        <h1>Inventory Summary Report</h1>
        <h2><a href="<?php echo get_bloginfo('url');?>">Home</a> &gt; Inventory Summary Report</h2>
      </div>
      <!--search transaction area start-->
      <div class="transaction_search">
		<form  method="get" action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
			<input type="hidden" name="inventory" value = "<?php echo $_GET['inventory'];?>"/>
			<input type="hidden" name="restaurant_id" value = "<?php echo $post['ID'];?>"/>
			<input type="hidden" name="search" value = "1"/>
			<h1>Search By</h1>
			<ul>
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
				<li>
					<select name = "inventory_seating">
						<option value="">All Seatings</option>
						<?php
							foreach($seatings as $seating){
								if(!empty($_GET['inventory_seating']) && $_GET['inventory_seating'] == $seating['table_type_id']){
									echo "<option selected='selected' value =".$seating['table_type_id'].">".$seating['table_type']."</option>";
								}else{
									echo "<option value =".$seating['table_type_id'].">".$seating['table_type']."</option>";
								}
							}
						?>
					</select>
				</li>
				<li class="long">
					<input type="text" name="from" id = 'from' class="over_fileld datepicker field1" value = "<?php echo date(get_option('date_format'),strtotime($from));?>"/>
				</li>
				<li class="long">
					<input type="text" name="to" id = 'to' class = "datepicker over_fileld field1" value = "<?php echo date(get_option('date_format'),strtotime($to));?>"/>
				</li>
				<li>
					<?php
						$booking = "";
						$inventory = "";
						$both = "";
						if($_GET['show']=='booking'){
							$booking = 'selected = "selected"';
						}elseif($_GET['show'] == 'inventory'){
							$inventory = 'selected = "selected"';
						}else{
							$both = 'selected = "selected"';
						}
					?>
					<select name = "show">
						<option value="inventory" <?php echo $inventory?>>Inventory</option>
						<option value="booking" <?php echo $booking?>>Booking</option>
						<option value="both" <?php echo $both?>>Both</option>
					</select>
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
	  <?php /*?><div class="export_excel"><a href="<?php echo add_query_arg(array('inventory' => 'reports'), get_rest_page_link()).'&report=inventory&csv=true&id='.$post['ID'];?>">Export Report to Excel</a></div><?php */?>
      	<!--search transaction area end-->
      	<div class="result">
	      	<div class = "frozen">
				<table width="270" border="0" cellspacing="0" cellpadding="0" id="frozen" class="rules_table inventory_frozen_table">
					<thead class="result_heading">
						<tr>
							<th class="left">Category</th>
							<th class="left">Slot</th>
							<th class="left">Time</th>
							<th class="center">Seatings</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 0; foreach($content as $data):?>    
							<tr <?php if($i%2 == 0):?>class="alternate"<?php endif;?>>
								<td align="left" class = 'category'>
									<?php echo $data['parent_category_name'];?>
								</td>
								<td align="left" class = 'slot'>
									<?php echo $data['category_name'];?>
								</td>
								<td align="left" class = 'time'>
									<?php echo date(DIVATIMEFORMAT,strtotime($data['start_time']))." - ".date(DIVATIMEFORMAT,strtotime($data['end_time']));?>
								</td>
								<td align="center" class = 'seating'>
									<?php echo $data['table_name'];?>
								</td>
							</tr>
						<?php $i++;endforeach;?>
					</tbody>
				</table>
			</div>
			<div class="movable">
				<table border="0" cellspacing="0" cellpadding="0" id="movable" class="rules_table inventory_frozen_table">
					<thead class="result_heading">
						<tr>
							<?php foreach($dates as $date):?>
								<th align="center" valign="middle" nowrap="nowrap" style="word-wrap:break-word;">
									<?php echo date(get_option('date_format'),strtotime($date['date']))."<br/>".date('D',strtotime($date['date']));?>
								</th>
							<?php endforeach;?>
						</tr>
					</thead>
					<tbody>
						<?php
							$j = 0;
							$i = 0;
							foreach($date_content as $data){
								if($i%2 == 0){
									echo "<tr class='alternate'>";
								}else{
									echo "<tr>";
								}
								foreach($data as $key => $value){
									if($_GET['show'] == 'inventory'){
										echo "<td width='90' align='center' valign='middle' >".$value."</td>";
									}elseif($_GET['show'] == 'booking'){
										echo "<td width='90' align='center' valign='middle' ><font style = 'color:green;'>".$date_content1[$j][$key]."</font></td>";
									}else{
										echo "<td width='90' align='center' valign='middle' >".$value." - <font style = 'color:green;'>".$date_content1[$j][$key]."</font></td>";
									}
								}
								if($j<count($date_content1))
								$j++;
								echo "</tr>";
								$i++;
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
		</div>