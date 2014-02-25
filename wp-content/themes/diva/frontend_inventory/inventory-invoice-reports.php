<script type="text/javascript">
	jQuery.noConflict();
	jQuery(function($) {
		$(document).ready(function(){
			$('.date_range').change(function(){
				var $val = $(this).val();
				var $from = '';
				var $to = '';
				var myDate = new Date();
				
				if($val == 't'){
					$from = "<?php echo date(get_option('date_format'));?>";
					$to = "<?php echo date(get_option('date_format'));?>";
				}else if($val == 'wd'){
					$from = "<?php echo date(get_option('date_format'),mktime(1, 0, 0, date('m'), date('d')-date('w'), date('Y')));?>";
					$to = "<?php echo date(get_option('date_format'));?>";
				}else if($val == 'md'){
					$from = "<?php echo date(get_option('date_format'),mktime(1, 0, 0, date('m'), '01', date('Y')));?>";
					$to = "<?php echo date(get_option('date_format'));?>";
				}else if($val == 'qd'){
					$from = "<?php echo date(get_option('date_format'),mktime(1, 0, 0,get_quater_start(CurrentQuarter()) , '01', date('Y')));?>";
					$to = "<?php echo date(get_option('date_format'));?>";
				}else if($val == 'lw'){
					$from = "<?php echo date(get_option('date_format'),mktime(1, 0, 0, date('m'), date('d')-date('w')-7, date('Y')));?>";
					$to = "<?php echo date(get_option('date_format'),mktime(1, 0, 0, date('m'), date('d')-date('w')-1, date('Y')));?>";
				}else if($val == 'lm'){
					$from = "<?php echo date(get_option('date_format'),mktime(1, 0, 0, date('m')-1, '+01', date('Y')));?>";
					$to = "<?php echo date(get_option('date_format'),mktime(1, 0, 0, date('m'),'-00', date('Y')));?>";
				}else if($val == 'lq'){
					$from = "<?php echo date(get_option('date_format'),mktime(1, 0, 0,get_quater_start(CurrentQuarter()-1) , '01', date('Y')));?>";
					$to = "<?php echo date(get_option('date_format'),mktime(1, 0, 0,get_quater_start(CurrentQuarter()) , '-00', date('Y')));?>";
				}
				$('#from').val($from);
				$('#to').val($to);
			});
			
		});
	});
</script>
<style type="text/css">
ul.overrider_from li h1 {
    width: 120px;
}
</style>
<?php
	$parameters = "";
	$sep="";
	foreach($_GET as $key => $val){
		if($key != 'page')
			$parameters.= $sep."$key=$val";
		$sep = '&';
	}
	$current_user = wp_get_current_user();
	$post = get_posts_by_author();
	
	/*
		Place code to connect to your DB here.
	*/
	// include your code to connect to DB.
	
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	$from = date("Y-m-d");
	$nextweek  = mktime(0, 0, 0, date("m"), date("d")+7, date("Y"));
	$to = date("Y-m-d",$nextweek);
	if(!empty($_GET['search'])){
		$from = date('Y-m-d',strtotime($_GET['from']));
		$to = date('Y-m-d',strtotime($_GET['to']));
	}
	if(!empty($from) && !empty($to)){
		$condition = " `date` BETWEEN '".$from."' AND '".$to."'";
	}elseif(empty($from ) && !empty($to)){
		$condition = " `date` <= '".$to."'";
	}elseif(empty($to ) && !empty($from)){
		$condition = " `date` >= '".$from."'";
	}
	$query = "SELECT COUNT(*) as num FROM `wp_invoices` where `restaurant_id` =".$post['ID']." AND $condition";
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages[num];
	
	/* Setup vars for query. */
	//$targetpage = "filename.php"; 	//your file name  (the name of this file)
	$limit = 10; 								//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
	$sql = "SELECT * FROM `wp_invoices` where `restaurant_id` =".$post['ID']." AND $condition LIMIT $start, $limit";
	$result = mysql_query($sql);
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class = 'trans_navigation'><ol class='wp-paginate'>";
		//previous button
		if ($page > 1) 
			$pagination.= "<li><a href=\"?$parameters&page=$prev\" class = 'prev'></a></li>";
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li><span class=\"current page\" >$counter</span></li>";
				else
					$pagination.= "<li><a href=\"?$parameters&page=$counter\" class = 'page'>$counter</a></li>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li><span class=\"current page\">$counter</span></li>";
					else
						$pagination.= "<li><a href=\"?$parameters&page=$counter\" class = 'page'>$counter</a></li>";					
				}
				$pagination.= "<li>...</li>";
				$pagination.= "<li><a href=\"?$parameters&page=$lpm1\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"?$parameters&page=$lastpage\">$lastpage</a></li>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<li><a href=\"?$parameters&page=1\">1</a></li>";
				$pagination.= "<li><a href=\"?$parameters&page=2\">2</a></li>";
				$pagination.= "<li>...</li>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li><span class=\"current page\">$counter</span></li>";
					else
						$pagination.= "<li><a href=\"?$parameters&page=$counter\" class = 'page'>$counter</a></li>";					
				}
				$pagination.= "...";
				$pagination.= "<li><a href=\"?$parameters&page=$lpm1\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"?$parameters&page=$lastpage\">$lastpage</a></li>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<li><a href=\"?$parameters&page=1\" class = 'page'>1</a></li>";
				$pagination.= "<li><a href=\"?$parameters&page=2\" class = 'page'>2</a></li>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li><span class=\"current page\">$counter</span></li>";
					else
						$pagination.= "<li><a href=\"?$parameters&page=$counter\" class = 'page'>$counter</a></li>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<li><a href=\"?$parameters&page=$next\" class = 'next'></a></li>";
		$pagination.= "</ol></div>\n";		
	}
	global $invoice_status;
