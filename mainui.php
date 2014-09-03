<?php
	//include_once 'activate.php';
	//some basic functions to deal with optional variables
	include_once 'code/common_functions.php';
	include_once 'code/const.php';
	//function to add post meta
	function sq_bgt_add_option($post_id, $option_name, $variable)
	{
		if (isset($variable))
		{
			add_post_meta($post_id, $option_name, $variable);			
		}
	}
	
	//function to update post meta
	function sq_bgt_update_option($post_id, $option_name, $variable)
	{
		if (isset($variable))
		{
			update_post_meta($post_id, $option_name, $variable);
		}
	}
	
	//function to query specific value from db (squeeze page only)
	
	function sq_bgt_edit_page_query($query, $wpdb)
	{
		try {
			$temp = $wpdb->get_results($query, "ARRAY_A");
			if (isset($temp[0]['meta_value']))
			{
				return $temp[0]['meta_value'];
			}
			
			return FALSE;
			
		} catch (Exception $e)
		{
			return FALSE;
		}
		
	}
	/*FUNCTIONS THAT LOAD THE UI****************************************************** */
	//load the main page, activ4tion stuffs
	function main_squeezers_cb()
	{
		$display = 'block';
		if (get_option('sq_activation_status') == 'activated')
		{
			$display = 'none';
		}
		echo '
			<div id="main_page">
				<div id="thankyou">
				'.show_upgrade_text().'
					<h2>Thanks for using WP Lead Plus</h2>
					
					<p>We hope you will enjoy the plugin. If you have any problem, please contact us at:</p>
					<p>Gmail: t2dx.inc@gmail.com</p>
					<p>We will get back to you soon!</p>
			
					<h2>How to use the plugin?</h2>
					<p>You can find detailed instructions for using WP Lead Plus following the link below:</p>
					<p><a href="https://www.youtube.com/playlist?list=PL6rw2AEN42EqC40Qp3H-GZb6ngFlx5IuQ" target="_blank">Click here to view video tutorials</a></p>
					<p>In the videos, you will see some difference in the interface. It was because I use the PRO version for demonstration. In addition, you don\'t need to activate this plugin as I showed in the first video of the playlist. Activation is for PRO version only. </p>
					<p>You can discover the benefits of the PRO version here: <a href="http://wpleadplus.com/?src=wporgthank" target="_blank">http://wpleadplus.com/</a></p>
				</div>
			</div>';
		

	}
    
    
    	
	//build the setting pages
	function sub_squeezers_settings_cb()
	{
		$tracking_code = '';
		if (get_option('sq_user_tracking_code') !== FALSE)
		{
			$tracking_code = get_option('sq_user_tracking_code');
		}

		
		$link = show_upgrade_text();
		echo '
		<div>
			<h2>Settings</h2>
			'.show_upgrade_text().'
			<p>You can set some settings for your squeeze pages here</p>
			<h3>Tracking code</h3>
			<p>If you want to install tracking code to your squeeze page, please paste the code in the box below.</p>
			<textarea cols="50" rows="10" id="tracking_code">'.$tracking_code.'</textarea> <br />
			<input type="submit" value="Save" class="button-primary" id="save_tracking_codeb"/>
		</div>';
		


	}
	
    
	//build the main page
	function sub_squeezers_new_cb()
		{?>
			
			<div id="main_container">
				<div id="left_panel">
				<div id="site_info">
					<div id="sq_bgt_https_enabled" style="display: none;"><?php echo get_option("sq_bgt_enable_https");?></div>
					<label for="page_title">Page Title <span style="font-size: 0.8em">*Required</span></label>
					<input type="text" name="title" id="page_title" class="widefat" />
					 
					<div id="sq_bgt_customize_left">
					<!-- 	<label for="page_url" class="other_label">Custom Background</label>
						<div></div>
						<input type="radio" id="custom_bg_image" title="URL of image, in JPG or PNG format" name="custom_bg_select" value="image" /><span title="URL of image, in JPG or PNG format" style="color: blue;">Image</span> 
						<!-- <input type="radio" title="Your self-hosted video's URL" name="custom_bg_select" value="self_hosted_video" /><span title="Your self-hosted video's URL" style="color: #00dd00;">Video</span> --> 
					<!--	<input type="radio" id="custom_bg_youtube" title="YouTube video EMBED URL" name="custom_bg_select" value="youtube_video" /><span title="YouTube video EMBED URL" style="color: #dd0000;">YT</span>
						
						<input type="text" name="custom_bg" id="custom_bg" class="widefat" />
						
						<label for="page_url" class="other_label">Custom CTA<span style="font-size: 0.8em"> .jpg/.png file</span></label>
						<input type="text" name="custom_cta" id="custom_cta" class="widefat" />
	-->
						<label for="sq_submit_url" class="other_label">Submit URL</label>
						<input type="text" id="sq_submit_url" class="widefat" />  <input type="checkbox" id="sq_open_new_window" /> New window?
					</div>
	
					<div id="custom_code_position" style="display: none;">
						<input type="radio" name="code_type" value="html" /> <span style="color: blue;">HTML</span>
						<input type="radio" name="code_type" value="javascript" /> <span style="color: red;">Javascript</span> <br />
						<div id="custom_code_html">
							<p style="font-style: italic;">HTML code options</p>
							<input type="radio" name="custom_code" value="below"  checked="checked" /> Below
							<input type="radio" name="custom_code" value="above" /> Above	<br />
							<input type="checkbox" name="pure" id="pure_code" /> Pure?
						</div>
						
						<div id="custom_code_js">
							<p style="font-style: italic;">Where to place your javascript code?</p>
							<select id="custom_code_js_option">
								<option value="after_head">After &lthead&gt</option>
								<option value="before_head">Before &lt/head&gt</option>
								<option value="after_body">After &ltbody&gt</option>
								<option value="before_body">Before &lt/body&gt</option>
							</select>
						</div>
						
					</div>
					
					
					<div id="sq_bgt_switch_color">
						<span style="display: none;" id="sq_bgt_link_to_colors"><?php echo sq_bgt_use_https(plugins_url('themes/common/colors/',__FILE__)); ?></span>
						<div id="color_switch_number" style="display: none;" >
						</div>
						<div id="sq_bgt_hex_color_changer" style="display: none;">
						</div>
						<a href="#" id="sq_bgt_hide_picker" style="display: none;">Hide picker</a>
					</div>
					
					<div id="likenbox" style="display: none;">
						<label for="likenbox_chk">Enable FB Script?</label>
						<input type="checkbox" id="likenbox_chk" />
					</div>
					
					
					<div id="frontier_images" style="display: none; padding-top: 5px;">
						<div class="widefat">L: <input type="text" id="custom_img_left" /></div>
						<div class="widefat">R: <input type="text" id="custom_img_right" /></div>
						<div class="widefat">T: <input type="text" id="custom_img_top" /></div>
						<div class="widefat">B: <input type="text" id="custom_img_bottom" /></div>
					</div>

				</div>
				
	
				</div>
				
				<div id="site_area">
				</div>
				<div style="clear:both;"></div>
				
			</div>

			<div id="gallery">
				<?php 
					$thumbnail_dir = plugin_dir_path(__FILE__);
					
					if (is_dir($thumbnail_dir.'themes/video/thumbnail') && is_dir($thumbnail_dir.'themes/traditional/thumbnail'))
					{
						$video_thumb = scandir($thumbnail_dir.'themes/video/thumbnail');
						$novid_thumb = scandir($thumbnail_dir.'themes/traditional/thumbnail');
					} else
					{
						sq_bgt_theme_download_error();
					}
					
					echo '<div id="video_themes">';
					for ($i=0; $i<count($video_thumb); $i++)
					{
						if ((stripos($video_thumb[$i], 'jpg') !== false))
						{
							if (in_array(intval($video_thumb[$i]), array(1,10,9)))
							{
								echo '<div class="bgt_thumb">
								<a href="'.plugins_url('themes/video/thumbnail/', __FILE__).$video_thumb[$i].'" rel="lightcase"><img src="'.plugins_url('themes/video/thumbnail/', __FILE__).$video_thumb[$i].'" /></a>
								<input theme_type="video" type="radio" name="theme" theme_id="'.$video_thumb[$i].'" />
								</div>';
							} else 
							{
								echo '<div class="bgt_thumb">
								<a href="'.plugins_url('themes/video/thumbnail/', __FILE__).$video_thumb[$i].'" rel="lightcase"><img src="'.plugins_url('themes/video/thumbnail/', __FILE__).$video_thumb[$i].'" /></a>
								<input theme_type="video" type="radio" disabled="disabled" name="theme" theme_id="'.$video_thumb[$i].'" />
								</div>';								
							}

						}
						
					}
					
					echo '</div>';
					
					echo '<div id="nonvid_themes">';
					for ($i=0; $i<count($novid_thumb); $i++)
					{
					if ((stripos($novid_thumb[$i], 'jpg') !== false))
					{
						if (intval($novid_thumb[$i] == 1))
						{
							echo '<div class="bgt_thumb">
							<a href="'.plugins_url('themes/traditional/thumbnail/', __FILE__).$novid_thumb[$i].'" rel="lightcase"><img src="'.plugins_url('themes/traditional/thumbnail/', __FILE__).$novid_thumb[$i].'" /></a>
							<input theme_type="traditional" type="radio" name="theme" theme_id="'.$novid_thumb[$i].'" />
							</div>';
						} else 
						{
							echo '<div class="bgt_thumb">
							<a href="'.plugins_url('themes/traditional/thumbnail/', __FILE__).$novid_thumb[$i].'" rel="lightcase"><img src="'.plugins_url('themes/traditional/thumbnail/', __FILE__).$novid_thumb[$i].'" /></a>
							<input theme_type="traditional" disabled = "disabled" type="radio" name="theme" theme_id="'.$novid_thumb[$i].'" />
							</div>';							
						}
					
					}

					}
					
					echo '</div>';
				?>
			</div>
			
			<!-- Get the footer panel -->		
			<div id="insert_code">
				<?php include_once 'code/editcode.txt'; include_once 'code/common.txt'; ?>
			</div>
			<?php echo show_upgrade_text(); ?>
		<?php }
		
        
        
	/* LOAD THE THEME AND SHOW TO USER */

	add_action('wp_ajax_theme_loader', 'theme_loader_callback');
	/*this function will load the index.html file of the theme and pass to user.
	 * first, get the path to the theme
	 * second, change relative path to absolute path
	 * third, load the css
	 */ 
	function theme_loader_callback()
	{
		//get access to wpdb
		global $wpdb;
		$theme_table = BGT_THEMES_TABLE; //$wpdb->get_blog_prefix().'sq_themes';
		
		//get the theme passed by the client, actually it's the name of thumbnail file
		$theme_id = explode(".",trim($_POST['theme_name']));
		$theme_id = $theme_id[0];
		
		$theme_type = trim($_POST['theme_type']);
		
		$theme_path = plugin_dir_path(__FILE__).'themes/'.$theme_type.'/'.$theme_id.'/themes/1';
		$theme_url = plugins_url('themes/'.$theme_type.'/'.$theme_id.'/themes/1', __FILE__);
		
		//END SETTING SESSION VARIABLES
		
		//get the general theme url
		$general_theme_url = plugins_url("themes/".$theme_type."/".$theme_id, __FILE__);;
		
		$theme_parent = plugins_url("themes/".$theme_type, __FILE__);//get this to join to the css later
		
		//get the content from the index.html file of the theme, need to strip the head and the close body part
		if (function_exists("curl_init")) //if curl available, use it to avoid complication
		{
			$index_file = sq_bgt_curl_theme_loader($theme_path.'/index.html');
			
			//if the content doesn't contain the proper body (<body>)
			if (stripos($index_file, 'sq_body_container') == FALSE)
			{
				$index_file = file_get_contents($theme_url.'/index.html');
			}
			
			//if the content doesn't contain the proper body (<body>)
			if (stripos($index_file, 'sq_body_container') == FALSE)
			{
				$index_file = file_get_contents($theme_path.'/index.html');
			}
		} else
		{
			$index_file = file_get_contents($theme_path.'/index.html');
		}
		//need to improve with regex, quite luxurious right now
		$theme_body = explode("<body>", $index_file);
		$theme_body = $theme_body[1];
		$theme_body = explode("</body>", $theme_body);
		$theme_body = $theme_body[0];
		
		//change the relative path to absolute
		$theme_body = str_replace("assets/", $theme_url."/assets/", $theme_body);
		
		//pass the style sheet
		$return_content['theme_css'] = (base64_encode(sq_bgt_use_https($theme_url.'/assets/style.css')));
		
		//pass content body
		$return_content['theme_body'] = base64_encode(sq_bgt_use_https($theme_body));
	
		//pass the type, it is neccessary to know this then can get the css later
		$return_content['theme_type'] = $theme_type;

		//pass the parent folder of the theme too
		$return_content['theme_type_url'] = $theme_parent;
		
		//get the theme name, without the .jpg part
		$return_content['current_theme_name'] = $theme_id;
		
		//get the general theme url, this is the url of the theme, not specific parent of themes and colors
		$return_content['general_theme_url']  = sq_bgt_use_https($general_theme_url);
		
		//pass to the site in json format, need to add extra characters because maybe users' page generate extra character too
		echo ("123dddsacxz".json_encode($return_content)."123dddsacxz");
		die();
	}
	/* END LOADING THE THEME AND SHOW TO USER */
	
	/* GET THE AVAILABLE COLORS OF CURRENT THEME */
	add_action('wp_ajax_edit_switch_color', 'switch_color_cb');
	
	function switch_color_cb()
	{
		$color_path = plugin_dir_path(__FILE__).'themes/'.$_POST['type'].'/'.$_POST['theme'].'/colors';
		
		if (is_dir($color_path))
		{
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
			echo "123dddsacxz".json_encode($valid_color)."123dddsacxz";
		} else 
		{
			echo 'no color';
		}
		
		die();
	}

	
	/* END GET THE AVAILABLE COLORS OF CURRENT THEME */

	/* LOAD THE BUTTONS AND SHOW TO USER */

	add_action('wp_ajax_show_buttons', 'show_buttons_callback');
		

	function show_buttons_callback()
	{
		//get the path to current theme button
		$theme_type = $_POST['current_theme_type'];
		if (substr($theme_type, 0, 3) == 'tra')
		{
			$theme_type = 'traditional'; //prevent text from being translated
		}
		
		$current_theme_button = plugin_dir_path(__FILE__).'themes/'.$theme_type.'/'.$_POST['current_theme_name'].'/themes/1/assets/imgs/submit.png';
		
		//get the button width and height
		$image_info = getimagesize($current_theme_button);
		//query the buttons
		$min_width = $image_info[0] - 3;
		$max_width = $image_info[0] + 3;
		
		$min_height = $image_info[1] - 3;
		$max_height = $image_info[1] + 3;
		global $wpdb;
			
		//get the button table
		$button_table = BGT_CTA_BUTTONS_TABLE; // $wpdb->get_blog_prefix()."cta_buttons";
		
		//build the query
		$query = 'SELECT name, width, height FROM '.$button_table.";";
			
		$button_name = array();//create an array to store the buttons' names
		
		//get the button from db
		$button_db = $wpdb->get_results($query, "ARRAY_A");
		for ($i=0; $i<count($button_db); $i++)
		{
			if (($button_db[$i]["width"] > $min_width) && ($button_db[$i]["width"] < $max_width) &&  ($button_db[$i]["height"] < $max_height) && ($button_db[$i]["height"] > $min_height))
			{
				$button_name[] = sq_bgt_use_https(plugins_url("themes/buttons",__FILE__).'/'.$button_db[$i]["name"]);
			}
		}
	echo "123dddsacxz".json_encode($button_name)."123dddsacxz";
	die();
	}
	
	/* END LOADING THE BUTTONS AND SHOW TO USER */
	
	
	/* LOAD THE BACKGROUNDS AND SHOW TO USER */
	
	add_action('wp_ajax_show_backgrounds', 'show_backgrounds_callback');
	
	
	function show_backgrounds_callback()
	{
			$bg_name = array();//create an array to store the bg' names
	
			//get the button from db
			$bg_db = scandir(plugin_dir_path(__FILE__).'themes/bgs/small/');
			
			for ($i=0; $i<count($bg_db); $i++)
			{
				if (stripos($bg_db[$i], ".jpg") !== false)
				{
					$bg_name[] = sq_bgt_use_https(plugins_url("themes/bgs/small",__FILE__).'/'.$bg_db[$i]);	
				}
				
			}
				
			echo "123dddsacxz".json_encode($bg_name)."123dddsacxz";
			die();
		}
	
	/* END LOADING THE BACKGROUND AND SHOW TO USER */	
	
	//PUBLISH THE PAGE
	add_action('wp_ajax_publish_post', 'publish_post_callback');	

	function publish_post_callback()
	{

		global $wpdb;
		//get the posts and postmeta table
		$post_table = $wpdb->get_blog_prefix().'posts';
		$post_meta_table = $wpdb->get_blog_prefix().'postmeta';
		
		//get the data passed by the javascript function
		$post_title = base64_decode($_POST['title']);
		$post_content = base64_decode($_POST['content']);
		
		//remove content editable
		$post_content = str_replace('contenteditable="true"', "", $post_content);
		$post_css = sq_bgt_use_https(base64_decode($_POST['cssfile']));
		$bg_url = base64_decode($_POST['bg_url']);
		$bg_type = base64_decode($_POST['bg_type']);
		$input_string = base64_decode($_POST['input_string']);
		
		$button_js_code = base64_decode($_POST['custom_js_code_button']);
		
		$button_code_to_page = "";
		if (trim($button_js_code) != "")
		{
			
			$new_button_js_code = json_decode($button_js_code, true);
			
			foreach ($new_button_js_code as $key => $value)
			{
				$button_code_to_page .= base64_decode($value) . " "; 		
			}	
		}
		
		var_dump($new_button_js_code);
		
		$current_post_id = ($_POST['current_post_id']);
		
		//prepare the function to hide the input text on click
		$input_array = explode("&&&", $input_string);
		
		$js_input = '';
		//glue the input elements
		for ($i=0; $i<count($input_array); $i++)
		{
			$single_input = explode('***',$input_array[$i]);
			
			$js_input .= "jQuery('#$single_input[0]').click(function(){ if (encodeURIComponent(jQuery(this).val()) == encodeURIComponent('$single_input[1]')) {	jQuery(this).val(''); } });jQuery('#$single_input[0]').blur(function(){ if (jQuery(this).val() == '') { jQuery(this).val('$single_input[1]'); } });";
			
			//if the user set form checking to yes
			if (get_option('sq_bgt_check_form_fields') == "yes")
			{
				$js_input .= "jQuery('form').submit(function(){ if (jQuery('#$single_input[0]').val() == '$single_input[1]' || jQuery.trim(jQuery('#$single_input[0]').val()) == '') { jQuery('#$single_input[0]').css('color', 'red'); alert('please complete the fields marked red'); return false; }});";	
			}
						
		}
		
		//get the path to current theme button
		$current_theme_type = $_POST['current_theme_type'];
		if (substr($current_theme_type, 0, 3) == 'tra')
		{
			$theme_type = 'traditional'; //prevent text from being translated
		}
		
		$js_input = '<script>'.trim($js_input).$button_code_to_page.'</script>';

		$head = "<link rel=\"stylesheet\" href='$post_css'><!--[if lt IE 9]><script src=\"http://html5shiv.googlecode.com/svn/trunk/html5.js\"></script><![endif]-->";

		
		//get the custom js libraries
		$custom_jq = plugins_url("js/jq.js", __FILE__);
		$custom_bs = plugins_url("js/bgbs.js", __FILE__);//backstretch
		
		//if the theme has background, then load the background and insert the backstretch code
		$head .= '<script src="'.$custom_jq.'"></script><script src="'.$custom_bs.'"></script>';
		$post_content .= trim($js_input);

		//insert the custom css style
		if (isset($_POST['custom_css_style']))
		{
			$head .= '<style class="custom_css_style">'.base64_decode($_POST['custom_css_style']).'</style>';
		}

		//prepare the data before inserting
		$data_post = array(
				'post_author' => wp_get_current_user()->ID,
				'post_title' => $post_title,
				'post_content' => $post_content,
				'post_type' => 'page',
				'post_status' => 'publish'
				);

		/* There is a need to store the css url in the db, specificly in the wp_postmeta. this is particularly
		 * important because when editing post, it will save a lot of time from spliting the head to get the css
		* url
		*/
		
		//in case this is the first time the user hit publish button, create a new post and save to db
		if ($current_post_id == "")
		{
			//$wpdb->insert($post_table, $data_post);
			$current_post_id = wp_insert_post($data_post);
			
			//$post_id = $wpdb->insert_id;
			
			$data_post_meta = array(
					'post_id' => $current_post_id,
					'meta_key' => '_wp_page_template',
					'meta_value' => 'sq_ddx_blankpage.php'
			);
			$wpdb->insert($post_meta_table, $data_post_meta);		

			//add the current css into postmeta, will pull it out later when edit post
			add_post_meta($current_post_id, 'pros_theme_css', $post_css);
			add_post_meta($current_post_id, 'pros_body_content', $_POST['content']);

			add_post_meta($current_post_id, 'pros_post_head', $head);
			add_post_meta($current_post_id, 'pros_post_full_content', $post_content);
			add_post_meta($current_post_id, 'pros_current_theme_url', $_POST['current_theme_url']);
			add_post_meta($current_post_id, 'pros_current_sub_theme', $_POST['current_sub_theme']);
			add_post_meta($current_post_id, 'pros_current_theme_name', $_POST['current_theme_name']);

			add_post_meta($current_post_id, 'pros_current_theme_type', $current_theme_type);
			
			add_post_meta($current_post_id, 'pros_custom_js_button', $button_js_code);
			
			//page background
			add_post_meta($current_post_id, 'pros_current_background_url', $bg_url);
			add_post_meta($current_post_id, 'pros_current_background_type', $bg_type);
			
			//add optional values to db if available
			//sq_bgt_add_option($current_post_id, 'pros_face_mail', $_POST['face_mail']);
			sq_bgt_add_option($current_post_id, 'pros_custom_js_code', $_POST['custom_js_code']);
			sq_bgt_add_option($current_post_id, 'pros_custom_js_position', $_POST['custom_js_code_position']);
			sq_bgt_add_option($current_post_id, 'pros_custom_css_style', $_POST['custom_css_style']);
			
		} else if (is_numeric($current_post_id)) //update the current post
		{
			$data_post['ID'] = $current_post_id;//need to set this variable to make sure the post will be updated, not create a new post
			wp_insert_post($data_post);
			
			//insert the  post body into postmeta, remember, this is the base64 encode form
			update_post_meta($current_post_id, 'pros_body_content', $_POST['content']);
			update_post_meta($current_post_id, 'pros_post_head', $head);
			update_post_meta($current_post_id, 'pros_theme_css', $post_css);
			update_post_meta($current_post_id, 'pros_current_theme_url', $_POST['current_theme_url']);
			update_post_meta($current_post_id, 'pros_current_sub_theme', $_POST['current_sub_theme']);
			update_post_meta($current_post_id, 'pros_current_theme_name', $_POST['current_theme_name']);
			
			update_post_meta($current_post_id, 'pros_current_theme_type', $current_theme_type);
			update_post_meta($current_post_id, 'pros_post_full_content', $post_content);
			
			update_post_meta($current_post_id, 'pros_custom_js_button', $button_js_code);
			
			//page background
			update_post_meta($current_post_id, 'pros_current_background_url', $bg_url);
			update_post_meta($current_post_id, 'pros_current_background_type', $bg_type);
			
			
			//page background
			add_post_meta($current_post_id, 'pros_current_background_url', $bg_url);
			add_post_meta($current_post_id, 'pros_current_background_type', $bg_type);
			//update optional values to db if available
			//sq_bgt_update_option($current_post_id, 'pros_face_mail', $_POST['face_mail']);
			sq_bgt_update_option($current_post_id, 'pros_custom_js_code', $_POST['custom_js_code']);
			sq_bgt_update_option($current_post_id, 'pros_custom_js_position', $_POST['custom_js_code_position']);
			sq_bgt_update_option($current_post_id, 'pros_custom_css_style', $_POST['custom_css_style']);			
			
		}
		$return_message = array('message' => 'Done', 'current_post_id' => $current_post_id);
		echo "123dddsacxz".json_encode($return_message)."123dddsacxz";
		die();
	}

	//END PUBLISHING THE PAGE

	//SHOW POSTS TO EDIT
	add_action('wp_ajax_show_posts', 'show_posts_callback');
	
	function show_posts_callback()
	{
		global $wpdb;
		//get the prefix
		$posts_table = $wpdb->get_blog_prefix().'posts';
		$meta_table = $wpdb->get_blog_prefix().'postmeta';
		//build the query 
		$query = "SELECT $posts_table.ID FROM $posts_table, $meta_table WHERE $posts_table.post_status = 'publish' AND $meta_table.post_id = $posts_table.ID AND $meta_table.meta_value = 'sq_ddx_blankpage.php'";
		$post_id = $wpdb->get_results($query, 'ARRAY_A');
		
		$posts_data = array();
		//get the post title, permalink
		for ($i=0; $i<count($post_id); $i++)
		{
			$posts_data[$i]['id'] = $post_id[$i]['ID'];
			$posts_data[$i]['title'] = get_the_title($post_id[$i]['ID']);
			$posts_data[$i]['link'] = get_permalink($post_id[$i]['ID']);
		}
		
		//return the data
		echo "123dddsacxz".json_encode($posts_data)."123dddsacxz";
		die();
		
	}
	
	//END SHOWING POSTS TO EDIT	
	
	//EDIT CREATED POST
	add_action('wp_ajax_edit_created_page', 'edit_created_page_callback');
	
	function edit_created_page_callback()
	{
		global $wpdb;
		//get the post id
		$post_id = $_POST['id'];
		//set the post_id to a session variable, this will make the post be updated when publish button is pressed,
		//not create a new post
	
 		//get wp_postmeta table
		$post_meta = $wpdb->get_blog_prefix().'postmeta';
		
		//Build the queries
		$query_body = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_body_content'";
		$query_css = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_theme_css'";
		//$query_face_mail = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_face_mail'";
		$query_custom_js_code = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_custom_js_code'";
		
		$query_current_theme_url = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_current_theme_url'";
		$query_current_sub_theme = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_current_sub_theme'";
		$query_current_theme_name = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_current_theme_name'";
		$query_current_theme_type = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_current_theme_type'";
		$query_custom_css_style = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_custom_css_style'";
		$query_background_url = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_current_background_url'";
		$query_background_type = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_current_background_type'";
		$query_custom_js_button = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_custom_js_button'";
		
		//pros_custom_css_style
		
		
		//declare the variables
		$body_content = sq_bgt_edit_page_query($query_body, $wpdb);
		$page_css = sq_bgt_edit_page_query($query_css, $wpdb);
		//$face_mail = sq_bgt_edit_page_query($query_face_mail, $wpdb);
		$custom_js_code = sq_bgt_edit_page_query($query_custom_js_code, $wpdb);
		
		$current_theme_url = sq_bgt_edit_page_query($query_current_theme_url, $wpdb);
		$current_sub_theme = sq_bgt_edit_page_query($query_current_sub_theme, $wpdb);
		$current_theme_name = sq_bgt_edit_page_query($query_current_theme_name, $wpdb);
		$current_theme_type = sq_bgt_edit_page_query($query_current_theme_type, $wpdb);
		$custom_css_style = sq_bgt_edit_page_query($query_custom_css_style, $wpdb);
		
		//custom button code
		$custom_button_js_code = sq_bgt_edit_page_query($query_custom_js_button, $wpdb);
		
		//background
		$bg_type = sq_bgt_edit_page_query($query_background_type, $wpdb);
		$bg_url = sq_bgt_edit_page_query($query_background_url, $wpdb);
		//get current theme number (in folder)
		$temp_id = explode("/", $current_theme_url);
		$return_data['theme_id'] = $temp_id[count($temp_id) - 1];
		
		$return_data['page_css'] 			= sq_bgt_use_https($page_css);
		$return_data['body_content'] 		= base64_encode(sq_bgt_use_https(base64_decode($body_content)));
		$return_data['title'] 				= get_the_title($post_id);
		//$return_data['face_mail'] 			= $face_mail;
		$return_data['custom_js_code'] 		= $custom_js_code;
		$return_data['current_theme_url'] 	= sq_bgt_use_https($current_theme_url);
		$return_data['current_sub_theme'] 	= $current_sub_theme;
		$return_data['current_theme_name'] 	= $current_theme_name;
		$return_data['current_theme_type'] 	= $current_theme_type;
		$return_data['custom_css_style'] 	= $custom_css_style;
		$return_data['bg_type'] 			= $bg_type;
		$return_data['bg_url'] 				= $bg_url;
		$return_data['custom_button_js_code'] = base64_encode($custom_button_js_code);
		echo "123dddsacxz".json_encode($return_data)."123dddsacxz";
		die();
	}
	//END EDITING CREATED POST

	function sub_squeezers_go_pro_cb()
		{ ?>
			<div id="go_pro_page">
				<h1>Get MUCH more leads with WP Lead Plus PRO!</h1>
				<h2>Do you want to have more templates to create more appealing squeeze pages?</h2>
				<p>While the free version only has 3 templates, you will find there are more than <span style="background-color: #f7fd52;">16 well-designed, highly SEO-optimized</span> squeeze
				templates with different sizes. <a style="background-color: #f7fd52;" id="in_get_pro" target="_blank" href="http://wpleadplus.com/?src=ingopro"> Find out more</a></p>
				
				<h2>Do you need to increase your conversion rates?</h2>
				
				<p>So don't use squeeze page only. With WP Lead Plus PRO, you can create widgets to place on your sidebar, posts. You can 
				also create unblockable popups. Using squeeze page, popup and widget helps you increase your conversion rate tremendously.<a style="background-color: #f7fd52;" id="in_get_pro" target="_blank" href="http://wpleadplus.com/?src=ingopro"> Find out more</a>
				</p>

				<h2>Do you want your squeeze page mobile-friendly?</h2>
				
				<p>Sure you do! Many of your subscribers are now using mobile to browse your site. All templates in WP Lead Plus are fully-responsive. 
				Your squeeze page will appear perfectly on mobile devices. <a style="background-color: #f7fd52;" id="in_get_pro" target="_blank" href="http://wpleadplus.com/?src=ingopro"> Find out more</a>
				</p>				

				<h2>Do you want to use your own background image?</h2>
				<p>The free version comes with stunning background images but they are limited. With WP Lead Plus PRO, you can use your 
				own image as the background of the squeeze page. <a style="background-color: #f7fd52;" id="in_get_pro" target="_blank" href="http://wpleadplus.com/?src=ingopro"> Find out more</a></p>
				
				<h2>Do you want to use video as background of your squeeze page?</h2>
				<p>Do you know that video background increase retention rate, thus increase conversion rate? With WP Lead Plus PRO you can 
				create squeeze page with video background in seconds.<a style="background-color: #f7fd52;" id="in_get_pro" target="_blank" href="http://wpleadplus.com/?src=ingopro"> Find out more</a></p>
				
				<h2>Are you tired of recurring payment?</h2>
				<p>Then WP Lead Plus PRO is right for you. With one time payment, you will get free life-time update of the plugin. You can also 
				contact me for support anytime without paying extra fee.</p>
				
				<h2>Are you planning to use WP Lead Plus PRO on more than one site?</h2>
				<p>No matter how many sites you own, you can install WP Lead Plus PRO on all of them with just one license. You don't need to
				buy extra licenses to install on other sites that you own.</p>
				
				
				
				<br />
				
				<h1><a id="in_get_pro" target="_blank" href="http://wpleadplus.com/?src=ingopro">Click here to find out more about WP Lead Plus PRO</a></h1>
				
				<br /><br /><br />
				
				<h2>Want to test the PRO version? Sure!</h2>
				<p>You can test the PRO version following the URL below</p>
				<h2 style="margin: auto; text-align: center;"><a href="http://wpleadplus.com/affdemo/wp-admin" target="_blank">http://wpleadplus.com/affdemo/wp-admin</a></h2>
				<p>Login details: </p>
	
					<p>ID: <strong>tester</strong></p>
					<p>Password: <strong>testerdemo</strong></p>
	
				
			</div>
			
			
		<?php }