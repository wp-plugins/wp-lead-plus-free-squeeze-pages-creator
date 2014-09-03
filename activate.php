<?php							 
	//error_reporting(E_ALL ^ E_NOTICE);
	include_once 'code/const.php';
	function sq_bgt_on_act(){
		update_option("sq_bgt_plugin_path", plugins_url(__FILE__));
		sq_bgt_add_buttons_to_db();
	};


/**********************************DB FUNCTIONS*****************************************/

	
	//INSERTING BUTTONS TO DB**************************************
	function sq_bgt_add_buttons_to_db()
	{
		global $wpdb;		
		try 
		{
			sq_bgt_create_button_table();
			sq_bgt_insert_button_to_table(BGT_CTA_BUTTONS_TABLE);
		} catch (Exception $e)
		{
			//var_dump($e);
		}
	}
	
	//create a table to store the buttons
	function sq_bgt_create_button_table()
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.BGT_CTA_BUTTONS_TABLE.'(
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
	function sq_bgt_insert_button_to_table($table)
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

	

	
	//function to add a column to a table
	function sq_bgt_add_column_if_not_exists($table, $column, $type, $wpdb)
	{
		try 
		{
		
			$test_query = "SHOW COLUMNS FROM $table";
			
			$all_columns = $wpdb->get_results($test_query, "ARRAY_A");
			
			for ($i = 0; $i < count($all_columns); $i++)
			{
				//if the column exists, return
				if ($all_columns[$i]["Field"] == $column)
				{
					return;
				}
			}
			
			$add_query = "ALTER TABLE $table ADD $column $type NOT NULL";

			$wpdb->query($add_query);

		} catch (Exception $e)
		{
			print($e);
		}
		
	}