?>
<!--body div start-->

	<div class="content_warp">
      	<div class="heading_warp">
        	<h1>Invoice Report</h1>
        	<h2><a href="<?php echo get_bloginfo('url');?>">Home</a> &gt; Invoice Report</h2>
      	</div>

      	<div class="transaction_search">
      		<form  method="get" action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
				<input type="hidden" name="inventory" value = "<?php echo $_GET['inventory'];?>"/>
				<input type="hidden" name="restaurant_id" value = "<?php echo $post['ID'];?>"/>
				<input type="hidden" name="search" value = "1"/>
				<h1>Search By</h1>
				<ul>
					<li class="long">
						<input type="text" name="from" id = 'from' class = "over_fileld datepicker field1" value = "<?php echo date(get_option('date_format'),strtotime($from));?>"/>
					</li>
					<li class="long">
						<input type="text" name="to" id = 'to' class = "datepicker over_fileld field1" value = "<?php echo date(get_option('date_format'),strtotime($to));?>"/>
					</li>
					<li>
						<select name = "date_range" class = 'date_range'>
							<option value="">Choose Date Range</option>
							<?php
								$date_options = array(
													  't'=>'Today',
													  'wd'=>'Week to date',
													  'md'=>'Month to date',
													  'qd'=>'Quater to date',
													  'lw'=>'Last week',
													  'lm'=>'Last month',
													  'lq'=>'Last quater'
													  );
								foreach($date_options as $key=>$value){
									if(!empty($_GET['date_range']) && $_GET['date_range'] == $key){
										echo "<option selected = 'selected' value =".$key.">".$value."</option>";
									}else{
										echo "<option value =".$key.">".$value."</option>";
									}
								}
							?>
						</select>
					</li>
					<li>
						<input name="" type="image" src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH;?>/go.png" />
					</li>
				</ul>
			</form>
      	</div>
		<div style = "float:left;margin-top:-3px;">
			<?php echo $pagination;?>
		</div>
      	<div class="result">
      		<table border="0" cellspacing="0" cellpadding="0" class="rules_table pad_cell">
				<thead class = "result_heading">
					<tr>
						<th align="left" >S No.</th>
						<th align="left" >ID</th>
						<th align="left" >Date</th>
						<th align="left" >Amount</th>
						<th align="left" >Status</th>
					</tr>
				</thead>
				<tbody>
					<?php $j= $start+1;;$i = 0; while($row = mysql_fetch_array($result)){?>    
						<tr <?php if($i%2 == 0):?>class="alternate"<?php endif;?>>
							<td align="left">
								<?php echo $j; $j++;?>
							</td>
							<td align="left" >
								<?php echo $row['id'];?>
							</td>
							<td align="left" >
								<?php echo date(get_option('date_format'),strtotime($row['date']));?>
							</td>
							<td align="left" >
								<?php echo $row['amount'];?>
							</td>
							<td align="left">
								<?php echo $invoice_status[$row['status']];?>
							</td>
						</tr>
					<?php $i++; }?>
				</tbody>
			</table>
      	</div>
    </div>