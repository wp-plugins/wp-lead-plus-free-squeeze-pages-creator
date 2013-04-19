jQuery(document).ready(function(){
	//init lightcase
	jQuery('a[rel^=lightcase]').lightcase('init');
	
	//SELECT THEME OF THE popup
	//show the gallery
	jQuery("#popup_selectb").click(function(){
		jQuery("#popup_themes").fadeToggle();
	});
	
	//show the edit button
	jQuery('#popup_themes input[type="radio"]').click(function(){
		jQuery('#popup_edit_this_theme').fadeIn();
		jQuery('#popup_theme_url').text(jQuery(this).attr("url"));
		jQuery('#popup_current_theme').text(jQuery(this).attr("id"));//record the current theme
	});
	
	//react on edit this click, load the theme actually
	jQuery('#popup_edit_this_theme').click(function(){
		jQuery(this).fadeOut();//hide the edit this button
		jQuery("#popup_themes").fadeOut();
		
		//generate an unique id to store in the db later. Each time the user load a new theme, new id is generated (this is stupid *facepalm*)
		var rand_pop_id = (Math.floor(Math.random()*1000000 + 1) + Math.floor(Math.random()*1000000 + 1) + Math.floor(Math.random()*1000000 + 1) + Math.floor(Math.random()*1000000 + 1) + Math.floor(Math.random()*1000000 + 1) + Math.floor(Math.random()*1000000 + 1)+Math.floor(Math.random()*1000000 + 1) + Math.floor(Math.random()*1000000 + 1) + Math.floor(Math.random()*1000000 + 1) + Math.floor(Math.random()*1000000 + 1) + Math.floor(Math.random()*1000000 + 1) + Math.floor(Math.random()*1000000 + 1));
		jQuery('#popup_unique_id').text(rand_pop_id);
		
		//load the theme
		var data = {
				action: 'popup_theme_loader',
				url: BASE64.encode(jQuery('#popup_theme_url').text())
		};
		
		jQuery.post(ajaxurl, data, function(response){
			//insert the code
			jQuery('#site_area').html(BASE64.decode(response));
			//load the css style
				//remove the current stylesheet of the theme (if any)
				jQuery("head").children(".popup_theme_css").remove();
				//insert css of the current theme
				jQuery('<link rel="stylesheet" class="popup_theme_css" type="text/css" href="'+jQuery('#popup_theme_url').text()+'/1/assets/style.css'+'" />').appendTo("head");
			
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
				
		
			//add ID to input
			jQuery("#site_area input").each(function(){
				var inpid = 'inpid' + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1);
				if (jQuery(this).attr("id") == undefined)
				{
					jQuery(this).attr('id', inpid);
				}
				
			});
			
			//add ID to editable
			jQuery("#site_area .editable").each(function(){
				var inpid = 'textid' + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1) + Math.floor((Math.random()*100)+1);
				if (jQuery(this).attr("id") == undefined)
				{
					jQuery(this).attr('id', inpid);
				}
				
			});
			
			//make the popup resizable
			jQuery('#sq_popup_optin_container').resizable();
			jQuery('#sq_popup_media').resizable();
			
		});
		
	});
	
	//END SELECTING THEME OF THE popup	
	
	//BASIC BEHAVIOR OF BOTTOM PANEL BUTTONS
		jQuery('#popup_editb').click(function(){
			jQuery('#popup_code_panel').fadeOut();
			jQuery('#popup_edit_panel').fadeToggle();
		});
	
	//END BASIC BEHAVIOR OF BOTTOM PANEL BUTTONS


	//CHANGE COLOR OF THE THEME
		jQuery('#popup_color_changer').ColorPicker({onChange: function(hsb, hex, rgb){ jQuery('#site_area > div').css("background-color", "#"+hex) }, flat:true});
	
	//INSERT THE CODE EMAIL/MEDIA
		//show the buttons of coding panel
		jQuery('#popup_codeb').click(function(){
			jQuery('#popup_edit_panel').fadeOut();
			jQuery('#popup_code_panel').fadeToggle();
		});
		
		//show the email box 
		jQuery('#popup_code_email').click(function(){
			jQuery("#code_boxes textarea").not("#popup_code_email").fadeOut();
			jQuery('#popup_email_code').fadeToggle();
		});
		
		//process the email code
		
		//show the media box
		jQuery('#popup_code_media').click(function(){
			jQuery("#code_boxes textarea").not("#popup_media_code").fadeOut();
			jQuery("#popup_media_code").fadeToggle();
		});
		
		//inserting media
		
		jQuery("#popup_media_code").blur(function(){
			if((jQuery(this).val().indexOf(".jpg") != -1) || (jQuery(this).val().indexOf(".png") != -1) || (jQuery(this).val().indexOf(".gif") != -1))//in case the user has pased the image code in
			{
				jQuery('#sq_popup_media').html('<img src="'+jQuery(this).val()+'" />');
			} else if((jQuery(this).val().indexOf("youtube.com") != -1) || (jQuery(this).val().indexOf("vimeo.com") != -1) || (jQuery(this).val().indexOf("blip.tv") != -1) || (jQuery(this).val().indexOf("dailymotion.com") != -1) || (jQuery(this).val().indexOf("metacafe.com") != -1))//in case the user has pased the video url in
			{
				var code = '';
				var width = jQuery('#sq_widget_media').width();
				var height = width*0.75;
				code = '<iframe width="95%" height="'+height+'" src="'+jQuery(this).val()+'" frameborder="0" allowfullscreen></iframe>';

				jQuery('#sq_popup_media').html(code);
			} else if (jQuery.trim(jQuery(this).val()) =="")
			{
				
			}
			
			jQuery(this).fadeOut();	
		});
		
	//END INSERTING THE CODE EMAIL/VIDEO
	
	
	//CHANGE THE BUTTON
		jQuery('#popup_edit_change_btn').click(function(){
			//show the buttons collection
			var theme_url = jQuery.trim(jQuery('#popup_theme_url').text()); 
			var data = {
					action: 'popup_show_buttons',
					theme: theme_url.substring(theme_url.length - 1)					
			};
			
			jQuery.post(ajaxurl, data, function(response){
				var buttons = jQuery.parseJSON(response);
				var button_string = "";
				
				for (var i=0; i<buttons.length; i++)
				{
					button_string += '<div class="popup_cta_btn" style="float:left;"><img src="'+buttons[i]+'" /><input type="radio" name="popup_cta" id="'+buttons[i]+'" /></div>';
				}
				
				jQuery('#popup_cta_btns').html(button_string);
				jQuery('#popup_cta_btns').fadeToggle();
				jQuery(this).fadeOut();
			});
			
		});
		
		//show the edit button
		jQuery('#popup_cta_btns input[type="radio"]').live('click',function(){
			jQuery('#popup_choosethisbtnb').fadeIn();
		});
		
		//change the button
		jQuery('#popup_choosethisbtnb').click(function(){
			jQuery('#popup_cta_btns').fadeOut();
			jQuery('#this').fadeOut();
			
			//get the button
			var selected_button = jQuery('#popup_cta_btns input[type="radio"]:checked').attr('id');
			jQuery("#site_area input[type='submit']").css("background", "url("+selected_button+") no-repeat");
		});
		
	//END CHANGING THE BUTTON

        //Add and remove button moved to common

	//SWITCH COLOR BUTTON
	jQuery('#popup_edit_switch_color').live('click', function(){
		jQuery('#popup_switch_color').fadeToggle();
	});
	
	//switch the color
	jQuery('input[name=switch_color]').click(function(){
		//get the css color
		var css_url = jQuery('#popup_theme_url').text()+'/'+jQuery(this).attr('color')+'/assets/style.css';
		
		jQuery('.popup_theme_css').remove();
		jQuery('<link rel="stylesheet" class="popup_theme_css" type="text/css" href="'+css_url+'" />').appendTo("head");
	
	});
	
	//END SWITCH COLOR BUTTON
	
	
	
	//GET POPUP CODE, save the code to db
		jQuery('#popup_getcodeb').click(function(){
			var popup_code = jQuery('#site_area').html();

			//get the code of the popup
			var popup_style = BASE64.encode('<link rel="stylesheet" class="popup_theme_css" type="text/css" href="'+jQuery('.popup_theme_css').attr("href")+'" />');
			//make the text in the input box autohide when clicked
			var input_code = "";
			jQuery('#sq_popup_optin_container input[type="text"], #sq_popup_optin_container input[type="email"]').each(function(){
				input_code +='jQuery("#'+jQuery(this).attr("id")+'").click(function(){if (jQuery(this).val() == "'+jQuery(this).val()+'") {jQuery(this).val("");}  });'
				input_code +='jQuery("#'+jQuery(this).attr("id")+'").blur(function(){if (jQuery(this).val() == "") {jQuery(this).val("'+jQuery(this).val()+'");} });'
				
			});
			
			popup_code += '<script>jQuery(document).ready(function(){'+input_code+'});</script>';
			popup_code = BASE64.encode(popup_code);

			//generate a name for the popup
			var name = "generic name";
			if (jQuery.trim(jQuery('#popup_name').val()) !== "")
			{
				name = jQuery.trim(jQuery('#popup_name').val());
			}
			
			var data = {
					action: 'popup_save_to_db',
					popup_code: popup_code,
					name: name,
					css_url: popup_style, 
					popup_id: jQuery('#popup_unique_id').text()
			};
			
			//send the ajax call to server
			jQuery.post(ajaxurl, data, function(response){
				alert(response);
			});
		});
		
		jQuery('#popup_show_code_close').click(function(){
			jQuery('#popup_show_code').fadeOut();
		});
		
