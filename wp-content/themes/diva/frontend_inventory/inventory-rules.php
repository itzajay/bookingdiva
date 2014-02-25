<script type = "text/javascript">
	jQuery(function($) {
		$(".editme h2").editInPlace({
			saving_animation_color: "#FFFFCC",
			bg_over : "#dddddd",
			callback: function(idOfEditor, enteredText, orinalHTMLContent, settingsParams, animationCallbacks) {
				animationCallbacks.didStartSaving();
				setTimeout(animationCallbacks.didEndSaving, 2000);
				
				var recordid = $(this).parent().parent().attr("recordid");
				var categoryid = $(this).parent().parent().attr("categoryid");
				var restaurantid = $(this).parent().parent().attr("restaurantid");
				var tabletypeid = $(this).parent().parent().attr("tabletypeid");
				var day = $(this).parent().attr("day");
				
				$.post(
					window.location,
					{
						no_of_tables: enteredText,
						record_id:recordid,
						restaurant_id:restaurantid,
						booking_category_id:categoryid,
						table_type_id:tabletypeid,
						field:day,
						update_day_inventory:true
					},
					function(data){
						if(data != null){
							$('#message').html(data);
						}
					}
				)
				return enteredText;
			}
		});
	});
</script>
<?php 
	$current_user = wp_get_current_user();
	$post = get_posts_by_author();
	$categories = $wpdb->get_results("select * from	(select temp.category_id, temp.table_id, rules.id as rule_id from ( select a.booking_category_id as category_id, a.restaurant_id,b.table_type_id as table_id from wp_restaurant_categories as a, wp_restaurant_seatings as b where a.restaurant_id = ".$_GET['restaurant_id']." AND b.restaurant_id = ".$_GET['restaurant_id']." order by category_id, table_id) as temp LEFT Join wp_inventory_rules as rules on rules.booking_category_id = category_id And rules.table_type_id = table_id And rules.restaurant_id = temp.restaurant_id ) as b_temp where b_temp.rule_id is null;",ARRAY_A);
	$string = "";
	$sep = " ";
	foreach($categories as $category){
		$string.= $sep."('".$post['ID']."','".$category['category_id']."','".$category['table_id']."','0','0','0','0','0','0','0')";
		$sep = ", ";
	}
	$wpdb->get_row("INSERT INTO `".$wpdb->prefix."inventory_rules` (`restaurant_id`, `booking_category_id`, `table_type_id`, `sun`,`mon`,`tue`,`wed`,`thu`,`fri`,`sat`) VALUES ".$string.";");
	$rules_condition = "";
	if(!empty($_GET['seating'])){
		$rules_condition.= ' AND a.`table_type_id`='.$_GET['seating']." ";
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
		$rules_condition.= ' AND a.`booking_category_id` in('.$slot.') ';
	}
	$rules_records = $wpdb->get_results("SELECT a.*, b.title as table_type, c.name as parent_booking_category, rc.start_time, rc.end_time, f.name as booking_category FROM `wp_inventory_rules` as a left join wp_table_types as b on a.table_type_id = b.id left join wp_restaurant_categories as rc on rc.booking_category_id = a.booking_category_id AND rc.restaurant_id = a.restaurant_id left join wp_booking_categories as c on  c.id in ( select parent_id from wp_booking_categories where id = a.booking_category_id) left join wp_booking_categories as f on f.id = a.booking_category_id WHERE a.`restaurant_id`=".$post['ID'].$rules_condition." ORDER BY `booking_category_id`,`table_type_id`;",ARRAY_A);
	$seatings = $wpdb->get_results("SELECT a.*,b.title as table_type FROM `wp_restaurant_seatings` as a left join wp_table_types as b on a.table_type_id = b.id WHERE a.`restaurant_id`=".$post['ID'].' ORDER BY b.title ASC',ARRAY_A);
	$booking_categories = $wpdb->get_results("select * from wp_booking_categories where id in ( SELECT b.parent_id as category FROM `wp_restaurant_categories` as a left join wp_booking_categories as b on a.booking_category_id = b.id WHERE a.`restaurant_id`=".$post['ID'].") ORDER BY display ASC",ARRAY_A);
