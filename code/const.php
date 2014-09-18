<?php
	
	include_once bgt_get_wp_installation().'/wp-load.php';
	//get the document root
	function bgt_get_wp_installation()
	{
		$full_path = getcwd();
		$ar = explode("wp-", $full_path);
		
		return $path = $ar[0];

		if ( (substr($path, -1) != '/') || (substr($path, -1) != '\\') )
		{
			$path .= '/';
		} 

		return $ar[0];
	}
	
	function bgt_get_plugins_location()
	{
		
		$this_folder_path = plugin_dir_path(__FILE__);
		$plugin_folder_path = substr($this_folder_path, 0, strlen($this_folder_path) - 5); //with slash -3 because this folder name is fn
		return $plugin_folder_path;
	}
	
	

	global $wpdb;	
	//define some constants

	define('BGT_THEMES_TABLE', $wpdb->prefix.'sq_themes');
	
	define('BGT_CTA_BUTTONS_TABLE', $wpdb->prefix.'cta_buttons');
	define('BGT_SERVER_THEME_URL', "http://wpleadplus.com/updater/");
	define('BGT_SERVER_URL', "http://wpleadplus.com/");
	define('BGT_SERVER_ARCHIVE_URL', "http://wpleadplus.com/archive/smallers/");

	
	