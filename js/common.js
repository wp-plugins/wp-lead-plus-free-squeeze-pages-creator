/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//PANEL LIST TO HIDE WHEN A PANEL SHOW
var panel_list = '#code_panel, #gallery_panel, #buttons_panel, #bgs_panel, #posts_panel, #gallery, #editthispageb, #editthisb, #choosethisbgb, #choosethisbtnb, #face_panel, #widget_themes, #popup_themes, #editor_control_panel, #editing_panel';

//DECLARE SOME COMMON FUNCTIONS
function sq_smart_toggle(id) {
	if (jQuery('#'+id).is(":visible")) {
		jQuery('#'+id).fadeOut();
	} else
	{
		jQuery('#'+id).fadeIn();
	}
}

//EDIT THE TEXT OF THE THEME
jQuery("document").ready(function(){
			
			//use submit button as a link
		jQuery("#sq_submit_url").blur(function(){
			//store the details in sq_custom_javascript, apply when submit
			
			//remove the current settings of current button
			jQuery('#sq_custom_javascript li.'+jQuery("#current_id").text()).remove(); //remove the li 

			//get the current select element, check if it is a submit button
			var current_elem = "#" + jQuery("#current_id").text();
			
			//if the user entered a valid url, wrap the link around the submit button
			if (jQuery(this).val().indexOf("http") != -1)
			{
				//create an li element called settings with class = current_elem, the content of this li will be used
				//to create the submit button's behavior
			
				var li_settings = "<li class='"+jQuery("#current_id").text()+"'>";
				
				//check if open new window is checked
				if (jQuery('#sq_open_new_window').is(":checked"))
				{
					//check if the current selected element is a submit button
					if ((jQuery(current_elem).is("input[type=submit]")) || (jQuery(current_elem).is("input[type=image]")) || (jQuery(current_elem).is("input[type=button]")))
					{
						//add the onlick property to the element
						li_settings += jQuery(this).val() + ", _blank</li>";
						
						//insert to the sq_custom_javascript
						jQuery('#sq_custom_javascript').append(li_settings);
						
					}	
					
				} else
				{
					//check if the current selected element is a submit button
					if ((jQuery(current_elem).is("input[type=submit]")) || (jQuery(current_elem).is("input[type=image]")) || (jQuery(current_elem).is("input[type=button]")))
					{
						//add the onlick property to the element
						li_settings += jQuery(this).val() + ", _self</li>";
						
						//insert to the sq_custom_javascript
						jQuery('#sq_custom_javascript').append(li_settings);
						
					}
				}
				
			} else if (jQuery.trim(jQuery(this).val()) == "")
			{
				jQuery('#sq_custom_javascript li.'+jQuery("#current_id").text()).remove(); //remove the li 
			}
			
		});
		
		//react to open in new window button
		jQuery('#sq_open_new_window').click(function(){
			/* this set the behavior of the open in new window checkbox. When the checkbox is toggled, it will change
			 * the settings of the button from _self to _blank or vice versa. So basicially, it will get the new settings
			 * remove the old settings and save the new settings.
			 */
			
			//if the URL box is emtpy, return
			if (jQuery.trim(jQuery('#sq_submit_url').val()).indexOf("http") == -1) {
				return;
			}
			var current_id = jQuery('#current_id').text(); //get the current selected element						
			//if the current selected element is not a button, return
			if (jQuery('#'+ current_id).is("input[type='submit']") || jQuery('#'+ current_id).is("input[type='button']") || jQuery('#'+ current_id).is("input[type='image']") || jQuery('#'+ current_id).is("button"))
			{
				console.log("here");
				
				var self = "_self";
				var url = jQuery.trim(jQuery('#sq_submit_url').val());
				
				
				if (jQuery(this).is(":checked"))
				{
					self = "_blank";
				}
				
				//remove the current settings under sq_custom_javascript if exists
				jQuery('#sq_custom_javascript li.'+current_id).remove();
				
				//add the settings to the list
				jQuery('#sq_custom_javascript').append('<li class="'+current_id+'">'+url+', '+self+'</li>');
				
			} else
			{
				return;
			}
		});
			
		jQuery(document).on("click","#site_area img, #site_area a, #site_area .editable, #site_area input, #site_area select, #site_area textarea" ,function(){
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
			if (jQuery(this).is("input") || jQuery(this).is("textarea")) //in case the clicked element is an input, put the text into the editbox
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
			
		} else if (current_element.hasClass("editable") || current_element.children("iframe").length > 0)
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
		jQuery('#face_panel').fadeOut();
		jQuery("#code_boxes textarea").not("#custom_code").fadeOut();
		jQuery("#custom_code").fadeToggle();
	
		jQuery("#custom_code_position").fadeToggle();
		
	
	});
	
	//show and hide panels
	jQuery("#codeb").click(function(){
		jQuery(panel_list).fadeOut();
		sq_smart_toggle('code_panel')
	});
	
	//edit button
	jQuery("#editb").click(function(){
		jQuery(panel_list).fadeOut();
		sq_smart_toggle("editing_panel");
	});
	
	jQuery('#editorb').click(function(){
		jQuery(panel_list).fadeOut();
		sq_smart_toggle('editor_control_panel');		
		
	});
	
		//open the gallery to select a template
	jQuery("#selectb").click(function(){
			jQuery(panel_list).fadeOut();
			sq_smart_toggle("gallery_panel");
	});
	//hide the editor
	
	//insert custom elements into the page
	jQuery("#custom_code").blur(function(){
		jQuery(this).fadeOut();
		jQuery('#custom_code_position').fadeOut();
		
		if ((jQuery(this).val() == "") || (jQuery(this).val() == "Enter your custom code here"))
		{
			return;
		}
		//if the code entered is HTML
		if (jQuery('#custom_code_position input[name=code_type]:checked').val() == 'html')
		{
			var id = "custom" + Math.round(Math.random()*10000) + Math.round(Math.random()*10000)
			//need to search through the entered code to inser the id to element
			var clone_elem = jQuery(jQuery(this).val());
			clone_elem.addClass("editable");
			var chil = clone_elem.find("*");//get all the children of current tr
			var randnum = Math.floor((Math.random()*1000)+1); //generate a random number
			for (var i = 0; i<chil.length; i++)
			{
				chil.eq(i).addClass("editable");
				if (chil.eq(i).attr("id") != undefined)
				{
					chil.eq(i).removeAttr("id");//remove the current id
					chil.eq(i).attr("id", "adder"+randnum+i);//assign new id
				} else
				{
					chil.eq(i).attr("id", "adder"+randnum+i);//assign new id
				}
			}
			//get a new id for the inserted element
			clone_elem.attr("id", "cloner"+randnum);
			
			
			var text = "<div id='"+id+"'></div>";
			if (jQuery('#custom_code_position input[name=custom_code]:checked').val() == "above")
			{
				//if the user select pure
				if (jQuery('#pure_code').is(":checked") )
				{
					jQuery(clone_elem).insertBefore('#'+jQuery('#current_id').text());
				} else
				{
					jQuery(text).insertBefore('#'+jQuery('#current_id').text());
					//insert the code into the newly created element
					jQuery('#'+ id).append(clone_elem);
				}
				
			} else
			{
				//if the user select pure
				if (jQuery('#pure_code').is(":checked") )
				{
					jQuery(clone_elem).insertAfter('#'+jQuery('#current_id').text());
				} else
				{
					jQuery(text).insertAfter('#'+jQuery('#current_id').text());
					jQuery('#'+ id).append(clone_elem);
				}
				
			}	
		} else //in case the user wants to include javascript
		{
			var script   = document.createElement("script");
			script.type  = "text/javascript";
			
			script.text  = jQuery(this).val();
			jQuery('#'+jQuery('#current_id').text()).append(script);
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
				var response_array = response.split("123dddsacxz");
				response = response_array[1];
				
				if (response == 'something wrong')
				{
					alert("something wrong with your code, please check it again");
				} else 
				{
					var form_elements = jQuery.parseJSON(response);
					
					action_url = form_elements['action_url'];
					var inputs = (form_elements['input']);
					
					var textarea = (form_elements['textarea']);
					//var styles = form_elements['style'];
					
					jQuery("#site_area form").attr("action", action_url);//add the action path to the form
					jQuery('#site_area form').attr("method", "post");
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
					if (form_elements['select'] != undefined) {
						var selects = (form_elements['select']);
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
					}

					
					//add the textarea to the form
					if (form_elements['textarea'] != undefined) {
						var textarea = form_elements['textarea'];
						try
						{
							for (var i=0; i<textarea.length; i++)
							{
								field_code += textarea[i];
							}	
						}
						catch(e)
						{
							console.log(e);
						}											
					}
					
					
					field_code += inputs[inputs.length - 1];
					
					//insert into form
					jQuery("#site_area form").html(field_code);
				}
			});
		} 
		
		jQuery(this).fadeOut();
	});
	
	//the expand button
	jQuery('#xpand').click(function(){
		if (jQuery(this).val() == "Xpand") {
			jQuery('#editparent').css("width", "500px");
			jQuery('#editparent > *').css("width", "500px");
			jQuery('#editparent iframe').css("width", "500px");
			jQuery(this).val("Shrink");
		} else
		{
			jQuery('#editparent').css("width", "220px");
			jQuery('#editparent > *').css("width", "220px");
			jQuery('#editparent iframe').css("width", "220px");
			jQuery(this).val("Xpand");	
		}
		
	});
	
	//hide editor button
	jQuery('#hide_editorb').click(function(){
		if (jQuery(this).val() == "Hide")
		{
			jQuery('#editparent').fadeOut();
			jQuery(this).val("Show");
		} else
		{
			jQuery('#editparent').fadeIn();
			jQuery(this).val("Hide");
		}
		
	});
	
	
});