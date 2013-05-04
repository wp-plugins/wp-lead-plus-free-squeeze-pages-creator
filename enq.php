<?php
    /* REGISTER THE SCRIPTS */
//register the libraries
	function wpl_reg_scripts()
	{
		wp_register_script('editscript', plugins_url('js/edit.js', __FILE__));
		wp_register_script('pickerscript', plugins_url('js/colorpicker.js', __FILE__));
		wp_register_script('widgetscript', plugins_url('js/widget.js', __FILE__));
		wp_register_script('popupscript', plugins_url('js/popup.js', __FILE__));
		wp_register_script('sqsocialscript', plugins_url('js/social.js', __FILE__));
		wp_register_script('sqcommon', plugins_url('js/common.js', __FILE__));
		wp_register_script('lightcase', plugins_url('js/lc/lc.js', __FILE__));
		wp_register_script('base64code', plugins_url('js/base64.js', __FILE__));
		wp_register_script('backstretch', plugins_url('js/bgbs.js', __FILE__));
		
	}
	
	add_action('admin_init', 'wpl_reg_scripts');
		//wp_register_script('flash_player', plugins_url('js/flp.js', __FILE__));

//load scripts for default page, create and edit and others	
	function load_scripts_default()
	{
		if (is_admin())
		{
			wp_enqueue_script('jquery');
			wp_enqueue_script('tiny_mce');
			wp_enqueue_script('sqcommon');
			wp_enqueue_script('editscript');//jquery-ui-core
			wp_enqueue_script('jquery-ui-resizable');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('lightcase');
			wp_enqueue_script('base64code');
			wp_enqueue_script('thickbox');
			//wp_enqueue_script('flash_player');

		}
	}

//load script for widget page	
	function load_scripts_widget()
	{
		if (is_admin())
		{
			wp_enqueue_script('jquery');
			wp_enqueue_script('tiny_mce');
			wp_enqueue_script('sqcommon');
			wp_enqueue_script('widgetscript');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-resizable');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('lightcase');
			wp_enqueue_script('pickerscript');
			wp_enqueue_script('base64code');
			wp_enqueue_script('thickbox');
	
		}
	}	
	
//load script for popup page	
	function load_scripts_popup()
	{
		if (is_admin())
		{
			wp_enqueue_script('jquery');
			wp_enqueue_script('tiny_mce');
			wp_enqueue_script('sqcommon');
			wp_enqueue_script('popupscript');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-resizable');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('lightcase');
			wp_enqueue_script('pickerscript');
			wp_enqueue_script('base64code');
			wp_enqueue_script('thickbox');
	
		}
	}	


//load script for popup page	
	function load_scripts_social()
	{
		if (is_admin())
		{
			wp_enqueue_script('jquery');
			wp_enqueue_script('sqsocialscript');
			wp_enqueue_script('base64code');
			wp_enqueue_script('google-plusone', 'https://apis.google.com/js/plusone.js', array(), null, true);
			wp_enqueue_script('linkedin', 'http://platform.linkedin.com/in.js', array(), null, true);
			wp_enqueue_script('twitter', 'http://platform.twitter.com/widgets.js', array(), null, true);
	
		}
	}	
	
	//including the custom stylesheet
	add_action('admin_init', 'add_style_sheet');

	
	function add_style_sheet()
	{
		wp_register_style('editstyle', plugins_url('css/style.css', __FILE__));
		
		wp_register_style('widgetstyle', plugins_url('css/widget.css', __FILE__));
		
		wp_register_style('popupstyle', plugins_url('css/popup.css', __FILE__));
		
		wp_register_style('pickerstyle', plugins_url('css/colorpicker.css', __FILE__));
		
		wp_register_style('commonstyle', plugins_url('css/common.css', __FILE__));
		
		wp_register_style('lcstyle', plugins_url('js/lc/css/lc.css', __FILE__));//light case
        
        wp_register_style('sqsocialstyle', plugins_url('css/social.css', __FILE__));//light case
		//wp_register_style('sq_flash_style', plugins_url('css/flp.css', __FILE__));//light case
	}
    
    
    
    	//load stylesheet for default page (within the plugin)
	function enqueue_custom_styles()
	{
		wp_enqueue_style('editstyle');
		wp_enqueue_style('lcstyle');
		wp_enqueue_style('commonstyle');

	}
	
	//load stylesheet for widget page
	function enqueue_widget_styles()
	{
		wp_enqueue_style('widgetstyle');
		wp_enqueue_style('lcstyle');
		wp_enqueue_style('pickerstyle');
		wp_enqueue_style('commonstyle');
	}	

	//load stylesheet for popup page
	function enqueue_popup_styles()
	{
		wp_enqueue_style('popupstyle');
		wp_enqueue_style('lcstyle');
		wp_enqueue_style('pickerstyle');
		wp_enqueue_style('commonstyle');
	
	}
    
	//load stylesheet for popup page
	function enqueue_social_styles()
	{
		wp_enqueue_style('sqsocialstyle');
	}    