<?php
//Common php functions for all the files

    //function to init the edit box
    function sq_common_editbox()
    { ?>
        <script>
		jQuery(document).ready(function(){
			//make the editbox resizable
			/*jQuery('#editparent').resizable({
				resize: function(){
					jQuery('#editbox').css("width", jQuery(this).width());
				}	
			});			*/
			tinymce.init({
			menubar: false,
			theme_advanced_resizing : true,
			theme_advanced_resizing_use_cookie : true,
			theme_advanced_font_sizes: "8px,9px,10px,11px,12px,13px,14px,15px,16px,17px,18px,19px,20px,21px,22px,23px,24px,25px,26px,27px,28px,29px,30px,31px,32px,33px,34px,35px,36px,37px,38px,39px,40px,41px,42px,43px,44px,45px,46px,47px,48px,49px,50px,51px,52px,53px,54px,55px,56px,57px,58px,59px,60px,61px,62px,63px,64px,65px,66px,67px,68px,69px,70px,71px,72px",
			fontsize_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 30px 31px 32px 33px 34px 35px 36px 37px 38px 39px 40px 41px 42px 43px 44px 45px 46px 47px 48px 49px 50px 51px 52px 53px 54px 55px 56px 57px 58px 59px 60px 61px 62px 63px 64px 65px 66px 67px 68px 69px 70px 71px 72px",
			plugins: 'textcolor link image code',
			selector: "#editbox",
			theme: 'modern',
			inline: false,
			toolbar1: 'code strikethrough forecolor backcolor removeformat',
			toolbar2: 'bullist numlist link unlink image',
			toolbar3: 'fontselect alignright alignleft',
			toolbar4: 'fontsizeselect alignjustify aligncenter ',
			resize: true,
			statusbar: true,
			browser_spellcheck : true,
			setup: function(ed)
			{
				ed.on("keyup", function(ed){
					//console.log(tinymce.get('editbox').getContent());
				//get the current id if the currentid span
				 var selected = jQuery("#"+jQuery("#current_id").text());
		
				  //get the current text in the edit box
				  var editbox_text = tinyMCE.get("editbox").getContent({format: 'text'});
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
				  }	else if (selected.is("input") || selected.is("textarea"))
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
			}
			
			});		
			
		});
		


		</script>
		
   <?php }
    
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
					$output['input'][] ='<input type="'.$input_array["type"][$i].'" name="'.$input_array["name"][$i].'" value="'.$input_array["value"][$i].'" id="'.$input_array["id"][$i].'" />';	
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
	//function to display upgrade
	function show_upgrade_text()
	{
		$link_text = array("Get PRO with a lot more templates, Conversion tracking and much more", "Get transparent templates, increase conversion rate now!", "Enable conversion tracking with WP Lead Plus now!", "Capture more leads with unblockable popups. Get it now!", "Get more cool templates with high conversion rate. Click here!");
		$link_anchor = array("get_pro", "transparent", "conversion", "popup", "more_templates");
		
		//generate a random number
		$x = rand(0, count($link_anchor) - 1);
		return '<div id="sq_bg_upgrade" style="font-size: 1.2em; position: fixed; top: 40px; right: 5px; font-weight: bold; z-index: 1000;"><a href="http://wpleadplus.com/?src=inedit'.$link_anchor[$x].'" target="_blank">'.$link_text[$x].'</a></div>';
	}