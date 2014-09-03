function vgt_wpl_enable_tinymce()
{
    tinymce.init({
    	selector: ".editable, img",
    	inline: true,
    	menubar: false,
        plugins: [
					"advlist autolink lists link image charmap print preview hr anchor",
					"searchreplace wordcount visualblocks visualchars code fullscreen",
					"insertdatetime media nonbreaking save table directionality", //contextmenu disabled
					"emoticons template paste textcolor colorpicker textpattern"
              ],
    	toolbar1 : "undo redo | styleselect | bold underline italic | alignleft aligncenter alignright alignjustify | outdent indent",
    	toolbar2 : " bullist numlist  | link image | forecolor, backcolor | fontselect fontsizeselect",
    	extended_valid_elements : "iframe[src|frameborder|style|scrolling|class|width|height|name|align]",
    	theme_advanced_font_sizes: "0.1em,0.2em,0.3em,0.4em,0.5em,0.6em,0.6em,0.7em,0.8em,0.8em,0.9em,1em,1.1em,1.2em,1.3em,1.4em,1.5em,1.6em,1.7em,1.8em,1.9em,2em,2.1em,2.2em,2.3em,2.4em,2.5em,2.6em,2.7em,2.8em,2.9em,3em,3.2em,3.4em,3.6em,3.8em,4em,4.2em,4.4em,4.6em",
    	fontsize_formats: "0.1em 0.2em 0.3em 0.4em 0.5em 0.6em 0.6em 0.7em 0.8em 0.9em 1em 1.1em 1.2em 1.3em 1.4em 1.5em 1.6em 1.7em 1.8em 1.9em 2em 2.1em 2.2em 2.3em 2.4em 2.5em 2.6em 2.7em 2.8em 2.9em 3em 3.2em 3.4em 3.6em 3.8em 4em 4.2em 4.4em 4.6em",
    });
}

function vgt_remove_button_link_editor()
{
	jQuery("#button_editor").siblings(".mce-tinymce").remove();
	jQuery("#button_editor").remove();
}

function vgt_wpl_enable_tinymce_button()
{
	 
	
	//input[type=submit], input[type=button] , input[type=image],
    tinymce.init({
    	selector: "#button_editor",
    	inline: false,
    	menubar: false,
        plugins: [
					"advlist autolink lists link image charmap print preview hr anchor",
					"searchreplace wordcount visualblocks visualchars code fullscreen",
					"insertdatetime media nonbreaking save table directionality",
					"emoticons template paste textcolor colorpicker textpattern" //contextmenu disabled
              ],
    	toolbar1 : "bold underline italic | link forecolor backcolor",
    	toolbar2 : "fontselect fontsizeselect",
    	extended_valid_elements : "iframe[src|frameborder|style|scrolling|class|width|height|name|align]",
    	theme_advanced_font_sizes: "0.1em,0.2em,0.3em,0.4em,0.5em,0.6em,0.6em,0.7em,0.8em,0.8em,0.9em,1em,1.1em,1.2em,1.3em,1.4em,1.5em,1.6em,1.7em,1.8em,1.9em,2em,2.1em,2.2em,2.3em,2.4em,2.5em,2.6em,2.7em,2.8em,2.9em,3em,3.2em,3.4em,3.6em,3.8em,4em,4.2em,4.4em,4.6em",
    	fontsize_formats: "0.1em 0.2em 0.3em 0.4em 0.5em 0.6em 0.6em 0.7em 0.8em 0.9em 1em 1.1em 1.2em 1.3em 1.4em 1.5em 1.6em 1.7em 1.8em 1.9em 2em 2.1em 2.2em 2.3em 2.4em 2.5em 2.6em 2.7em 2.8em 2.9em 3em 3.2em 3.4em 3.6em 3.8em 4em 4.2em 4.4em 4.6em",
    	setup: function(e)
    	{
    		e.on("keyup", function(e){

    			
    			var target = jQuery("#" + localStorage.getItem("current_button_link_id"));
    			var content = (this.getContent());
    			var content_text = this.getContent({format: 'text'});
    			
    			content = jQuery(content);
    			
    			var content_text = content_text.replace(/<[^>]*>/g, "");
    			
    			if (target.is("input"))
    			{
    				//set content and style for the button
        			target.css("font-size", content.children("span").css("font-size"));
        			target.css("color", content.children("span").css("color"));
        			target.css("font-style", content.children("span").css("font-style"));
        			target.css("font-weight", content.children("span").css("font-weight"));
        			target.css("text-decoration", content.children("span").css("text-decoration"));
        			
        			target.val(content_text);
        				
    			} else if (target.is("a"))
    			{
    				//set content and style for the button
        			target.css("font-size", content.children("span").css("font-size"));
        			target.css("color", content.children("span").css("color"));
        			target.css("font-style", content.children("span").css("font-style"));
        			target.css("font-weight", content.children("span").css("font-weight"));
        			target.css("text-decoration", content.children("span").css("text-decoration"));
        			
        			target.attr("href", content.children("a").attr("href"));
        			target.attr("target", content.children("a").attr("target"));
        			target.text(content.children("a").text());
 
    			}

    			
    			
    		});
    		
    		e.on("blur", function(){
    			vgt_remove_button_link_editor();
    		});
    	}
    });	
}

		