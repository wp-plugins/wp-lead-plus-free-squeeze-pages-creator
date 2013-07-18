<?php
    /* REGISTER THE SCRIPTS */
//register the libraries
	wp_register_script('editscript', plugins_url('js/edit.js', __FILE__));
	wp_register_script('sqcommon', plugins_url('js/common.js', __FILE__));
	wp_register_script('lightcase', plugins_url('js/lc/lc.js', __FILE__));
	wp_register_script('base64code', plugins_url('js/base64.js', __FILE__));
	wp_register_script('backstretch', plugins_url('js/bgbs.js', __FILE__));
	wp_register_script('sq_custom_editbox', plugins_url('js/tinymce/tinymce.min.js', __FILE__)); //wp_deregister_script

	

//load scripts for default page, create and edit and others	
	function load_scripts_default()
	{
		if (is_admin())
		{
			wp_deregister_script('tiny_mce'); 
			
			wp_enqueue_script('jquery');
			wp_enqueue_script('sq_custom_editbox');
			wp_enqueue_script('sqcommon');
			wp_enqueue_script('editscript');//jquery-ui-core
			wp_enqueue_script('sq_custom_jui');
			wp_enqueue_script('lightcase');
			wp_enqueue_script('base64code');
			wp_enqueue_script('thickbox');
		}
	}



	//load script for theme gallery
	function load_scripts_theme_gallery()
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('lightcase');
		wp_enqueue_script('sq_gallery_script');
		wp_enqueue_script('base64code');
	}
	
	//including the custom stylesheet
	add_action('admin_init', 'add_style_sheet');

	
	function add_style_sheet()
	{
		wp_register_style('editstyle', plugins_url('css/style.css', __FILE__));
	
		wp_register_style('sq_ng_commonstyle', plugins_url('css/common.css', __FILE__));

		
		wp_register_style('lcstyle', plugins_url('js/lc/css/lc.css', __FILE__));//light case

	}
    
    
    
    	//load stylesheet for default page (within the plugin)
	function enqueue_custom_styles()
	{
		wp_enqueue_style('editstyle');
		wp_enqueue_style('lcstyle');
		wp_enqueue_style('sq_ng_commonstyle');

	}
