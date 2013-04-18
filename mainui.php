<?php
	include_once 'activate.php';
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
					<h2>Thanks for using WP Lead Plus</h2>
					<p>We hope you enjoy the plugin. If you have any suggestion, request, bug report, please find us at:</p>
					<p>Skype: cbnoob</p>
					<p>Gmail: t2dx.inc@gmail.com</p>
					<p>We will get back to you a.s.a.p</p>
				</div>
				<div><span style="font-weight: bold; color: red; font-size: 2em;">ATTENTION: </span> Do not forget to go to <span style="font-weight: bold; color: blue;">WP Lead Plus</span> -> <span style="font-weight: bold; color: blue;">Settings</span> and click on <span style="font-weight: bold; color: blue;">Complete Setup</span> </div>
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
		
		echo '<h2>Complete basic setups</h2>';
		echo '<form method="post" action="">
				<input name="basic_setup" type="submit" value="Complete setup" class="button-primary"/>
			</form>
		
		';
		
		if (isset($_POST['basic_setup']))
		{
			sq_bgt_on_act();
		}
		echo '
		<div>
			<h2>Settings</h2>
			<p>You can set some settings for your squeeze pages here</p>
			<h3>Tracking code</h3>
			<p>Please copy and paste your tracking code you want to install in your squeeze page below. eg. Google
			Analytic... then hit the Save button</p>
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
				
					<label for="page_title">Page Title <span style="font-size: 0.8em">*Required</span></label>
					<input type="text" name="title" id="page_title" class="widefat" />
					
					<label for="page_url">Custom Background<span style="font-size: 0.8em"> .jpg/.png file</span></label>
					<input type="text" name="custom_bg" id="custom_bg" class="widefat" />
					
					<label for="page_url">Custom CTA<span style="font-size: 0.8em"> .jpg/.png file</span></label>
					<input type="text" name="custom_cta" id="custom_cta" class="widefat" />

					<label for="sq_submit_url">Submit URL</label>
					<input type="text" id="sq_submit_url" class="widefat" />
					
					<div id="custom_code_position" style="display: none;">
						<input type="radio" name="custom_code" value="below" /> Below
						<input type="radio" name="custom_code" value="above" /> Above	
					</div>
					
					
					<div id="switch_color" style="display: none;">
						<h4>Switch color</h4>
						<div id="colors_gallery"></div>
					</div>
					
					
					<div id="frontier_images" style="display: none; padding-top: 5px;">
						<div class="widefat">L: <input type="text" id="custom_img_left" /></div>
						<div class="widefat">R: <input type="text" id="custom_img_right" /></div>
						<div class="widefat">T: <input type="text" id="custom_img_top" /></div>
						<div class="widefat">B: <input type="text" id="custom_img_bottom" /></div>
					</div>

				</div>
				
					<?php sq_common_editbox(); //start the editbox?>
	
	
				</div>
				
				<div id="site_area">
				</div>
				<div style="clear:both;"></div>
				
				
			</div>
			<div id="gallery">
				<?php 
					$thumbnail_dir = plugin_dir_path(__FILE__);
					$thumbnail = scandir($thumbnail_dir.'themes/thumbnail');
					echo '<div id="video_themes">';
					for ($i=0; $i<count($thumbnail); $i++)
					{
						if ((stripos($thumbnail[$i], 'jpg') !== false) && (stripos($thumbnail[$i], 'v_') === 0))
						echo '<div class="thumb">
								<a href="'.plugins_url('themes/thumbnail/', __FILE__).$thumbnail[$i].'" rel="lightcase"><img src="'.plugins_url('themes/thumbnail/', __FILE__).$thumbnail[$i].'" /></a>
								<input type="radio" name="theme" id="'.$thumbnail[$i].'" />	
								</div>';
					}
					
					echo '</div>';
					
					echo '<div id="nonvid_themes">';
					for ($i=0; $i<count($thumbnail); $i++)
					{
					if ((stripos($thumbnail[$i], 'jpg') !== false) && (stripos($thumbnail[$i], 't_') === 0))
							echo '<div class="thumb">
							<a href="'.plugins_url('themes/thumbnail/', __FILE__).$thumbnail[$i].'" rel="lightcase"><img src="'.plugins_url('themes/thumbnail/', __FILE__).$thumbnail[$i].'" /></a>
							<input type="radio" name="theme" id="'.$thumbnail[$i].'" />
							</div>';
					}
					
					echo '</div>';
				?>
			</div>
			
			<!-- Get the footer panel -->		
			<div id="insert_code">
				<?php include_once 'code/editcode.txt'; ?>
			</div>
			
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
		$theme_table = $wpdb->get_blog_prefix().'sq_themes';
		
		//get the theme passed by the client, actually it's the name of thumbnail file
		$theme_thumbnail = trim($_POST['theme_name']);
		
		//get information about the theme in the db
		$query = "SELECT * FROM $theme_table  WHERE thumbnail='$theme_thumbnail'";
		$selected_theme = $wpdb->get_results($query, 'ARRAY_A');
		
		//SET SOME SESSION VARIABLE SO OTHER FUNCTION CAN USE THEM LATER
		$_SESSION['published'] = 'no';//this will reset the value if it was set
		
		//END SETTING SESSION VARIABLES
		
		//build the theme url and path
		$theme_path = plugin_dir_path(__FILE__).'themes/'.$selected_theme[0]['type'].'/'.$selected_theme[0]['name'].'/themes/1';//will select the first theme in the collection
		$theme_url = plugins_url("themes/".$selected_theme[0]['type']."/".$selected_theme[0]['name'].'/themes/1', __FILE__);
		
		//get the general theme url
		$general_theme_url = plugins_url("themes/".$selected_theme[0]['type']."/".$selected_theme[0]['name'], __FILE__);;
		
		$theme_parent = plugins_url("themes/".$selected_theme[0]['type'], __FILE__);//get this to join to the css later
		
		//get the content from the index.html file of the theme, need to strip the head and the close body part
		$index_file = file_get_contents($theme_path.'/index.html');
		
		//need to improve with regex, quite luxurious right now
		$theme_body = explode("<body>", $index_file);
		$theme_body = $theme_body[1];
		$theme_body = explode("</body>", $theme_body);
		$theme_body = $theme_body[0];
		
		//change the relative path to absolute
		$theme_body = str_replace("assets/", $theme_url."/assets/", $theme_body);
		
		//pass the style sheet
		$return_content['theme_css'] = base64_encode($theme_url.'/assets/style.css');
		
		//pass content body
		$return_content['theme_body'] = base64_encode($theme_body);
		
		//pass whether it has bg or not, necessary to hide the change BG button
		$return_content['has_img_bg'] = $selected_theme[0]['has_bg'];
		
		//pass the type, it is neccessary to know this then can get the css later
		$return_content['theme_type'] = $selected_theme[0]['type'];

		//pass the parent folder of the theme too
		$return_content['theme_type_url'] = $theme_parent;
		
		//get the theme name, without the .jpg part
		$return_content['current_theme_name'] = $selected_theme[0]['name'];
		
		//get the general theme url, this is the url of the theme, not specific parent of themes and colors
		$return_content['general_theme_url']  = $general_theme_url;
		
		//pass to the site in json format
		echo (json_encode($return_content));
		
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
			echo json_encode($valid_color);
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
		$current_theme_button = plugin_dir_path(__FILE__).'themes/'.$_POST['current_theme_type'].'/'.$_POST['current_theme_name'].'/themes/1/assets/imgs/submit.png';
		
		//get the button width and height
		$image_info = getimagesize($current_theme_button);
		//query the buttons
		$min_width = $image_info[0] - 3;
		$max_width = $image_info[0] + 3;
		global $wpdb;
			
		//get the button table
		$button_table = $wpdb->get_blog_prefix()."cta_buttons";
		
		//build the query
		$query = 'SELECT name, width, height FROM '.$button_table.";";
			
		$button_name = array();//create an array to store the buttons' names
		
		//get the button from db
		$button_db = $wpdb->get_results($query, "ARRAY_A");
		for ($i=0; $i<count($button_db); $i++)
		{
			if (($button_db[$i]["width"] > $min_width) && ($button_db[$i]["width"] < $max_width))
			{
				$button_name[] = plugins_url("themes/buttons",__FILE__).'/'.$button_db[$i]["name"];
			}
		}
	echo json_encode($button_name);
	die();
	}
	
	/* END LOADING THE BUTTONS AND SHOW TO USER */
	
	
	/* LOAD THE BACKGROUNDS AND SHOW TO USER */
	
	add_action('wp_ajax_show_backgrounds', 'show_backgrounds_callback');
	
	
	function show_backgrounds_callback()
	{
	
			global $wpdb;
				
			//get the button table
			$bg_table = $wpdb->get_blog_prefix()."background_img";
	
			//build the query
			$query = 'SELECT name FROM '.$bg_table.";";
				
			$bg_name = array();//create an array to store the buttons' names
	
			//get the button from db
			$bg_db = $wpdb->get_results($query, "ARRAY_A");
			for ($i=0; $i<count($bg_db); $i++)
			{
				$bg_name[] = plugins_url("themes/bgs/small",__FILE__).'/'.$bg_db[$i]["name"];
			}
				
			echo json_encode($bg_name);
			die();
		}
	
	/* END LOADING THE BACKGROUND AND SHOW TO USER */	
		
	/* PARSE THE EMAIL AND SEND BACK TO THE CLIENT */
	add_action('wp_ajax_parse_autoresponder', 'parse_autoresponder_callback');

	/* END PARSING THE EMAIL AND SEND BACK TO THE CLIENT */
	
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
		$post_css = base64_decode($_POST['cssfile']);
		$bg_url = base64_decode($_POST['bg_url']);
		$input_string = base64_decode($_POST['input_string']);
		$face_mail = preg_replace('/\s\s+/', '',base64_decode($_POST['face_mail']));//facebook integrate autoresponder code, remove all new line
		$current_post_id = ($_POST['current_post_id']);
		
		//prepare the function to hide the input text on click
		$input_array = explode("*", $input_string);
		
		$js_input = '';
		//glue the input elements
		for ($i=0; $i<count($input_array); $i++)
		{
			$single_input = explode(':',$input_array[$i]);
			
			$js_input .= "jQuery('#$single_input[0]').click(function(){ if (jQuery(this).val() == '$single_input[1]') {	jQuery(this).val(''); } });jQuery('#$single_input[0]').blur(function(){ if (jQuery(this).val() == '') { jQuery(this).val('$single_input[1]'); } });";	
		}
		
		$js_input = '<script>'.$js_input.'</script>';
		//check the facemail, add to the page if the code is set, do nothing if not
		if ((trim($face_mail) != "") || (stripos($face_mail, "enter your") !== FALSE))
		{
			$post_content = '<img src="'.plugins_url("themes/common/face_banner.png", __FILE__).'" id="show_fb_optin" style="position: fixed; cursor: pointer; left:0; bottom:0; display:none; z-index: 999999" /><iframe id="sq_fb_frame" height="0" width="0" src="'.plugins_url("code/fb.php", __FILE__).'"></iframe><div id="face_mail" style="display: none; z-index: 999999; text-align: center; position: absolute; top: 0;width: 100%; height: 100%; background: url('.plugins_url("themes/common/fb_bg.png", __FILE__).');"><div style="padding-top: 80px;">'.$face_mail.'</div><img src="'.plugins_url("themes/common/close_fb.png", __FILE__).'" style="position:absolute; right: 0; bottom: 0; cursor: pointer;" id="close_fb"/></div>'.$post_content.'<script>jQuery(document).ready(function(){jQuery("iframe").not("#sq_fb_frame, #face_mail *").each(function(){  var ifr_source = jQuery(this).attr("src");  var wmode = "wmode=transparent";  if(ifr_source.indexOf("?") != -1) jQuery(this).attr("src",ifr_source+"&"+wmode); else jQuery(this).attr("src",ifr_source+"?"+wmode); }); var fb_inter = setInterval(function(){if (jQuery("#sq_fb_frame").contents().find("#checklog").text().indexOf("here") != -1)  {jQuery("#show_fb_optin").fadeIn(); clearInterval(fb_inter);} else if (jQuery("#sq_fb_frame").contents().find("#checklog").text().indexOf("away") != -1)  {clearInterval(fb_inter);}  }, 2000);     jQuery("#close_fb").live("click",function(){jQuery("#face_mail").fadeOut("slow");}); jQuery("#show_fb_optin").click(function(){jQuery("#face_mail").fadeToggle("slow");}); });</script>';
		}

		//prepare the head of the page
		//$head = "<!doctype html><html lang=\"en\"><head><meta charset=\"utf-8\"><title>$post_title</title><link rel=\"stylesheet\" href='$post_css'><!--[if lt IE 9]><script src=\"http://html5shiv.googlecode.com/svn/trunk/html5.js\"></script><![endif]-->";
		$head = "<link rel=\"stylesheet\" href='$post_css'><!--[if lt IE 9]><script src=\"http://html5shiv.googlecode.com/svn/trunk/html5.js\"></script><![endif]-->";
		//get the custom js libraries
		$custom_jq = plugins_url("js/jq.js", __FILE__);
		$custom_bs = plugins_url("js/bgbs.js", __FILE__);//backstretch
		
		//if the theme has background, then load the background and insert the backstretch code
		if ($bg_url != 'none') //if $bg_url == none, the theme has no background
		{
			if ($bg_url == 'default')
			{
				$bg_url = plugins_url("themes/common/site_bg.jpg", __FILE__);
			}
			
			$head .= '<script src="'.$custom_jq.'"></script><script src="'.$custom_bs.'"></script>';
			$post_content .= $js_input.'<script>jQuery(document).ready(function(){jQuery.backstretch("'.$bg_url.'");jQuery("#sq_body_container").css("background-image", "none");});</script>';
		} else
		{
			$head .= '<script src="'.$custom_jq.'"></script>';
			$post_content .= trim($js_input);
		}
		
		//add custom css to head if any
		
		
		//close the head
		$head .= "</head>";
		
		$post_content = "<body>".$post_content;
		//prepare the data before inserting
		$data_post = array(
				'post_author' => wp_get_current_user()->ID,
				'post_title' => $post_title,
				'post_content' => $head.$post_content,
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
			if ($bg_url !== 'none')
			{
				add_post_meta($current_post_id, 'pros_has_bg', 'yes');
			} else
			{
				add_post_meta($current_post_id, 'pros_has_bg', 'no');
			}
			
			add_post_meta($current_post_id, 'pros_face_mail', $_POST['face_mail']);
			add_post_meta($current_post_id, 'pros_current_theme_url', $_POST['current_theme_url']);
			add_post_meta($current_post_id, 'pros_current_sub_theme', $_POST['current_sub_theme']);
			add_post_meta($current_post_id, 'pros_current_theme_name', $_POST['current_theme_name']);
			add_post_meta($current_post_id, 'pros_current_theme_type', $_POST['current_theme_type']);
			add_post_meta($current_post_id, 'pros_custom_css_code', $_POST['custom_css_code']);
			
		} else if (is_numeric($current_post_id)) //update the current post
		{
			$data_post['ID'] = $current_post_id;//need to set this variable to make sure the post will be updated, not create a new post
			wp_insert_post($data_post);
			
			//insert the  post body into postmeta, remember, this is the base64 encode form
			update_post_meta($current_post_id, 'pros_body_content', $_POST['content']);
			
			if ($bg_url !== 'none')
			{
				update_post_meta($current_post_id, 'pros_has_bg', 'yes');
			} else
			{
				update_post_meta($current_post_id, 'pros_has_bg', 'no');
			}
			update_post_meta($current_post_id, 'pros_theme_css', $post_css);
			update_post_meta($current_post_id, 'pros_face_mail', $_POST['face_mail']);
			update_post_meta($current_post_id, 'pros_current_theme_url', $_POST['current_theme_url']);
			update_post_meta($current_post_id, 'pros_current_sub_theme', $_POST['current_sub_theme']);
			update_post_meta($current_post_id, 'pros_current_theme_name', $_POST['current_theme_name']);
			update_post_meta($current_post_id, 'pros_custom_css_code', $_POST['custom_css_code']);
		}
		$return_message = array('message' => 'Done', 'current_post_id' => $current_post_id);
		echo json_encode($return_message);
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
		echo json_encode($posts_data);
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
		$_SESSION['post_id'] = $post_id;
		$_SESSION['published'] = 'yes';
		
 		//get wp_postmeta table
		$post_meta = $wpdb->get_blog_prefix().'postmeta';
		
		//Build the queries
		$query_body = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_body_content'";
		$query_css = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_theme_css'";
		$query_has_bg = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_has_bg'";
		$query_face_mail = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_face_mail'";
		
		$query_current_theme_url = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_current_theme_url'";
		$query_current_sub_theme = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_current_sub_theme'";
		$query_current_theme_name = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_current_theme_name'";
		$query_current_theme_type = "SELECT meta_value FROM $post_meta WHERE post_id = $post_id AND meta_key = 'pros_current_theme_type'";

		//declare the variables
		$body_content ="";
		$page_css ="";
		$page_has_bg ="";
		$current_theme_url ="";
		$current_sub_theme ="";
		$current_theme_name ="";
		$current_theme_type ="";
		$face_mail ="";

		
		//get the body
		try 
		{
			$body_content = $wpdb->get_results($query_body, "ARRAY_A");
			$body_content = $body_content[0]['meta_value'];
		} catch (Exception $e)
		{
			echo "shit";
		}
 		//get the css
		try 
		{
			$page_css = $wpdb->get_results($query_css, "ARRAY_A");
			$page_css = $page_css[0]['meta_value'];
		} catch (Exception $e)
		{
			echo "shit";
		}
		
		//get the has bg value, whether the post has a background image or not
		try
		{		
			$page_has_bg = $wpdb->get_results($query_has_bg, "ARRAY_A");
			$page_has_bg = $page_has_bg[0]['meta_value'];
		} catch (Exception $e)
		{
			echo "shit";
		}

		//get current theme url
		try
		{		
			$current_theme_url = $wpdb->get_results($query_current_theme_url, "ARRAY_A");
			$current_theme_url = $current_theme_url[0]['meta_value'];
		} catch (Exception $e)
		{
			echo "shit";
		}
	
		//get sub theme name
		try
		{		
			$current_sub_theme = $wpdb->get_results($query_current_sub_theme, "ARRAY_A");
			$current_sub_theme = $current_sub_theme[0]['meta_value'];
		} catch (Exception $e)
		{
			echo "shit";
		}		
		//get current theme name
		try
		{		
			$current_theme_name = $wpdb->get_results($query_current_theme_name, "ARRAY_A");
			$current_theme_name = $current_theme_name[0]['meta_value'];
		} catch (Exception $e)
		{
			echo "shit";
		}		
		//get current theme type
 		try
		{		
			$current_theme_type = $wpdb->get_results($query_current_theme_type, "ARRAY_A");
			$current_theme_type = $current_theme_type[0]['meta_value'];
		} catch (Exception $e)
		{
			echo "shit";
		}	

		//get the face mail value
		try 
		{
			$face_mail = $wpdb->get_results($query_face_mail, "ARRAY_A");
			$face_mail = $face_mail[0]['meta_value'];
		} catch (Exception $e)
		{
			echo "shit";
		} 
		
	
		$return_data['page_css'] = $page_css;
		$return_data['body_content'] = $body_content;
		$return_data['title'] = get_the_title($post_id);
		$return_data['page_has_bg'] = $page_has_bg;
		$return_data['face_mail'] = $face_mail;
		$return_data['current_theme_url'] = $current_theme_url;
		$return_data['current_sub_theme'] = $current_sub_theme;
		$return_data['current_theme_name'] = $current_theme_name;
		$return_data['current_theme_type'] = $current_theme_type;  
		
		echo json_encode($return_data);
		die();
	}
	//END EDITING CREATED POST
	
	//Main page, activation
	add_action('wp_ajax_goodmail_action', 'goodmail_edit_cb');
	
	function goodmail_edit_cb(){
		try
		{
			$fh = fopen(plugin_dir_path(__FILE__).'code/'.base64_decode('dHVybnVwLnR4dA=='), 'w');
			fwrite($fh, base64_decode('cmFuZG9tIHRleHQ='));
			update_option('sq_activation_status', 'activated');
			echo 'done';
		} catch (Exception $e)
		{
			echo "problem ". $e->getMessage();
		}
		
		die();
	}
	
	//get server response on checking mail
	add_action('wp_ajax_sq_check_email', 'sq_server_check_email_cb');
	
	function sq_server_check_email_cb()
	{
		$email = $_POST['us_email'];
		
		$ch = curl_init('http://wpleadplus.com/archive/spring.php?us_email='.urlencode($email));
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		
		
		if (stripos($response, "looksgood") !== false)
		{
				try
				{
					$fh = fopen(plugin_dir_path(__FILE__).'code/'.base64_decode('dHVybnVwLnR4dA=='), 'w');
					fwrite($fh, base64_decode('cmFuZG9tIHRleHQ='));
					update_option('sq_activation_status', 'activated');
					echo 'done';
				} catch (Exception $e)
				{
					echo "problem ". $e->getMessage();
				}
		} else
		{
				echo "wrong mail! Have you activated your license?";
		}
	
		die();
	}
	//End Main page
	
	//Setting page
	add_action('wp_ajax_save_tracking', 'save_tracking_cb');
	
	function save_tracking_cb(){
		try 
		{
			$tracking_code = base64_decode($_POST['tracking_code']);
			
			$tracking_code = preg_replace('/\s\s+/', ' ', $tracking_code);//remove new lines
			update_option('sq_user_tracking_code', $tracking_code);
			echo 'Tracking code saved';
		} catch(Exception $e)
		{
			echo "Error ". $e->getMessage();
		}
		
		die();
	}
	//End setting page save_tracking    