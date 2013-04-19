jQuery(document).ready(function(){
	//init lightcase
	jQuery('a[rel^=lightcase]').lightcase('init');
	
	//SELECT THEME OF THE WIDGET
	//show the gallery
	jQuery("#widget_selectb").click(function(){
		jQuery("#widget_themes").fadeToggle();
	});
	
	//show the edit button
	jQuery('#widget_themes input[type="radio"]').click(function(){
		jQuery('#widget_edit_this_theme').fadeIn();
		jQuery('#widget_theme_url').text(jQuery(this).attr("url"));
		jQuery('#widget_current_theme').text(jQuery(this).attr("id"));//record the current theme
	});
	
	//react on edit this click, load the theme actually
	jQuery('#widget_edit_this_theme').click(function(){
		jQuery(this).fadeOut();//hide the edit this button
		jQuery("#widget_themes").fadeOut();
		//load the theme
		var data = {
				action: 'widget_theme_loader',
				url: BASE64.encode(jQuery('#widget_theme_url').text())
		};
		
		jQuery.post(ajaxurl, data, function(response){
			//insert the code
			jQuery('#site_area').html(BASE64.decode(response));
			//load the css style
				//remove the current stylesheet of the theme (if any)
				jQuery("head").children(".widget_theme_css").remove();
				//insert css of the current theme
				jQuery('<link rel="stylesheet" class="widget_theme_css" type="text/css" href="'+jQuery('#widget_theme_url').text()+'/assets/widget.css'+'" />').appendTo("head");
			
			jQuery("#site_area *").not("a,li").contents().filter(function(){	return (this.nodeType == 3); }).wrap("<span class='editable'></span>");
			
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
			
			//make the widget resizable
			jQuery('#sq_widget_optin_container').resizable();
			
		});
		
	});
	
	//END SELECTING THEME OF THE WIDGET	
	
	//BASIC BEHAVIOR OF BOTTOM PANEL BUTTONS
		jQuery('#widget_editb').click(function(){
			jQuery('#widget_code_panel').fadeOut();
			jQuery('#widget_edit_panel').fadeToggle();
		});
	
	//END BASIC BEHAVIOR OF BOTTOM PANEL BUTTONS
		//CHANGE COLOR OF THE THEME
		jQuery('#widget_color_changer').ColorPicker({onChange: function(hsb, hex, rgb){ jQuery('#site_area > div').css("background-color", "#"+hex) }, flat:true});
	//END SWITCHING COLOR OF THE THEME

	

	//END SWITCHING SIZE OF THE THEME
	
	//INSERT THE CODE EMAIL/MEDIA
		//show the buttons of coding panel
		jQuery('#widget_codeb').click(function(){
			jQuery('#widget_edit_panel').fadeOut();
			jQuery('#widget_code_panel').fadeToggle();
		});
		
		//show the email box 
		jQuery('#widget_code_email').click(function(){
			jQuery("#code_boxes textarea").not("#widget_email_code").fadeOut();
			jQuery('#widget_email_code').fadeToggle();
		});
		
		//process the email code

		
		//show the media box
		jQuery('#widget_code_media').click(function(){
			jQuery("#code_boxes textarea").not("#widget_media_code").fadeOut();
			jQuery("#widget_media_code").fadeToggle();
		});

		//inserting media
		jQuery("#widget_media_code").blur(function(){
			if((jQuery(this).val().indexOf(".jpg") != -1) || (jQuery(this).val().indexOf(".png") != -1) || (jQuery(this).val().indexOf(".gif") != -1))//in case the user has pased the image code in
			{
				jQuery('#sq_widget_media').html('<img width="95%" src="'+jQuery(this).val()+'" />');
			} else if((jQuery(this).val().indexOf("youtube.com") != -1) || (jQuery(this).val().indexOf("vimeo.com") != -1) || (jQuery(this).val().indexOf("blip.tv") != -1) || (jQuery(this).val().indexOf("dailymotion.com") != -1) || (jQuery(this).val().indexOf("metacafe.com") != -1))//in case the user has pased the video url in
			{
				var code = '';
				var width = jQuery('#sq_widget_media').width();
				code = '<iframe width="95%" src="'+jQuery(this).val()+'" frameborder="0" allowfullscreen></iframe>';
				
				jQuery('#sq_widget_media').html(code);
			} else if (jQuery.trim(jQuery(this).val()) =="")
			{
				
			}
			
			jQuery(this).fadeOut();	
		});
	//END INSERTING THE CODE EMAIL/VIDEO
	
	
	//CHANGE THE BUTTON
		jQuery('#widget_edit_change_btn').click(function(){
			//show the buttons collection
			var data = {
					action: 'widget_show_buttons',
					size: jQuery('#widget_current_size').text()
					
			};
			
			jQuery.post(ajaxurl, data, function(response){
				var buttons = jQuery.parseJSON(response);
				var button_string = "";
				
				for (var i=0; i<buttons.length; i++)
				{
					button_string += '<div class="widget_cta_btn" style="float:left;"><img src="'+jQuery('#widget_root_url').text()+'/themes/widgets/buttons/'+jQuery('#widget_current_size').text()+'/'+buttons[i]+'" /><input type="radio" name="widget_cta" id="'+buttons[i]+'" /></div>';
				}
				
				jQuery('#widget_cta_btns').html(button_string);
				jQuery('#widget_cta_btns').fadeToggle();
				jQuery(this).fadeOut();
			});
			
		});
		
		//show the edit button
		jQuery('#widget_cta_btns input[type="radio"]').live('click',function(){
			jQuery('#widget_choosethisbtnb').fadeIn();
		});
		
		//change the button
		jQuery('#widget_choosethisbtnb').click(function(){
			jQuery('#widget_cta_btns').fadeOut();
			jQuery('#this').fadeOut();
			
			//get the button
			var selected_button = jQuery('#widget_root_url').text()+'/themes/widgets/buttons/'+jQuery('#widget_current_size').text()+'/'+jQuery('#widget_cta_btns input[type="radio"]:checked').attr('id');
			jQuery("#site_area input[type='submit']").css("background", "url("+selected_button+") no-repeat");
		});
		
	//END CHANGING THE BUTTON
	
	
	//SWITCH COLOR BUTTON
	jQuery('#widget_edit_switch_color').live('click', function(){
		jQuery('#widget_switch_color').fadeToggle();
	});
	
	//END SWITCH COLOR BUTTON
	
	//GET WIDGET CODE
		jQuery('#widget_getcodeb').click(function(){
			var widget_code = jQuery('#site_area').html();
			var widget_style = '<link rel="stylesheet" class="widget_theme_css" type="text/css" href="'+jQuery('#widget_theme_url').text()+'/assets/widget.css'+'" />';
			var input_code = "";
			jQuery('#sq_widget_optin_container input[type="text"], #sq_widget_optin_container input[type="email"]').each(function(){
				input_code +='jQuery("#'+jQuery(this).attr("id")+'").click(function(){if (jQuery(this).val() == "'+jQuery(this).val()+'") {jQuery(this).val("");}  });'
				input_code +='jQuery("#'+jQuery(this).attr("id")+'").blur(function(){if (jQuery(this).val() == "") {jQuery(this).val("'+jQuery(this).val()+'");} });'
				
			});
			
			widget_code += '<script>jQuery(document).ready(function(){ jQuery(\''+widget_style+'\').appendTo("head"); });'+input_code+'</script>';
			
			jQuery('#widget_show_code textarea').text(widget_code);
		});

	//END GET WIDGET CODE
	
});