		<!--welcome bottom div start-->
		<div class="welcome_bottom_div">
			<div class="asso_img_div">
				<h1>Our Associated Restaurant</h1>
				<?php 
					$logo_posts = get_logo_posts();
					echo "<ul id='rest_logo'>";
					for($i=0;$i<count($logo_posts);$i++){
						$logo = get_field('logo',$logo_posts[$i]['ID']);
						echo "<li>";
							echo '<img src="'.$logo.'" title = "'.$logo_posts[$i]['post_title'].'" alt = "'.$logo_posts[$i]['post_title'].'" width = "211" height = "111">';
						echo "</li>";
					}
					echo "</ul>";
				?>
			</div>
		  <div class="comment_section">
				<?php if ( ! dynamic_sidebar( 'Home Sidebar' ) ) : ?>
				<!--Enter Default content For Side Bar here-->
				<?php endif; // end sidebar widget area ?>  
		  </div>
		</div>
		<!--welcome bottom div end-->
	</div>
	<!--body div end-->
	</div>
	<!--main div end-->
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td><div class="footer_container">
				<div class="footer_main">
				  <div class="footer_foolow_div">
					<div class="follow_div">
					  <ul>
						<li class="text">Follow us :</li>
						<li>
							<?php follow_icon();?>
						</li>
						<li>
							<?php
								if(!empty($_SESSION['admin'])){
									echo '<a style="color:white;" href="'.get_bloginfo('url').'/?login_user=admin" target = "blank">Switch to Admin</a>';
								}
							?>
						</li>
					  </ul>
					</div>
				  </div>
				  <div class="footer_link_div">
					<?php wp_nav_menu( array( 'theme_location' => 'footer_menu', 'container' => '','menu_class' =>'footer_link' ) );?>	
				  </div>
				  <div class="copy_right_main">
					<div class="footer_lt_text">
						<?php if(is_general_user()){ ?>
						<a href="<?php echo get_friend_page_link(); ?>"><?php echo get_field('friend_invitation_text',THEMESETTINGS);?></a>
						<?php } ?>
					</div>
					<div class="copy_text_div">&copy; 2012 All Rights Reserved.</div>
					<?php wp_nav_menu( array( 'theme_location' => 'footer_menu_2', 'container' => '','menu_class' =>'privacy_link' ) );?>	
				  </div>
				</div>
			  </div></td>
		</tr>
	</table>
<?php /*		<footer id="footer" class="source-org vcard copyright">
			<small>&copy;<?php echo date("Y"); echo " "; bloginfo('name'); ?></small>
		</footer>

	</div>

	<?php wp_footer(); ?>


<!-- here comes the javascript -->

<!-- jQuery is called via the Wordpress-friendly way via functions.php -->

<!-- this is where we put our custom functions -->
<script src="<?php bloginfo('template_directory'); ?>/_/js/functions.js"></script>

<!-- Asynchronous google analytics; this is the official snippet.
	 Replace UA-XXXXXX-XX with your site's ID and uncomment to enable.
	 
<script>

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-XXXXXX-XX']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
-->
	  */?>
<!--main div end-->
<?php wp_footer(); ?>
<script type="text/javascript">
	jQuery(function(){
		jQuery('ul#rest_logo').innerfade({
			speed: 1000,
			timeout: 5000,
			type: 'sequence',
			containerheight: '220px'
		});
	});
</script>
</div>
</body>
</html>