<?php
/*
@since Colonial 1.0
Template Name: Front Page
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
<!--body div start-->
<div class="body_div">

	<div class="welocome_container">
      <div class="welocome_lt">
        <?php if (have_posts()) : while (have_posts()) : the_post();
					the_content();
					endwhile; 
			  else : ?>
					<h2>Not Found</h2>
		<?php endif; ?>
      </div>
      <div class="restauro_list_div">
        <div class="restauro_heading">Our Premier Associated Restaurants</div>
		<div id="photofy"></div>
      </div>
      <div class="join_div">
        <h1>Join Now For Free</h1>
        <div class="face_con"><a href="#"><img src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/connect_facebook.jpg" /></a></div>
        <div class="or">Or</div>
        <ul class="join_form">
          <li>
            <h2>Username</h2>
            <h3>
				<input name="" type="text" class="field1" />
            </h3>
          </li>
          <li>
            <h2>E-mail</h2>
            <h3>
				<input name="" type="text" class="field1" />
            </h3>
          </li>
          <li>
            <h2>Password</h2>
            <h3>
				<input name="" type="text" class="field1" />
            </h3>
          </li>
          <li>
            <h2>Mobile</h2>
            <h3>
				<input name="" type="text" class="field1" />
            </h3>
          </li>
          <li class="remember">
				<input name="" type="checkbox" value="" />
				Remember me
		  </li>
          <li>
				<input name="" type="image" src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH ?>/join_now.jpg" />
          </li>
        </ul>
      </div>
    </div>	

		
</div>
<!--body div end-->

<?php get_footer(); ?>