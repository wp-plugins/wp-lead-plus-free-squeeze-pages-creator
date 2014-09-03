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
		$full_path = plugin_dir_path(__FILE__);
		//$ar = explode("wpleadplus", $full_path);
		$pattern = '|[/\\\]wpleadplus[/\\\]|';
		$ar = preg_split($pattern, $full_path);
		//return plugin_dir_path(__FILE__);
		return $ar[0];
	}
	
	

	global $wpdb;	
	//define some constants

	define('BGT_THEMES_TABLE', $wpdb->prefix.'sq_themes');
	
	define('BGT_CTA_BUTTONS_TABLE', $wpdb->prefix.'cta_buttons');
	define('BGT_SERVER_THEME_URL', "http://wpleadplus.com/updater/");
	define('BGT_SERVER_URL', "http://wpleadplus.com/");
	define('BGT_SERVER_ARCHIVE_URL', "http://wpleadplus.com/archive/smallers/");

	
	