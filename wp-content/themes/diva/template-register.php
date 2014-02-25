<?php
/*
@since Colonial 1.0
Template Name: Register
*/
?>
<?php get_header(); ?>
<?php
	$small_logo_posts = get_small_logo_posts();
	$variable = "";
	$sep="";
	for($i=0;$i<count($small_logo_posts);$i++){
		$logo = get_field('small_logo',$small_logo_posts[$i]['ID']);
		if(!empty($logo)){
			$variable.= $sep.'{ImageUrl: "'.$logo.'"}';
			$sep=",";
		}
	}
?>
<style type="text/css">
li.long input {
    width: auto;
}
</style>
<script type="text/javascript">
	jQuery(document).ready(function() {
		var images = [
		<?php echo $variable;?>
		]
		jQuery("#photofy").photofy({
			imageSource: images,
			maxImages: 15,
			delay: 4000,
			fadeDuration:1000,
			select: function(){
				return false;
			}
			
		});
	});
</script>
<?php
	if (have_posts()) : while (have_posts()) : the_post();
?>
<!--body div start-->
  <div class="body_div">
    <!--welcome container div start-->
    <div class="welocome_container">
      <div class="reges_warp">
        <h1>Welcome!</h1>
        <h2>You are now a Booking Diva Member.
          Before continuing, help us to customize your experience for you:</h2>
		<form name = "add_info" action="<?php $frontpage_id = get_option('page_for_posts'); echo get_permalink($frontpage_id);?>" method="post">
			<input type="hidden" name="add_meta" value=1 />
			<input type="hidden" name="new_register" value=1 />
			
			<ul class="regesform">
			  <li class="shortlt">
				<h2>First Name:</h2>
				<h3>
				  <input name="first_name" type="text" class="rgfield" />
				</h3>
			  </li>
			  <li class="shortrt">
				<h2>Last Name:</h2>
				<h3>
				  <input name="last_name" type="text" class="rgfield" />
				</h3>
			  </li>
			  <li class="shortlt">
				<h2>Date of Birth:</h2>
				<h3 class='regg'>
				  <input name="dob" type="text" class="rgfield  datepicker" />
				</h3>
			  </li>
			  <li class="shortrt">
				<h2>Zip Code:</h2>
				<h3>
				  <input name="zipcode" type="text" class="rgfield" />
				</h3>
			  </li>
			  <li class="shortlt">
				<h2>Gender:</h2><br/><br/>
				<input name="gender" type="radio" value="male" />
				Male &nbsp;&nbsp;&nbsp;
				<input name="gender" type="radio" value="female" />
				Female
			  </li>
			  <li class="long">
				<h2>Email Preferences:</h2>
				<ul>
					<li>
						<input name="email_alert" type="checkbox" value="alert1" />
						<?php
							global $email_alert;
							echo $email_alert['alert1'];
						?>
					</li>
					<li>
						<input name="email_alert" type="checkbox" value="alert2" />
						<?php
							echo $email_alert['alert2'];
						?>
					</li>
					<li>
						<input name="email_alert" type="checkbox" value="alert3" />
						<?php
							echo $email_alert['alert3'];
						?>
					</li>
				</ul>
			  </li>
			  <li class="long">
				<div class="skipnow">
					<input name="" type="image" src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/submitnow.png" />
					<br/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php $frontpage_id = get_option('page_for_posts'); echo get_permalink($frontpage_id)."?new_register=1";?>">Skip this for now</a>
				</div>
			  </li>
			</ul>
		</form>
      </div>
      <div class="welocome_lt" id="rtwelcome" style = "margin-left:10px;";>
        <div class="restauro_list_div">
			<div class="restauro_heading">Our Premier Associated Restaurants</div>
			<div id="photofy"></div>
		</div>
      </div>
    </div>
    <!--welcome container div end-->
<?php endwhile; ?>
<?php else : ?>
	<h2>Not Found</h2>
<?php endif; ?>
<?php get_footer(); ?>