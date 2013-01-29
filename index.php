<?php
	error_reporting('E_ALL');
	/* Plugin name: WP Lead Plus
	 * Plugin URI: http://wpleadplus.com/
	 * Author: Bueno Gato
	 * Author URI: http://wpleadplus.com/
	 * Description: Creating high converting squeeze pages can't be easier. Simply point and click and you will get a really awesome squeeze page. Find out more at http://wpleadplus.com/
	 * Version: 1.4.1
	 */
	if (!($_SESSION))
	{
		session_start();
	}
	
	
	//include the settings
	include_once 'settings.php';

	//add the buttons and the backgrounds to the db
	register_activation_hook(__FILE__, 'add_buttons_to_db');	
	//add the background images to the db
	register_activation_hook(__FILE__, 'add_backgrounds_to_db');
	//add the theme to the db
	register_activation_hook(__FILE__, 'add_theme_to_db');
