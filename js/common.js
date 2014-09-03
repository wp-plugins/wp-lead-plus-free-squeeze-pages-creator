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

//function to show then hide a div after a certain amount of second (4), mostly sq_bgt_general_notification
function blink_general_notification(notification_id, bg_color, text_color, message, appear_seconds)
{
	jQuery('#'+ notification_id).css('background-color', bg_color);
	jQuery('#'+ notification_id).css('color', text_color);
	jQuery('#'+ notification_id).html(message);
	jQuery('#' + notification_id).slideDown();
	setTimeout(function(){ jQuery('#' + notification_id).fadeOut(); }, appear_seconds * 1000);
}

function get_custom_js_code_button()
{
	//check if the user has set any special setting to the submit button
	if (jQuery('#custom_button_js_code li').length != 0)
	{
		var custom_button_js_code = {};
		jQuery('#custom_button_js_code li').each(function(){
			custom_button_js_code[BASE64.encode(jQuery(this).attr("for_button"))] = BASE64.encode(jQuery(this).text());
		});		
		
		console.log(custom_button_js_code);
		return JSON.stringify(custom_button_js_code);
	} else
	{
		return "";
	}
}

function return_button_js_code_when_loading_for_edit(return_data)
{
	button_obj = (jQuery.parseJSON(BASE64.decode(return_data)));
	
	for (var key in button_obj)
	{
		var single_li = "<li for_button='"+ BASE64.decode(key) +"'>"+ BASE64.decode(button_obj[key]) +"</li>";
		jQuery(single_li).appendTo("#custom_button_js_code");
	}
}

//function to change hex color
//the type is defined in a hidden div inside each page popup/squeeze/widget
function sq_bgt_apply_hex_color(type)
{
	jQuery('#sq_bgt_hex_color_changer').ColorPicker({onChange: function(hsb, hex, rgb){ 
		
		//if the current selected element is a background changeable element
		//if (jQuery('#'+jQuery('#bgt_bg_change_id').text()))
		if (jQuery('#bgt_bg_change_id').text() !== "")
		{
			jQuery('#'+jQuery('#bgt_bg_change_id').text()).css("background-color", "#"+hex , " !important");
			return;
		}
		
		if (type == "widget")
		{
			if ( jQuery('#'+jQuery('#current_id').text()).is("input[type='submit']") && jQuery('#'+jQuery('#current_id').text()).css("background-image") == "none")
			{
				var attr = "#"+hex + " !important";
				jQuery('#'+jQuery('#current_id').text()).css("background-color", attr);
				console.log(attr);
			} else
			{
				jQuery('#site_area > div').css("background-color", "#"+hex);
			}
							
		} else if (type == "squeeze")
		{
			if ( jQuery('#'+jQuery('#current_id').text()).is("input[type='submit']") && jQuery('#'+jQuery('#current_id').text()).css("background-image") == "none")
			{
				jQuery('#'+jQuery('#current_id').text()).css("background-color", "#"+hex + " !important");
			} else
			{
				jQuery('#sq_box_container').css("background-color", "#"+hex);
			}			
			
			
		} else if (type == "popup")
		{
			if ( (jQuery('#'+jQuery('#current_id').text()).is("input[type='submit']")) && (jQuery('#'+jQuery('#current_id').text()).css("background-image") == "none") )
			{
				jQuery('#'+jQuery('#current_id').text()).css("background-color", "#"+hex + " !important");
			} else
			{
				if (jQuery('#sq_popup_optin_body').length != 0)
				{
					jQuery('#sq_popup_optin_body').css("background-color", "#"+hex);
				} else
				{
					jQuery('#sq_box_container').css("background-color", "#"+hex);
				}	

			}	
			
		}

		
	}, flat:true});	

}

