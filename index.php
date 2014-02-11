<?php
if (!($_SESSION))
{
session_start();
}
	/* Plugin Name: WP Lead Plus Free Squeeze Page Creator
	 * Plugin URI: http://wpleadplus.com/
	 * Author: Gato Vago
	 * Author URI: http://wpleadplus.com/
	 * Description: New way of creating squeeze pages/squeeze popup/squeeze sidebar optin with simple, revolutionary edit system. Get more powerful features at <a href="http://wpleadplus.com/?src=infreeplugin">http://wpleadplus.com/</a>
	 * Version: 1.6.2
	 */
	/*

	*/
	
	register_activation_hook(__FILE__, 'sq_bgt_on_activate');
	
	function sq_bgt_on_activate()
	{
		//copy the template to the current activate theme
		copy(plugin_dir_path(__FILE__).'code/sq_ddx_blankpage.php', get_template_directory().'/sq_ddx_blankpage.php' );
		//do the db things
		sq_bgt_on_act();
	}
    
	function sq_bgt_on_act(){
		add_buttons_to_db();
		add_backgrounds_to_db();
		add_theme_to_db();
	};


/**********************************DB FUNCTIONS*****************************************/

	
	//INSERTING BUTTONS TO DB**************************************
	function add_buttons_to_db()
	{
		global $wpdb;		
		try 
		{
			create_button_table($wpdb->prefix);
			insert_button_to_table($wpdb->prefix.'cta_buttons');
		} catch (Exception $e)
		{
			//var_dump($e);
		}
	}
	
	//create a table to store the buttons
	function create_button_table($prefix)
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.$prefix.'cta_buttons(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(50),
		`height` int(11),
		`width` int(11),
		PRIMARY KEY(`id`),
		UNIQUE (`name`)
		);';
	
		global $wpdb;
		$wpdb->query($myquery); 
	}
	
	//get the buttons and insert to the db
	function insert_button_to_table($table)
	{
		$buttons_folder = plugin_dir_path(__FILE__).'themes/buttons';
		$buttons = scandir($buttons_folder);
		global $wpdb;
	
		for ($i=0; $i<count($buttons); $i++)
		{
			if (stripos($buttons[$i], ".png") !== false)
			{
			//get the info of the image
				$image_info = getimagesize($buttons_folder.'/'.$buttons[$i]);
				//insert the button info into db
			$myquery = 'INSERT IGNORE INTO '.$table."(name, width, height) VALUES ('$buttons[$i]', '$image_info[0]', '$image_info[1]')";
			$wpdb->query($myquery);
			}
		}
	
	}
	//END INSERTING BUTTONS TO DB**************************************	

	//INSERTING BACKGROUND IMAGES TO DB************************************
	function add_backgrounds_to_db()
	{
		global $wpdb;
		try 
		{
			create_background_table($wpdb->prefix);
			insert_background_to_table($wpdb->prefix.'background_img');
		} catch (Exception $e)
		{
			//var_dump($e);
		}
		
	}

	//create table for the backgrounds
	function create_background_table($prefix)
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.$prefix.'background_img(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(50),
		`height` int(11),
		`width` int(11),
		PRIMARY KEY(`id`),
		UNIQUE (`name`)
		);';	
		global $wpdb;
		$wpdb->query($myquery);
	}
	
	//get the buttons and insert to the db
	function insert_background_to_table($table)
	{
		$bg_folder = plugin_dir_path(__FILE__).'themes/bgs';
		$bgs = scandir($bg_folder);
		global $wpdb;	
		for ($i=0; $i<count($bgs); $i++)
		{
		if (stripos($bgs[$i], ".jpg") !== false)
		{
		//get the info of the image
		$image_info = getimagesize($bg_folder.'/'.$bgs[$i]);
		//insert the button info into db
		$myquery = 'INSERT IGNORE INTO '.$table."(name, width, height) VALUES ('$bgs[$i]', '$image_info[0]', '$image_info[1]')";
		$wpdb->query($myquery);
		}
		}
	
		}
	//END INSERTING BACKGROUND IMAGES TO DB************************************	

	//ADD THE THEMES TO DB**************************************************
	function add_theme_to_db()
	{
		global $wpdb;
		try
		{
			create_theme_table($wpdb->prefix);
			insert_theme_to_db($wpdb->prefix.'sq_themes');
		} catch (Exception $e)
		{
			//var_dump($e);
		}		
	}
	
	function create_theme_table($prefix)
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.$prefix.'sq_themes(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`thumbnail` VARCHAR(50),
		`name` VARCHAR(50),
		`type` ENUM(\'video\', \'traditional\'),
		`has_bg` ENUM(\'yes\', \'no\'),
		PRIMARY KEY(`id`),
		UNIQUE (`thumbnail`, `type`)
		);';//group will be decide which themes can it switch theme to		
		global $wpdb;
		$wpdb->query($myquery);
		
	}
	
    //activation
	function insert_theme_to_db($table)
	{
		//access to wpdb
		global $wpdb;		
		//find path to the themes directories
		$video_themes = scandir(plugin_dir_path(__FILE__).'themes/video');
		$traditional_themes = scandir(plugin_dir_path(__FILE__).'themes/traditional');		
		//insert the video themes
		for ($i=0; $i<count($video_themes); $i++)
		{
			if ((stripos($video_themes[$i], ".") === FALSE) && ($video_themes[$i] !== thumbnail))
			{
					
				//check if the theme has image bg or not
				$has_bg = 'yes';

				//prepare the data to insert into the db
				$theme_data = array(
						'name' => $video_themes[$i],
						'thumbnail' => $video_themes[$i].'.jpg',
						'type' => 'video',
						'has_bg' => $has_bg
				);
				try
				{
					$wpdb->insert($table, $theme_data);
				} catch(Exception $e)
				{
					//var_dump($e);
				}
			}

		}


		//insert the traditional themes
		for ($i=0; $i<count($traditional_themes); $i++)
		{
			if ((stripos($traditional_themes[$i], ".") === FALSE)  && ($traditional_themes[$i] !== thumbnail))
			{
				
				//all themes can have bg
				$has_bg = 'yes';

				//prepare the data to insert into the db
				$theme_data = array(
						'name' => $traditional_themes[$i],
						'thumbnail' => $traditional_themes[$i].'.jpg',
						'type' => 'traditional',
						'has_bg' => $has_bg
						);
				try
				{
					$wpdb->insert($table, $theme_data);
				} catch(Exception $e)
				{
					//var_dump($e);
				}
			}
		
		}
		
	}
    
    

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
		
