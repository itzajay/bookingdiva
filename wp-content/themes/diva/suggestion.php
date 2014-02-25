<?php if(!empty($_GET['cuisine_type'])):
		$args = array();
		global $wpdb;
		$search_date = date('Y-m-d');
		if($_GET['date'] != false) {
			$search_date = date('Y-m-d',strtotime($_GET['date']));
		}
		$condition.=' where `date` ="'.$search_date.'"';
		$parent_id = get_current_parent_booking_id();
		if($_GET['time'] != false) {
			$parent_id = $_GET['time'];
		}
		$booking_ids = get_booking_ids($parent_id);
		if(!empty($booking_ids)){
			$condition.=' AND i.`booking_category_id` IN('.$booking_ids.')';
		}
		
		if(!empty($_GET['search'])){
			if($_GET['seating'] != false){
				if(!empty($_GET['seating'])) {
					$all_id = get_all_id();
					$condition.=' AND `table_type_id` in ('.$_GET['seating'].','.$all_id.')';
				}
			}
			$tax_condition=array();
			if(!empty($_GET['location'])) {
				$tax_condition[] = array(
										'taxonomy'=>'location',
										'field'=>'id',
										'terms'=>array($_GET['location'])
									);
			}
			if(!empty($_GET['cuisine_type'])) {
				$term_data = get_term_by( 'id', $_GET['cuisine_type'], 'cuisine', ARRAY_A);
				$query_data = $wpdb->get_results('select term_id from wp_term_taxonomy where taxonomy ="'.$term_data['taxonomy'].'" AND description = "'.$term_data['description'].'"');
				$cusine_id = "";
				foreach($query_data as $term_data){
					$cusine_id[]=$term_data->term_id;
				}
				$tax_condition[] = array(
										'taxonomy'=>'cuisine',
										'field'=>'id',
										'terms'=>$cusine_id,
										'operator'=>'IN'
									);
			}
			$args['tax_query']=$tax_condition;
			$args['meta_query']=$metaQuery;
		}
		$condition.=' AND no_of_tables > booked_tables';
		$seatings = $wpdb->get_results('SELECT i.*, rc.start_time, rc.end_time FROM `'.$wpdb->prefix.'inventory` as i left join wp_restaurant_categories as rc ON rc.restaurant_id = i.restaurant_id AND rc.booking_category_id = i.booking_category_id'.$condition.' GROUP BY `restaurant_id`',ARRAY_A);
		foreach($seatings as $seating){
			$postin[] = $seating['restaurant_id'];
		}
		if(empty($postin)){
			$postin[] = 0;
		}
		$args['post__in'] = $postin;
		$posts = get_posts($args);
		if(!empty($posts)){
	?>
			<div class = 'no_found_data' style = "text-align:justify;">
				<span>Currently </span>we don't have Premier Restaurants Deals with your exact search criteria.<br/>
				<span>However, you may want to consider these </span>"somewhat close cuisine" DEALs listed BELOW.<br/>
				<span>Or you may want to </span>CHANGE YOUR SEARCH options ABOVE,<span> (e.g. Broaden or Change the </span>>AREA,<span> or</span> DATE <span> criteria  above) </span>to show more Deals.<br/>
				<span>We</span> are adding NEW RESTAURANTS AND DEALS DAILY<span> and hope to soon provide you with more "high quality deals."</span>
			</div>
		<?php
		}
		foreach($posts as $post):
		?>
			<!--booking main div start-->
			<div class="restro_warp">
				<div class="restro_list_lt">
					<div class="restro_offer_div">
						<span><?php echo get_field('discount_percentage',$post->ID);?>% Off</span>
						<?php echo get_field('custom_message',THEMESETTINGS); ?>
					</div>
					<div class="restro_img_div">
						<?php if(get_field('thumb_image',$post->ID)):?>
							<div class="restro_img_lt"><img src="<?php echo get_field('thumb_image',$post->ID); ?>" alt="" /></div>
						<?php endif;?>
						<div class="view_restro">
							<?php
								$image_data = get_field('slideshow_images',$post->ID);
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
				</div>  
				<div class="restro_list_con">
					<div class="restro_con_head">
						<h1><?php the_title();?></h1>
						<ul>
							<li class="food" style = 'width:260px;'>
								<h2><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/listing_img/food_icon.jpg" alt="" /></h2>
								<h3><?php show_restaurant_terms('cuisine', $post->ID)?></h3>
							</li>
							<li class="rs" style = 'width:92px;'>
								<h2><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/listing_img/rs_icon.jpg" alt="" /></h2>
								<h3>
									<?php
										$val = get_field('cost_for_two',$post->ID);
										for($i=1;$i<=$val;$i++){
											echo "<del>&#2352;</del>";
											echo " ";
										}
									?>
								</h3>
							</li>
							<li class="food" style = 'width:162px;'>
								<h3 style="color:#FE7300;font-size:14px;"><b>Cost For Two:</b></h3>
								<h3><?php echo " Rs. ".get_field('cost_for_two_amount');?></h3>
							</li>
						</ul>
					</div>
					<div class="restro_con_head">
						<ul>
							<li class="food">
								<h2><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/beer.png" alt="" /></h2>
								<h3>
									<?php
										if(get_field('bar_or_no_bar')=="full_bar"){
											echo "Full Bar";
										}elseif(get_field('bar_or_no_bar')=="wine_and_beer_bar_only"){
											echo "Wine and Beer Bar Only";
										}elseif(get_field('bar_or_no_bar')=="no_bar"){
											echo "No Bar";
										}
									?>
								</h3>
							</li>
							<li class="rs">
								<h2><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/wi_fi.png" alt="" /></h2>
								<h3>
									<?php
										if(get_field('wifi')==true){
											echo "Wifi - Yes";
										}else{
											echo "Wifi - No";
										}
									?>
								</h3>
							</li>
							<li class="food">
								<h2><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/listing_img/location_icon.jpg" alt="" /></h2>
								<h3><?php show_restaurant_terms('location', $post->ID)?></h3>
							</li>
						</ul>
					</div>
					<div class="restro_sence_div">
						<?php
							$terms = get_the_terms($post->ID,"scene");
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
			
					<div class="restro_con_text">
						<h1>Its Cool Because:</h1>
						<?php echo get_field('its_cool_because_short',$post->ID);?>
					</div>
					<div class="list_share_area">
						<div class="list_share_div">
							<?php
								//$url = get_permalink($post->ID);
								//share_icon($url);
							?>
						</div>
						<div class="read_more"><a href="<?php the_permalink() ?>"><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/readmore.jpg" alt="" /></a></div>
					</div>    
				</div>
				<div class="restro_book_div">
					<h1><a>Limited offer: Book Now!</a></h1>
					<?php
						global $diva_time;
						global $all_table_types;
						$no_of_people = $wpdb->get_results("select a.*,b.title from wp_restaurant_seatings as a left join wp_table_types as b on b.id=a.table_type_id where restaurant_id = ".$post->ID,ARRAY_A);
					?>
					<ul>
						<li>
							<input type="hidden" class = 'reserve_restaurant_id' value ="<?php echo $post->ID;?>">
							<input type="hidden" class = 'reserve_discount' value ="<?php echo get_field('discount_percentage',$post->ID);?>">
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
							<h2 class="input_date" style = "margin-top:0px;">
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
						</li>
						<li class = 'append_time'>
							<?php
								$discount = get_field('discount_percentage',$post->ID);
								get_time_slots($post->ID,$table_value,$date_value,$time_value,$discount,$table_title);
							?>
						</li>
						<li>
							Grey boxes = Unavailable Times
						</li>
					</ul>
				</div>
			</div> 
			<div class='continue online_content popup_block' id = "popup_<?php echo $post->ID;?>"  style = "height:auto;">
				<?php if (is_user_logged_in()){ ?>
					<div class="popup_warp">
						<div class="popup_heading"></div>
						<div class="popup_content_div">
							<h2>General Terms</h2>
							<?php echo get_field('deals_terms',THEMESETTINGS);?>
							<?php echo get_field('deals_and_terms',$post->ID);?>
							<div class="accept">
								<input type="image" class = 'popup_accept'src ="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/accept.png" class = "submit_booking" id = "booking<?php echo $cnt;?>" />
							</div>
							<div class="cancle"> <strong>--- OR ---</strong><br/>
								<a href="#" class = 'cancel_button'>Cancel My Reservation</a>
							</div>
						</div>
					</div>
				 <?php }else{?>
					<div class="popup_warp">
						<div class="popup_heading">You Need to Sign-up/Login to make a Booking</div>
					</div>
				<?php }?>
				<?php $cnt++;?>
			</div>
	<?php endforeach;?>
<?php endif; ?>