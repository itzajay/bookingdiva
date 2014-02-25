<?php
	if(!empty($_POST['restaurant_id'])){
		//$wpdb->get_results("select * form wp_restaurant_categories where restaurant_id =".$_GET['restaurant_id']." AND ");
	}
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('.mobile_content').hide();
		$('.online_content').hide();
		$('.mobile_booking').click(function(){
			$(this).next('div.mobile_content').toggle();
			$('.online_content').hide();
		});
		$('.online_booking').click(function(){
			$('.mobile_content').hide();
		});
		
		$(".slideshow_images").fancybox({
			'titlePosition'		: 'inside',
			'transitionIn'		: 'none',
			'transitionOut'		: 'none'
		});

		$('.submit_booking').click(function(){
			var form = $(this).attr('id');
			$('.'+form).submit();
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
						if(data != null){
							current.parent().parent().parent().find('li.append_time').html(data);
						}
					}
				)
		});
	
		//$('ul.time').live('click',function(event){
		$('ul.time').delegate('a.confirm_booking','click', function(event){
			event.preventDefault();
			$('ul.time a').removeClass('clicked_confirmed');
			$(this).addClass('clicked_confirmed');
			var $current = $(this);
			var $reserve_restaurant_id = $current.attr('reserve_restaurant_id');
			var $reserve_time = $current.attr('reserve_time');
			var $table_type_id = $current.attr('table_type_id');
			var $reserve_date = $current.attr('reserve_date');
			var $reserve_slot = $current.attr('reserve_slot');
			var $reserve_discount = $current.attr('reserve_discount');
			var $reserve_table_type = $current.attr('reserve_table_type');
			var $jquery_time = $current.attr('jquery_time');
			var $restro = $current.attr('restro');
			
			$target = $current;
			var popID = $target.attr('rel'); //Get Popup Name
			var popURL = $target.attr('href'); //Get Popup href to define size
			<?php if (is_user_logged_in()){ ?>
				$('#' + popID + ' .popup_heading').html('<span>THIS RESERVATION IS VALID FOR</span> '+$reserve_table_type+' <span>PEOPLE AT</span> '+$restro+' <span>ON</span> '+$reserve_date+' <span>AT</span> '+$jquery_time);
			<?php }?>
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
		
		$('.popup_accept').click(function(){
			var $current = $('a.clicked_confirmed');
			$('div.accept').html('<img src = "<?php echo DIVATEMPLATEPATH."/".IMAGEPATH."/ajax-loader.gif"?>" style = "margin-left:88px;" />');
			var $reserve_restaurant_id = $current.attr('reserve_restaurant_id');
			var $reserve_time = $current.attr('reserve_time');
			var $table_type_id = $current.attr('table_type_id');
			var $reserve_date = $current.attr('reserve_date');
			var $reserve_slot = $current.attr('reserve_slot');
			var $reserve_discount = $current.attr('reserve_discount');
			var $reserve_table_type = $current.attr('reserve_table_type');

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
						add_booking:true
					},
					function(data){
						if(data != null){
							window.location = '<?php echo get_permalink(get_field('confirmation_page_id',THEMESETTINGS))."?booking_id=";?>'+data;
						}
					}
				)
		});
		//Close Popups and Fade Layer
		$('body').delegate('a.close, #fade, .cancel_button','click', function(event){
			event.preventDefault();
			$('#fade , .popup_block').fadeOut(function() {
				$('#fade, a.close').remove();  //fade them both out
			});
			return false;
		});
	});
</script>
<?php $cnt = 0;?>
<?php get_template_part( 'content', 'pagination_head' );?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
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
					<div class="popup_heading" style = "border-bottom:none;padding-bottom:0px;margin-bottom:0px;"><span>You need to be</span> logged in <span>to make a reservation. Please close this box, and click login or signup at the top right of the screen to make reservations.</span></div>
				</div>
			<?php }?>
			<?php $cnt++;?>
		</div>
<?php endwhile; ?>
<?php get_template_part( 'content', 'pagination_footer' );?>
<?php
	if(isset($_GET['new_register']) || isset($_POST['new_register'])):
?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('a#poplight[href^=#]').click(function() {
					var popID = $(this).attr('rel'); //Get Popup Name
					var popURL = $(this).attr('href'); //Get Popup href to define size
				
					//Pull Query & Variables from href URL
					var query= popURL.split('?');
					var dim= query[1].split('&');
					var popWidth = dim[0].split('=')[1]; //Gets the first query string value
				
					//Fade in the Popup and add close button
					$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a style = "display:inline;" href="<?php echo site_url('/');?>restaurants" class="close" ><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/close.png" style = "margin: -5px -5px 0 0;" class="btn_close" title="Close Window" alt="Close" /></a>');
				
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
				
				
				$('a#poplight').click();
			});
		</script>
		<a href = "#?w=675" rel='welcome_popup' id = 'poplight'></a>
		<div style = "display:none;" id = "welcome_popup" class="popup_block">
			<div class="welcome_popup">
				<div class="welcome_heading">
					<h1>Welcome to BookingDiva.com</h1>
					<h2>The Smartest Way To Wine and Dine</h2>
				</div>
				<div class="how_it_work">How It Works</div>
				<ul class="work_steps">
					<li>
						<h1>Step 1: Browse</h1>
						<p> Browse the various top restaurants offering discounts.</p>
					</li>
					<li>
						<h1>Step 2: Book</h1>
						<p>Find the Perfect Restaurant, then use BookingDiva (online or call in by phone) to make your reservation for FREE!</p>
					</li>
					<li>
						<h1>Step 3: Relish</h1>
						<p class="smile"> Enjoy your fabulous meal and tell others about it! :-)</p>
					</li>
				</ul>
				<div class="getstart"><a href="<?php echo site_url('/');?>restaurants"><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH;?>/getstarted.png" alt="" /></a></div>
			</div>
		</div>
<?php
	endif;
?>
<?php else : ?>
	<?php if(isset($_GET['s'])){?>
		<div class = 'no_found_data' style = "text-align:justify;">
			<span>Currently </span>we don't have Premier Restaurants Deals with your exact search criteria.<br/>
			<span>However, you may want to consider these </span>"somewhat close cuisine" DEALs listed BELOW.<br/>
			<span>Or you may want to </span>SEARCH FOR TABLE <span>box or </span> RE-SPELL YOUR SEARCH,<span> in the quick search box, to show better results.</span><br/>
			<span>We</span> are adding NEW RESTAURANTS AND DEALS DAILY<span> and hope to soon provide you with more "high quality deals."</span>
		</div>
	<?php }?>
	<?php get_template_part( 'suggestion'); ?>
<?php endif; ?>