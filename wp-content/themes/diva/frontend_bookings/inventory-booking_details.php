<?php
	global $booking_status;
	$booking_details = $wpdb->get_row('SELECT p.post_title,t.id as tarns_id , b.* FROM `wp_bookings` as b left join wp_posts as p on p.ID = b.restaurant_id left join wp_table_types as tt on tt.id = b.table_type_id left join wp_transactions as t on t.ref_id = b.id AND t.type_id =3 where b.user_id ='.$current_user->ID.' AND b.id = '.$_GET['booking_id'],ARRAY_A);
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
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
			<h1>Booking Deatils</h1>
			<h2><a href="<?php echo get_bloginfo('url');?>">Home</a> &gt; Booking Deatils</h2>
		</div>
		<!-- body container start-->
		<div class="main_body_conainer">
			<div class="restro_deatail_warp">
				<div class="restro_list_lt">
					<div class="restro_offer_div">
						<span><?php echo get_field('discount_percentage',$booking_details['restaurant_id']);?>% Off</span>
						<?php echo get_field('custom_message',THEMESETTINGS); ?>
					</div>
					<div class="restro_img_div">
						<?php if(get_field('thumb_image',$booking_details['restaurant_id'])):?>
							<div class="restro_img_lt"><img src="<?php echo get_field('thumb_image',$booking_details['restaurant_id']); ?>" /></div>
						<?php endif;?>
						<h1>
							<span>Bar :</span>
							<?php
								if(get_field('bar_or_no_bar',$booking_details['restaurant_id'])=="full_bar"){
									echo "Full Bar";
								}elseif(get_field('bar_or_no_bar',$booking_details['restaurant_id'])=="wine_and_beer_bar_only"){
									echo "Wine and Beer Bar Only";
								}elseif(get_field('bar_or_no_bar',$booking_details['restaurant_id'])=="no_bar"){
									echo "No Bar";
								}
							?>
						</h1>
						<div class="view_restro">
							<?php
								$image_data = get_field('slideshow_images',$booking_details['restaurant_id']);
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
						<?php get_location_info($booking_details['restaurant_id']);?>
					</div>
				</div>  
				<div class="restro_list_con">
					<div class="restro_con_head">
						<?php if(get_field('main_image',$booking_details['restaurant_id'])):?>
							<div class="restro_main_img"><img src="<?php echo get_field('main_image',$booking_details['restaurant_id']); ?>" width="518" height="189" /></div>
						<?php endif;?>
						<ul>
							<li class="rs">
								<h2><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/listing_img/rs_icon.jpg" alt="" /></h2>
								<h3>
									<?php
										$val = get_field('cost_for_two',$booking_details['restaurant_id']);
										for($i=1;$i<=$val;$i++){
											echo "<del>&#2352;</del>";
											echo " ";
										}
									?>
								</h3>
							</li>
							<li class="food">
								<h2><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/listing_img/food_icon.jpg" alt="" /></h2>
								<h3><?php show_restaurant_terms('cuisine', $booking_details['restaurant_id'])?></h3>
							</li>
							<li class="food">
								<h2><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/listing_img/location_icon.jpg" alt="" /></h2>
								<h3><?php show_restaurant_terms('location',$booking_details['restaurant_id'])?></h3>
							</li>
						</ul>
					</div>
					<div class="restro_sence_div">
						<?php
							$terms = get_the_terms($booking_details['restaurant_id'],"scene");
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
						<dl class="confirmed_list">
							<dt>Valid for:</dt>
							<dd><?php echo $booking_details['seatings'];?> People</dd>
						</dl>
						<dl class="confirmed_list">         
							<dt>At:</dt>
							<dd><?php echo $booking_details['post_title'] ?></dd>
						</dl>
						<dl class="confirmed_list">
							<dt>On:</dt>
							<dd><?php echo date("l, F d, Y",strtotime($booking_details['booking_date']));?> <?php echo date(DIVATIMEFORMAT,strtotime($booking_details['booking_time']));?></dd>
						</dl>
						<dl class="confirmed_list">
							<dt>Deal:</dt>
							<dd><?php echo get_field('discount_percentage',$booking_details['restaurant_id']);?>% OFF ALL FOOD AND DRINKS (please read terms)</dd>
						</dl>
						<dl class="confirmed_list">
							<dt>Unconfirmed Number:</dt>
							<dd>wpj0</dd>
						</dl>
						<?php
							$deal = get_field('deals_and_terms',$booking_details['restaurant_id']);
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
    </div>
    <!-- body container end -->
</div>