?>
<!--page content container start-->
    <div class="content_warp">
      <div class="heading_warp">
        <h1>Inventory Rules</h1>
        <h2><a href="<?php echo get_bloginfo('url');?>">Home</a> &gt; Inventory Rules</h2>
      </div>
      <div id="message"></div>
      <!--search transaction area start-->
      <div class="transaction_search">
		<form  method="get" action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
			<input type="hidden" name="inventory" value = "<?php echo $_GET['inventory'];?>"/>
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
				<select name = "seating">
					<option value="">All Seatings</option>
					<?php
						foreach($seatings as $seating){
							if(!empty($_GET['seating']) && $_GET['seating'] == $seating['table_type_id']){
								echo "<option selected='selected' value =".$seating['table_type_id'].">".$seating['table_type']."</option>";
							}else{
								echo "<option value =".$seating['table_type_id'].">".$seating['table_type']."</option>";
							}
						}
					?>
				</select>
			  </li>
			  <li>
				<input name="" type="image" src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH;?>/go.png" />
			  </li>
			</ul>
		</form>
      </div>
      <!--search transaction area end-->
      <div class="result">
		<table border="0" cellspacing="0" cellpadding="0" class = "rules_table">
			<thead class ="result_heading">
				<tr class='rules-page-heading'>
					<th class="left">Category</th>
					<th class="left">Slot</th>
					<th class="left">Time</th>
					<th class="center">Seatings</th>
					<th class="center">Sun</th>
					<th class="center">Mon</th>
					<th class="center">Tue</th>
					<th class="center">Wed</th>
					<th class="center">Thu</th>
					<th class="center">Fri</th>
					<th class="center last">Sat</th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 0;foreach($rules_records as $rules_record):?>
					<tr <?php if($i%2 == 0):?>class="alternate"<?php endif;?> recordid = "<?php echo $rules_record['id']?>" categoryid ="<?php echo $rules_record['booking_category_id']?>"  tabletypeid ="<?php echo $rules_record['table_type_id']?>" restaurantid ="<?php echo $rules_record['restaurant_id']?>">
						<td align="left"  class = "non_editable category">
							<?php echo $rules_record['parent_booking_category'];?>
						</td>
						<td align="left"  class = "non_editable slot">
							<?php echo $rules_record['booking_category'];?>
						</td>
						<td align="left"  class = "non_editable time">
							<?php echo date(DIVATIMEFORMAT,strtotime($rules_record['start_time']))." - ".date(DIVATIMEFORMAT,strtotime($rules_record['end_time']));?>
						</td>
						<td align="center"  class = "non_editable seating">
							<?php echo $rules_record['table_type'];?>
						</td>
						<td class = "editme" day="sun" align="center"  day="sun">
							<h2>
								<?php echo $rules_record['sun'];?>
							</h2>
						</td>
						<td class = "editme" day="mon" align="center"  day="mon">
							<h2>
								<?php echo $rules_record['mon'];?>
							</h2>
						</td>
						<td class = "editme" day="tue" align="center"  day="tue">
							<h2>
								<?php echo $rules_record['tue'];?>
							</h2>
						</td>
						<td class = "editme" day="wed" align="center"  day="wed">
							<h2>
								<?php echo $rules_record['wed'];?>
							</h2>
						</td>
						<td class = "editme" day="thu" align="center"  day="thu">
							<h2>
								<?php echo $rules_record['thu'];?>
							</h2>
						</td>
						<td class = "editme" day="fri" align="center"  day="fri">
							<h2>
								<?php echo $rules_record['fri'];?>
							</h2>
						</td>
						<td class = "editme" day="sat" align="center"  day="sat">
							<h2>
								<?php echo $rules_record['sat'];?>
							</h2>
						</td>
					</tr>
				<?php $i++;endforeach;?>
			</tbody>
		</table>
		<!--
        <ul>
          <li>
            <h1>1201</h1>
            <h2>Credit -250 </h2>
            <h3>09/02/212 </h3>
            <h5>&nbsp;</h5>
            <h5>250</h5>
            <h4>250</h4>
          </li>
          <li>
            <h1>1202</h1>
            <h2>Booking Chez Papa Restro</h2>
            <h3>10/02/2012 </h3>
            <h5>250</h5>
            <h5>&nbsp;</h5>
            <h4>0</h4>
          </li>
          <li>
            <h1>1203</h1>
            <h2>My Account Top up </h2>
            <h3>12/02/2012 </h3>
            <h5>&nbsp;</h5>
            <h5>500</h5>
            <h4>500</h4>
          </li>
          <li>
            <h1>1204</h1>
            <h2>Booking2 </h2>
            <h3>17/02/2012 </h3>
            <h5>250</h5>
            <h5>&nbsp;</h5>
            <h4>250</h4>
          </li>
          <li>
            <h1>1205</h1>
            <h2>Booking3</h2>
            <h3>23/02/2012 </h3>
            <h5>&nbsp;</h5>
            <h5>100</h5>
            <h4>100</h4>
          </li>
        </ul>
    	-->
      </div>
    </div>
    <!--page content container end-->