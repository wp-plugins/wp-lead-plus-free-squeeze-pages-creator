<?php
	include_once 'code/html_dom.php';
	function show_widget_themes() 
	{
		$widget_url = plugins_url("/themes/widgets/", __FILE__);
		$widget_path = plugin_dir_path(__FILE__).'/themes/widgets/';
		
		$thumbnail = scandir($widget_path.'thumbnail'); 
		
		for ($i=0; $i<count($thumbnail); $i++)
		{
			if (stripos($thumbnail[$i], '.jpg') !== FALSE)
			{
				$id = str_replace(".jpg", '', $thumbnail[$i]);
				echo '<div class="thumb">
				<a href="'.$widget_url.'thumbnail/'.$thumbnail[$i].'" rel="lightcase"><img src="'.$widget_url.'thumbnail/'.$thumbnail[$i].'" /></a>
				<input type="radio" name="widget_theme" id="'.$id.'" url="'.$widget_url."themes/$id".'" />
				</div>';
			}
			
		}
		
		echo '<div style="clear:both;"></div>';
		
		
	}
	
	//load the theme and return the code
	add_action('wp_ajax_widget_theme_loader', 'widget_theme_loader_cb');
	
	function widget_theme_loader_cb() {
		$content = file_get_contents(base64_decode($_POST['url']).'/code.txt');
		echo base64_encode($content);
		die();
	}
	
	//get the available colors of the current themes
	add_action('wp_ajax_widget_edit_switch_color', 'widget_switch_color_cb');
	
/* 	function widget_switch_color_cb()
	{
		$color_path = plugin_dir_path(__FILE__).'/themes/widgets/themes/'.$_POST['theme'].'/colors';
		$colors = scandir($color_path);
		$valid_color = array();
		
		for ($i=0; $i<count($colors); $i++)
		{
			if(stripos($colors[$i], '.jpg') !== FALSE)
			{
				$key = str_replace(".jpg", "", $colors[$i]);
				$valid_color[] = $key; 
			}
		}
		
		echo json_encode($valid_color);
		die();
	} */
	
	/* PARSE THE EMAIL AND SEND BACK TO THE CLIENT */
	add_action('wp_ajax_widget_parse_autoresponder', 'widget_parse_autoresponder_callback');
	
	function widget_parse_autoresponder_callback()
	{
		//get the email code passed by the client
		$mail_code = base64_decode($_POST['ar_code']);
		try {
			//get the action url
			$action_url = array();
			preg_match('/\baction.*\b/', $mail_code, $action_url);
			preg_match('/\bhttp.*\b/', $action_url[0], $action_url);
	
			$action_url = $action_url[0];
			$action_explode = explode('"', $action_url);
			$action_url = trim($action_url[0]);
	
			//create a new html dom object and load the email code
			$mail_object = str_get_html($mail_code);
				
			//get the input fields
			$inputs = $mail_object->find("input");
				
			//declare an array to store input fields
			$input_array = array();
	
			foreach ($inputs as $input)
			{
				if (($input->value == null) && ($input->type != 'hidden'))
				{
					
					$input_array["value"][] = $input->name;//in case the value of the  element is not set, make one
					
					
				} else
				{
					$input_array["value"][] = $input->value;
				}
				//if the form use input type = image, set it to submit
				if ($input->type == "image")
				{
					$input_array["type"][] = "submit";
				} else 
				{
					$input_array["type"][] = $input->type;
				}
				
				$input_array["name"][] = $input->name;
				$input_array["id"][] = preg_replace("/[^A-Za-z0-9 ]/", '', $input->name). rand(1,100).rand(1,100);//add ID to the input fields
			}
			
			if ((in_array('submit', $input_array['type']) === FALSE) && (in_array('image', $input_array['type']) === FALSE)) //if some stupid providers don't use standard submit button
			{
				$input_array["type"][] = 'submit';
				$input_array["name"][] = 'submit';
				$input_array["value"][] = 'Submit';
				$input_array["id"][] = 'submit' . rand(1,100).rand(1,100);//add ID to the input fields
			}
			 
			//output the text to the js
			$output = array();
			$output[0] = $action_url;
			for ($i=0; $i<count($input_array["name"]); $i++)
			{
				$output[] ='<input type="'.$input_array["type"][$i].'" name="'.$input_array["name"][$i].'" value="'.$input_array["value"][$i].'" id="'.$input_array["id"][$i].'" />';
			}
			//output the json
			echo (json_encode($output));
				
		} catch (Exception $e)
		{
		echo "something wrong";
		}
	
	
		die();
	}
	
	/* END PARSING THE EMAIL AND SEND BACK TO THE CLIENT */
	
	/* SHOW THE BUTTONS TO USERS */
		add_action('wp_ajax_widget_show_buttons', 'widget_show_button_cb');
		
		function widget_show_button_cb() 
		{
			$buttons = scandir(plugin_dir_path(__FILE__).'themes/widgets/buttons/'.$_POST['size']);
			
			$valid_buttons = array();
			
			for ($i=0; $i<count($buttons); $i++)
			{
				if (stripos($buttons[$i], '.png') !== FALSE)
				{
					$valid_buttons[] = $buttons[$i];
				}
			}
			
			echo json_encode($valid_buttons);
			
			die();
		}
	/* END SHOWING THE BUTTONS TO USERS */
		