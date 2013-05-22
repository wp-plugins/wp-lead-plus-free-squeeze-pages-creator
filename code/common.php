<?php
//Common php functions for all the files
    //add custom font-size to the editor
	function sq_my_new_text_sizes($initArray){
		$initArray['theme_advanced_font_sizes'] = "8px,9px,10px,11px,12px,13px,14px,15px,16px,17px,18px,19px,20px,21px,22px,23px,24px,25px,26px,27px,28px,29px,30px,32px,36px,38px,40px,42px,44px,46px,48px,50px,60px,68px,72px";
		return $initArray;
	}
	
	// Assigns customize_text_sizes() to "tiny_mce_before_init" filter
	add_filter('tiny_mce_before_init', 'sq_my_new_text_sizes');
    //function to init the edit box
    function sq_common_editbox()
    {
        $settings = array(
								'textarea_name' => 'editbox',
								'media_buttons' => true,
								'editor_css' => '',
								'tinymce' => array(
										'theme_advanced_buttons1' => 'bold,italic,link,unlink,bullist,backcolor,cut',
                                                                                'theme_advanced_buttons2' => 'fontselect,forecolor,removeformat,justifyfull',
                                                                                'theme_advanced_buttons3' => 'fontsizeselect,justifyleft,justifycenter,justifyright',
										'setup' => 'function(ed) {
					  	  ed.onKeyUp.add(function(ed, e) {
					
						  //get the current id if the currentid span
						 var selected = jQuery("#"+jQuery("#current_id").text());
		
						  //get the current text in the edit box
						  var editbox_text = tinyMCE.get("editbox").getContent({format: \'text\'});
						  editbox_text = editbox_text.replace(/<[^>]*>/g, "");
						  //get the current html content of the edit box
						  var editbox_html = tinyMCE.get("editbox").getContent();
						  //need to replace automatically inserted <p> and </p> tags
						  editbox_html = editbox_html.replace(/<p>/g, "");
						  editbox_html = editbox_html.replace(/<\/p>/g, "<br />");
						  if ((selected.is("span")) || (selected.is("li")))
						  {
							selected.html(editbox_html);			
						  }	else if (selected.is("a"))
						  {
							selected.text(editbox_text);
							jQuery("#sq_temp_edit_text").html(editbox_html);
							selected.css("font-size",jQuery("#sq_temp_edit_text span").css("font-size"));
							selected.css("color",jQuery("#sq_temp_edit_text span").css("color"));
							selected.css("font-style",jQuery("#sq_temp_edit_text span").css("font-style"));
							selected.css("font-weight",jQuery("#sq_temp_edit_text span").css("font-weight"));	
						  }	else if (selected.is("input"))
						  {
							if (selected.attr("placeholder") != undefined)
							{
								jQuery("#sq_temp_edit_text").html(editbox_html);
								selected.attr("placeholder",jQuery.trim(editbox_text));		
								selected.css("font-size",jQuery("#sq_temp_edit_text span").css("font-size"));
								selected.css("color",jQuery("#sq_temp_edit_text span").css("color"));
								selected.css("font-style",jQuery("#sq_temp_edit_text span").css("font-style"));
								selected.css("font-weight",jQuery("#sq_temp_edit_text span").css("font-weight"));
								selected.css("font-family",jQuery("#sq_temp_edit_text span").css("font-family"));						
							} else 
							{
								selected.attr("value",jQuery.trim(editbox_text));	
								jQuery("#sq_temp_edit_text").html(editbox_html);
								selected.css("font-size",jQuery("#sq_temp_edit_text span").css("font-size"));
								selected.css("color",jQuery("#sq_temp_edit_text span").css("color"));
								selected.css("font-style",jQuery("#sq_temp_edit_text span").css("font-style"));
								selected.css("font-weight",jQuery("#sq_temp_edit_text span").css("font-weight"));	
								selected.css("font-family",jQuery("#sq_temp_edit_text span").css("font-family"));			
							}			
												
						  }	else if (selected.hasClass("editable"))
						  {
								selected.html(editbox_html);	
						  } else if (selected.is("img")) //if selected is an image			
                                                        {
                                                            jQuery("#sq_temp_edit_text").html(editbox_html);
                                                            selected.attr("src", jQuery("#sq_temp_edit_text img").attr("src"));
                                                            selected.attr("width", jQuery("#sq_temp_edit_text img").attr("width"));
                                                            selected.attr("height", jQuery("#sq_temp_edit_text img").attr("height"));
                                                            selected.attr("alt", jQuery("#sq_temp_edit_text img").attr("alt"));

                                                        }
							
					  });
				   }'));
					
					wp_editor("start editing here", "editbox", $settings);
    }
    
    //function to parse the auto responder
    	function parse_autoresponder_callback()
		{
		//get the email code passed by the client
		$mail_code = urldecode(base64_decode($_POST['ar_code']));
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
		//declare an array to store input fields
		$input_array = array();
		$select_array = array();
		
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
					$output['input'][] ='<input type="'.$input_array["type"][$i].'" name="'.$input_array["name"][$i].'" value="'.$input_array["value"][$i].'" id="'.$input_array["id"][$i].'" />';	
				}
			}
			
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
			//output the json
			echo (json_encode($output));
	
		} catch (Exception $e)
		{
			echo "something wrong";
		}
	
	
		die();
		}