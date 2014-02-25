<?php
	global $booking_status;
	$status_query = 'b.booking_status != 1';
	$current_bookings = $wpdb->get_results('SELECT p.post_title,t.id as tarns_id , b.* FROM `wp_bookings` as b left join wp_posts as p on p.ID = b.restaurant_id left join wp_table_types as tt on tt.id = b.table_type_id left join wp_transactions as t on t.ref_id = b.id AND t.type_id =3 where b.user_id ='.$current_user->ID.' AND '.$status_query,ARRAY_A);
?>
<?php $cnt = 1;?>
<div class="content_warp">
    <div class="heading_warp">
        <h1>Booking History</h1>
        <h2><a href="<?php echo get_bloginfo('url');?>">Home</a> &gt; Booking History</h2>
    </div>
	<div id="message"></div>
	<div class="result">
		<table border="0" cellspacing="0" cellpadding="0" class = "rules_table pad_cell">
			<thead class ="result_heading">
				<tr class='rules-page-heading'>
					<th valign="middle" class="left">
						Serial No.
					</th>
					<th valign="middle" class="left">
						Valid For
					</th>
					<th valign="middle" class="left">
						At
					</th>
					<th valign="middle" class="left">
						On
					</th>
					<th valign="middle" class="center">
						Type
					</th>
					<th valign="middle" class="center">
						View
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($current_bookings as $current_booking_info){?>
					<tr>
						<td align="left" valign="middle" >
							<?php echo $cnt;?>
						</td>
						<td align="left" valign="middle" >
							<?php echo $current_booking_info['seatings'];?> People
						</td>
						<td align="left" valign="middle" >
							<?php echo $current_booking_info['post_title'] ?>
						</td>
						<td align="left" valign="middle" >
							<?php echo date("l, F d, Y",strtotime($current_booking_info['booking_date']));?> <?php echo date(DIVATIMEFORMAT,strtotime($current_booking_info['booking_time']));?>
						</td>
						<td align="center" valign="middle">
							<?php echo $booking_status[$current_booking_info['booking_status']]; ?>
						</td>
						<td align="center" valign="middle">
							<a href="<?php echo add_query_arg(array('inventory' => 'booking_details','booking_id'=>$current_booking_info['id']), get_permalink()); ?>"><img src = "<?php echo DIVATEMPLATEPATH."/".IMAGEPATH;?>/search_img.jpg"></a>
						</td>
					</tr>
				<?php $cnt++;}?>
			</tbody>
		</table>
	</div>
 </div>