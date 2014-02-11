<?php 

	//if uninstall not called from WordPress exit
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
		exit ();
	//drop the background and buttons tables
	global $wpdb;
	
	$buttons = $wpdb->get_blog_prefix().'cta_buttons';
	$background = $wpdb->get_blog_prefix().'background_img';
	$themes = $wpdb->get_blog_prefix().'sq_themes'; //wp_sq_popup_code, wp_sq_widget_code
	
	//dropping the tables
	$wpdb->query("DROP TABLE IF EXISTS $buttons");
	$wpdb->query("DROP TABLE IF EXISTS $background");
	$wpdb->query("DROP TABLE IF EXISTS $themes");