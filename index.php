<?php
	error_reporting('E_ALL');
	/* Plugin Name: WP Lead Plus Free Squeeze Page Creator
	 * Plugin URI: http://wpleadplus.com/
	 * Author: Bueno Gato
	 * Author URI: http://wpleadplus.com/
	 * Description: New way of creating squeeze pages/squeeze popup/squeeze sidebar optin with simple, revolutionary edit system.
	 * Version: 1.4.8
	 */
	if (!($_SESSION))
	{
		session_start();
	}
	
	
	//include the settings
	include_once 'settings.php';
	
	register_activation_hook(__FILE__, 'copy_to_current_theme');
	
	function copy_to_current_theme()
	{
		//copy the template to the current activate theme
		copy(plugin_dir_path(__FILE__).'code/sq_ddx_blankpage.php', get_template_directory().'/sq_ddx_blankpage.php' );
	}
	
	
	