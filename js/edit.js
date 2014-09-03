jQuery(document).ready(function(){
	//init lightcase
	jQuery('a[rel^=lightcase]').lightcase('init');
	
	/* this part does few things. First, let user select the theme with the radio button. when they decided which theme to\
	 * use, they will hit edit this button and the button will send an ajax call to server which will load the theme and 
	 * show in the right area.
	 */

	jQuery("#hideb").click(function(){
		jQuery("#foot_panel, #buttons_panel, #bgs_panel, #posts_panel, #gallery, #editthispageb, #editthisb, #choosethisbgb, #choosethisbtnb").fadeOut();
		jQuery("#showb").fadeIn();
	});
	
	jQuery("#showb").click(function(){
		jQuery("#foot_panel").fadeIn();
		jQuery(this).fadeOut();
	});

	//show the video themes
	jQuery("#videothemeb").click(function(){
		jQuery("#nonvid_themes").fadeOut();
		jQuery("#video_themes").fadeIn();
		jQuery("#gallery").fadeIn();
		
	});
	
	//show non video themes
	jQuery("#nonvidb").click(function(){
		jQuery("#video_themes").fadeOut();
		jQuery("#nonvid_themes").fadeIn();
		jQuery("#gallery").fadeIn();
	});
	
	//record the selected theme
	jQuery("#gallery input[type='radio']").click(function(){
		jQuery("#selected_theme").text(jQuery(this).attr("id"));
		jQuery("#editthisb").fadeIn();
		
	});
	
	//send the ajax call and start editing the theme
	jQuery("#editthisb").click(function(){
		jQuery("#video_themes, #nonvid_themes, #gallery, #gallery_panel").fadeOut();
		//get the selected theme
		var selected_theme = jQuery('#gallery input[type="radio"]:checked').attr("theme_id");
		var theme_type = jQuery('#gallery input[type="radio"]:checked').attr("theme_type");
		
		//record the type of template, will be used to switch color
		jQuery('#current_theme_type').text(theme_type);
		jQuery('#current_theme_id').text(parseInt(selected_theme));
		//prepare the needed data
		data = {
			action: 'theme_loader',
			theme_name: selected_theme,
			theme_type: theme_type
		};
		
		//reset the post id
		jQuery('#sq_current_post_id').text("");
		//send the request
		jQuery.post(
		ajaxurl, data, function(response){
			var respon_array = response.split("123dddsacxz");
			response = respon_array[1];
			var return_array = jQuery.parseJSON(response);
			//decode the return values
			var css_url = BASE64.decode(return_array['theme_css']);
			var theme_content = BASE64.decode(return_array['theme_body']);
			var has_bg = return_array['has_img_bg'];
			var theme_type = return_array['theme_type'];
			var parent_folder = return_array['theme_type_url'];
			var current_theme_name = return_array['current_theme_name'];
			var general_theme_url = return_array['general_theme_url'];
			
			//hide color switch
			jQuery('#switch_color').fadeOut();
			//update the theme name
			//update the theme type, this will be used to build the url later
			jQuery('#current_theme_name').text(current_theme_name);
			
			//set the total theme url
			
			jQuery('#current_theme_url').text(general_theme_url);
			
			//update the theme type, this will be used to build the url later
			jQuery('#current_theme_type').text(theme_type);
			//reset the facebook mail code
			jQuery("#facebook_mail_code").text("");
			//build the theme rotator
			
			
			jQuery("#changeable_bg").text("yes");
			
			//remove the current stylesheet of the theme (if any)
			jQuery("head").children(".theme_css").remove();
			
			//set the class so it will be easier to remove later
			jQuery("<link class='theme_css' rel='stylesheet' href='"+css_url+"' />").insertBefore(jQuery("head").children("link[href*='style.css']"));//insert before the stylesheet of the editing panels
				
			jQuery("#site_area").html(theme_content);
			jQuery("#site_area *").not("a,li,h1,h2,h3,h4,h5,h6,p").contents().filter(function(){	return (this.nodeType == 3); }).wrap("<span class='editable'></span>");
			//jQuery("#site_area *").contents().filter(function(){	return (this.nodeType == 3); }).parent().addClass("editable");
			
			//add editable class to elements
			jQuery("#site_area h1, #site_area h2, #site_area h3, #site_area h4, #site_area h5, #site_area h6, #site_area p, #site_area ul").addClass("editable");
						
			//remove blanks spans
			jQuery(".editable").filter(function(){return ((jQuery.trim(jQuery(this).text())).length == false);}).remove();

			//show the publish button
			jQuery("#publishb").fadeIn();
			//make the big box draggable, in themes which have image bg
                        //jQuery("#sq_box_container").draggable();
			//jQuery('#sq_box_container').draggable();
			vgt_wpl_enable_tinymce();
		}		
		);
		
		jQuery("#gallery").fadeOut();
		jQuery(this).fadeOut();
		
                

		//make the text of the page editable
		//jQuery("#site_area").children().contents().filter(function(){	return (this.nodeType == 3); }).wrap("<span class='editable'></span>");
	});


	//SWITCH COLOR OF THE THEME
	//change the color
	jQuery(document).on('click','.color_switch_img input[type="radio"]',function(){
		//load the css style
			//remove the current stylesheet of the theme (if any)
			jQuery("head").children(".theme_css").remove();
			//insert css of the current theme
			jQuery('<link rel="stylesheet" class="theme_css" type="text/css" href="'+jQuery('#current_theme_url').text()+'/themes/'+jQuery(this).attr("theme")+'/assets/style.css'+'" />').appendTo("head");
			
		//record current sub theme
		jQuery('#current_sub_theme').text(jQuery(this).attr("theme"));
				
	});
	//END SWITCHING COLOR OF THE THEME
	
	//SHOW FACEBOOK SCRIPT
	jQuery('#code_face_like').click(function(){
		jQuery('#likenbox').fadeToggle();	
	});
	
//EDITING THE CONTENT ON PAGE************************************************

	//update the url for the link
	  jQuery("#linkurl").keyup(function(){
		var the_link = jQuery("#"+ jQuery("#current_id").text());
		if (the_link.is("a"))
		{
			the_link.attr("href", jQuery(this).val());
		}
		
	});
//END EDITING THE CONTENT ON PAGE************************************************	

	
//CHANGE THE SUBMIT BUTTON*******************************************************

	
//END CHANGING THE SUBMIT BUTTON*******************************************************	
	
	
//EDITING PANEL AND ITS BUTTONS BEHAVIOR************************************************	
	
	//CHANGE CTA BUTTON BEHAVIOR
	jQuery("#edit_changebtnb").click(function(){
		//hide non-related buttons
		jQuery("#editthispageb, #editthisb, #choosethisbgb, #bgs_panel").fadeOut();
		//prepare the needed data
		data = {
			action: 'show_buttons',
			current_theme_type: jQuery('#current_theme_type').text(),
			current_theme_name: jQuery('#current_theme_name').text()
		};
		
		jQuery.post(ajaxurl, data, function(response){
			var response_array = response.split("123dddsacxz");
			response = response_array[1];
			
			var buttons = jQuery.parseJSON(response);
			var buttons_gallery = "";
			for (var i=0; i < buttons.length; i++)
			{
				buttons_gallery += "<div class='cta_button'><img src='"+ buttons[i] +"' /><input name='cta_button' type='radio' id='cta_btn"+i+"'/></div>";
			}
			
			jQuery("#buttons_panel").html(buttons_gallery);
			jQuery("#buttons_panel").fadeToggle();

		});
		
	});
	
	jQuery(document).on("click", "#buttons_panel input[type='radio']", function(){
		jQuery("#choosethisbtnb").fadeIn();
		jQuery("#selected_button").text(jQuery(this).prev().attr("src"));;
	});
	
	jQuery(document).on("click", "#choosethisbtnb", function(){
		jQuery(this).fadeOut();
		jQuery("#buttons_panel").fadeOut();
		jQuery("#site_area input[type='submit']").css("background", "url("+jQuery("#selected_button").text()+")");
		
	});
	//END CHANGING CTA BUTTON BEHAVIOR
	
	//CHANGE BACKGROUND BUTTON BEHAVIOR
	
	jQuery("#edit_changebgb").click(function(){
		//hide non-related buttons
		jQuery("#editthispageb, #editthisb, #choosethisbtnb, #buttons_panel").fadeOut();
		
		//prepare the needed data
		data = {
			action: 'show_backgrounds',
		};
		
		jQuery.post(ajaxurl, data, function(response){
			var response_array = response.split("123dddsacxz");
			response = response_array[1];
			
			var buttons = jQuery.parseJSON(response);
			var buttons_gallery = "";
			for (var i=0; i < buttons.length; i++)
			{
				buttons_gallery += "<div class='site_bg'><img src='"+ buttons[i] +"' /><br /><input name='site_bg' type='radio' id='cta_btn"+i+"'/></div>";
			}
			
			jQuery("#bgs_panel").html(buttons_gallery);
			jQuery("#bgs_panel").fadeToggle();

		});
	
	});
	
	jQuery(document).on("click", "#bgs_panel input[type='radio']", function(){
		jQuery("#choosethisbgb").fadeIn();
		jQuery("#selected_bg").text(jQuery(this).siblings("img").attr("src").replace("small/", ""));
	});
	
	jQuery(document).on("click", "#choosethisbgb", function(){
		jQuery(this).fadeOut();
		jQuery("#bgs_panel").fadeOut();
		jQuery("#sq_body_container").css("background", "url("+jQuery("#selected_bg").text()+") center center");
		
	});
	
	//END CHANGING BACKGROUND BUTTON BEHAVIOR

	
//END EDITING PANEL AND ITS BUTTONS BEHAVIOR************************************************	
	
	
//CODING PANEL AND ITS BUTTONS BEHAVIOR************************************************	

	//show and hide the video button
	jQuery("#code_mediab").click(function(){
		jQuery("#code_boxes textarea").not("#media_code").fadeOut();
		jQuery('#face_panel').fadeOut();
		jQuery("#media_code").fadeToggle();		
	});

	//show and hide the email box	
	jQuery("#code_emailb").click(function(){
		jQuery("#code_boxes textarea").not("#email_code").fadeOut();
		jQuery('#face_panel').fadeOut();
		jQuery("#email_code").fadeToggle();
		
	});	
	
	
	//show and hide the video button
	jQuery("#code_trackingb").click(function(){
		jQuery("#code_boxes textarea").not("#tracking_code").fadeOut();
		jQuery("#tracking_code").fadeToggle();
	});	
	
	//show and hide the facebook button //code_custom_css
	jQuery('#code_faceb').click(function(){
		jQuery('#face_panel').fadeToggle();	
	});
	
	jQuery("#code_face_mail").click(function(){
		jQuery("#code_boxes textarea").not("#face_code").fadeOut();
		jQuery("#face_code").fadeToggle();
		
	});	
	
	
	//show facebook connect button
	jQuery('#code_face_conn').click(function(){
		jQuery('.sq_facebook_div').fadeToggle();
	});
	
	//THE CODING BOXES AND THEIR ON BLUR BEHAVIOR
	
	//insert the video to the page
	sq_bgt_media_parser("media_code", "sq_media");
	


	//insert the facebook code to the page
	jQuery("#face_code").blur(function(){
		if((jQuery(this).val().indexOf("your autoresponder") != -1) || (jQuery.trim(jQuery(this).val()) == ""))//in case the user passed nothing
		{
			jQuery(this).fadeOut(); //fadeout and do nothing
		} else //if user passed something different
		{
			jQuery("#facebook_mail_code").html(BASE64.encode(jQuery(this).val()));//enter the code in the div
			jQuery(this).fadeOut();
		}
	});
	
	//END THE CODING BOXES AND THEIR ON BLUR BEHAVIOR	
	
//END CODING PANEL AND ITS BUTTONS BEHAVIOR************************************************		
	
//PUBLISH BUTTON BEHAVIOR******************************************************************
	
	jQuery("#publishb").click(function(){
		//check if the title was set 
		if (jQuery("#page_title").val() == "")
		{
			//require a title before continuing
			blink_general_notification('sq_bgt_general_notification', '#972121', '#fff', 'Please set a title!', 4)
			jQuery("#page_title").css("border", "1px solid red");
			return false;
		}
		
		//remove the red border of the title box
		jQuery("#page_title").css("border", "none");
		
		var custom_js_code_button = get_custom_js_code_button();
		
		//BACKGROUND FUNCTIONS
			/* 1. Get the background (image's URL/YouTube video) if any
			 * 2. If there is no video sent, send none
			 * 3. If the background is set, get the type and also the URL
			 * */
			
		//check if the user has change the background yet, if yes, get the image, if not, get the origial image
		
		//get the current background, if the user has changed it, then in will be in sq_body_container style tag
		
		var bg_url = "none";
		var bg_type = "none";
		
		//prepare the content, need to remove the background image and video before sending to the server
		var content = jQuery('#site_area').clone();
		
		if ((jQuery('#custom_bg_youtube').is(":checked")) && (jQuery("#custom_bg").val().indexOf("youtube") !== -1) && (jQuery("#custom_bg").val().indexOf("embed") !== -1)) // in case the background 
		{
			bg_url = jQuery('#custom_bg').val();
			bg_type = "video";
			content.children("#sq_body_container").css("background", "");
			content.children("#sq_body_container").removeAttr("style");

			

		} else if (jQuery("#sq_body_container").attr("style") !== undefined) // in case the use set the background image, 
		//it will be added to the stle
		//tag of the sq_body_container
		{
			console.log("nice");
			var raw_url = jQuery("#sq_body_container").attr("style");
			//get the url
			bg_url = "http"+ raw_url.match(/http(.*?(.jpg|.png))/i)[1];
			bg_type = "image";

			//remove the background of the clone of the whole content (before sending to the server)
			content.children("#sq_body_container").removeAttr("style");

		}
		
		//get the text from input boxes
		var input_array = new Array(); //this will store the id and the value of the input fields
		var i=0;
		jQuery("#site_area input[type='text'], #site_area input[type='email'], #site_area textarea").each(function(){
			input_array[i] = jQuery(this).attr("id")+"***"+jQuery(this).val();
			i++;
		});
		var input_string = input_array.join("&&&");
		

		
		 
		//prepare the needed data
		var data = {
			action: 'publish_post',
			title: BASE64.encode(jQuery("#page_title").val()),
			content: BASE64.encode(jQuery.trim(content.html())),
			cssfile: BASE64.encode(jQuery("head").children(".theme_css").attr("href")),
			bg_url: BASE64.encode(bg_url),
			bg_type: BASE64.encode(bg_type),
			input_string: BASE64.encode(input_string),
			current_theme_url: jQuery('#current_theme_url').text(),
			current_sub_theme: jQuery('#current_sub_theme').text(),
			current_theme_name: jQuery('#current_theme_name').text(),
			current_theme_type: jQuery('#current_theme_type').text(),
			current_post_id: jQuery.trim(jQuery('#sq_current_post_id').text()),
			custom_js_code_button : BASE64.encode(custom_js_code_button)
		};
		
		//add optional variables to the data array
		
		//if custom css available, add it
		if (jQuery("head style.custom_css_style").length > 0) {
			data.custom_css_style = BASE64.encode(jQuery("head style.custom_css_style").html());
		} else {
			data.custom_css_style = "";
		}
		
		//add custom javascript code if available
		if (jQuery.trim(jQuery('#sq_user_js_code').text()) != "")
		{
			//add the code, don't pass base64 here because it was base64 encoded in common.js (when blurring)
			data.custom_js_code = (jQuery.trim(jQuery('#sq_user_js_code').text()));
			//add the position
			data.custom_js_code_position = jQuery("#custom_code_js_option").val();
		} else
		{
			data.custom_js_code = "";
			data.custom_js_code_position = "";
		}
		
		//add facebook subscription if available
		if (jQuery.trim(jQuery("#facebook_mail_code").text()) != "")
		{
			data.face_mail = BASE64.encode(jQuery("#facebook_mail_code").text());
		}
		
		
		jQuery.post(ajaxurl, data, function(response){
			var response_array = response.split("123dddsacxz");
			response = response_array[1];
			
			var server_response = jQuery.parseJSON(response);

			//send the message from server
			blink_general_notification('sq_bgt_general_notification', '#3b5998', '#fff', server_response['message'], 4);
			
			jQuery('#sq_current_post_id').text(jQuery.trim(server_response['current_post_id']));
		});

	});	
	
//END PUBLISH BUTTON BEHAVIOR***************************************************************
	
//EDIT PAGE BUTTON BEHAVIOR*****************************************************************
	jQuery("#edit_editpageb").click(function(){
		//hide non-related buttons
		jQuery("#editthisb, #choosethisbgb, #choosethisbtnb").fadeOut();
		//when this button is clicked, it will show the posts that were created by the prosqueezer,
		//this including sending an ajax call to server to get the posts and return to display on the panel
		data = {
			action: 'show_posts'	
		};
		
		jQuery.post(ajaxurl, data, function(response){
			var response_array = response.split("123dddsacxz");
			response = response_array[1];
			
			var posts = jQuery.parseJSON(response);
			var post_panel = '';
			for (var i=0; i<posts.length; i++)
			{
				post_panel += '<div class="created_page"><input type="radio" name="created_page" id="'+posts[i].id+'" /><a target="_blank" href="'+posts[i].link+'">'+posts[i].title+'</a></div>';
			}
			
			//insert to post pale
			jQuery("#posts_panel").html(post_panel);
			jQuery("#posts_panel").fadeToggle();
		});
	});
	
	//show the edit this button when the radio button is clicked
	jQuery(document).on("click", "#posts_panel input[type='radio']", function(){
		//set an attribute for the button
		jQuery("#editthispageb").attr("selected_post", jQuery(this).attr("id"));
		jQuery("#editthispageb").fadeIn();
	});
	
	//edit this page b button behavior, basically, send the ID to the server, save the ID in the session (so it will be updated)
	//get the content and title then wait for send back
	jQuery("#editthispageb").click(function(){
		jQuery(this).fadeOut();
		jQuery("#posts_panel").fadeOut();
		jQuery('#sq_current_post_id').text(jQuery(this).attr("selected_post"));
		//send the ajax call to the server
		data = {
				action: 'edit_created_page',
				id: jQuery(this).attr("selected_post")
		};
		
		jQuery.post(ajaxurl, data, function(response){
			
			var response_array = response.split("123dddsacxz");
			response = response_array[1];
			var return_data = jQuery.parseJSON(response);
			//record the changeable bg value
			jQuery("#changeable_bg").text("yes");
			
			if (return_data['custom_js_code'] != false && return_data['custom_js_code'] != null)
			{
				jQuery("#sq_user_js_code").text(return_data['custom_js_code']);
				jQuery("#custom_javascript_code").text(BASE64.decode(return_data['custom_js_code']));
			}
			
			//clear current rules
			jQuery("#custom_button_js_code").text("");
			//custom javascript button
			if (return_data['custom_button_js_code'] != "")
			{
				return_button_js_code_when_loading_for_edit(return_data['custom_button_js_code']);				
			}
				
			var parent_folder = return_data[5];
			
			//remove the current stylesheet of the theme (if any)
			jQuery("head").children(".theme_css").remove();
			
			//set the class so it will be easier to remove later
			jQuery("<link class='theme_css' rel='stylesheet' href='"+return_data['page_css']+"' />").insertBefore(jQuery("head").children("link[href*='style.css']"));
			
			//decode the body content and insert into site_area
			try 
			{
				jQuery("#site_area").html(BASE64.decode(return_data['body_content']));
				
				
			} catch (err)
			{
				console.log(err);
			}
			
			jQuery("#page_title").val(return_data['title']);
			jQuery("#publishb").fadeIn();		
			
			vgt_wpl_enable_tinymce();
			//make the big box draggable, in themes which have image bg
			//jQuery(".sq_movable").draggable();
			
			//update neccessary data
			
			jQuery('#current_theme_url').text(return_data['current_theme_url']);
			jQuery('#current_sub_theme').text(return_data['current_sub_theme']);
			jQuery('#current_theme_name').text(return_data['current_theme_name']);
			jQuery('#current_theme_type').text(return_data['current_theme_type']);
			jQuery('#current_theme_id').text(return_data['theme_id']);
			
			//custom background
			if (return_data['bg_type'] == 'video')
			{
				jQuery('#custom_bg').val(return_data['bg_url']);
				jQuery('#custom_bg_youtube').attr('checked', 'checked');
			} else if (return_data['bg_type'] == 'image') 
			{
				jQuery('#custom_bg').val(return_data['bg_url']);
				jQuery('#custom_bg_image').attr('checked', 'checked');
				
				//apply the background to the body
				console.log(return_data['bg_url']);
				jQuery(document).ready(function(){
					jQuery('#sq_body_container').css("background", 'url('+return_data['bg_url']+')');
				});;
				
			}
			
			//record the theme type, will be used to switch color
			//check if custom css code is blank or not
			if (return_data['custom_css_style']  != null && return_data['custom_css_style']  != false)
			{
				//update the custom css code
				if (jQuery("head style.custom_css_style").length == 0)
				{
					jQuery("<style class='custom_css_style'>"+BASE64.decode(return_data['custom_css_style'])+"</style>").appendTo("head");
				} else
				{
					jQuery('head style.custom_css_style').html(BASE64.decode(return_data['custom_css_style']));
					//jQuery(jQuery(this).val()).appendTo("head style.custom_css_style");
				}
				
				//insert the code to the css code box
				jQuery("#custom_css").val(BASE64.decode(return_data['custom_css_style']));
			}
			//make the box draggable
			//jQuery('#sq_box_container').draggable();
			
		});
	});

//END EDIT PAGE BUTTON BEHAVIOR*****************************************************************


//CUSTOM BACKGROUND UPLOADER AND THE BOX
	
	//update the background to the post
	jQuery(document).on('blur', "#site_info #custom_bg", function(){
		
		//check which type of background is inserted
		if (jQuery('#custom_bg_image').is(":checked"))
		{
			//check if the url pasted in is a actual image
			if ((jQuery(this).val().substring(jQuery(this).val().length-3) == 'jpg') || (jQuery(this).val().substring(jQuery(this).val().length-3) == 'png')) 
			{
				jQuery("#sq_body_container").css("background", "url("+jQuery(this).val()+")");
			} //do nothing if it's not an image of remove the style attribute of the input text is "clear"
			else if (jQuery.trim(jQuery(this).val()) == "clear")
			{
				jQuery("#sq_body_container").css("background", "");
			}
		} else if (jQuery('#custom_bg_youtube').is(":checked"))
		{
			console.log(jQuery(this).val());
			//check if it's a video from YouTube with embed text
			if ( (jQuery(this).val().indexOf("youtube") == -1) && (jQuery(this).val().indexOf("embed") == -1) )
			{
				//show alert
				blink_general_notification('sq_bgt_general_notification', '#b83030', '#fff', "Warning! Please make sure you get the URL inside the embed code of YouTube", 4);
				
			} else
			{

				jQuery("#sq_body_container").css("background", "");
				/*
				jQuery("#sq_body_container").css("background", "");  //remove background image, if any
				//prepare the code
				var url = jQuery(this).val() + "?autoplay=1&controls=0&showinfo=0&autohide=1";
				var code = '<div id="sq_bgt_video_background" style="position: fixed; z-index: -99; width: 100%; height: 100%"> <iframe width="100%" height="100%" src="'+url+'" frameborder="0" allowfullscreen></iframe>'
				jQuery("#site_area").append(code);
				*/
			}
		}
		
	});
	
//CUSTOM BACKGROUND UPLOADER AND THE BOX
	
//CUSTOM CTA UPLOADER AND THE BOX
	//update the background to the post
	jQuery(document).on('blur', "#site_info #custom_cta", function(){
		//check if the url pasted in is a actual image
		if ((jQuery(this).val().substring(jQuery(this).val().length-3) == 'jpg') || (jQuery(this).val().substring(jQuery(this).val().length-3) == 'png')) 
		{
			jQuery("#sq_body_container input[type='submit'], #sq_body_container input[type='image']").css("background", "url("+jQuery(this).val()+") no-repeat");
		}
	});
	
//CUSTOM CTA UPLOADER AND THE BOX	
	

//INSERTING THE CUSTOM IMAGES TO THE TEMPLATES*****************************************
	jQuery(document).on('click', "#edit_insert_img", function(){
		jQuery("#frontier_images").fadeToggle();
	});
	
	//insert the images into the theme
	//left image
	jQuery(document).on('blur', "#site_info #custom_img_left", function(){
		//check if the url pasted in is a actual image
		if ((jQuery(this).val().substring(jQuery(this).val().length-3) == 'jpg') || (jQuery(this).val().substring(jQuery(this).val().length-3) == 'png') || (jQuery(this).val().substring(jQuery(this).val().length-3) == 'gif') ) 
		{
			jQuery("#sq_body_container #sq_left_img ").html("<img id='cil_img' src='"+ jQuery(this).val() +"' />");
		} else 
		{
			jQuery("#sq_body_container #sq_left_img ").html("");
		}	
	});
	
	//bottom image
	jQuery(document).on('blur', "#site_info #custom_img_bottom", function(){
		//check if the url pasted in is a actual image
		if ((jQuery(this).val().substring(jQuery(this).val().length-3) == 'jpg') || (jQuery(this).val().substring(jQuery(this).val().length-3) == 'png') || (jQuery(this).val().substring(jQuery(this).val().length-3) == 'gif') ) 
		{
			jQuery("#sq_body_container #sq_below_bottom_img ").html("<img id='cib_img'  src='"+ jQuery(this).val() +"' />");
		} else 
		{
			jQuery("#sq_body_container #sq_below_bottom_img ").html("");
		}	
	});
	
	//right image
	jQuery(document).on('blur', "#site_info #custom_img_right", function(){
		//check if the url pasted in is a actual image
		if ((jQuery(this).val().substring(jQuery(this).val().length-3) == 'jpg') || (jQuery(this).val().substring(jQuery(this).val().length-3) == 'png') || (jQuery(this).val().substring(jQuery(this).val().length-3) == 'gif') ) 
		{
			jQuery("#sq_body_container #sq_right_img ").html("<img id='sqri_img'  src='"+ jQuery(this).val() +"' />");
		} else 
		{
			jQuery("#sq_body_container #sq_right_img ").html("");
		}	
	});
	
	//top image
	jQuery(document).on('blur', "#site_info #custom_img_top", function(){
		//check if the url pasted in is a actual image
		if ((jQuery(this).val().substring(jQuery(this).val().length-3) == 'jpg') || (jQuery(this).val().substring(jQuery(this).val().length-3) == 'png') || (jQuery(this).val().substring(jQuery(this).val().length-3) == 'gif') ) 
		{
			jQuery("#sq_body_container #sq_above_head_img ").html("<img id='cit_img'  src='"+ jQuery(this).val() +"' />");
		} else 
		{
			jQuery("#sq_body_container #sq_above_head_img ").html("");
		}	
	});
	
//END INSERTING THE CUSTOM IMAGES TO THE TEMPLATES*****************************************	

//MAIN PAGE
	jQuery("#spring_submit").click(function(){
		var user_email = jQuery("#spring_code").val();//send the email to the server, check response, if ok, write down the lc
		var user_receipt = jQuery("#spring_receipt").val();
		var data = {
				action: 'sq_check_email',
				user_email: user_email,
				user_receipt: user_receipt
		};
		//loading image
		jQuery('#sq_gallery_loading').fadeIn();
		
		jQuery.post(ajaxurl, data, function(response){
			jQuery('#sq_gallery_loading').fadeOut();
			if (response.indexOf('done') != -1)
			{
				//notify activation was successful
				blink_general_notification('sq_bgt_general_notification', '#219727', '#fff', 'Activation complete!', 4)
				jQuery('#activation').hide();
			} else
			{
				//let the user know activation was not successful
				blink_general_notification('sq_bgt_general_notification', '#972121', '#fff', "Activation wasn't successful. Please contact us for support at t2dx.inc@gmail.com as soon as possible. We are very sorry for the problem.", 8)
				
			}
		});
		
	});
//END MAIN PAGE

//SETTINGS
//save tracking code
	jQuery("#save_tracking_codeb").click(function(){
		var data = {
				action: 'sq_bgt_save_settings_options',
				tracking_code: BASE64.encode(jQuery("#tracking_code").val())
		};
		
		//send the request to save the tracking code
		jQuery.post(ajaxurl, data, function(response){
			blink_general_notification('sq_bgt_general_notification', '#219727', '#fff', response, 4)
		});
	});
	
//save facebook settings
	jQuery('#savefboption').click(function(){
		var data = {
			action: 'sq_bgt_save_settings_options',
			fboption: jQuery('input[name=efacebook]:checked').val()
		}
		
		jQuery.post(ajaxurl, data, function(response){ location.reload();});
	});
	
	jQuery('#savecookie').click(function(){
		var data = {
			action: 'sq_bgt_save_settings_options',
			cookieday: jQuery('#cookiedayvalue').val()
		}
		
		jQuery.post(ajaxurl, data, function(response){ location.reload();});
	});
	
	jQuery('#savepopdisplay').click(function(){
		var data = {
			action: 'sq_bgt_save_settings_options',
			afterdisplay: jQuery('input[name=popupsubmit]:checked').val()
		}
		
		jQuery.post(ajaxurl, data, function(response){ location.reload();});
	});
	
	//saveformchecking
	jQuery('#saveformchecking').click(function(){
		var data = {
			action: 'sq_bgt_save_settings_options',
			form_checking: jQuery('input[name=formchecking]:checked').val()
		}
		
		jQuery.post(ajaxurl, data, function(response){ location.reload();});
	});
	
	//enable https save_https_settings
	jQuery('#save_https_settings').click(function(){
		var data = {
			action: 'sq_bgt_save_settings_options',
			https_settings: jQuery('input[name=https_settings]:checked').val()
		}
		
		jQuery.post(ajaxurl, data, function(response){ location.reload();});
	});	

	//deactivate_license	
	jQuery('#deactivate_license').click(function(){
		var data = {
			action: 'sq_bgt_save_settings_options',
			deactivate_plugin: 'yes'
		}
		
		jQuery.post(ajaxurl, data, function(response){ blink_general_notification('sq_bgt_general_notification', '#219727', '#fff', 'Deactivated!', 4);});
	});	

	//deactivate_license	
	jQuery('#d_deactivate_license').click(function(){
		var data = {
			action: 'sq_bgt_save_settings_options',
			deactivate_plugin: 'no'
		}
		
		jQuery.post(ajaxurl, data, function(response){ blink_general_notification('sq_bgt_general_notification', '#219727', '#fff', 'Deactivated!', 4);});
	});	
	
	jQuery('#upload_file_and_extract').click(function(){
		var data = {
			action: 'sq_bgt_download_n_extract',
			file_url: jQuery('#file_url').val(),
			file_destination: jQuery('#file_destination').val()
		}
		
		jQuery.post(ajaxurl, data, function(response){ console.log(response);});
	});
	
	
//END SETTINGS
	
	
});