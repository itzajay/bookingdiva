<div class="left_link_heading">My Acccount</div>
<ul class = "left_link">
	<li>
		<a href = "<?php echo add_query_arg(array('inventory' => 'current_bookings'), get_rest_page_link()); ?>">Current Bookings</a>
	</li>
	<li>
		<a href = "<?php echo add_query_arg(array('inventory' => 'booking_history'), get_rest_page_link()); ?>">Bookings History</a>
	</li>
	<li>
		<a href = "<?php echo add_query_arg(array('inventory' => 'my_account'), get_rest_page_link()); ?>">Account Settings</a>
	</li>
	<li>
		<a href = "<?php echo add_query_arg(array('inventory' => 'transaction'), get_rest_page_link()); ?>">Transaction Reports</a>
	</li>
</ul>