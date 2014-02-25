<?php
	global $transaction_type;
	$to = date("Y-m-d");
	$nextweek  = mktime(0, 0, 0, date("m"), date("d")-14, date("Y"));
	$from = date("Y-m-d",$nextweek);
	$rules_condition = '';
	$condition = '';
	if(!empty($_GET['search'])){
		$from = date('Y-m-d',strtotime($_GET['from']));
		$to = date('Y-m-d',strtotime($_GET['to']));
		
		if(!empty($_GET['id'])){
			$rules_condition.= ' AND t.`id` ='.$_GET['id'];
		}
		if(!empty($_GET['transaction_type'])){
			$rules_condition.= ' AND t.`type_id` ='.$_GET['transaction_type'];
		}
	}
	if(!empty($from) && !empty($to)){
		$condition = " AND t.`date` BETWEEN '".$from."' AND '".$to."'";
	}elseif(empty($from ) && !empty($to)){
		$condition = " AND t.`date` <= '".$to."'";
	}elseif(empty($to ) && !empty($from)){
		$condition = " AND t.`date` >= '".$from."'";
	}
	$transaction_record = $wpdb->get_results('select b.restaurant_id,t.*,if(((`type_id` = 1) or (`type_id` = 2) or (`type_id` = 3) or (`type_id` = 4)),`amount`,0)as debit, if((`type_id` = 5),`amount`,0) as credit from wp_transactions as t left join wp_bookings as b on b.id = t.ref_id AND (t.type_id = 3 OR t.type_id = 5) where t.user_id='.$current_user->ID.' AND t.`status` = 1 '.$rules_condition.$condition.' ORDER BY id ASC',ARRAY_A);
	
?>
 <div class="content_warp">
    <div class="heading_warp">
        <h1>Transaction Report</h1>
        <h2><a href="<?php echo get_bloginfo('url');?>">Home</a> &gt; Transaction Report</h2>
    </div>
	<div id="message"></div>
	<div class="transaction_search">
		<form  method="get" action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
			<input type="hidden" name="inventory" value = "<?php echo $_GET['inventory'];?>"/>
			<input type="hidden" name="search" value = "1"/>
			<h1>Search By</h1>
			<ul>
				<li>
					<select name = "transaction_type">
						<option value="">Select Type</option>
						<?php
							foreach($transaction_type as $key => $val){
								if(!empty($_GET['transaction_type']) && $_GET['transaction_type'] == $key){
									echo "<option selected='selected' value =".$key.">".$val."</option>";
								}else{
									echo "<option value =".$key.">".$val."</option>";
								}
							}
						?>
					</select>
				</li>
				<li class="long">
					<input type="text" name="id" class = "over_fileld field1" value = "<?php echo $_GET['id'];?>" placeholder = 'Transaction ID' />
				</li>
				<li class="long">
					<input type="text" name="from" class = "over_fileld datepicker field1" value = "<?php echo date(get_option('date_format'),strtotime($from));?>"/>
				</li>
				<li class="long">
					<input type="text" name="to" class = "datepicker over_fileld field1" value = "<?php echo date(get_option('date_format'),strtotime($to));?>"/>
				</li>
				<li>
					<input name="" type="image" src="<?php echo DIVATEMPLATEPATH."/".IMAGEPATH;?>/go.png" />
				</li>
			</ul>				
		</form>
	</div>
	
	<div class="result">
		<table border="0" cellspacing="0" cellpadding="0" class = "rules_table pad_cell">
			<thead class ="result_heading">
				<tr class='rules-page-heading'>
					<th valign="middle" class="left">
						ID
					</th>
					<th valign="middle" class="left">
						Type
					</th>
					<th valign="middle" class="left">
						Date
					</th>
					<th valign="middle" class="center">
						Debit
					</th>
					<th valign="middle" class="center">
						Credit
					</th>
					<th valign="middle" class="center">
						Balance
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$bal = 0;
					foreach($transaction_record as $trans){
						$bal = ($bal+$trans['debit']-$trans['credit']);
				?>
					<tr>
						<td align="left" valign="middle" >
							<?php echo $trans['id'];?>
						</td>
						<td align="left" valign="middle" >
							<?php
								echo $transaction_type[$trans['type_id']];
								if($trans['restaurant_id'] != NULL){
									echo " - ".get_the_title($trans['restaurant_id']);
								}
							?>
						</td>
						<td align="left" valign="middle" >
							<?php echo date(get_option('date_format'),strtotime($trans['date']));?>
						</td>
						<td align="center" valign="middle" >
							<?php
								if($trans['debit']!= 0)
									echo $trans['debit'];
							?>
						</td>
						<td align="center" valign="middle" >
							<?php
								if($trans['credit']!= 0)
									echo $trans['credit'];
							?>
						</td>
						<td align="center" valign="middle">
							<?php echo $bal;?>
						</td>
					</tr>
				<?php }?>
				
			</tbody>
		</table>
	</div>
 </div>