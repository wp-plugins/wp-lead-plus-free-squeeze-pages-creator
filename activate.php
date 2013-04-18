<?php							 
	function sq_bgt_on_act(){
		add_buttons_to_db();
		add_backgrounds_to_db();
		add_theme_to_db();
		add_sq_widget_popup_table();
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
			var_dump($e);
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
	function wp_editor_fontsize_filter( $options ) {
		array_shift( $options );
		array_unshift( $options, 'fontsizeselect');
		array_unshift( $options, 'formatselect');
		return $options;
	}
	add_filter('mce_buttons_2', 'wp_editor_fontsize_filter');
	
	
	
	//CREATE A TABLE FOR STORING WIDGETS
	function create_sq_widget_table($prefix)
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.$prefix.'sq_widget_code(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`widget_id` VARCHAR(50),		
		`name` VARCHAR(255),
		`code` text,
		`css_url` text,
		PRIMARY KEY(`id`),
		UNIQUE (`widget_id`)
		);';	
		global $wpdb;
		$wpdb->query($myquery);
	}	
	//CREATE A TABLE FOR STORING POPUP
	function create_sq_popup_table($prefix)
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.$prefix.'sq_popup_code(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`popup_id` VARCHAR(50),		
		`name` VARCHAR(255),
		`code` text,
		`css_url` text,
		PRIMARY KEY(`id`),
		UNIQUE (`popup_id`)		
		);';	
		global $wpdb;
		$wpdb->query($myquery);
	}
	
	
	//CREATE A TABLE FOR STORING POPUP OPTIONS
	function create_sq_popup_option_table($prefix)
	{
		//display_area (on total site or on specific post/category)
		$myquery = 'CREATE TABLE IF NOT EXISTS '.$prefix.'sq_popup_option(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`popup_id` VARCHAR(50),
		`appear_position` VARCHAR(255),
		`appear_behavior` VARCHAR(255),
		`background_color` VARCHAR(255),
		`display_area` VARCHAR(255), 
		`background_cover` VARCHAR(255),
		`delay` int(11),
		`status` VARCHAR(255),
		`frequency` VARCHAR(255),
		PRIMARY KEY(`id`)
		);';	
		global $wpdb;
		$wpdb->query($myquery);
	}	
	//ACTUALLY CREATE THE TABLE OF WIDGET AND POPUP
	function add_sq_widget_popup_table()
	{
		global $wpdb;
	
		try
		{
			create_sq_popup_table($wpdb->prefix);
			create_sq_widget_table($wpdb->prefix);
			create_sq_popup_option_table($wpdb->prefix);			
		} catch (Exception $e)
		{
			var_dump($e);
		}
	}
	
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
			var_dump($e);
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
			var_dump($e);
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
		UNIQUE (`thumbnail`)
		);';//group will be decide which themes can it switch theme to		
		global $wpdb;
		$wpdb->query($myquery);
		
	}
	
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
			if (stripos($video_themes[$i], ".") === FALSE)
			{
					
				//check if the theme has image bg or not
				if (file_exists(plugin_dir_path(__FILE__).'themes/video/'.$video_themes[$i].'/site_bg.jpg'))
				{
					$has_bg = 'yes';
				} else
				{
					$has_bg = 'no';
				}
				//prepare the data to insert into the db
				$theme_data = array(
						'name' => $video_themes[$i],
						'thumbnail' => 'v_'.$video_themes[$i].'.jpg',
						'type' => 'video',
						'has_bg' => $has_bg
				);
				try
				{
					$wpdb->insert($table, $theme_data);
				} catch(Exception $e)
				{
					var_dump($e);
				}
			}

		}


		//insert the traditional themes
		for ($i=0; $i<count($traditional_themes); $i++)
		{
			if (stripos($traditional_themes[$i], ".") === FALSE)
			{
				
				//check if the theme has image bg or not
				if (file_exists(plugin_dir_path(__FILE__).'themes/traditional/'.$traditional_themes[$i].'/site_bg.jpg'))
				{
					$has_bg = 'yes';
				} else
				{
					$has_bg = 'no';
				}
				//prepare the data to insert into the db
				$theme_data = array(
						'name' => $traditional_themes[$i],
						'thumbnail' => 't_'.$traditional_themes[$i].'.jpg',
						'type' => 'traditional',
						'has_bg' => $has_bg
						);
				try
				{
					$wpdb->insert($table, $theme_data);
				} catch(Exception $e)
				{
					var_dump($e);
				}
			}
		
		}
		
	}
	
	//END ADDING THE THEMES TO DB**************************************************	

/**********************************END DB FUNCTIONS*****************************************/