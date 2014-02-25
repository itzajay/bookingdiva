<?php
/*
Template Name: Friend Invitation
*/
?>
<?php get_header();?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#select_url").click(function(){
			copyCode();
		});
		function copyCode() {
			jQuery("#output-code").focus();
			jQuery("#output-code").select();
		}
	});
</script>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<!--body div start-->
	<div class="body_div">
		<!--welcome container div start-->
		<div class="content_warp">
			<div class="heading_warp">
				<h1>Invite your friends - we'll give you 250 loyalty points</h1>
			</div>
			<div class="invite_container">
				<div class="reges_warp" style = "float:right;margin-right:20px;margin-left:0px;">
					<form name="invite" action = "<?php echo $_SERVER["REQUEST_URI"] ?>" method = "post">
						<input type = "hidden" name="invite_friend" value = true />
						<h1>Invite your friends</h1>
						<ul class="inviteform">
							<li class="long">
								<h2>Add emails:</h2>
								<h3>
									<input type="text" name="recipient_email" class="l_field" value="" />
								</h3>
							</li>
							<li class="long">
								<h2>Add a message:</h2>
								<h4>
									<textarea name="recipient_message" class="l_area" ></textarea>
								</h4>
							</li>
							<li class="long">
								<div class="skipnow">
									<input name="" type="image" src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/send_invite.png" />
								</div>
							</li>
						</ul>
					</form>
				</div>
				<div class="welocome_lt" id="rtwelcome" style = "float:left;border-right:1px #dddddd solid;margin-right:8px;margin-left:0px;">
					<div class="facebook_invite">
						<?php
							global $current_user;
							$refUrl = get_bloginfo('url')."/?ref=".$current_user->user_email;
						?>
						<h1>Invite Your Facebook Friends</h1>
						<h2>
							<a name="fb_share" type="button" share_url="<?php echo urlencode($refUrl);?>">Share on Facebook</a>
							<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
						</h2>
					</div>
					<br/>
					<br/>
					<div class="divider">&nbsp;</div>
					<br/>
					<br/>
					<div class="facebook_invite">
						<h1>Your Invite Link</h1>
						<p>This link is yours. Email it, blog it, share it however you can and build valuabe loyalty points. Just copy and paste the link below to share it now.</p>
						
						<div class="email_field"><input name="" id = "output-code" class="l_field" type="text" value =<?php echo $refUrl?> /></div>
						<div class="copy_link"><input name="" id = "select_url" type="image" src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/copy_link.png" /></div>
					</div>
				</div>
			</div>
		</div>
		<!--welcome container div end-->
	</body>
	<!--body div end-->
<?php endwhile; endif; ?>
<?php get_footer(); ?>