/* -----------------------------------*/
/* ---------->>> MANAGE POPUP <<<-----------*/
/* -----------------------------------*/
	//END GET popup CODE
		
	//POPUP MANAGE JS
		
	//show the preview of the popup
	jQuery('.listing_view_popup, .pop_manage_preview_pop').live('click', function(){
		//prepare data to send to the server
		data = {
			action: 'popup_manage_show_preview',
			popup_id: jQuery(this).attr("pop_id")
		};
		
		//send the ajax to server
		jQuery.post(ajaxurl, data, function(response){
			var data = jQuery.parseJSON(response);
			
		//remove the current stylesheet of the theme (if any)
		jQuery("head").children(".popup_theme_css").remove();
		//insert css of the current theme
		jQuery(BASE64.decode(data['css_url'])).appendTo("head");
		
		//increase the height of the preview box
		jQuery(".contentInner").css("height", "");
		jQuery(".contentInner").css("min-height", "400px");
		jQuery(".inlineWrap").css("height", "");
		jQuery(".inlineWrap").css("min-height", "400px");
		//insert the code into the box
		jQuery('#pop_theme_preview').html(BASE64.decode(data['code']));
		
		});
	});
	
	//end showing preview
	
	//show the box to set time
	jQuery('#pop_timer').click(function(){
		jQuery('#timer_div').fadeIn();
	}); //timer_div
	
	jQuery('#pop_on_exit').click(function(){
		jQuery('#timer_div').fadeOut();
	}); //timer_div
	
	//save option button of the newly created popup
	jQuery('#save_manage_popup_option').click(function(){
		//get the options
		var selected_popup = jQuery('.popup_manage_listing:checked').attr("pop_id");
		var appear_position = jQuery('input[name="display_pos"]:checked').attr("id");
		var appear_behavior = jQuery('input[name="how_appear"]:checked').attr("id");
		var bg_cover = jQuery('input[name="pop_cover_bg"]:checked').attr("id");
		var bg_color = jQuery('input[name="pop_bg_color"]:checked').attr("id");
		var appear_where = jQuery('input[name="display_where"]:checked').attr("id");
		var delay = jQuery.trim(jQuery('#pop_timer_time').val());
		var frequency = jQuery('input[name="pop_display_freq"]:checked').attr("id");
		var active = jQuery('input[name="pop_active"]:checked').attr("id");
		
		//prevent submitting if user select timer but haven't set the delay
		if ((appear_behavior == 'pop_timer') &&(delay == ''))
		{
			jQuery('#pop_timer_time').css("border", "1px solid red");
			return false;
		}
		
		//set a default value for delay
		if (delay == '')
		{
			delay = 0;
		}
		
		var data = {
				action: 'popup_save_option',
				selected_popup: selected_popup, 
				appear_position: appear_position,
				appear_behavior: appear_behavior,
				bg_color: bg_color,
				bg_cover: bg_cover,
				appear_where: appear_where,
				delay: delay,
				frequency: frequency,
				active: active
		};
		//send the ajax request
		jQuery.post(ajaxurl, data, function(response){
			alert(response);
			
		});
	});
	
	
	//save available option pop_save_btn
	jQuery('.pop_save_btn').click(function(){
		//get the options
		var op_id = jQuery(this).parent().parent().children().children("a").attr("op_id");
		var active = jQuery(this).parent().parent().children().children('select[name=listing_status]').find(":selected").attr("value");
		var appear_position = jQuery(this).parent().parent().children().children('select[name=listing_appear_position]').find(":selected").attr("value");
		var appear_behavior = jQuery(this).parent().parent().children().children('select[name=listing_appear_behavior]').find(":selected").attr("value");
		var background_color = jQuery(this).parent().parent().children().children('select[name=listing_background_color]').find(":selected").attr("value");
		var display_area = jQuery(this).parent().parent().children().children('select[name=listing_display_area]').find(":selected").attr("value");
		var background_cover = jQuery(this).parent().parent().children().children('select[name=listing_background_cover]').find(":selected").attr("value");
		var frequency = jQuery(this).parent().parent().children().children('select[name=listing_frequency]').find(":selected").attr("value");
		var delay = jQuery(this).parent().parent().children().children('.pop_listing_delay').attr("value");
		

		
		//prevent submitting if user select timer but haven't set the delay
		if ((appear_behavior == 'pop_timer') &&(delay == '0'))
		{
			jQuery(this).parent().parent().children().children('.pop_listing_delay').css("border", "1px solid red");
			return false;
		}
		
		//set a default value for delay
		if (delay == '')
		{
			delay = 0;
		}
		
		var data = {
				action: 'popup_save_listing_option',
				op_id: op_id, 
				appear_position: appear_position,
				appear_behavior: appear_behavior,
				background_color: background_color,
				background_cover: background_cover,
				display_area: display_area,
				delay: delay,
				frequency: frequency,
				active: active
		};
		//send the ajax request
		jQuery.post(ajaxurl, data, function(response){
			alert(response);
			
		});
		return false;
	});
		
	
	/* DELETE FUNCTIONS */
	//delete popup
	jQuery('.pop_manage_delete_pop').click(function(){
		var pop_id = jQuery(this).parent().children("input").attr("pop_id");
		
		//send the delete request
		var data = {
				action: 'pop_delete_pop',
				popup_id: pop_id
		}
		jQuery.post(ajaxurl, data, function(){			
			
			
		});
		jQuery(this).parent().fadeOut();
		return false;
	});
	
	//delete options
	jQuery('.pop_delete_btn').click(function(){
		var op_id = jQuery(this).parent().parent().children().children("a").attr("op_id");
		//send the delete request
		var data = {
				action: 'pop_delete_pop_option',
				op_id: op_id
		}
		jQuery.post(ajaxurl, data, function(){
		});
		jQuery(this).parent().parent().fadeOut();
		return false;
	});
	
	//display availabe popups
	jQuery('#pop_listing_options select').each(function(){
		var selected_value = jQuery(this).siblings('.pop_selected_option').text();
		jQuery(this).children('option').each(function(){
			if (jQuery(this).attr("value") == selected_value)
			{
				jQuery(this).attr("selected", "selected");
			}
			
		});
		
	})

	//get the shortcode
	jQuery('.pop_getcode_btn').click(function(){
		var popup_id = jQuery(this).parent().parent().children().children('[pop_id]').attr("pop_id");
		var appear_position = jQuery(this).parent().parent().children().children('select[name=listing_appear_position]').find(":selected").attr("value");
		var appear_behavior = jQuery(this).parent().parent().children().children('select[name=listing_appear_behavior]').find(":selected").attr("value");
		var background_color = jQuery(this).parent().parent().children().children('select[name=listing_background_color]').find(":selected").attr("value");
		var display_area = jQuery(this).parent().parent().children().children('select[name=listing_display_area]').find(":selected").attr("value");
		var background_cover = jQuery(this).parent().parent().children().children('select[name=listing_background_cover]').find(":selected").attr("value");
		var frequency = jQuery(this).parent().parent().children().children('select[name=listing_frequency]').find(":selected").attr("value");
		var delay = jQuery(this).parent().parent().children().children('input').attr("value");
	
		var text = '[sq_pop_shortcode popup_id="'+popup_id+'" appear_position="'+appear_position+'" appear_behavior="'+appear_behavior+'" appear_behavior="'+appear_behavior+'" background_color="'+background_color+'" display_area="'+display_area+'" background_cover="'+background_cover+'" frequency="'+frequency+'" delay="'+delay+'" ]';

		jQuery('#pop_shortcode').text(text);
	});
	
	
});