//function to parse the media code
function sq_bgt_media_parser(media_id, id) {
	jQuery("#"+media_id).blur(function(){
		if(((jQuery(this).val().indexOf(".jpg") != -1) || (jQuery(this).val().indexOf(".png") != -1) || (jQuery(this).val().indexOf(".gif") != -1)) && (jQuery(this).val().indexOf("*") == -1))//in case the user has pased the image code in
		{
			jQuery('#'+id).html('<img width="95%" height="95%" src="'+jQuery(this).val()+'" />');
		} else if((jQuery(this).val().indexOf("youku.com") != -1) ||  (jQuery(this).val().indexOf("youtube.com") != -1) || (jQuery(this).val().indexOf("vimeo.com") != -1) || (jQuery(this).val().indexOf("blip.tv") != -1) || (jQuery(this).val().indexOf("dailymotion.com") != -1) || (jQuery(this).val().indexOf("metacafe.com") != -1) || (jQuery(this).val().indexOf("wistia.com") != -1) || (jQuery(this).val().indexOf("screencast.com") != -1))//in case the user has pased the video url in
		{
			/* get the video embed code from user, if it's a full code, parse and get the URL, if it's the url embed,
			 * use that url				
			*/
			
			var user_code = jQuery.trim(jQuery(this).val());
			
			if (user_code.indexOf("http") == 0 || user_code.indexOf("//www") == 0 ) { //the second condition to match the new youtube embed code, without the http:
				var pure_url = user_code;
			} else
			{
				var pattern = /src=".*?[" ]/i;
			    var x = user_code.match(pattern);
				
				var pure_url = jQuery.trim(x[0].replace(/"/g, ''));

				pure_url = jQuery.trim(pure_url.replace('src=', ''));
				
		     }
			//insert http: before the pure url if it doesn't have http
			if (pure_url.indexOf("http") != 0) {
				pure_url = "http:" + pure_url;
			}
			if (jQuery('#sq_bgt_https_enabled').text() == "yes")
			{
				pure_url = pure_url.replace('http:', 'https:');
			}
			console.log(pure_url);
			//console.log(pure_url);
			var code = '';

			code = '<iframe width="95%" height="95%" src="'+pure_url+'" frameborder="0" allowfullscreen></iframe>';
			
			jQuery('#'+id).html(code);
		} else if ((jQuery(this).val().indexOf(".mp4") != -1) || (jQuery(this).val().indexOf(".webm") != -1) || (jQuery(this).val().indexOf(".ogv") != -1) || (jQuery(this).val().indexOf(".3gp") != -1))
		{
			function get_vid_type(url_string)
			{
				var type = '';
			
				if (url_string.indexOf(".mp4") != -1)
				{
					type = 'mp4';
				} else if (url_string.indexOf(".webm") != -1)
				{
					type = 'webm';
				} else if (url_string.indexOf(".ogv") != -1)
				{
					type = 'ogg';
				}
				
				return type;
			}
			
			//get the video link and type
			var video_array = jQuery(this).val().split("*");

			var code = '';

			var vide_encode = encodeURIComponent(jQuery(this).val());
			code = '<video controls="controls" width="100%" height="100%">';
			
			for (var i = 0; i < video_array.length; i++)
			{
				code += '<source src="'+video_array[i]+'" type="video/'+get_vid_type(video_array[i])+'" />';	
			}
			
			code += '<object type="application/x-shockwave-flash" data="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf" width="95%" height="95%">';
			code += '<param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf" />';
			code += '<param name="allowFullScreen" value="true" />';
			code += '<param name="wmode" value="transparent" />';
			code += '<param name="flashVars" value="controlbar=over&amp;file='+vide_encode+'" />';
			code += '<span title="No video playback capabilities, please download the video below"></span>';
			code += '</object></video>';	

			
			jQuery('#'+id).html(code);
		} else if (jQuery.trim(jQuery(this).val()) =="")
		{
			
		} else if (jQuery.trim((jQuery(this).val())).indexOf("http") == 0 )  {//suppose the user enters a link, treat it like a video
			var code = '';
			code = '<iframe style="overflow: hidden;" width="100%" height="100%" src="'+jQuery(this).val()+'" scrolling="no" frameborder="0" allowfullscreen></iframe>';
			jQuery('#'+id).html(code);
		}
		
		jQuery(this).fadeOut();	
	});
}

//EDIT THE TEXT OF THE THEME
jQuery(document).ready(function(){
	
		//reset position button
		jQuery('#edit_resetb').click(function(){
			jQuery('#sq_box_container').css("top", "");
			jQuery('#sq_box_container').css("left", "");
		});
	
		//use submit button as a link
		jQuery("#sq_submit_url").blur(function(){
			var target = jQuery("#" + localStorage.getItem("current_button_link_id"));
			
			if ( target.is("input[type=submit]") || target.is("input[type=button]") || target.is("input[type=image]") )
			{
				var url = jQuery(this).val();
				if (url.indexOf("http") == -1 )
				{
					return false;
				}
				
				//generate the code
				if (jQuery("#sq_open_new_window").is(":checked"))
				{
					var code = 'jQuery("'+"#" + localStorage.getItem("current_button_link_id")+'").click(function(){window.open("'+url+'", "_blank", false); returl false;});';
				} else
				{
					var code = 'jQuery("'+"#" + localStorage.getItem("current_button_link_id")+'").click(function(){window.open("'+url+'", "_self", false); return false;});';
				}
				
				//clear the code for this button first before inserting in
				jQuery("li[for_button='"+localStorage.getItem("current_button_link_id")+"']").remove();
				
				//append the style to custom Javascript code list
				jQuery("<li for_button='"+localStorage.getItem("current_button_link_id")+"'>"+ code +"</li>").appendTo("#custom_button_js_code");
				
			}
		});
		
		
	jQuery(document).on("click", ".editable", function(){
		vgt_wpl_enable_tinymce();

		
	});
	//END EDITING THE TEXT OF THE THEME

	jQuery(document).on("click", ".editable", function(){
		
		vgt_remove_button_link_editor();
	});
	
	jQuery(document).on("click", "#site_area", function(){
		console.log("out");
		setTimeout(function(){
			if (jQuery(".mce-tinymce-inline").is(":visible"))
			{
				vgt_remove_button_link_editor();
			}
			
		}, 10);
	});
	//editing the submit button
	jQuery(document).on("click", "#site_area a, #site_area input[type=submit], #site_area input[type=button] , #site_area input[type=image], #site_area input[type=text], #site_area input[type=email], #site_area input[type=number]", function(){
		//insert a context editor near the button, if not exists already

		jQuery("#crazy_vgt").append("<div id='button_editor'></div>");
		vgt_wpl_enable_tinymce_button();
	
		//get current position of the button/link, then append the editor below that
		jQuery("#button_editor").siblings(".mce-tinymce").css("position", "absolute");

		var elem_offset = jQuery(this).offset();
		var elem_offset_top 	= elem_offset.top;
		var elem_offset_left 	= elem_offset.left;
		var elem_height			= jQuery(this).height();
		var elem_width			= jQuery(this).width();
		
		
		jQuery("#button_editor").siblings(".mce-tinymce").css("max-width", "300px");
		jQuery("#button_editor").siblings(".mce-tinymce").offset({top: elem_offset_top + elem_height, left: elem_offset_left});
		
		jQuery("#button_editor").siblings(".mce-tinymce").css("z-index", 90);
		


		//add an ID to the current button, if not exists

		if (jQuery(this).attr("id") == undefined)
		{
			//generate a random number to be the id of the button
			var btn_id = "id" + Math.round(Math.random()*1000000);
			jQuery(this).attr("id", btn_id);
		} else
		{
			btn_id = jQuery(this).attr("id");
		}
		
		//log current button id
		localStorage.setItem("current_button_link_id", btn_id);
		//get the current editor
		var button_editor = tinyMCE.get("button_editor");
		var selected = jQuery(this);
		
		//if the element that was clicked is an input
		if (selected.is("input"))
		{
			//get the current style of button's text
			var btn_size 			= selected.css("font-size");
			var btn_color 			= selected.css("color");
			var btn_font_style 		= selected.css("font-style");
			var btn_font_weight 	= selected.css("font-weight");
			var btn_text_decoration = selected.css("text-decoration");
			
			var pass_to_editor_content = "<span style='font-size: "+btn_size+"; color: "+btn_color+"; font-style: "+btn_font_style+"; font-weight: "+btn_font_weight+"; text-decoration: "+btn_text_decoration+";'>"+jQuery(this).val()+"</span>";

			
		} else if (selected.is("a"))
		{
			var a_size 				= selected.css("font-size");
			var a_color 			= selected.css("color");
			var a_font_style 		= selected.css("font-style");
			var a_font_weight 		= selected.css("font-weight");
			var a_text_decoration 	= selected.css("text-decoration");
			var a_val 				= selected.attr("href");
			var a_target			= selected.attr("target");
			
			var pass_to_editor_content = "<a target='"+ a_target +"' href='"+ a_val +"' style='font-size: "+a_size+"; color: "+a_color+"; font-style: "+a_font_style+"; font-weight: "+a_font_weight+"; text-decoration: "+a_text_decoration+";>"+ selected.text() +"</a>";
		}
		button_editor.setContent(pass_to_editor_content);

		return false;
	});
	//ADD AND REMOVE BUTTON
		/* get the current element, decide what it is, if it's an editable, remove its parent, if it's an image, remove
		 * itself, if it's a link inside a li, remove its parent, do nothing with a input
		 */
	jQuery("#edit_removeb").click(function(){
		//get the current selected id
		var current_element = jQuery("#"+ localStorage.getItem("vgt_current_selected_item"));
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
	
	//add ID and record ID when .editable/li/a is clicked
	jQuery(document).on("click", "#site_area a, #site_area li, #site_area .editable", function(){
		if (jQuery(this).attr("id") == undefined)
		{
			//generate a random id
			var rid = "rid"+ Math.round(Math.random()*2000000);
			jQuery(this).attr("id", rid);
		}
		
		localStorage.setItem("vgt_current_selected_item", jQuery(this).attr("id"));
		
	});
	
	//the add button
	jQuery("#edit_addb").click(function(){
		//get the current selected id
		var current_element = jQuery("#"+ localStorage.getItem("vgt_current_selected_item"));
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
		
		vgt_wpl_enable_tinymce();
		
		
	});
	
	//show and hide the custom code box	
	jQuery("#code_customb").click(function(){
		jQuery('#face_panel').fadeOut();
		jQuery("#code_boxes textarea").not("#custom_code").fadeOut();
		//jQuery("#custom_code").fadeToggle();
	
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
	//Insert the custom HTML code into the page
	jQuery("#custom_html_code").blur(function(){
		jQuery(this).fadeOut();
		jQuery('#custom_code_position').fadeOut();
		
		if ((jQuery(this).val() == "") || (jQuery(this).val() == "Enter your custom HTML code here"))
		{
			return;
		}		
		
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
				jQuery(clone_elem).insertBefore('#'+ localStorage.getItem("vgt_current_selected_item"));
			} else
			{
				jQuery(text).insertBefore('#'+localStorage.getItem("vgt_current_selected_item"));
				//insert the code into the newly created element
				jQuery('#'+ id).append(clone_elem);
			}
			
		} else
		{
			//if the user select pure
			if (jQuery('#pure_code').is(":checked") )
			{
				jQuery(clone_elem).insertAfter('#'+localStorage.getItem("vgt_current_selected_item"));
			} else
			{
				jQuery(text).insertAfter('#'+localStorage.getItem("vgt_current_selected_item"));
				jQuery('#'+ id).append(clone_elem);
			}
			
		}		
		vgt_wpl_enable_tinymce();
		
	});
	
	//insert custom Javascript code into the page
	jQuery("#custom_javascript_code").blur(function(){
		jQuery(this).fadeOut();
		jQuery('#custom_code_position').fadeOut();
		
		if ((jQuery(this).val() == "") || (jQuery(this).val() == "Enter your custom Javascript code here"))
		{
			return;
		}

		jQuery("#sq_user_js_code").html(BASE64.encode(jQuery(this).val()));//enter the code in the div
		
	});
	
/*	
	//the undo button
	jQuery('#edit_undob').click(function(){
		//get the latest removed element's id and restore it then remove the li in the history
		if (!jQuery('#' + jQuery('#sq_remove_history li:last-child').text()).is(":visible")) {
			jQuery('#' + jQuery('#sq_remove_history li:last-child').text() ).fadeIn();
		}
		
		jQuery('#sq_remove_history li:last-child').remove();
	
	});
	
*/	
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
					//console.log(inputs);
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
	
	//toggle the custom submit url, background... on the left
	jQuery('#code_otherb').click(function(){
		jQuery('#sq_bgt_customize_left').fadeToggle();
	});
	
	//CUSTOM HTML AND JAVASCRIPT FOR THE SQUEEZE PAGE ONLY
	jQuery("#custom_code_position input[value='html']").click(function(){
		
		jQuery("#custom_code_html").fadeIn(); //the option area
		jQuery("#custom_html_code").fadeIn(); //the textarea box
		
		jQuery("#custom_javascript_code").fadeOut(); //the textarea box
		jQuery("#custom_code_js").fadeOut();
	});

	jQuery("#custom_code_position input[value='javascript']").click(function(){
		
		jQuery("#custom_code_js").fadeIn();
		jQuery("#custom_javascript_code").fadeIn(); //the textarea box

		jQuery("#custom_html_code").fadeOut(); //the textarea box
		jQuery("#custom_code_html").fadeOut();
	});

	//show and hide the custom css code //
	jQuery("#code_custom_css").click(function(){
		jQuery("#code_boxes textarea").not("#custom_css").fadeOut();
		jQuery('#face_panel').fadeOut();
		jQuery("#custom_css").fadeToggle();
		
	});
	
	//insert custom css to the header of the page
	jQuery('#custom_css').blur(function(){
		jQuery(this).fadeOut();

		//return false if the user enters nothing
		if ((jQuery(this).val() == "Enter your custom css here") || (jQuery.trim(jQuery(this).val()) == "")) {
			
			return false;
			
		} else 	if (jQuery.trim(jQuery(this).val()) == "clear code") 	//clear the style if the user enters clear
		{
			if (jQuery("head style.custom_css_style") == undefined) {
				
			} else
			{
				//clear the stye
				jQuery("head style.custom_css_style").remove();

			}
			return false;
		} else //if the code entered is valid code 
		{
			if (jQuery("head style.custom_css_style").html() == undefined)
			{
				jQuery("<style class='custom_css_style'>"+jQuery.trim(jQuery(this).val())+"</style>").appendTo("head");
				console.log("done");
			} else
			{
				//append the code if there are differences
				jQuery('head style.custom_css_style').html(jQuery.trim(jQuery(this).val()));			 
				
			}			
		}		

	});	

	
	//switch color
	//define a function to switch color
		/* get to the current theme folder, if it contains more than one child folder, that means the theme will switch color using the images, otherwise,
		 * it will use the color picker.
		 */

		jQuery('#edit_switch_colorb').click(function(){
			var theme_type = jQuery('#current_theme_type').text();
			//get theme id
			var theme_id = jQuery('#current_theme_id').text();
			
			var data = {action: 'sq_bgt_switch_color', theme_type: theme_type, theme_id: theme_id};
			//send the ajax post
			jQuery.post(ajaxurl, data, function(response){
					var respon_array = response.split("123dddsacxz");
					response = respon_array[1];
					if (response == "hex")
					{
						jQuery('#sq_bgt_hex_color_changer').fadeIn();
						jQuery('#sq_bgt_hide_picker').fadeIn();
					} else if (response == "image")
					{
						var code = '';
						//display the number from 1 to 9
						for (var i = 1; i < 10; i++)
						{
							code += '<div class="color_switch_img" style="float: left; text-align: center;">';
							code += '<img src="'+jQuery('#sq_bgt_link_to_colors').text() + i+'.jpg" /><br />';
							code += '<input type="radio" theme="'+(i)+'" name="switch_color" color="'+i+'" /></div>';
						}
						
						jQuery('#color_switch_number').html(code);
						jQuery('#color_switch_number').fadeToggle();
					} else //in case json is returned
					{
						
						var return_array = jQuery.parseJSON(response);

						var code = '';
						//display the number from 1 to 9
						for (var i = 0; i < return_array.length; i++)
						{
							code += '<div class="color_switch_img" style="float: left; text-align: center;">';
							code += '<img src="'+jQuery('#sq_bgt_link_to_colors').text() + return_array[i]+'" /><br />';
							code += '<input type="radio" theme="'+parseInt(return_array[i])+'" name="switch_color" color="'+return_array[i]+'" /></div>';
						}
						jQuery('#color_switch_number').html(code);
						jQuery('#color_switch_number').fadeToggle();
					}			
					
			});


		});
		
		var type = jQuery('#current_theme_type').text();
		sq_bgt_apply_hex_color(type);
		
		//show the color picker on changeable divs
		jQuery(document).on("click", ".bgt_bg_change", function(){
			/* When the element is clicked, record the id of the element in order to change the color
			 * generate an ID of the current element doesn't have one
			 *  */	
			if (jQuery(this).attr("id") == undefined)
			{
				var rand_id = "bg_changer" +  Math.round(Math.random()*100000) + "" + Math.round(Math.random()*100000);
				jQuery(this).attr("id", rand_id);
				jQuery('#bgt_bg_change_id').text(rand_id);
			} else 
			{
				jQuery('#bgt_bg_change_id').text(jQuery(this).attr("id"));
			}
			
			jQuery('#sq_bgt_hex_color_changer').fadeIn();
			jQuery('#sq_bgt_hide_picker').fadeIn();
			
		});		
		jQuery('#sq_bgt_hide_picker').click(function(){
			jQuery('#sq_bgt_hex_color_changer').fadeOut();
			jQuery(this).fadeOut();
			return false;
		});	
	
	
});


