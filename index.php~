<?php
	error_reporting('E_ALL');
	/* Plugin name: WP Lead Plus
	 * Plugin URI: http://wpleadplus.com/
	 * Author: Bueno Gato
	 * Author URI: http://wpleadplus.com/
	 * Description: New way of creating squeeze pages/squeeze popup/squeeze sidebar optin. Forget about confusing code. &copy WP Lead Plus. A production of Bueno Gato
	 * Version: 1.3
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
	//create the popup/widget table
	register_activation_hook(__FILE__, 'add_sq_widget_popup_table');