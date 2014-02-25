<script type="text/javascript">
	jQuery.noConflict();
	jQuery(function($) {
		$(document).ready(function(){
			var divWidth = $('div.movable').width();
			var tableWidth = $('div.movable>table').width();
			if (tableWidth<divWidth){
				$('div.movable>table').width(divWidth);
			}
			$(".editme h2").editInPlace({
				saving_animation_color: "#FFFFCC",
				bg_over : "#dddddd",
				callback: function(idOfEditor, enteredText, orinalHTMLContent, settingsParams, animationCallbacks) {
					animationCallbacks.didStartSaving();
					setTimeout(animationCallbacks.didEndSaving, 2000);
					var row_index = $(this).parent().parent().index();
					var $info_row = $('table#frozen tr').eq(row_index + 1);
					var category = $info_row.attr("category");
					var table = $info_row.attr("table");
					var restaurant = $info_row.attr("restaurant");
					var date = $(this).parent().attr('date');
					var curtd = $(this).parent();
					var orignalval = orinalHTMLContent;
					$.post(
						window.location,
						{
							no_of_tables: enteredText,
							cat_id:category,
							restaurant_id:restaurant,
							table_id:table,
							cur_date:date,
							update_date_inventory:true
						},
						function(data){
							if(data != null){
								var substr = data.split(',');
								if(substr[0]=='false'){
									curtd.html(orignalval);
								}
								$('div#message').html(substr[1]);
							}
						}
					)
					return enteredText;
				}
			});
			
			// If you need to remove an already bound editor you can call
		
			// > $(selectorForEditors).unbind('.editInPlace')
		
			// Which will remove all events that this editor has bound. You need to make sure however that the editor is 'closed' when you call this.
			
		});
	});
</script>
<?php 
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
	$content_query = "SELECT `table_type_id`, tt.title as table_name,a.`booking_category_id`, c.name as parent_category_name, f.name as category_name, rc.start_time, rc.end_time,  p.post_title from `".$wpdb->prefix."inventory` as a left join wp_booking_categories as c on c.id in ( select parent_id from wp_booking_categories where id = a.booking_category_id) left join wp_restaurant_categories as rc on rc.booking_category_id = a.booking_category_id  left join wp_booking_categories as f on f.id = a.booking_category_id  left join wp_table_types as tt on tt.id = a.table_type_id left join wp_posts as p on p.ID = a.restaurant_id  WHERE a.`restaurant_id` = ".$post['ID']." AND ".$condition.$content_rules_condition." GROUP BY a.`booking_category_id`,`table_type_id` ORDER BY a.`booking_category_id`,`table_type_id`;";
	$content = $wpdb->get_results($content_query,ARRAY_A);
	$query = "SELECT ".$sum." from ( select ".$string.",`booking_category_id`, `table_type_id`, `restaurant_id` from `".$wpdb->prefix."inventory` WHERE `restaurant_id` = ".$post['ID']." AND ".$condition.$date_rules_condition.") as temp GROUP BY `booking_category_id`,`table_type_id` ORDER BY `booking_category_id`,`table_type_id`;";
	$date_content = $wpdb->get_results($query,ARRAY_A);
	$seatings = $wpdb->get_results("SELECT a.*,b.title as table_type FROM `wp_restaurant_seatings` as a left join wp_table_types as b on a.table_type_id = b.id WHERE a.`restaurant_id`=".$post['ID'].' ORDER BY b.title ASC',ARRAY_A);
	$booking_categories = $wpdb->get_results("select * from wp_booking_categories where id in ( SELECT b.parent_id as category FROM `wp_restaurant_categories` as a left join wp_booking_categories as b on a.booking_category_id = b.id WHERE a.`restaurant_id`=".$post['ID'].") ORDER BY display ASC",ARRAY_A);
?>
<!--body div start-->

	<div class="content_warp">
      	<div class="heading_warp">
        	<h1>Inventory Override</h1>
        	<h2><a href="<?php echo get_bloginfo('url');?>">Home</a> &gt; Inventory Override</h2>
      	</div>
		<div id="message"></div>
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
						<select  name = "inventory_seating">
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
						<input type="text" name="from" class = "over_fileld datepicker field1" value = "<?php echo date(get_option('date_format'),strtotime($from));?>"/>
					</li>
					<li class="long">
						<input type="text" name="to" class = "datepicker over_fileld field1" value = "<?php echo date(get_option('date_format'),strtotime($to));?>"/>
					</li>
				  	<li>
						<input name="" type="image" src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH;?>/go.png" />
				  	</li>
				</ul>
			</form>
	    </div>

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
							<tr <?php if($i%2 == 0):?>class="alternate"<?php endif;?> category = "<?php echo $data['booking_category_id'];?>" table = "<?php echo $data['table_type_id'];?>" restaurant = "<?php echo $post['ID'];?>">
								<td align="left" valign="middle"  class = "non_editable category">
									<?php echo $data['parent_category_name'];?>
								</td>
								<td align="left" valign="middle" class = "non_editable slot">
									<?php echo $data['category_name'];?>
								</td>
								<td align="left" valign="middle" class = "non_editable time">
									<?php echo date(DIVATIMEFORMAT,strtotime($data['start_time']))." - ".date(DIVATIMEFORMAT,strtotime($data['end_time']));?>
								</td>
								<td  align="center" valign="middle" class = "non_editable seating">
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
								<th align="center" nowrap="nowrap" style="word-wrap:break-word;">
									<?php echo date(get_option('date_format'),strtotime($date['date']))."<br/>".date('D',strtotime($date['date']));?>
								</th>
							<?php endforeach;?>
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 0;
							foreach($date_content as $data){
								if($i%2 == 0){
									echo "<tr class='alternate'>";
								}else{
									echo "<tr>";
								}
								foreach($data as $key => $value){
									echo "<td class='editme' align='center' date='$key'><h2>$value</h2></td>";
								}
								echo "</tr>";
								$i++;
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
    </div>