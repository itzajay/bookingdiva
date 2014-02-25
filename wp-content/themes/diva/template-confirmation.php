<?php
/*
Template Name: Confirmation
*/
?>
<?php get_header();?>
<?php
	$confirmed_bookings = $wpdb->get_row('SELECT p.post_title, b.* FROM `wp_bookings` as b left join wp_posts as p on p.ID = b.restaurant_id left join wp_table_types as tt on tt.id = b.table_type_id  where b.id ='.$_GET['booking_id'],ARRAY_A);
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#message').append("<?php echo $return_msg;?>");
		$(".slideshow_images, #map").fancybox({
			'titlePosition'		: 'inside',
			'transitionIn'		: 'none',
			'transitionOut'		: 'none'
		});
	});
</script>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="body_div">
		<!--page content container start-->
		<div class="content_warp">
			<div class="heading_warp">
				<h1>
					<?php
						if($confirmed_bookings['booking_status']==1){
							echo"Congratulations, Your Booking is Confirmed! :-)";
						}elseif($confirmed_bookings['booking_status']==5){
							echo "Your Booking is Pending Confirmation";
						}
					?>
				</h1>
				<h2><a href="<?php echo get_bloginfo('url');?>">Home</a> >> Restro >> <?php echo $booking_status[$confirmed_bookings['booking_status']];?></h2>
			</div>
			<div id ="message"></div>
			<!-- body container start-->
			<div class="main_body_conainer">
				<div class="detail_lt_div">
					<div class="display_list_lt">
						<div class="display_img_div"><img src="<?php echo get_field('thumb_image',$confirmed_bookings['restaurant_id']); ?>" />
						</div>
						<div class="left_other">
							<?php get_location_info($confirmed_bookings['restaurant_id']);?>
						</div>
					</div>
					<div class="detail_content_div">
						<?php if($confirmed_bookings['booking_status']==5){	?>
							<div class="list_con_text" style = "margin-bottom:0px;">
								<h1 style= "font-size:14px;">
									As soon as the Restaurant Confirms this booking we will inform you by email/sms.
								</h1>
								<p>
									Note:  Email/SMS preferences can be set by you at any time in your "My Account" Section.
								</p>
							</div>
						<?php }?>
						<div class="list_con_text">
							<dl class="confirmed_list">
								<dt>Name:</dt>
								<dd>
									<?php
										$first_name = get_usermeta($confirmed_bookings['user_id'],'first_name');
										$last_name = get_usermeta($confirmed_bookings['user_id'],'last_name');
										$name = $first_name." ".$last_name;
										echo $name;
									?>
								</dd>
							</dl>
							<dl class="confirmed_list">
								<dt>Valid for:</dt>
								<dd><?php echo $confirmed_bookings['seatings']." People";?></dd>
							</dl>
							<dl class="confirmed_list">         
								<dt>At:</dt>
								<dd><?php echo $confirmed_bookings['post_title']; ?></dd>
							</dl>
							<dl class="confirmed_list">
								<dt>On:</dt>
								<dd><?php echo date("l, F d, Y",strtotime($confirmed_bookings['booking_date']));?> <?php echo date(DIVATIMEFORMAT,strtotime($confirmed_bookings['booking_time']));?></dd>
							</dl>
							<dl class="confirmed_list">
								<dt>Deal:</dt>
								<dd><?php echo $confirmed_bookings['discount'];?>% OFF ALL FOOD AND DRINKS (please read terms)</dd>
							</dl>
							<dl class="confirmed_list">
								<dt>Booking Number:</dt>
								<dd><?php echo $confirmed_bookings['id'];?></dd>
							</dl>
							<dl class="confirmed_list">
								<dt>Booking Status:</dt>
								<dd><?php echo $booking_status[$confirmed_bookings['booking_status']];?></dd>
							</dl>
						</div>
						<?php
							$deal = get_field('deals_and_terms',$confirmed_bookings['restaurant_id']);
							if(!empty($deal)){
								echo '<div class="list_con_text"><h1>Deals And Terms:</h1>';
								echo $deal;
								echo "</div>";
							}
						?>
						<div class="list_con_text">
							<h1>TO CHANGE OR CANCEL YOUR RESERVATION:</h1>
							<ul class="bullet_lit">
								<li> Visit you're My Account page to change your reservation. You may change your
									reservation up to 2 hours in advance for a full refund. After that you will forfeit your
									booking fee.
								</li>
								<li> Please note: There is no need to call the restaurant after changing or canceling a
									reservation through our website or BookingDiva phone assistant.
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="body_conainer_rt">
					<div class="booking_div">
						<div class="save_date">
							<div class="save_head">Save the Date</div>
								<?php
									$url = get_permalink($confirmed_bookings['restaurant_id']);
									share_icon_confirmation($url);
								?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- body container end -->
	</div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>