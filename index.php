<?php
	if (!(isset($_SESSION)))
	{
		session_start();
	}
	
	/* Plugin Name: WP Lead Plus Free Squeeze Page Creator
	 * Plugin URI: http://wpleadplus.com/
	 * Author: Gato Vago
	 * Author URI: http://wpleadplus.com/
	 * Description: New way of creating squeeze pages/squeeze popup/squeeze sidebar optin with simple, revolutionary edit system. Get more powerful features at <a href="http://wpleadplus.com/?src=infreeplugin">http://wpleadplus.com/</a>
	 * Version: 1.6.4
	 */

	include_once ('settings.php');
	include_once ('activate.php');


	register_activation_hook(__FILE__, 'sq_bgt_on_activate');

	function sq_bgt_on_activate()
	{
		//copy the template to the current activate theme
		copy(plugin_dir_path(__FILE__).'code/sq_ddx_blankpage.php', get_template_directory().'/sq_ddx_blankpage.php' );
		copy(plugin_dir_path(__FILE__).'code/sq_ddx_blankpage_ab.php', get_template_directory().'/sq_ddx_blankpage_ab.php' );
		//do the db things
		sq_bgt_on_act();
	} 