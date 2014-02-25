<?php if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => __('Sidebar Widgets'),
		'id'   => 'sidebar-widgets',
		'description'   => __( 'These are widgets for the sidebar.','html5reset' ),
		'before_widget' => '<div class="faq_div">',
		'after_widget'  => '</div>',
		'before_title'  => '<h1>',
		'after_title'   => '</h1>'
	));
	register_sidebar(array(
		'name' => __('Restaurant Owner Sidebar'),
		'id'   => 'rest-widgets',
		'description'   => __( 'These are widgets for the sidebar.','html5reset' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => ''
	));
    register_sidebar(array(
		'name' => __('Home Sidebar'),
		'id'   => 'home-widgets',
		'description'   => __( 'These are widgets for the sidebar.','html5reset' ),
		'before_widget' => '<div id="%1$s" class="comment %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '',
		'after_title'   => ''
	));
}?>