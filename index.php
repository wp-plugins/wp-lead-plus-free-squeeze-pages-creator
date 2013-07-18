<?php
if (!($_SESSION))
{
session_start();
}
	/* Plugin Name: WP Lead Plus Free Squeeze Page Creator
	 * Plugin URI: http://wpleadplus.com/
	 * Author: Bueno Gato
	 * Author URI: http://wpleadplus.com/
	 * Description: New way of creating squeeze pages/squeeze popup/squeeze sidebar optin with simple, revolutionary edit system. Get more powerful features at http://wpleadplus.com/
	 * Version: 1.5.6
	 */
	/*

	*/
	
	//include the settings
	include_once 'settings.php';
	include_once 'activate.php';
	
	register_activation_hook(__FILE__, 'sq_bgt_on_activate');
	
	function sq_bgt_on_activate()
	{
		//copy the template to the current activate theme
		copy(plugin_dir_path(__FILE__).'code/sq_ddx_blankpage.php', get_template_directory().'/sq_ddx_blankpage.php' );
		//do the db things
		sq_bgt_on_act();
	}