<?php
	global $current_user;
	global $wpdb;
	$return_msg = "";
	if(!empty($_POST['reserve_restaurant_id']))
		$restro_id = $_POST['reserve_restaurant_id'];
	elseif(!empty($_POST['cancel_restaurant_id']))
		$restro_id = $_POST['cancel_restaurant_id'];
	$post_details = $wpdb->get_row('select post_title from wp_posts where ID = '.$restro_id,ARRAY_A);
	$booking_confirm = get_field('booking_confirmation',$restro_id);
	$booking_status =1;
	$trans_status =0;
	$save_inventory = true;
	if($booking_confirm == 2){
		$booking_status =5;
		$save_inventory = false;
	}
	if(!empty($_POST['cancel']) && $_POST['cancel'] == true){
		$booking_details = $wpdb->get_row("select * from wp_bookings where id = ".$_POST['cancel_booking_id'],ARRAY_A);
		$time_diff = $wpdb->get_row("select time_to_sec(timediff('".date('Y-m-d',strtotime($booking_details['booking_date']))." ".$booking_details['booking_time']."',NOW() )) / 3600 as time",ARRAY_A);
		if($time_diff['time']>2){
			$query1 = 'update wp_bookings set booking_status = 3 where id ='.$_POST['cancel_booking_id'];
			$wpdb->get_row($query1);
			$query2 = 'insert into wp_transactions (`user_id`,`ref_id`,`type_id`,`amount`,`date`) values ('.$_POST['cancel_user_id'].','.$_POST['cancel_booking_id'].',5,10,curdate())';
			$wpdb->get_row($query2);
			$query3 = 'update wp_inventory set booked_tables = booked_tables - 1 where `restaurant_id` ='.$_POST['cancel_restaurant_id'].' AND `booking_category_id` = '.$_POST['cancel_category_id'].' AND `table_type_id` = '.$_POST['cancel_table_type_id'].' AND `date` ="'.$_POST['cancel_booking_date'].'"';
			$wpdb->get_row($query3);
			
			$mail_content['restro_name'] = $post_details['post_title'];
			$mail_content['address'] = get_field('address',$restro_id);
			$mail_content['reserve_booking_id'] = $_POST['cancel_booking_id'];
			
			$mail_content['reserve_date'] = $booking_details['booking_date'];
			$mail_content['reserve_time'] = $booking_details['booking_time'];
			$mail_content['reserve_table_type'] = $booking_details['seatings'];
			
			$mail_content['user_name'] =  get_usermeta( $current_user->ID, 'first_name')." ".get_usermeta( $current_user->ID, 'last_name');
			if(empty($mail_content['user_name'])){
				$mail_content['user_name'] =  $current_user->display_name;
			}
			send_notifications('cancellation',$_POST['cancel_booking_id'],$mail_content);
			$return_msg = "Your booking has been canceled";
		}else{
			$return_msg = "You can cancel reservation before two hours.";
		}
	}

	global $booking_status;
	$status_query = 'b.booking_status = 1';
	if(isset($_GET['inventory']) && $_GET['inventory'] == 'booking_history')
		$status_query = 'NOT b.booking_status = 1';
	$current_bookings = $wpdb->get_results('SELECT p.post_title,t.id as tarns_id , b.* FROM `wp_bookings` as b left join wp_posts as p on p.ID = b.restaurant_id left join wp_table_types as tt on tt.id = b.table_type_id left join wp_transactions as t on t.ref_id = b.id AND t.type_id =3 where b.user_id ='.$current_user->ID.' AND '.$status_query,ARRAY_A);
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('a.poplight[href^=#]').click(function() {
			var popID = $(this).attr('rel'); //Get Popup Name
			var popURL = $(this).attr('href'); //Get Popup href to define size
		
			//Pull Query & Variables from href URL
			var query= popURL.split('?');
			var dim= query[1].split('&');
			var popWidth = dim[0].split('=')[1]; //Gets the first query string value
		
			//Fade in the Popup and add close button
			$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a style = "display:inline;" href="#" class="close"><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/close.png" class="btn_close" title="Close Window" alt="Close" /></a>');
		
			//Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
			var popMargTop = ($('#' + popID).height() + 80) / 2;
			var popMargLeft = ($('#' + popID).width() + 80) / 2;
		
			//Apply Margin to Popup
			$('#' + popID).css({
				'margin-top' : -popMargTop,
				'margin-left' : -popMargLeft
			});
		
			//Fade in Background
			$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
			$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer - .css({'filter' : 'alpha(opacity=80)'}) is used to fix the IE Bug on fading transparencies 
		
			return false;
		});
		
		//Close Popups and Fade Layer
		$('body').delegate('a.close, #fade, .cancel_button','click', function(event){
			event.preventDefault();
			$('#fade , .popup_block').fadeOut(function() {
				$('#fade, a.close').remove();  //fade them both out
			});
			return false;
		});
		
		$('.reserve_time,.reserve_table_type').change(function(){
			var $table_value = "";
			if($(this).attr('class') == 'reserve_date'){
				var $table_cond = $(this).parent().parent().parent().find('.table_type_id').val();
			}else{
				var $table_cond = $(this).parent().parent().find('.table_type_id').val();
			}
			var $table = '';
			if($table_cond != undefined)
			{
				$table_value =$(this).parent().parent().find('.table_type_id').val();
				$table = $(this).parent().parent().find('.reserve_table_type').val();
			}else{
				$table_value = $(this).parent().parent().find('.reserve_table_type').val();
				var $table_value_array =  $table_value.split('_');
				$table_value = $table_value_array[0];
				$table = $table_value_array[1];
			}
			var $time_value =$(this).parent().parent().find('.reserve_time').val();
			var $date_value =$(this).parent().parent().find('.reserve_date').val();
			var $post_id =$(this).parent().parent().find('.reserve_restaurant_id').val();
			var $discount =$(this).parent().parent().find('.reserve_discount').val();
			var current = $(this);
			$.post
				(
					window.location,
					{
						table_value: $table_value,
						time_value:$time_value,
						date_value:$date_value,
						post_id:$post_id,
						discount:$discount,
						table:$table,
						update_buttons:true
					},
					function(data){
						if(data != null){
							current.parent().parent().find('li.append_time').html(data);
						}
					}
				)
		});
		$('.reserve_date').change(function(){
			var $table_value = "";
			var $table_cond = $(this).parent().parent().parent().find('.table_type_id').val();
			
			var $table = '';
			if($table_cond != undefined)
			{
				$table_value =$(this).parent().parent().parent().find('.table_type_id').val();
				$table = $(this).parent().parent().parent().find('.reserve_table_type').val();
			}else{
				$table_value = $(this).parent().parent().parent().find('.reserve_table_type').val();
				var $table_value_array =  $table_value.split('_');
				$table_value = $table_value_array[0];
				$table = $table_value_array[1];
			}
			var $time_value =$(this).parent().parent().parent().find('.reserve_time').val();
			var $date_value =$(this).parent().parent().parent().find('.reserve_date').val();
			var $post_id =$(this).parent().parent().parent().find('.reserve_restaurant_id').val();
			var $discount =$(this).parent().parent().parent().find('.reserve_discount').val();
			var current = $(this);
			$.post
				(
					window.location,
					{
						table_value: $table_value,
						time_value:$time_value,
						date_value:$date_value,
						post_id:$post_id,
						discount:$discount,
						table:$table,
						update_buttons:true
					},
					function(data){
						current.parent().parent().parent().find('li.append_time').html(data);
					}
				)
		});
		
		//$('ul.time').live('click',function(event){
		$('ul.time').delegate('a.confirm_booking','click', function(event){
			event.preventDefault();
			var $current = $(this);
			var $reserve_restaurant_id = $current.attr('reserve_restaurant_id');
			var $reserve_time = $current.attr('reserve_time');
			var $table_type_id = $current.attr('table_type_id');
			var $reserve_date = $current.attr('reserve_date');
			var $reserve_slot = $current.attr('reserve_slot');
			var $reserve_discount = $current.attr('reserve_discount');
			var $reserve_table_type = $current.attr('reserve_table_type');
			
			var $change_booking_id = $current.parent().parent().parent().parent().find('.change_booking_id').val();
			var $change_trans_id = $current.parent().parent().parent().parent().find('.change_trans_id').val();
			var $change_category_id = $current.parent().parent().parent().parent().find('.change_category_id').val();
			var $change_user_id = $current.parent().parent().parent().parent().find('.change_user_id').val();
			var $change_discount = $current.parent().parent().parent().parent().find('.change_discount').val();
			var $change_old_booking_date = $current.parent().parent().parent().parent().find('.change_old_booking_date').val();
			var $change_old_table_type_id = $current.parent().parent().parent().parent().find('.change_old_table_type_id').val();
			
			$(this).parent().html('<img src = "<?php echo DIVATEMPLATEPATH."/".IMAGEPATH."/ajax-loader.gif"?>" style = "margin:0px 15px;" />');
			$.post
				(
					window.location,
					{
						reserve_restaurant_id:$reserve_restaurant_id ,
						reserve_table_type:$reserve_table_type ,
						reserve_time:$reserve_time ,
						table_type_id:$table_type_id ,
						reserve_date:$reserve_date,
						reserve_slot:$reserve_slot,
						reserve_discount:$reserve_discount,
						change_booking_id:$change_booking_id,
						change_trans_id:$change_trans_id,
						change_category_id:$change_category_id,
						change_user_id:$change_user_id,
						change_discount:$change_discount,
						change_old_booking_date:$change_old_booking_date,
						change_old_table_type_id:$change_old_table_type_id,
						change_booking:true
					},
					function(data){
						window.location.href = "<?php echo get_bloginfo('url')."/restaurant-owner/?inventory=current_bookings"?>";
					}
				)
		});
		$(".slideshow_images, #map").fancybox({
			'titlePosition'		: 'inside',
			'transitionIn'		: 'none',
			'transitionOut'		: 'none'
		});
	});
