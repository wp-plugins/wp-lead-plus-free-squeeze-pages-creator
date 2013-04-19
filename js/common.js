/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    	//EDIT THE TEXT OF THE THEME
        jQuery("document").ready(function(){
			
			//use submit button as a link
		jQuery("#sq_submit_url").blur(function(){
			//get the current select element, check if it is a submit button
			var current_elem = "#" + jQuery("#current_id").text();
			
			//if the user entered a valid url, wrap the link around the submit button
			if (jQuery(this).val().indexOf("http") != -1)
			{
				
				//check if the current selected element is a submit button
				if ((jQuery(current_elem).is("input[type=submit]")) || (jQuery(current_elem).is("input[type=image]")))
				{
					//add the onlick property to the element
					jQuery(current_elem).attr("onclick", 'window.open(\''+jQuery(this).val()+ '\');')
					
				}	
			} else if (jQuery.trim(jQuery(this).val()) == "")
			{
				jQuery(current_elem).removeAttr("onclick");
			}
			
		});
			
		jQuery("#site_area img, #site_area a, #site_area .editable, #site_area input, #site_area select").live("click", function(){
			//register the id
			jQuery("#current_id").text("");
                        
			//create an id if the current element doesn't have an ID
			if (jQuery(this).attr("id") == undefined)
			{
				var random = 'rand_id' + Math.round((Math.random()*1000 + Math.random()*1000 + Math.random()*1000));
				jQuery(this).attr("id", random);
			}
                        
			jQuery("#current_id").text(jQuery(this).attr("id"));
			//update the content to the edit box
			if (jQuery(this).is("input")) //in case the clicked element is an input, put the text into the editbox
			{
				if (jQuery(this).attr("placeholder") != undefined)//html5, if placeholder is used
				{
					var text = jQuery(this).attr("placeholder");
				} else 
				{
					var text = jQuery(this).attr("value");
				}
				
				tinyMCE.get("editbox").setContent(text);
				return false;

			} else if ((jQuery(this).is("span")) || (jQuery(this).is("li"))) 
			{
				tinyMCE.get("editbox").setContent(jQuery(this).html());
				
			} else if (jQuery(this).is("img"))
			{
				var cloner = jQuery(this).clone();
				//clear the temp edit
				jQuery('#sq_temp_edit_text').html("");
				cloner.removeAttr("id");
				cloner.appendTo('#sq_temp_edit_text');
				tinyMCE.get("editbox").setContent(jQuery('#sq_temp_edit_text').html());					
					
			} else if (jQuery(this).is("a"))
			{
				jQuery("#linkurl").fadeIn();
				tinyMCE.get("editbox").setContent(jQuery(this).text());
				return false;
			
			} else if (jQuery(this).hasClass("editable"))
			{
				tinyMCE.get("editbox").setContent(jQuery(this).html());
			} 
		});		
	//END EDITING THE TEXT OF THE THEME
	
	
	//ADD AND REMOVE BUTTON
		/* get the current element, decide what it is, if it's an editable, remove its parent, if it's an image, remove
		 * itself, if it's a link inside a li, remove its parent, do nothing with a input
		 */
	jQuery("#edit_removeb").click(function(){
		//get the current selected id
		var current_element = jQuery("#"+jQuery("#current_id").text());
		if (current_element.is("a"))
		{
			if (current_element.parent().is("li"))
			{
				current_element.parent().toggle()
				
				//if the parent doesn't have an ID, add one
				if (current_element.parent().attr("id") == undefined) {
					current_element.parent().attr("id", "rmvid"+Math.random(1,1000) + Math.random(1,10000));
				}	
				
				//append the hidden element id to the history
				if (!current_element.parent().is(":visible"))
				{
					jQuery('#sq_remove_history').append("<li>"+current_element.parent().attr("id")+"</li>");	
				}
				
			} else
			{
				current_element.toggle();
				//append the hidden element id to the history
				if (!current_element.is(":visible"))
				{
					jQuery('#sq_remove_history').append("<li>"+current_element.attr("id")+"</li>");	
				}
				
			}	
			
		} else if (current_element.is("img"))
		{
			current_element.toggle();
			//append the hidden element id to the history
			if (!current_element.is(":visible"))
			{
				jQuery('#sq_remove_history').append("<li>"+current_element.attr("id")+"</li>");	
			}
			
		} else if (current_element.is("li"))
		{
            current_element.toggle();
			//append the hidden element id to the history
			if (!current_element.is(":visible"))
			{
				jQuery('#sq_remove_history').append("<li>"+current_element.attr("id")+"</li>");	
			}
			
		} else if (current_element.is("input"))
		{
            current_element.toggle();
			//append the hidden element id to the history
			if (!current_element.is(":visible"))
			{
				jQuery('#sq_remove_history').append("<li>"+current_element.attr("id")+"</li>");	
			}
			
		} else if (current_element.hasClass("editable"))
		{
			if (current_element.is("div"))
			{
				current_element.toggle();
			} else
			{
				current_element.parent().toggle();	
			}
			
			
			//if the parent doesn't have an ID, add one
			if (current_element.parent().attr("id") == undefined) {
				current_element.parent().attr("id", "rmvid"+Math.round(Math.random()*10000 )+ Math.round(Math.random()*10000));
			}
			//append the hidden element id to the history
			if (!current_element.parent().is(":visible"))
			{
				jQuery('#sq_remove_history').append("<li>"+current_element.parent().attr("id")+"</li>");
			}
			
		} else if (current_element.is("select"))
		{
			current_element.toggle();
			//append the hidden element id to the history
			if (!current_element.is(":visible"))
			{
				jQuery('#sq_remove_history').append("<li>"+current_element.attr("id")+"</li>");	
			}
		}
		
		
	});
	
	//the add button
	jQuery("#edit_addb").click(function(){
		//get the current selected id
		var current_element = jQuery("#"+jQuery("#current_id").text());
		if (current_element.is("a"))
		{
			if (current_element.parent().is("li"))
			{
				var clone_elem = current_element.parent().clone();
				var chil = clone_elem.find("*");//get all the children of current tr
				var randnum = Math.floor((Math.random()*1000)+1); //generate a random number
				for (var i = 0; i<chil.length; i++)
				{
					if (chil.eq(i).attr("id") != undefined)
					{
						chil.eq(i).removeAttr("id");//remove the current id
						chil.eq(i).attr("id", "adder"+randnum+i);//assign new id
					}
				}
				//get a new id for the inserted element
				clone_elem.attr("id", "cloner"+randnum);
				
				//insert the newly created element into the page
				clone_elem.insertAfter(current_element.parent());
				
			} else
			{
				var clone_elem = current_element.clone();
				
				//generate a random number
				var randnum = Math.floor((Math.random()*1000)+1); //generate a random number
				clone_elem.attr("id", "aclone"+ randnum);
				//insert into dom
				clone_elem.insertAfter(current_element);
			}
		} else if (current_element.hasClass("editable")) //it could be span or li
		{
			if (current_element.is("li"))
                        {
                            var clone_elem = current_element.clone();
                            var chil = clone_elem.find("*");//get all the children of current tr
                            var randnum = Math.floor((Math.random()*10000)+1); //generate a random number
                            for (var i = 0; i<chil.length; i++)
                            {
                                    if (chil.eq(i).attr("id") != undefined)
                                    {
                                            chil.eq(i).removeAttr("id");//remove the current id
                                            chil.eq(i).attr("id", "adder"+randnum+i);//assign new id
                                    }
                            }
                            //get a new id for the inserted element
                            clone_elem.attr("id", "ecloner"+randnum);
                            //insert the newly created element into the page
                            clone_elem.insertAfter(current_element);      
                        } else
                        {
                            var clone_elem = current_element.parent().clone();
                            var chil = clone_elem.find("*");//get all the children of current tr
                            var randnum = Math.floor((Math.random()*10000)+1); //generate a random number
                            for (var i = 0; i<chil.length; i++)
                            {
                                    if (chil.eq(i).attr("id") != undefined)
                                    {
                                            chil.eq(i).removeAttr("id");//remove the current id
                                            chil.eq(i).attr("id", "adder"+randnum+i);//assign new id
                                    }
                            }
                            //get a new id for the inserted element
                            clone_elem.attr("id", "ecloner"+randnum);
                            //insert the newly created element into the page
                            clone_elem.insertAfter(current_element.parent());    
                        }
                        
			
		} 
		
		
	});
	
	//show and hide the custom code box	
	jQuery("#code_customb").click(function(){
		jQuery("#code_boxes textarea").not("#custom_code").fadeOut();
		jQuery("#custom_code").fadeToggle();
		jQuery("#custom_code_position").fadeToggle();
	});

	//show and hide the css code box	
	jQuery("#code_cssb").click(function(){
		jQuery("#code_boxes textarea").not("#sq_css_code").fadeOut();
		jQuery("#sq_css_code").fadeToggle();
	});	
	
	//insert custom elements into the page
	jQuery("#custom_code").blur(function(){
		jQuery(this).fadeOut();
		jQuery('#custom_code_position').fadeOut();
		
		if ((jQuery(this).val() == "") || (jQuery(this).val() == "Enter your custom code here"))
		{
			return;
		}
		
		var id = "custom" + Math.round(Math.random()*10000) + Math.round(Math.random()*10000)
		var text = "<div class='editable' id='"+id+"'>" + jQuery(this).val() + "</div>";
		if (jQuery('#custom_code_position input[type=radio]:checked').val() == "above")
		{
			jQuery(text).insertBefore('#'+jQuery('#current_id').text());
		} else
		{
			jQuery(text).insertAfter('#'+jQuery('#current_id').text());			
		}
	});
	
	
	//the undo button
	jQuery('#edit_undob').click(function(){
		//get the latest removed element's id and restore it then remove the li in the history
		if (!jQuery('#' + jQuery('#sq_remove_history li:last-child').text()).is(":visible")) {
			jQuery('#' + jQuery('#sq_remove_history li:last-child').text() ).fadeIn();
		}
		
		jQuery('#sq_remove_history li:last-child').remove();
	
	});
		//insert the email code
	jQuery("#email_code, #popup_email_code, #widget_email_code").blur(function(){

		if(jQuery(this).val().indexOf("method") != -1)//in case the user has pased the autoresponder code in, at least not default
		{
			//filter the data before sending to server
			var email_code = (jQuery(this).val());
			
			//jQuery("#tempo_responder").html(email_code);
			//jQuery("#tempo_responder li, #tempo_responder div").filter(function(){return jQuery(this).css("display") == 'none';}).find("input[type='text']").remove();
			//prepare the data before sending to server
			data = {
					action: 'parse_autoresponder',
					ar_code: BASE64.encode(escape(email_code))
			};
			//send the code to server to process
			jQuery.post(ajaxurl, data, function(response){
				if (response == 'something wrong')
				{
					alert("something wrong with your code, please check it again");
				} else 
				{
					var form_elements = jQuery.parseJSON(response);
					
					action_url = form_elements['action_url'];
					var inputs = (form_elements['input']);
					var selects = (form_elements['select']);
					
					jQuery("#site_area form").attr("action", action_url);//add the action path to the form
					if (form_elements['form_id'] != "")
					{
						jQuery("#site_area form").attr("id", form_elements['form_id']);
					}
					
					if (form_elements['form_name'] != "")
					{
						jQuery("#site_area form").attr("name", form_elements['form_name']);
					}
					
					
					//add the input fields to the form
					var field_code = "";
					for (var i=0; i<inputs.length - 1; i++)
					{
						field_code += inputs[i];
					}
					
					//add the select to the form
					try
					{
						for (var i=0; i<selects.length; i++)
						{
							field_code += selects[i];
						}	
					}
					catch(e)
					{
						console.log(e);
					}
					
					
					field_code += inputs[inputs.length - 1];
					
					//insert into form
					jQuery("#site_area form").html(field_code);
				}
			});
		} 
		
		jQuery(this).fadeOut();
	});
	
	
	//insert custom css to the page
	jQuery('#sq_css_code').blur(function(){
		jQuery(this).fadeOut();
		if ((jQuery(this).val() == "Enter your css code here") || (jQuery.trim(jQuery(this).val()) == "")) {
			return;
		}
		
		//in case the user wants to clear the css code
		if (jQuery.trim(jQuery(this).val()) == "clear") {
			jQuery("head style.custom_css_code").remove();
		}
		
		//insert current style to head
		jQuery("<style class='custom_css_code' type='text/css'>"+jQuery(this).val()+"</style>").appendTo("head");
		
		
	});
	
	
		});