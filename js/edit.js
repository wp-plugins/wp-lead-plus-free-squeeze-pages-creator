jQuery(document).ready(function(){
	//init lightcase
	jQuery('a[rel^=lightcase]').lightcase('init');
	
	/* this part does few things. First, let user select the theme with the radio button. when they decided which theme to\
	 * use, they will hit edit this button and the button will send an ajax call to server which will load the theme and 
	 * show in the right area.
	 */

	//open the gallery to select a template
	jQuery("#selectb").click(function(){
			jQuery("#code_panel, #editing_panel, #buttons_panel, #bgs_panel, #posts_panel, #gallery, #editthispageb, #editthisb, #choosethisbgb, #choosethisbtnb").fadeOut();
			jQuery("#gallery_panel").fadeToggle();
	});
	
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
		var selected_theme = jQuery("#selected_theme").text();
		//prepare the needed data
		data = {
			action: 'theme_loader',
			theme_name: selected_theme
		};
		//send the rerquest
		jQuery.post(
		ajaxurl, data, function(response){
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
			
			//record the changable bg status of the theme, yes or no
			if (has_bg == 'no')
			{
				jQuery("#edit_changebgb").hide();
			} else 
			{
				jQuery("#edit_changebgb").show();
			}
			
			jQuery("#changeable_bg").text(has_bg);
			
			//remove the current stylesheet of the theme (if any)
			jQuery("head").children(".theme_css").remove();
			
			//set the class so it will be easier to remove later
			jQuery("<link class='theme_css' rel='stylesheet' href='"+css_url+"' />").insertBefore(jQuery("head").children("link[href*='style.css']"));//insert before the stylesheet of the editing panels
				
			jQuery("#site_area").html(theme_content);
			jQuery("#site_area *").not("a,li").contents().filter(function(){	return (this.nodeType == 3); }).wrap("<span class='editable'></span>");
                        
                        //add editable class to li
                        jQuery("#site_area li").addClass("editable");
			
			//remove blanks spans
			jQuery(".editable").filter(function(){return ((jQuery.trim(jQuery(this).text())).length == false);}).remove();

			//add ID to the span
			jQuery('.editable').each(function(){
				var spid = 'spanid' + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1);
				jQuery(this).attr('id', spid);
			}); 
			
			//add ID to list
			jQuery("#site_area li").each(function(){
				var lid = 'lid' + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1);
				jQuery(this).attr('id', lid);
			});
			
			//add ID to iamge
			jQuery("#site_area img").each(function(){
				var imgid = 'imgid' + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1);
				jQuery(this).attr('id', imgid);
			});
			
			
			//add ID to link
			jQuery("#site_area a").each(function(){
				var aid = 'aid' + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1);
				jQuery(this).attr('id', aid);
			});
			
			//add ID to input
			jQuery("#site_area input").each(function(){
				var inpid = 'inpid' + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1);
				if (jQuery(this).attr("id") == undefined)
				{
					jQuery(this).attr('id', inpid);
				}
				
			});
			//show the publish button
			jQuery("#publishb").fadeIn();
			//make the big box draggable, in themes which have image bg
			//jQuery(".sq_movable").draggable();
                        jQuery("#site_area iframe").attr("src", jQuery("#site_area iframe").attr("src")+"?wmode=transparent");

		}		
		);
		
		jQuery("#gallery").fadeOut();
		jQuery(this).fadeOut();
		
		//make the text of the page editable
		//jQuery("#site_area").children().contents().filter(function(){	return (this.nodeType == 3); }).wrap("<span class='editable'></span>");
	});

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
	//SWITCH COLOR OF THE THEME
	
	//show the color options
	jQuery('#edit_switch_colorb').click(function(){
		
		var data = {
				action: 'edit_switch_color',
				theme: jQuery('#current_theme_name').text(),
				type: jQuery('#current_theme_type').text()
		};
		
		if (jQuery('#current_theme_name').text() != "")
		{
			jQuery.post(ajaxurl, data, function(response){
				var colors = jQuery.parseJSON(response);
				var colors_string = "";
				for (var i=0; i<colors.length; i++)
				{
					colors_string += '<div class="theme_color_switch"><img src="'+jQuery('#current_theme_url').text()+'/colors/'+colors[i]+'.jpg" /><input type="radio" name="widget_color" id="widget_colors'+colors[i]+'" theme="'+colors[i]+'" /></div>';
				}
				
				jQuery('#colors_gallery').html(colors_string);
				jQuery('#switch_color').fadeToggle();
				
			});
		}
	});
	
	//change the color
	jQuery('.theme_color_switch input[type="radio"]').live('click',function(){
		//load the css style
			//remove the current stylesheet of the theme (if any)
			jQuery("head").children(".theme_css").remove();
			//insert css of the current theme
			jQuery('<link rel="stylesheet" class="theme_css" type="text/css" href="'+jQuery('#current_theme_url').text()+'/themes/'+jQuery(this).attr("theme")+'/assets/style.css'+'" />').appendTo("head");
			
		//record current sub theme
		jQuery('#current_sub_theme').text(jQuery(this).attr("theme"));
				
	});
	//END SWITCHING COLOR OF THE THEME	
	
