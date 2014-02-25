<div class="faq_container">
	<div class = "faq_div">
		<?php the_field('deals_and_terms'); ?>
	</div>
	<?php if ( ! dynamic_sidebar( 'Sidebar Widgets' ) ) : ?>
		<!--Enter Default content For Side Bar here-->
	<?php endif; // end sidebar widget area ?>
</div>