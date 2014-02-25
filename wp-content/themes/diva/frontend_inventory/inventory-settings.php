<?php 
	$post = get_posts_by_author();
	if(isset($_POST['discount'])):
		update_post_meta($post['ID'], 'discount_percentage',$_POST['discount']);
	endif;
	if(isset($_POST['confirmation'])):
		update_post_meta($post['ID'], 'booking_confirmation',$_POST['confirmation']);
	endif;
	$selected_discount = get_post_meta($post['ID'], 'discount_percentage');
	$selected_booking = get_post_meta($post['ID'], 'booking_confirmation');
	global $booking_confirmation;
	global $discount_percantage;
?>
<script type="text/javascript">
	jQuery.noConflict();
	jQuery(function($) {
		$(document).ready(function(){
			$('.setting_discount, .setting_confirmation').change(function(){
				$('#setting_form').submit();
			});
		});
	});
</script>
<!--page content container start-->
    <div class="content_warp">
      <div class="heading_warp">
        <h1>Settings</h1>
        <h2><a href="<?php echo get_bloginfo('url');?>">Home</a> &gt; Settings</h2>
      </div>
	  <!-- body container start-->
      <div class="main_body_conainer">
        <!--body left div start-->
        <div class="body_conainer_lt">
			<form  method="post" action='<?php echo $_SERVER["REQUEST_URI"] ?>' id = 'setting_form'>
				<div class="accout_detail_div">
					<div class="accout_detail_heading">
						<h1>1. Discount % for all Future Bookings:</h1>
					</div>
					<select name = 'discount' class = "setting_discount">
						<?php
							foreach($discount_percantage as $key=>$val){
								if($key == $selected_discount[0]){
									echo "<option selected = 'selected' value = '$key'>$val</option>";
								}
								else{
									echo "<option value = '$key'>$val</option>";
								}
							}
						?>
					</select>
					<div class="list_con_text"  style ='margin-top:10px;border-bottom:none;'>
						<h1>Please note:</h1>
						<ul class="bullet_lit">
							<li>
								This discount % is off of all food and drinks.
							</li>
							<li>
								This will apply to all bookings from now onwards.
							</li>
							<li>
								There will be bookings coming up in the future that will not have this latest discount Percentage. This is because these bookings were made before today with prior discount settings.
							</li>
							<li>
								All booking notifications will reflect the applicable discount %. 
							</li>
							<li>
								When making a note of a booking in your Register please enter the following elements to minimize confusion:<br/>
								a.Name of the Party (and number of people)<br/>
								b.The applicable discount percentage
							</li>
						</ul>
					</div>
				</div>
				<div class="accout_detail_div">
					<div class="accout_detail_heading">
						<h1>2. Manual or Automatic Confirmations:</h1>
					</div>
					<select name = 'confirmation' class = "setting_confirmation">
						<?php
							foreach($booking_confirmation as $key=>$val):
								if($key == $selected_booking[0]):
									echo "<option selected = 'selected' value = '$key'>$val</option>";
								else:
									echo "<option value = '$key'>$val</option>";
								endif;
							endforeach;
						?>
					</select>
					<div class="list_con_text" style ='margin-top:10px;'>
						<h1>Please note:</h1>
						<ul class="bullet_lit">
							<li>
								With manual confirmations you must respond to a booking within 10 minutes of receiving it. Else it will reflect poorly on your restaurant.
							</li>
							<li>
								With automatic confirmations you must have a strong backend that ensures that someone is noting all the bookings real time, so that no customer is left unsatisfied with the experience. 
							</li>
						</ul>
					</div>
				</div>
			</form>
        </div>
        <!--body left div end-->
        <div class="body_conainer_rt">
          <ul class="rt_banner">
            <li><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/inner_body_banner.jpg" alt="" /></li>
            <li><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/inner_body_banner2.jpg" alt="" /></li>
          </ul>
        </div>
      </div>
      <!-- body container end -->
    </div>
    <!--page content container end-->