//add facebook to the edit page
	function sq_insert_fb_script()
	{
		echo '<div id="fb-root"></div><script>jQuery(document).ready(function(){(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";  fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));});</script>';
	}
	//add the menu to dashboard
	add_action('admin_menu', 'register_pro_squeezers');
	function register_pro_squeezers()
	{
		$main_page = add_menu_page('WP Lead Plus Home', 'WP Lead Plus', 'manage_options', 'pro_sqz_set', 'main_squeezers_cb');
		$edit_page = add_submenu_page('pro_sqz_set', 'Add New/ Edit Page', 'Create Squeeze Pages', 'manage_options', 'sub_squeezers_new', 'sub_squeezers_new_cb');
		
		//go pro page
		$go_pro_page = add_submenu_page('pro_sqz_set', 'Go PRO', 'Go PRO!', 'manage_options', 'sub_squeezers_go_pro', 'sub_squeezers_go_pro_cb');
		
		$settings_page = add_submenu_page('pro_sqz_set', 'WP Lead Plus Settings', 'Settings', 'manage_options', 'sub_squeezers_set', 'sub_squeezers_settings_cb');
		
		
		add_action( 'admin_print_styles-' . $main_page, 'enqueue_custom_styles' );
		add_action( 'admin_print_styles-' . $edit_page, 'enqueue_custom_styles' );
		add_action( 'admin_print_styles-' . $settings_page, 'enqueue_custom_styles' );
		add_action( 'admin_print_styles-' . $go_pro_page, 'enqueue_custom_styles' );
		
		add_action( 'admin_print_styles-' . $widget_page, 'enqueue_widget_styles' );
		
		add_action( 'admin_print_styles-' . $social_page, 'enqueue_social_styles' );
		
		add_action( 'admin_print_styles-' . $popup_create, 'enqueue_popup_styles' );
		add_action( 'admin_print_styles-' . $popup_manage, 'enqueue_popup_styles' );
		
		add_action( 'admin_print_styles-' . $gallery_page, 'enqueue_gallery_styles' );
		
		//script
		add_action( 'admin_print_styles-' . $main_page, 'load_scripts_default' );
		add_action( 'admin_print_styles-' . $edit_page, 'load_scripts_default' );
		add_action( 'admin_print_styles-' . $settings_page, 'load_scripts_default' );
		
		add_action( 'admin_print_styles-' . $widget_page, 'load_scripts_widget' );
		
		add_action( 'admin_print_styles-' . $popup_create, 'load_scripts_popup' );
		add_action( 'admin_print_styles-' . $popup_manage, 'load_scripts_popup' );
		
		add_action( 'admin_print_styles-' . $gallery_page, 'load_scripts_theme_gallery' );
		
		add_action( 'admin_print_styles-' . $social_page, 'load_scripts_social' );
	}    