</script>
<?php $cnt = 0;?>
<div class="body_div">
    <!--page content container start-->
    <div class="content_warp">
		<div class="heading_warp">
			<h1>Current Booking/s</h1>
			<h2><a href="<?php echo get_bloginfo('url');?>">Home</a> &gt; Current Booking:</h2>
		</div>
		<div id="message"></div>
		<!-- body container start-->
		<?php foreach($current_bookings as $current_booking_info){;?>
			<div class="main_body_conainer">
				<div class="restro_deatail_warp">
					<div class="restro_list_lt">
						<div class="restro_offer_div">
							<span><?php echo get_field('discount_percentage',$current_booking_info['restaurant_id']);?>% Off</span>
							<?php echo get_field('custom_message',THEMESETTINGS); ?>
						</div>
						<div class="restro_img_div">
							<?php if(get_field('thumb_image',$current_booking_info['restaurant_id'])):?>
								<div class="restro_img_lt"><img src="<?php echo get_field('thumb_image',$current_booking_info['restaurant_id']); ?>" /></div>
							<?php endif;?>
							<h1>
								<span>Bar :</span>
								<?php
									if(get_field('bar_or_no_bar',$current_booking_info['restaurant_id'])=="full_bar"){
										echo "Full Bar";
									}elseif(get_field('bar_or_no_bar',$current_booking_info['restaurant_id'])=="wine_and_beer_bar_only"){
										echo "Wine and Beer Bar Only";
									}elseif(get_field('bar_or_no_bar',$current_booking_info['restaurant_id'])=="no_bar"){
										echo "No Bar";
									}
								?>
							</h1>
							<div class="view_restro">
								<?php
									$image_data = get_field('slideshow_images',$current_booking_info['restaurant_id']);
									$urls = array();
									$view_data = 'View Slide Show';
									if($image_data != ""){
										$urls = explode(',',$image_data);
									}
									if($urls != NULL){
										foreach($urls as $url){
											echo '<a href="'.$url.'" class = "slideshow_images" rel="lightbox'.$cnt.'">'.$view_data.'</a>';
											$view_data = '';
										}
									}
								?>
							</div>
						</div>
						<div class="left_other">
							<?php get_location_info($current_booking_info['restaurant_id']);?>
						</div>
					</div>  
					<div class="restro_list_con">
						<div class="restro_con_head">
							<?php if(get_field('main_image',$current_booking_info['restaurant_id'])):?>
								<div class="restro_main_img"><img src="<?php echo get_field('main_image',$current_booking_info['restaurant_id']); ?>" width="518" height="189" /></div>
							<?php endif;?>
							<ul>
								<li class="rs">
									<h2><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/listing_img/rs_icon.jpg" alt="" /></h2>
									<h3>
										<?php
											$val = get_field('cost_for_two',$current_booking_info['restaurant_id']);
											for($i=1;$i<=$val;$i++){
												echo "<del>&#2352;</del>";
												echo " ";
											}
										?>
									</h3>
								</li>
								<li class="food">
									<h2><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/listing_img/food_icon.jpg" alt="" /></h2>
									<h3><?php show_restaurant_terms('cuisine', $current_booking_info['restaurant_id'])?></h3>
								</li>
								<li class="food">
									<h2><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/listing_img/location_icon.jpg" alt="" /></h2>
									<h3><?php show_restaurant_terms('location', $current_booking_info['restaurant_id'])?></h3>
								</li>
							</ul>
						</div>
						<div class="restro_sence_div">
							<?php
								$terms = get_the_terms($current_booking_info['restaurant_id'],"scene");
								if ( $terms ){
									echo "<h1>Scene:</h1>";
									echo "<ul class='sence_list'>";
									foreach ( $terms as $term ) {
									  echo "<li>" . $term->name . "</li>";
									   
									}
									echo "</ul>";
								}
							?>
						</div>
						<div class="change_booking">
							<ul class="booking_bt">
								<li><a href = "#?w=400" rel='change_booking<?php echo $cnt;?>' class = 'poplight'><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/change_booking.png" /></a></li>
								<li><a href = "#?w=700" rel='cancel_booking<?php echo $cnt;?>' class = 'poplight'><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/cancle_booking.png" /></a></li>
							</ul>
							<dl class="confirmed_list">
								<dt>Name:</dt>
								<dd>
									<?php
										$first_name = get_usermeta($current_booking_info['user_id'],'first_name');
										$last_name = get_usermeta($current_booking_info['user_id'],'last_name');
										$name = $first_name." ".$last_name;
										echo $name;
									?>
								</dd>
							</dl>
							<dl class="confirmed_list">
								<dt>Valid for:</dt>
								<dd><?php echo $current_booking_info['seatings'];?> People</dd>
							</dl>
							<dl class="confirmed_list">         
								<dt>At:</dt>
								<dd><?php echo $current_booking_info['post_title'] ?></dd>
							</dl>
							<dl class="confirmed_list">
								<dt>On:</dt>
								<dd><?php echo date("l, F d, Y",strtotime($current_booking_info['booking_date']));?> <?php echo date(DIVATIMEFORMAT,strtotime($current_booking_info['booking_time']));?></dd>
							</dl>
							<dl class="confirmed_list">
								<dt>Deal:</dt>
								<dd><?php echo get_field('discount_percentage',$current_booking_info['restaurant_id']);?>% OFF ALL FOOD AND DRINKS (please read terms)</dd>
							</dl>
							<dl class="confirmed_list">
								<dt>Unconfirmed Number:</dt>
								<dd>wpj0</dd>
							</dl>
							<?php
								$deal = get_field('deals_and_terms',$current_booking_info['restaurant_id']);
								if(!empty($deal)){
									echo '<div class="list_con_text"><h1>Deals And Terms:</h1>';
									echo $deal;
									echo "</div>";
								}
							?>
						</div>
					</div>
					<div class="body_conainer_rt">
						<ul class="rt_banner">
							<li><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/inner_body_banner.jpg" alt="" /></li>
							<li><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/inner_body_banner2.jpg" alt="" /></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="display_list"></div>
			<br/><br/>
			<?php if(isset($_GET['inventory']) && $_GET['inventory'] == 'current_bookings'){?>
				<div style = "display:none;" id = "cancel_booking<?php echo $cnt;?>" class="popup_block">
					<div class="popup_warp">
						<div class="popup_heading">CANCEL YOUR RESERVATION</div>
						<div class="popup_content_div">
							<h1>Are you sure you want to cancel your reservation? Once canceled, it cannot be undone. You can also edit your booking. </h1>
							<div class="divider_line" style="margin-bottom:15px;" ></div>
							<form method="post" action="<?php echo add_query_arg(array('inventory' => 'current_bookings'), get_rest_page_link()); ?>">
								<input type = "hidden" name = "cancel_booking_id" value="<?php echo $current_booking_info['id'];?>">
								<input type = "hidden" name = "cancel_trans_id" value="<?php echo $current_booking_info['trans_id'];?>">
								<input type = "hidden" name = "cancel_category_id" value="<?php echo $current_booking_info['category_id'];?>">
								<input type = "hidden" name = "cancel_user_id" value="<?php echo $current_user->ID;?>">
								<input type = "hidden" name = "cancel_restaurant_id" value="<?php echo $current_booking_info['restaurant_id'];?>">
								<input type = "hidden" name = "cancel_booking_date" value="<?php echo $current_booking_info['booking_date'];?>">
								<input type = "hidden" name = "cancel_table_type_id" value="<?php echo $current_booking_info['table_type_id'];?>">
								<input type = "hidden" name="cancel" value=true>
								<div class="accept">
									<input type="image" src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/cancle_booking.png" />
								</div>
							</form>
						</div>
					</div>
				</div>
				<div style = "display:none;" id = "change_booking<?php echo $cnt;?>" class="popup_block restro_change">
					<?php
						global $diva_time;
						global $all_table_types;
						$no_of_people = $wpdb->get_results("select a.*,b.title from wp_restaurant_seatings as a left join wp_table_types as b on b.id=a.table_type_id where restaurant_id = ".$current_booking_info['restaurant_id'],ARRAY_A);
					?>
					<div class="popup_warp">
						<div class="popup_heading">CHANGE YOUR RESERVATION</div>
						<div class="popup_content_div">
							<div class="restro_book_div" style = 'float:left;'>
								<?php
									global $diva_time;
									global $all_table_types;
									$no_of_people = $wpdb->get_results("select a.*,b.title from wp_restaurant_seatings as a left join wp_table_types as b on b.id=a.table_type_id where restaurant_id = ".$current_booking_info['restaurant_id'],ARRAY_A);
								?>
								<ul class = 'no_decoration'>
									<li>
										<input type = "hidden" class = 'reserve_restaurant_id' value ="<?php echo $current_booking_info['restaurant_id'];?>">
										<?php
											$string = "";
											$table_type_id = "";
											$table_value = "";
											$selected = "SELECTED = SELECTED";
											foreach($no_of_people as $no){
												if(strtolower($no['title']) == 'all'){
													$table_type_id = $no['table_type_id'];
													$table_value = $table_type_id;
													$table_title = reset($all_table_types);
													foreach($all_table_types as $key => $val){
														$string.= "<option $selected value='".$val."'>".$val."</option>";
														$selected = "";
														if($selected != ""){
															$table_title = $val;
														}
													}
												}else{
													if(!empty($_GET['seating'])){
														$string.="<option $selected value='".$no['table_type_id']."_".$no['title']."'>".$no['title']."</option>";
														if($selected != ""){
															$table_value = $no['table_type_id'];
															$table_title = $no['title'];
														}
														$selected = "";
													}else{
														if($no['table_type_id'] == $_GET['seating']){
															$string.="<option $selected value='".$no['table_type_id']."_".$no['title']."'>".$no['title']."</option>";
															$table_value = $no['table_type_id'];
															$table_title = $no['title'];
														}
														else
															$string.="<option $selected value='".$no['table_type_id']."_".$no['title']."'>".$no['title']."</option>";
															if($selected != ""){
																$table_value = $no['table_type_id'];
																$table_title = $no['title'];
															}
															$selected = "";
													}
												}
											}
										?>
										<?php if($table_type_id != ""){?>
											<input name = 'table_type_id' type ="hidden" value = "<?php echo $table_type_id;?>" class = 'table_type_id' >
										<?php }?>
										<select class="select_reseve reserve_table_type" name = "reserve_table_type" id="standard-dropdown" style="width:176px;">
											<?php echo $string;?>
										</select>
									</li>
									<li>
										<h2 class="input_date" style = "margin:0px;padding-left:0px;">
											<?php
												$date_value = date(get_option('date_format'));
												if(!empty($_GET['date'])){
													$date_value = $_GET['date'];
												}
											?>
											<input class="select_reseve reserve_date datepickerRange field1" value="<?php echo $date_value;?>" type = "text" name = "reserve_date">
										</h2>
									</li>
									<li>
										<select id="standard-dropdown" name = "reserve_time" class="select_reseve reserve_time" style="width:176px;">
											<?php
												$time_value = get_current_parent_booking_id();
												if(!empty($_GET['time'])) {
													$time_value = $_GET['time'];
												}
												$terms = $wpdb->get_results("select * from `".$wpdb->prefix."booking_categories` where parent_id <= 0 ORDER BY display");
												foreach($terms as $term){
													if($time_value == $term->id) {
														echo "<option SELECTED = 'SELECTED' value="."'".$term->id."'>".$term->name."</option>";
													}else{
														echo "<option value="."'".$term->id."'>".$term->name."</option>";
													}
												}
											?>
										</select>
										<input type = "hidden" class = "change_booking_id" value="<?php echo $current_booking_info['id'];?>" />
										<input type = "hidden" class = "change_trans_id" value="<?php echo $current_booking_info['trans_id'];?>" />
										<input type = "hidden" class = "change_category_id" value="<?php echo $current_booking_info['category_id'];?>" />
										<input type = "hidden" class = "change_user_id" value="<?php echo $current_user->ID;?>" />
										<input type = "hidden" class = "change_discount" value="<?php echo get_field('discount_percentage',$current_booking_info['restaurant_id']);?>" />
										<input type = "hidden" class = "change_old_booking_date" value="<?php echo $current_booking_info['booking_date'];?>" />
										<input type = "hidden" class = "change_old_table_type_id" value="<?php echo $current_booking_info['table_type_id'];?>" />
										<input type = "hidden" class = 'reserve_discount' value ="<?php echo get_field('discount_percentage',$current_booking_info['restaurant_id']);?>" />
									</li>
									<li class = 'append_time'>
										<?php
											$discount = get_field('discount_percentage',$current_booking_info['restaurant_id']);
											get_time_slots($current_booking_info['restaurant_id'],$table_value,$date_value,$time_value,$discount,$table_title);
										?>
									</li>
								</ul>
							</div>
							<?php /*?>
							<form method="post" action="<?php echo add_query_arg(array('inventory' => 'current_bookings'), get_rest_page_link()); ?>">
								<input type = "hidden" name = "change_booking_id" value="<?php echo $current_booking_info['id'];?>">
								<input type = "hidden" name = "change_trans_id" value="<?php echo $current_booking_info['trans_id'];?>">
								<input type = "hidden" name = "change_category_id" value="<?php echo $current_booking_info['category_id'];?>">
								<input type = "hidden" name = "change_user_id" value="<?php echo $current_user->ID;?>">
								<input type = "hidden" name = "reserve_restaurant_id" value="<?php echo $current_booking_info['restaurant_id'];?>">
								<input type = "hidden" name = "change_discount" value="<?php echo get_field('discount_percentage',$current_booking_info['restaurant_id']);?>">
								<input type = "hidden" name = "change_old_booking_date" value="<?php echo $current_booking_info['booking_date'];?>">
								<input type = "hidden" name = "change_old_table_type_id" value="<?php echo $current_booking_info['table_type_id'];?>">
								<input type = "hidden" name="change" value=true>
								<center><h1>EDIT YOUR RESERVATION</h1></center>
								<br/>
								<ul class = "find_list advace_search">
									<li style="background:none;">
										<h2>
											<?php
												$string = "";
												$table_type_id = "";
												foreach($no_of_people as $no){
													if($no['title'] == 'All'){
														$table_type_id = $no['table_type_id'];
														foreach($all_table_types as $key => $val){
															$string.= "<option value='".$val."'>".$val."</option>";
														}
													}else{
														$string.="<option value='".$no['table_type_id']."_".$no['title']."'>".$no['title']."</option>";
													}
												}
											?>
											<?php if($table_type_id != ""){?>
												<input name = 'table_type_id' type ="hidden" value = "<?php echo $table_type_id;?>">
											<?php }?>
											<select class="" name = "reserve_table_type">
												<option value="">Select No. of People</option>
												<?php echo $string;?>
											</select>
										</h2>
									</li>
									<li style="background:none;">
										<h1 style="margin-top:7px;">For</h1>
									</li>
									<li style="background:none;">
										<h2 class="input_date" style="margin-top:0px;">
											<input type = "text" name = "reserve_date" class ="select_reseve datepickerRange" value = "Select Date" >
										</h2>
									</li>
									<li style="background:none;">
										<h1 style="margin-top:7px;">At</h1>
									</li>
									<li style="background:none;">
										<h2>
											<select name = "reserve_time" class="">
												<?php
													foreach($diva_time as $key => $value){
														echo "<option value = '".$key."'>".$value."</option>";
													}
												?>
											</select>
										</h2>
									</li>
								</ul>
								<div class="accept">
									<input type="image" src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/change_booking.png" />
								</div>
							</form>
							<?php */?>
						</div>
					</div>
				</div>
			<?php $cnt++;}?>
		<?php }?>
    </div>
    <!-- body container end -->
</div>