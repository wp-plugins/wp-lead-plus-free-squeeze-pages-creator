<?php
    /* REGISTER THE SCRIPTS */
//register the libraries
	
	add_action('admin_enqueue_scripts', 'sq_bgt_register_all_scripts');
	

	function sq_bgt_register_all_scripts()
	{
		wp_register_script('sq_custom_ebox', plugins_url('js/ebox.js', __FILE__));
		wp_register_script('editscript', plugins_url('js/edit.js', __FILE__));
		wp_register_script('pickerscript', plugins_url('js/colorpicker.js', __FILE__));
		wp_register_script('widgetscript', plugins_url('js/widget.js', __FILE__));
		wp_register_script('popupscript', plugins_url('js/popup.js', __FILE__));
		wp_register_script('sq_gallery_script', plugins_url('js/gallery.js', __FILE__));
		wp_register_script('sqsocialscript', plugins_url('js/social.js', __FILE__));
		wp_register_script('sq_conversion_script', plugins_url('js/conv.js', __FILE__));
		wp_register_script('sqcommon', plugins_url('js/common.js', __FILE__));
		wp_register_script('lightcase', plugins_url('js/lc/lc.js', __FILE__));
		wp_register_script('base64code', plugins_url('js/base64.js', __FILE__));
		wp_register_script('sq_custom_tinymce', plugins_url('js/tinymce/tinymce.min.js', __FILE__)); //wp_deregister_script
		wp_register_script('sq_custom_jui', plugins_url('js/jui-min.js', __FILE__)); //jquery ui	
		wp_register_script('sq_custom_jq', plugins_url('js/jq.js', __FILE__)); //jquery ui
	}
//load scripts for default page, create and edit and others	
	function load_scripts_default()
	{
		if (is_admin())
		{
			wp_deregister_script('tiny_mce'); 
			
			global $wp_version;
			if ($wp_version > 3.6)
			{
				wp_enqueue_script('jquery');
			} else 
			{
				wp_deregister_script('jquery');
				wp_enqueue_script('sq_custom_jq');
			}
			
			wp_enqueue_script('sq_custom_tinymce');
			wp_enqueue_script('sq_custom_ebox', false, array('jquery'));

			wp_enqueue_script('sq_custom_jui');
			wp_enqueue_script('lightcase');
			wp_enqueue_script('base64code');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('pickerscript');
			wp_enqueue_script('sqcommon');
		}
	}
	
	function load_scripts_squeeze_page()
	{
		wp_enqueue_script('editscript');
	}

//load script for social page	
	function load_scripts_social()
	{
		if (is_admin())
		{
			global $wp_version;
			if ($wp_version > 3.6)
			{
				wp_enqueue_script('jquery');
			} else 
			{
				wp_deregister_script('jquery');
				wp_enqueue_script('sq_custom_jq');
			}
			
			
			wp_enqueue_script('sqsocialscript');
			wp_enqueue_script('base64code');
			wp_enqueue_script('google-plusone', 'https://apis.google.com/js/plusone.js', array(), null, true);
			wp_enqueue_script('linkedin', 'http://platform.linkedin.com/in.js', array(), null, true);
			wp_enqueue_script('twitter', 'http://platform.twitter.com/widgets.js', array(), null, true);
	
		}
	}	

//load script for conversion tracking page
	function load_scripts_conversion_tk()
	{
		global $wp_version;
		if ($wp_version > 3.6)
		{
			wp_enqueue_script('jquery');
		} else 
		{
			wp_deregister_script('jquery');
			wp_enqueue_script('sq_custom_jq');
		}
		
		
		wp_enqueue_script('base64code');
		wp_enqueue_script('lightcase');
		wp_enqueue_script('sq_conversion_script');
	}	
	
	//including the custom stylesheet
	add_action('admin_init', 'add_style_sheet');

	
	function add_style_sheet()
	{
		wp_register_style('editstyle', plugins_url('css/style.css', __FILE__));
		
		wp_register_style('pickerstyle', plugins_url('css/colorpicker.css', __FILE__));
		
		wp_register_style('sq_ng_commonstyle', plugins_url('css/common.css', __FILE__));
		
		wp_register_style('sq_jui_min', plugins_url('css/jui-min.css', __FILE__));
		
		wp_register_style('lcstyle', plugins_url('js/lc/css/lc.css', __FILE__));//light case

		wp_register_style('sq_conv_style', plugins_url('css/conversion.css', __FILE__));//light case
		
        wp_register_style('sqsocialstyle', plugins_url('css/social.css', __FILE__));//social
		//wp_register_style('sq_flash_style', plugins_url('css/flp.css', __FILE__));//light case
	}
    
    
    
    	//load stylesheet for default page (within the plugin)
	function enqueue_custom_styles()
	{
		wp_enqueue_style('editstyle');
		wp_enqueue_style('lcstyle');
		wp_enqueue_style('sq_ng_commonstyle');
		wp_enqueue_style('pickerstyle');

	}
	
	//load stylesheet for social page
	function enqueue_social_styles()
	{
		wp_enqueue_style('sq_ng_commonstyle');
		wp_enqueue_style('sqsocialstyle');
	}
