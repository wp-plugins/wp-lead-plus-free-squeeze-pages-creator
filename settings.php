<?php
		include_once 'enq.php';
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
		
/**************************************FIX KITCHEN SINK PROBLEM OF THE EDITOR****************************************************/        
	function unhide_kitchensink( $args )
	{
		$args['wordpress_adv_hidden'] = false;
		return $args;
	}
	
	add_filter( 'tiny_mce_before_init', 'unhide_kitchensink' );
	
/**************************************END FIX KITCHEN SINK PROBLEM OF THE EDITOR****************************************************/                

	//add the menu to dashboard
	add_action('admin_menu', 'register_pro_squeezers');
	function register_pro_squeezers()
	{
		$main_page = add_menu_page('WP Lead Plus Home', 'WP Lead Plus', 'manage_options', 'pro_sqz_set', 'main_squeezers_cb');
		$edit_page = add_submenu_page('pro_sqz_set', 'Add New/ Edit Page', 'Create n Edit', 'manage_options', 'sub_squeezers_new', 'sub_squeezers_new_cb');
		
		$settings_page = add_submenu_page('pro_sqz_set', 'WP Lead Plus Settings', 'Settings', 'manage_options', 'sub_squeezers_set', 'sub_squeezers_settings_cb');
		
		
		add_action( 'admin_print_styles-' . $main_page, 'enqueue_custom_styles' );
		add_action( 'admin_print_styles-' . $edit_page, 'enqueue_custom_styles' );
		add_action( 'admin_print_styles-' . $settings_page, 'enqueue_custom_styles' );
		
		
		add_action( 'admin_print_styles-' . $main_page, 'load_scripts_default' );
		add_action( 'admin_print_styles-' . $edit_page, 'load_scripts_default' );
		add_action( 'admin_print_styles-' . $settings_page, 'load_scripts_default' );
	
	}