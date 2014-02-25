<div class="find_select_area search_table_div">
	<form action="<?php echo get_bloginfo('url'); ?>" method="get">
		<input type="hidden" value = 1 name="search">
		<h1>Search for table:</h1>
		<div>
			<ul class="find_list advace_search">
				<li>
					<?php 
						$terms = get_terms("area",array('orderby'=>'id'));
						echo '<select id="standard-dropdown" name="area" class="custom-class1 custom-class2" style="width:163px;">';
						echo "<option value=0>All Areas</option>";
						
						foreach ($terms as $term) {
							if(isset($_GET['area']) && $_GET['area'] == $term->term_id) { 
								echo "<option SELECTED value="."'".$term->term_id."'>".$term->name."</option>";
							}else{
								echo "<option value="."'".$term->term_id."'>".$term->name."</option>";
							}
						}
						echo "</select>";
					?>
				</li>
				
				<li>
					<?php 
						$terms = get_terms("cuisine",array('orderby'=>'id'));
						echo '<select id="standard-dropdown" name="cuisine_type" class="custom-class1 custom-class2" style="width:163px;">';
						echo "<option value=0>All Cuisines</option>";
						foreach ($terms as $term) {
							if(isset($_GET['cuisine_type']) && $_GET['cuisine_type'] == $term->term_id) { 
								echo "<option SELECTED value="."'".$term->term_id."'>".$term->name."</option>";
							}else{
								echo "<option value="."'".$term->term_id."'>".$term->name."</option>";
							}
						}
						echo "</select>";
					?>
				</li>
				
				<li>
					<?php 
						$terms = $wpdb->get_results("select * from `".$wpdb->prefix."table_types` where NOT id = ".get_all_id());
						echo '<select id="standard-dropdown" name="seating" class="custom-class1 custom-class2" style="width:163px;">';
						echo "<option value=0>Any No of Guests</option>";
						foreach ($terms as $term) {
							if(isset($_GET['seating']) && $_GET['seating'] == $term->id) { 
								echo "<option SELECTED value="."'".$term->id."'>".$term->title."</option>";
							}else{
								echo "<option value="."'".$term->id."'>".$term->title."</option>";
							}
						}
						echo "</select>";
					?>
				</li>
				
				<li class = 'long'>
					<?php
						$value = date(get_option('date_format'));
						if(!empty($_GET['date'])) {
							$value = $_GET['date'];
						}
					?>
					<input class="field1 datepickerRange" value="<?php echo $value;?>"  type = "text" name = "date">
				</li>
				
				<li>
					<?php
						$time_value = get_current_parent_booking_id();
						if(!empty($_GET['time'])) {
							$time_value = $_GET['time'];
						}
						$terms = $wpdb->get_results("select * from `".$wpdb->prefix."booking_categories` where parent_id <= 0 ORDER BY display");
						echo '<select id="standard-dropdown" name="time" class="custom-class1 custom-class2 select-date" style="width:163px;">';
						foreach ($terms as $term) {
							if($time_value == $term->id) {
								echo "<option SELECTED = 'SELECTED' value="."'".$term->id."'>".$term->name."</option>";
							}else{
								echo "<option value="."'".$term->id."'>".$term->name."</option>";
							}
						}
						echo "</select>";
					?>
				</li>
				<li class="bt">
					<input type="submit" value="" class="search_btn">
				</li>
			</ul>
		</div>
	</form>
</div>
<!--find area end-->