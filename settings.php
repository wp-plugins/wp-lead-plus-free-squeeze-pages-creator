<?php
		include_once 'enq.php';
		include_once 'social.php';
		include_once 'mainui.php';
        include_once 'code/common.php';
	//add background and buttons to db


	//add jquery
	add_action('init', 'widget_init_sidebar');
	function widget_init_sidebar() {
		if (!is_admin()) {
			wp_enqueue_script('jquery');
		}
	}	
		
//add facebook to the edit page
	function sq_insert_fb_script()
	{
		echo '<div id="fb-root"></div><script>jQuery(document).ready(function(){(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";  fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));});</script>';
	}
	//add the menu to dashboard
	add_action('admin_menu', 'register_pro_squeezers');

	add_action('admin_menu', 'register_pro_squeezers');
	function register_pro_squeezers()
	{
		$main_page = add_menu_page('WP Lead Plus Home', 'WP Lead Plus', 'manage_options', 'pro_sqz_set', 'main_squeezers_cb');
		$edit_page = add_submenu_page('pro_sqz_set', 'Add New/ Edit Page', 'Create Squeeze Pages', 'manage_options', 'sub_squeezers_new', 'sub_squeezers_new_cb');
		
		//go pro page
		$go_pro_page = add_submenu_page('pro_sqz_set', 'Get More Leads', 'Get More Leads', 'manage_options', 'sub_squeezers_go_pro', 'sub_squeezers_go_pro_cb');
		
		$settings_page = add_submenu_page('pro_sqz_set', 'WP Lead Plus Settings', 'Settings', 'manage_options', 'sub_squeezers_set', 'sub_squeezers_settings_cb');
	
		add_action( 'admin_print_styles-' . $main_page, 'enqueue_custom_styles' );
		add_action( 'admin_print_styles-' . $edit_page, 'enqueue_custom_styles' );
		add_action( 'admin_print_styles-' . $go_pro_page, 'enqueue_custom_styles' );
		
		
		add_action( 'admin_print_styles-' . $settings_page, 'enqueue_custom_styles' );
		
		add_action( 'admin_print_styles-' . $main_page, 'load_scripts_default' );
		add_action( 'admin_print_styles-' . $main_page, 'load_scripts_squeeze_page' );
		
		add_action( 'admin_print_styles-' . $go_pro_page, 'load_scripts_default' );
		
		add_action( 'admin_print_styles-' . $edit_page, 'load_scripts_default' );
		add_action( 'admin_print_styles-' . $settings_page, 'load_scripts_default' );
		add_action( 'admin_print_styles-' . $settings_page, 'load_scripts_squeeze_page' );
		
		add_action( 'admin_print_styles-' . $edit_page, 'load_scripts_squeeze_page' );
		
		
	}    