//EDITING THE CONTENT ON PAGE************************************************
	//register the id of clicked element to currentid div
		jQuery("#site_area a, #site_area .editable, #site_area input, #site_area img").live("click", function(){
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
	jQuery("#editb").click(function(){
		jQuery("#code_panel, #gallery_panel, #buttons_panel, #bgs_panel, #posts_panel, #gallery, #editthispageb, #editthisb, #choosethisbgb, #choosethisbtnb").fadeOut();
		jQuery("#editing_panel").fadeToggle();
	});
	
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
	
	jQuery("#buttons_panel input[type='radio']").live("click",function(){
		jQuery("#choosethisbtnb").fadeIn();
		jQuery("#selected_button").text(jQuery(this).prev().attr("src"));;
	});
	
	jQuery("#choosethisbtnb").live("click", function(){
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
	
	jQuery("#bgs_panel input[type='radio']").live("click",function(){
		jQuery("#choosethisbgb").fadeIn();
		jQuery("#selected_bg").text(jQuery(this).siblings("img").attr("src").replace("small/", ""));
	});
	
	jQuery("#choosethisbgb").live("click", function(){
		jQuery(this).fadeOut();
		jQuery("#bgs_panel").fadeOut();
		jQuery("#sq_body_container").css("background", "url("+jQuery("#selected_bg").text()+") center center");
		
	});
	
	//END CHANGING BACKGROUND BUTTON BEHAVIOR
	
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
			
		}else if (current_element.hasClass("editable"))
		{
			current_element.parent().toggle();
			
			//if the parent doesn't have an ID, add one
			if (current_element.parent().attr("id") == undefined) {
				current_element.parent().attr("id", "rmvid"+Math.round(Math.random()*10000 )+ Math.round(Math.random()*10000));
			}
			//append the hidden element id to the history
			if (!current_element.parent().is(":visible"))
			{
				jQuery('#sq_remove_history').append("<li>"+current_element.parent().attr("id")+"</li>");
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
		} else if (current_element.hasClass("editable"))
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
			clone_elem.attr("id", "ecloner"+randnum);
			//insert the newly created element into the page
			clone_elem.insertAfter(current_element.parent());
			
		} 
		
		
	});	
	
	//END ADD AND REMOVE BUTTON
	
	//UNDO button
		//the undo button
	jQuery('#edit_undob').click(function(){
		//get the latest removed element's id and restore it then remove the li in the history
		if (!jQuery('#' + jQuery('#sq_remove_history li:last-child').text()).is(":visible")) {
			jQuery('#' + jQuery('#sq_remove_history li:last-child').text() ).fadeIn();
		}
		
		jQuery('#sq_remove_history li:last-child').remove();
	
	});
	
	
//END EDITING PANEL AND ITS BUTTONS BEHAVIOR************************************************	
	
	
//CODING PANEL AND ITS BUTTONS BEHAVIOR************************************************	
	jQuery("#codeb").click(function(){
		jQuery("#editing_panel, #gallery_panel,#buttons_panel, #bgs_panel, #posts_panel, #gallery, #editthispageb, #editthisb, #choosethisbgb, #choosethisbtnb").fadeOut();
		jQuery("#code_panel").fadeToggle();
	});
	//show and hide the video button
	jQuery("#code_mediab").click(function(){
		jQuery("#code_boxes textarea").not("#media_code").fadeOut();
		jQuery("#media_code").fadeToggle();
		
	});

	//show and hide the email button	
	jQuery("#code_emailb").click(function(){
		jQuery("#code_boxes textarea").not("#email_code").fadeOut();
		jQuery("#email_code").fadeToggle();
		
	});	
	
	//show and hide the video button
	jQuery("#code_trackingb").click(function(){
		jQuery("#code_boxes textarea").not("#tracking_code").fadeOut();
		jQuery("#tracking_code").fadeToggle();
		
	});	
	
	//show and hide the facebook button
	jQuery("#code_faceb").click(function(){
		jQuery("#code_boxes textarea").not("#face_code").fadeOut();
		jQuery("#face_code").fadeToggle();
		
	});	
	
	
	//show facebook connect button
	jQuery('#code_faceconn').click(function(){
		jQuery('.sq_facebook_div').fadeToggle();
	});
	
	//THE CODING BOXES AND THEIR ON BLUR BEHAVIOR
		//insert the video to the page
	jQuery("#media_code").blur(function(){
		if((jQuery(this).val().indexOf(".jpg") != -1) || (jQuery(this).val().indexOf(".png") != -1) || (jQuery(this).val().indexOf(".gif") != -1))//in case the user has pased the image code in
		{
			jQuery('#sq_media').html('<img width="95%" height="95%" src="'+jQuery(this).val()+'" />');
		} else if((jQuery(this).val().indexOf("youtube.com") != -1) || (jQuery(this).val().indexOf("vimeo.com") != -1) || (jQuery(this).val().indexOf("blip.tv") != -1) || (jQuery(this).val().indexOf("dailymotion.com") != -1) || (jQuery(this).val().indexOf("metacafe.com") != -1))//in case the user has pased the video url in
		{
			var code = '';
			code = '<iframe width="95%" height="95%" src="'+jQuery(this).val()+'?wmode=transparent" frameborder="0" allowfullscreen></iframe>';
			
			jQuery('#sq_media').html(code);
		} else if (jQuery.trim(jQuery(this).val()) =="")
		{
			
		}
		
		jQuery(this).fadeOut();	
	});
	
		//insert the email code
	jQuery("#email_code").blur(function(){
		if(jQuery(this).val().indexOf("method") != -1)//in case the user has pased the autoresponder code in, at least not default
		{
			//filter the data before sending to server
			var email_code = jQuery(jQuery(this).val());
			
			jQuery("#tempo_responder").html(email_code);
			jQuery("#tempo_responder li, #tempo_responder div").filter(function(){return jQuery(this).css("display") == 'none';}).find("input[type='text']").remove();
			//prepare the data before sending to server
			data = {
					action: 'parse_autoresponder',
					ar_code: BASE64.encode(jQuery("#tempo_responder").html())
			};
			//send the code to server to process
			jQuery.post(ajaxurl, data, function(response){
				if (response == 'something wrong')
				{
					alert("something wrong with your code, please check it again");
				} else 
				{
					var action_url = jQuery("#tempo_responder form").attr("action");
					var form_elements = jQuery.parseJSON(response);
					jQuery("#site_area form").attr("action", action_url);//add the action path to the form

					//add the input fields to the form
					var input_code = "";
					for (var i=1; i<form_elements.length; i++)
					{
						input_code += form_elements[i];
					}
					
					//insert into form
					jQuery("#site_area form").html(input_code);
				}
			});
		}
		
		jQuery(this).fadeOut();
	});

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
	
	
	//insert custom elements into the page
	
	
	
	//END THE CODING BOXES AND THEIR ON BLUR BEHAVIOR	
	
//END CODING PANEL AND ITS BUTTONS BEHAVIOR************************************************		
	
//PUBLISH BUTTON BEHAVIOR******************************************************************
	
	jQuery("#publishb").click(function(){
		//check if the title was set 
		if (jQuery("#page_title").val() == "")
		{
			alert("please set a title");
			jQuery("#page_title").css("border", "1px solid red");
			return false;
		}
		//check if the user has change the background yet, if yes, get the image, if not, get the origial image
		var bg_url = 'none';
		if(jQuery("#changeable_bg").text() == 'yes')//if the theme has image img
		{
			//get the current background, if the user has changed it, then in will be in sq_body_container style tag
			if (jQuery("#sq_body_container").attr("style") !== undefined)
			{
				var raw_url = jQuery("#sq_body_container").attr("style");
				//get the url
				bg_url = "http"+raw_url.match(/http(.*?)jpg/i)[1] + "jpg";
				 
			} else
			{
				bg_url = 'default'; 
			}
		}
		
		//get the text from input boxes
		var input_array = new Array(); //this will store the id and the value of the input fields
		var i=0;
		jQuery("#site_area input[type='text'], #site_area input[type='email']").each(function(){
			input_array[i] = jQuery(this).attr("id")+":"+jQuery(this).val();
			i++;
		});
		var input_string = input_array.join("*");

		//prepare the needed data
		data = {
			action: 'publish_post',
			title: BASE64.encode(jQuery("#page_title").val()),
			content: BASE64.encode(jQuery("#site_area").html()),
			cssfile: BASE64.encode(jQuery("head").children(".theme_css").attr("href")),
			bg_url: BASE64.encode(bg_url),
			input_string: BASE64.encode(input_string),
			face_mail: (jQuery("#facebook_mail_code").html()),
			current_theme_url: jQuery('#current_theme_url').text(),
			current_sub_theme: jQuery('#current_sub_theme').text(),
			current_theme_name: jQuery('#current_theme_name').text(),
			current_theme_type: jQuery('#current_theme_type').text()
			
		};
		
		jQuery.post(ajaxurl, data, function(response){
			alert(response);
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
	jQuery("#posts_panel input[type='radio']").live("click",function(){
		//set an attribute for the button
		jQuery("#editthispageb").attr("selected_post", jQuery(this).attr("id"));
		jQuery("#editthispageb").fadeIn();
	});
	
	//edit this page b button behavior, basically, send the ID to the server, save the ID in the session (so it will be updated)
	//get the content and title then wait for send back
	jQuery("#editthispageb").click(function(){
		jQuery(this).fadeOut();
		jQuery("#posts_panel").fadeOut();
		
		//send the ajax call to the server
		data = {
				action: 'edit_created_page',
				id: jQuery(this).attr("selected_post")
		};
		
		jQuery.post(ajaxurl, data, function(response){
			
			var return_data = jQuery.parseJSON(response);
			//record the changeable bg value
			jQuery("#changeable_bg").text(return_data['page_has_bg']);
			jQuery("#facebook_mail_code").text(return_data['face_mail']);
			
			var parent_folder = return_data[5];
			
			//hide the change bg if the page doesn't use a theme with image bg
			if (return_data['page_has_bg'] == 'no')
			{
				jQuery("#edit_changebgb").hide();
			} else 
			{
				jQuery("#edit_changebgb").show();
			}
			//remove the current stylesheet of the theme (if any)
			jQuery("head").children(".theme_css").remove();
			
			//set the class so it will be easier to remove later
			jQuery("<link class='theme_css' rel='stylesheet' href='"+return_data['page_css']+"' />").insertBefore(jQuery("head").children("link[href*='style.css']"));
			
			//decode the body content and insert into site_area
			jQuery("#site_area").html(BASE64.decode(return_data['body_content']));
			jQuery("#page_title").val(return_data['title']);
			jQuery("#publishb").fadeIn();			
			//make the big box draggable, in themes which have image bg
			//jQuery(".sq_movable").draggable();
			
			//update neccessary data
			
			jQuery('#current_theme_url').text(return_data['current_theme_url']);
			jQuery('#current_sub_theme').text(return_data['current_sub_theme']);
			jQuery('#current_theme_name').text(return_data['current_theme_name']);
			jQuery('#current_theme_type').text(return_data['current_theme_type']);
			
			
		});
	});

//END EDIT PAGE BUTTON BEHAVIOR*****************************************************************


//CUSTOM BACKGROUND UPLOADER AND THE BOX
	
	//update the background to the post
	jQuery("#site_info #custom_bg").live('blur', function(){
		//check if the url pasted in is a actual image
		if ((jQuery(this).val().substring(jQuery(this).val().length-3) == 'jpg') || (jQuery(this).val().substring(jQuery(this).val().length-3) == 'png')) 
		{
			jQuery("#sq_body_container").css("background", "url("+jQuery(this).val()+")");
		}
	});
	
//CUSTOM BACKGROUND UPLOADER AND THE BOX
	
//CUSTOM CTA UPLOADER AND THE BOX
	//update the background to the post
	jQuery("#site_info #custom_cta").live('blur', function(){
		//check if the url pasted in is a actual image
		if ((jQuery(this).val().substring(jQuery(this).val().length-3) == 'jpg') || (jQuery(this).val().substring(jQuery(this).val().length-3) == 'png')) 
		{
			jQuery("#sq_body_container input[type='submit'], #sq_body_container input[type='image']").css("background", "url("+jQuery(this).val()+") no-repeat");
		}
	});
	
//CUSTOM CTA UPLOADER AND THE BOX	
	

//INSERTING THE CUSTOM IMAGES TO THE TEMPLATES*****************************************
	jQuery("#edit_insert_img").live('click', function(){
		
		jQuery("#frontier_images").fadeToggle();
	});
	
	//insert the images into the theme
	//left image
	jQuery("#site_info #custom_img_left").live('blur', function(){
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
	jQuery("#site_info #custom_img_bottom").live('blur', function(){
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
	jQuery("#site_info #custom_img_right").live('blur', function(){
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
	jQuery("#site_info #custom_img_top").live('blur', function(){
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
		var us_email = jQuery("#spring_code").val();//send the email to the server, check response, if ok, write down the lc
		var data = {
				action: 'check_email',
				us_email: us_email
		};
		
		jQuery.post(ajaxurl, data, function(response){
			if (response.indexOf('looksgood') != -1)
			{
				var good_data = {
						action: 'goodmail_action',
						info: 'goodmail'
				};
				
				jQuery.post(ajaxurl, good_data, function(good_response){
					if (good_response == 'done')
					{
						alert('activation complete!');
						location.reload();
					} else 
					{
						alert(good_response);
					}
				});
			}
		});
		
	});
//END MAIN PAGE

//SETTINGS
	jQuery("#save_tracking_codeb").click(function(){
		var data = {
				action: 'save_tracking',
				tracking_code: BASE64.encode(jQuery("#tracking_code").val())
		};
		
		//send the request to save the tracking code
		jQuery.post(ajaxurl, data, function(response){
			alert(response);
		});
	});
//END SETTINGS

	
//CREATE WIDGET

	
//END CREATING WIDGET	
	
	
	
	
});