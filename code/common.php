<?php
//Common php functions for all the files
	//echo getcwd();
    //function to init the edit box
    include_once 'const.php';


   //function to notify users to activate the plugin
   function sq_bgt_activation_notice()
   {
   		if (get_option('sq_activation_status') != 'activated')
   		{
   			return '<div style="position: fixed; padding: 10px 20px; bottom: 60px; right: 20px; border-radius: 5px; background: #FFD7A8;">You haven\'t activated your license yet. Please <a href="?page=pro_sqz_set">click here</a> and do it now. </div>';
   		}
   		
   		return false;
   }
   

   /* PARSE THE EMAIL AND SEND BACK TO THE CLIENT */
   add_action('wp_ajax_parse_autoresponder', 'parse_autoresponder_callback');
      
    //function to parse the auto responder
    	function parse_autoresponder_callback()
		{
		//get the email code passed by the client
		$mail_code = urldecode(base64_decode($_POST['ar_code']));
		
		//remove spam protection from mailchimp
		if (stripos($mail_code, 'mailchimp') !== FALSE)
		{
			$pattern = '/<div style="position: absolute; left: -5000px;">.*.<\/div>/i';
			$mail_code = preg_replace($pattern, '', $mail_code);
		}
		
		
		if (!function_exists("str_get_html"))
		{
			include_once 'html_dom.php';
		}
		
		try {
		
	
		//create a new html dom object and load the email code
		$mail_object = str_get_html($mail_code);
		$form_object = $mail_object->find("form");
		$form_id = $form_object[0]->id ? $form_object[0]->id : "";
		$form_name = $form_object[0]->name ? $form_object[0]->name : ""; 
		$action_url = $form_object[0]->action;
		//check if there is some inputs inside a hidden div, add the hidden style to them
		//$hidden_inputs = $mail_object->find("div[style: none]");
		
		//get the input fields
		$inputs = $mail_object->find("input");
		$selects = $mail_object->find("select");
		$text_area = $mail_object->find("textarea");
		//declare an array to store input fields
		$input_array = array();
		$select_array = array();
		$textarea_array = array();
		
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
				$input_array["id"][] = preg_replace("/[\W ]/", '', $input->name). rand(1,100).rand(1,100);//add ID to the input fields
			}
		

			
			if ((in_array('submit', $input_array['type']) === FALSE) && (in_array('image', $input_array['type']) === FALSE)) //if some stupid providers don't use standard submit button
			{
				$input_array["type"][] = 'submit';
				$input_array["name"][] = 'submit';
				$input_array["value"][] = 'Submit';
				$input_array["id"][] = 'submit' . rand(1,100).rand(1,100);//add ID to the input fields
			}
			
			//do the same for the select
			foreach ($selects as $select)
			{
				if ($select->name != NULL)
				{
					$select_array['name'][] = $select->name;
					
					if ($select->multiple != NULL)
					{
						$select_array['multiple'][] = $select->multiple;	
					}
					
					$temp_text = $select->innertext;
					$select_array['text'][] = $temp_text;
					$select_array['id'][] = 'selec'. rand(1,100).rand(1,100);
				}
				
			}
			
			//do the same for textarea
			foreach($text_area as $ta)
			{
				if ($ta->name != NULL)
				{
					$textarea_array['name'][] = $ta->name;
					$textarea_array['id'][] = 'textarea'. rand(1,100).rand(1,100);
				}
			}
						
			 
			//output the text to the js
			$output = array();
			$output['action_url'] = $action_url;
			$output['form_id'] = $form_id;
			$output['form_name'] = $form_name;
			
			for ($i=0; $i<count($input_array["name"]); $i++)
			{
				//discard the input with abs from wisyaja lol
				if (stripos($input_array["name"][$i], "[abs]") === false)
				{
					
					if (($input_array["type"][$i] == 'checkbox') || ($input_array["type"][$i] == 'radio'))
					{
						$output['input'][] ='<input type="'.$input_array["type"][$i].'" name="'.$input_array["name"][$i].'" value="'.$input_array["value"][$i].'" id="'.$input_array["id"][$i].'" /> '.'<span class="editable" id="spanchra"'.rand(1,100000000).'>'.$input_array["value"][$i].'</span>';
					} else 
					{
						$output['input'][] ='<input type="'.$input_array["type"][$i].'" name="'.$input_array["name"][$i].'" value="'.$input_array["value"][$i].'" id="'.$input_array["id"][$i].'" />';
					}
						
				}
				
			}
			
			//insert the select to the form
			for ($i=0; $i<count($select_array["name"]); $i++)
			{
				if (isset($select_array["multiple"][$i]))
				{
					$output['select'][] ='<select id="'.$select_array["id"][$i].'" multiple="'.$select_array["multiple"][$i].'" name="'.$select_array["name"][$i].'">'.$select_array['text'][$i].'</select>';					
				} else
				{
					$output['select'][] ='<select id="'.$select_array["id"][$i].'" name="'.$select_array["name"][$i].'">'.$select_array['text'][$i].'</select>';					
				}
				
			}
			
			for ($i = 0; $i < count($textarea_array['name']); $i++)
			{
				$output['textarea'][] = '<textarea id="'.$textarea_array['id'][$i].'" name="'.$textarea_array['name'][$i].'">'.$textarea_array['name'][$i].'</textarea>';
				
			}
			
			//insert the textarea to the form
			
			//output the json
			echo ("123dddsacxz". json_encode($output). "123dddsacxz");
	
		} catch (Exception $e)
		{
			echo "something wrong";
		}
	
	
		die();
		}
	add_action('wp_ajax_sq_bgt_switch_color', 'sq_bgt_check_theme_switch_color_cb');
	//function to switch the color
	function sq_bgt_check_theme_switch_color_cb()
	{
		//get the type of theme (squeeze page, popup, widget)
		$type = $_POST['theme_type'];
		$theme_id = $_POST['theme_id'];
		
		//get wp lead plus location
		$location = bgt_get_plugins_location() . '/wpleadplus/';
		
		$color = "hex";
		$img_array = array();
		if ($type == 'video')
		{
			if (is_dir($location . 'themes/video/' . $theme_id . '/colors'))
			{
				$color_choices = scandir($location . 'themes/video/' . $theme_id . '/colors');
				
				for ($i = 0; $i < count($color_choices); $i ++)
				{
					if (stripos($color_choices[$i], 'jpg') != false)
					{
						$img_array[] = $color_choices[$i];
					}
				}
				$color = json_encode($img_array);
			}	
		} else if ($type == 'traditional')
		{
			if (is_dir($location . 'themes/traditional/' . $theme_id . '/colors'))
			{
				$color_choices = scandir($location . 'themes/traditional/' . $theme_id . '/colors');
				
				for ($i = 0; $i < count($color_choices); $i ++)
				{
					if (stripos($color_choices[$i], 'jpg') != false)
					{
						$img_array[] = $color_choices[$i];
					}
				}
				$color = json_encode($img_array);
			}
		} else if ($type == 'popup')
		{
			if (is_dir($location . 'themes/popups/themes/' . $theme_id . '/2'))
			{
				$color = "image";
			}
		} else if ($type == 'widget')
		{
			$color = "hex";
		}
		var_dump($type);
		echo '123dddsacxz'.$color.'123dddsacxz';
		die();

		//check the theme folder, find if there is more than one child folder
		
	}
	
	function show_upgrade_text()
	{

		return '<div id="sq_bg_upgrade" style="font-size: 1.2em; position: fixed; top: 40px; right: 5px; font-weight: bold; z-index: 1000;"><a href="http://wpleadplus.com/contact" target="_blank">Need help? Send me a message</a></div>';
	}	