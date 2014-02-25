<!--search display div start-->
    <div class="search_display_div">
      <div class="display_pagi">
             <?php
			 if(function_exists('wp_paginate')) {
				wp_paginate();
			}
			?>
        <div class="listed_by">
			<form action = "<?php echo $_SERVER['REQUEST_URI'];?>" >
				<?php
					$default= get_option('posts_per_page');
					for($i=5;$i<=25;$i=$i+5){
						if($default == $i){
							$options.= '<option selected="selected" value = "'.$i.'">List '.$i.' Items</option>';
						}else{
							$options.= '<option value = "'.$i.'">List '.$i.' Items</option>';
						}
					}
				?>
				<select id="standard-dropdown" name="r_per_page" class="custom-class1 custom-class2" style="width:163px;" onchange="this.form.submit();" >
					<?php echo $options;?>
				</select>
			</form>
		</div>
    </div>
    <!--display div start-->