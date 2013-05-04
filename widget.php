<?php
//show current themes of the widgets        
	function show_widget_themes() 
	{
		$widget_url = plugins_url("themes/widgets/", __FILE__);
		$widget_path = plugin_dir_path(__FILE__).'themes/widgets/';
		
		$thumbnail = scandir($widget_path.'thumbnail'); 
		
		for ($i=0; $i<count($thumbnail); $i++)
		{
			if (stripos($thumbnail[$i], '.jpg') !== FALSE)
			{
				$id = str_replace(".jpg", '', $thumbnail[$i]);
				echo '<div class="thumb">
				<a href="'.$widget_url.'thumbnail/'.$thumbnail[$i].'" rel="lightcase"><img src="'.$widget_url.'thumbnail/'.$thumbnail[$i].'" /></a>
				<input type="radio" name="widget_theme" id="'.$id.'" url="'.$widget_url."themes/$id".'" />
				</div>';
			}
			
		}
		
		echo '<div style="clear:both;"></div>';
		
		
	}
        
 //show the widget page
 	//create widget page
	function sub_squeezers_widget_cb()
	{?>
	<div id="squeezer_widget">
		
		<div id="left_squeezer_widget" style="width: 20%; float: left;">
			<label for="sq_submit_url">Submit URL</label>
			<input type="text" id="sq_submit_url" class="widefat" />
			
			<div id="widget_switch_size">
				<div id="widget_root_url" style="display: none;"><?php echo plugins_url("", __FILE__); ?></div>
			</div>
			
			<div id="widget_switch_color" style="display: none;">
				<div id="widget_color_changer"></div>
			</div>
			<div id="custom_code_position" style="display: none;">
				<input type="radio" name="custom_code" value="below" /> Below
				<input type="radio" name="custom_code" value="above" /> Above	
			</div>
			<?php sq_common_editbox(); //start the editbox?>
		
		</div>

		<div id="site_area">
		</div>
		<div style="clear:both;"></div>
		
		<!-- Display the themes -->
		<div id="widget_themes" style="display: none;">
			<?php show_widget_themes();?>
			
		</div>
		<div id="widget_cta_btns" style="display: none;"></div>			
	
	
	</div>
	<?php include_once 'code/widgetcode.txt';}
	
	/*END FUNCTIONS THAT LOAD THE UI****************************************************** */       
        
	
	//load the theme and return the code
	add_action('wp_ajax_widget_theme_loader', 'widget_theme_loader_cb');
	
	function widget_theme_loader_cb() {
		$content = file_get_contents(base64_decode($_POST['url']).'/code.txt');
		//replace the relative url to absolute
		//get the absolute URL
		$abs_url = base64_decode($_POST['url']).'/assets/';
		$content = str_replace("assets/", $abs_url, $content);
		echo base64_encode($content);
		die();
	}
	
	//get the available colors of the current themes
	add_action('wp_ajax_widget_edit_switch_color', 'widget_switch_color_cb');
	
/* 	function widget_switch_color_cb()
	{
		$color_path = plugin_dir_path(__FILE__).'/themes/widgets/themes/'.$_POST['theme'].'/colors';
		$colors = scandir($color_path);
		$valid_color = array();
		
		for ($i=0; $i<count($colors); $i++)
		{
			if(stripos($colors[$i], '.jpg') !== FALSE)
			{
				$key = str_replace(".jpg", "", $colors[$i]);
				$valid_color[] = $key; 
			}
		}
		
		echo json_encode($valid_color);
		die();
	} */
	
	/* PARSE THE EMAIL AND SEND BACK TO THE CLIENT */
	add_action('wp_ajax_widget_parse_autoresponder', 'parse_autoresponder_callback');

	/* END PARSING THE EMAIL AND SEND BACK TO THE CLIENT */
	
	/* SHOW THE BUTTONS TO USERS */
		add_action('wp_ajax_widget_show_buttons', 'widget_show_button_cb');
		
		function widget_show_button_cb() 
		{
			$buttons = scandir(plugin_dir_path(__FILE__).'themes/widgets/buttons/'.$_POST['size']);
			
			$valid_buttons = array();
			
			for ($i=0; $i<count($buttons); $i++)
			{
				if (stripos($buttons[$i], '.png') !== FALSE)
				{
					$valid_buttons[] = $buttons[$i];
				}
			}
			
			echo json_encode($valid_buttons);
			
			die();
		}
	/* END SHOWING THE BUTTONS TO USERS */