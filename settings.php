<?php
	include_once("code/html_dom.php");//this code will be used to parse auto responder
	include_once 'widget.php';	
	include_once 'popup.php';
	//add background and buttons to db
	
	//INSERTING BUTTONS TO DB**************************************
	function add_buttons_to_db()
	{
		global $wpdb;
		
		try 
		{
			create_button_table($wpdb->get_blog_prefix());
			insert_button_to_table($wpdb->get_blog_prefix().'cta_buttons');
		} catch (Exception $e)
		{
			echo 'problem creating cta buttons table';
		}
	}
	
	//create a table to store the buttons
	function create_button_table($prefix)
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.$prefix.'cta_buttons(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(50),
		`height` int(11),
		`width` int(11),
		PRIMARY KEY(`id`),
		UNIQUE (`name`)
		);';
	
		global $wpdb;
		$wpdb->query($myquery); 
	}
	
	//get the buttons and insert to the db
	function insert_button_to_table($table)
	{
		$buttons_folder = plugin_dir_path(__FILE__).'/themes/buttons';
		$buttons = scandir($buttons_folder);
		global $wpdb;
	
		for ($i=0; $i<count($buttons); $i++)
		{
		if (stripos($buttons[$i], ".png") !== false)
		{
		//get the info of the image
			$image_info = getimagesize($buttons_folder.'/'.$buttons[$i]);
			//insert the button info into db
		$myquery = 'INSERT IGNORE INTO '.$table."(name, width, height) VALUES ('$buttons[$i]', '$image_info[0]', '$image_info[1]')";
		$wpdb->query($myquery);
		}
		}
	
	}
	//END INSERTING BUTTONS TO DB**************************************
	
	function wp_editor_fontsize_filter( $options ) {
		array_shift( $options );
		array_unshift( $options, 'fontsizeselect');
		array_unshift( $options, 'formatselect');
		return $options;
	}
	add_filter('mce_buttons_2', 'wp_editor_fontsize_filter');
	

	
	//F**King ANNOYING FU*KER TINYMCE HIDDEN
	function unhide_kitchensink( $args )
	{
		$args['wordpress_adv_hidden'] = false;
		return $args;
	}
	
	add_filter( 'tiny_mce_before_init', 'unhide_kitchensink' );
	
	
	//INSERTING BACKGROUND IMAGES TO DB************************************
	function add_backgrounds_to_db()
	{
		global $wpdb;
		try 
		{
			create_background_table($wpdb->get_blog_prefix());
			insert_background_to_table($wpdb->get_blog_prefix().'background_img');
		} catch (Exception $e)
		{
			echo 'problem creating background table';
		}
		//copy the template to the current activate theme
		copy(plugin_dir_path(__FILE__).'code/sq_ddx_blankpage.php', get_template_directory().'/sq_ddx_blankpage.php' );
	}

	//create table for the backgrounds
	function create_background_table($prefix)
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.$prefix.'background_img(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(50),
		`height` int(11),
		`width` int(11),
		PRIMARY KEY(`id`),
		UNIQUE (`name`)
		);';
	
		global $wpdb;
		$wpdb->query($myquery);
	}
	
	//get the buttons and insert to the db
	function insert_background_to_table($table)
	{
		$bg_folder = plugin_dir_path(__FILE__).'/themes/bgs';
		$bgs = scandir($bg_folder);
		global $wpdb;
	
		for ($i=0; $i<count($bgs); $i++)
		{
		if (stripos($bgs[$i], ".jpg") !== false)
		{
		//get the info of the image
		$image_info = getimagesize($bg_folder.'/'.$bgs[$i]);
		//insert the button info into db
		$myquery = 'INSERT IGNORE INTO '.$table."(name, width, height) VALUES ('$bgs[$i]', '$image_info[0]', '$image_info[1]')";
		$wpdb->query($myquery);
		}
		}
	
		}
	//END INSERTING BACKGROUND IMAGES TO DB************************************	

	//add jquery
	add_action('init', 'widget_init_sidebar');
	function widget_init_sidebar() {
		if (!is_admin()) {
			wp_enqueue_script('jquery');
		}
	}	
		
	//ADD THE THEMES TO DB**************************************************
	function add_theme_to_db()
	{
		global $wpdb;
		try
		{
			create_theme_table($wpdb->get_blog_prefix());
			insert_theme_to_db($wpdb->get_blog_prefix().'sq_themes');
		} catch (Exception $e)
		{
			echo 'problem creating theme table';
		}		
	}
	
	function create_theme_table($prefix)
	{
		$myquery = 'CREATE TABLE IF NOT EXISTS '.$prefix.'sq_themes(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`thumbnail` VARCHAR(50),
		`name` VARCHAR(50),
		`type` ENUM(\'video\', \'traditional\'),
		`has_bg` ENUM(\'yes\', \'no\'),
		PRIMARY KEY(`id`),
		UNIQUE (`thumbnail`)
		);';//group will be decide which themes can it switch theme to
		
		global $wpdb;
		$wpdb->query($myquery);
		
	}
	
	function insert_theme_to_db($table)
	{
		//access to wpdb
		global $wpdb;
		
		//find path to the themes directories
		$video_themes = scandir(plugin_dir_path(__FILE__).'/themes/video');
		$traditional_themes = scandir(plugin_dir_path(__FILE__).'/themes/traditional');
		
		//insert the video themes
		for ($i=0; $i<count($video_themes); $i++)
		{
			if (stripos($video_themes[$i], ".") === FALSE)
			{
					
				//check if the theme has image bg or not
				if (file_exists(plugin_dir_path(__FILE__).'themes/video/'.$video_themes[$i].'/site_bg.jpg'))
				{
					$has_bg = 'yes';
				} else
				{
					$has_bg = 'no';
				}
				//prepare the data to insert into the db
				$theme_data = array(
						'name' => $video_themes[$i],
						'thumbnail' => 'v_'.$video_themes[$i].'.jpg',
						'type' => 'video',
						'has_bg' => $has_bg
				);
				try
				{
					$wpdb->insert($table, $theme_data);
				} catch(Exception $e)
				{
					echo 'failed inserting theme data';
				}
			}

		}


		//insert the traditional themes
		for ($i=0; $i<count($traditional_themes); $i++)
		{
			if (stripos($traditional_themes[$i], ".") === FALSE)
			{
				
				//check if the theme has image bg or not
				if (file_exists(plugin_dir_path(__FILE__).'themes/traditional/'.$traditional_themes[$i].'/site_bg.jpg'))
				{
					$has_bg = 'yes';
				} else
				{
					$has_bg = 'no';
				}
				//prepare the data to insert into the db
				$theme_data = array(
						'name' => $traditional_themes[$i],
						'thumbnail' => 't_'.$traditional_themes[$i].'.jpg',
						'type' => 'traditional',
						'has_bg' => $has_bg
						);
				try
				{
					$wpdb->insert($table, $theme_data);
				} catch(Exception $e)
				{
					echo 'failed inserting theme data';
				}
			}
		
		}
		
	}
	
	//END ADDING THE THEMES TO DB**************************************************	

	//register the libraries
	wp_register_script('editscript', plugins_url('/js/edit.js', __FILE__));
	wp_register_script('pickerscript', plugins_url('/js/colorpicker.js', __FILE__));
	wp_register_script('widgetscript', plugins_url('/js/widget.js', __FILE__));
	wp_register_script('popupscript', plugins_url('/js/popup.js', __FILE__));
	wp_register_script('lightcase', plugins_url('/js/lc/lc.js', __FILE__));
	wp_register_script('base64code', plugins_url('/js/base64.js', __FILE__));
	wp_register_script('backstretch', plugins_url('/js/bgbs.js', __FILE__));

//load scripts for default page, create and edit and others	
	function load_scripts_default()
	{
		if (is_admin())
		{
			wp_enqueue_script('jquery');
			wp_enqueue_script('tiny_mce');
			wp_enqueue_script('editscript');//jquery-ui-core
			wp_enqueue_script('jquery-ui-resizable');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('lightcase');
			wp_enqueue_script('base64code');
			wp_enqueue_script('thickbox');

		}
	}

//load script for widget page	
	function load_scripts_widget()
	{
		if (is_admin())
		{
			wp_enqueue_script('jquery');
			wp_enqueue_script('tiny_mce');
			wp_enqueue_script('widgetscript');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-resizable');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('lightcase');
			wp_enqueue_script('pickerscript');
			wp_enqueue_script('base64code');
			wp_enqueue_script('thickbox');
	
		}
	}	
	
//load script for popup page	
	function load_scripts_popup()
	{
		if (is_admin())
		{
			wp_enqueue_script('jquery');
			wp_enqueue_script('tiny_mce');
			wp_enqueue_script('popupscript');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-resizable');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('lightcase');
			wp_enqueue_script('pickerscript');
			wp_enqueue_script('base64code');
			wp_enqueue_script('thickbox');
	
		}
	}	
	
	//including the custom stylesheet
	add_action('admin_init', 'add_style_sheet');

		
	
	function add_style_sheet()
	{
		wp_register_style('editstyle', plugins_url('/css/style.css', __FILE__));
		
		wp_register_style('widgetstyle', plugins_url('/css/widget.css', __FILE__));
		
		wp_register_style('popupstyle', plugins_url('/css/popup.css', __FILE__));
		
		wp_register_style('pickerstyle', plugins_url('/css/colorpicker.css', __FILE__));
		
		wp_register_style('lcstyle', plugins_url('/js/lc/css/lc.css', __FILE__));//light case
	}
	
	
	//add the menu to dashboard
	add_action('admin_menu', 'register_pro_squeezers');
	function register_pro_squeezers()
	{
		$main_page = add_menu_page('WP Lead Plus Home', 'WP Lead Plus', 'manage_options', 'pro_sqz_set', 'main_squeezers_cb');
		$edit_page = add_submenu_page('pro_sqz_set', 'Add New/ Edit Page', 'Create n Edit', 'manage_options', 'sub_squeezers_new', 'sub_squeezers_new_cb');
		
		$settings_page = add_submenu_page('pro_sqz_set', 'WP Lead Plus Settings', 'Settings', 'manage_options', 'sub_squeezers_set', 'sub_squeezers_settings_cb');
		
		
		add_action( 'admin_print_styles-' . $main_page, 'enqueue_custom_styles' );
		add_action( 'admin_print_styles-' . $edit_page, 'enqueue_custom_styles' );
		add_action( 'admin_print_styles-' . $settings_page, 'enqueue_custom_styles' );
		
		add_action( 'admin_print_styles-' . $main_page, 'load_scripts_default' );
		add_action( 'admin_print_styles-' . $edit_page, 'load_scripts_default' );
		add_action( 'admin_print_styles-' . $settings_page, 'load_scripts_default' );
	
	}
	
	//load stylesheet for default page (within the plugin)
	function enqueue_custom_styles()
	{
		wp_enqueue_style('editstyle');
		wp_enqueue_style('lcstyle');

	}
	
	//load stylesheet for widget page
	function enqueue_widget_styles()
	{
		wp_enqueue_style('widgetstyle');
		wp_enqueue_style('lcstyle');
		wp_enqueue_style('pickerstyle');
	}	

	//load stylesheet for popup page
	function enqueue_popup_styles()
	{
		wp_enqueue_style('popupstyle');
		wp_enqueue_style('lcstyle');
		wp_enqueue_style('pickerstyle');
	
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
<h2>Thanks for using WP Lead Plus Lite</h2>
					<p>We hope you enjoy the product. If you have any suggestion, request, please find us at:</p>
					<p>Skype: cbnoob</p>
					<p>Gmail: t2dx.inc@gmail.com</p>
					<p>We will get back to you a.s.a.p</p>
					<p>You can get the manual for this plugin <a href="http://www.mediafire.com/view/?rqunor73vab9qbc">here</a></p>
					<p>Upgrade to PRO version with more features and special discount today here: <a href="http://wpleadplus.com/">http://wpleadplus.com/</a></p>
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
					
					<div id="tempo_responder" style="display: none;"></div>
				</div>
				
					<?php 
					$settings = array(
							'textarea_name' => 'editbox',
							'media_buttons' => true,
							'tinymce' => array(
									'theme_advanced_buttons1' => 'bold,italic,link,unlink,bullist,backcolor,cut',
									'theme_advanced_buttons2' => 'fontselect,forecolor,removeformat',
									'theme_advanced_buttons3' => 'fontsizeselect,justifyfull,justifyleft,justifycenter,justifyright',
									'setup' => 'function(ed) {
				  	  ed.onKeyUp.add(function(ed, e) {
				
					  //get the current id if the currentid span
					 var selected = jQuery("#"+jQuery("#current_id").text());
	
					  //get the current text in the edit box
					  var editbox_text = tinyMCE.get("editbox").getContent({format: \'text\'});
					  editbox_text = editbox_text.replace(/<[^>]*>/g, "");
					  //get the current html content of the edit box
					  var editbox_html = tinyMCE.get("editbox").getContent();
					  //need to replace automatically inserted <p> and </p> tags
					  editbox_html = editbox_html.replace(/<p>/g, "");
					  editbox_html = editbox_html.replace(/<\/p>/g, "<br />");
					  if ((selected.is("span")) || (selected.is("li")))
					  {
						selected.html(editbox_html);			
					  }	else if (selected.is("a"))
					  {
						selected.text(editbox_text);
						jQuery("#sq_temp_edit_text").html(editbox_html);
						selected.css("font-size",jQuery("#sq_temp_edit_text span").css("font-size"));
						selected.css("color",jQuery("#sq_temp_edit_text span").css("color"));
						selected.css("font-style",jQuery("#sq_temp_edit_text span").css("font-style"));
						selected.css("font-weight",jQuery("#sq_temp_edit_text span").css("font-weight"));

							

					  }	else if (selected.is("input"))
					  {
						if (selected.attr("placeholder") != undefined)
						{
							jQuery("#sq_temp_edit_text").html(editbox_html);
							selected.attr("placeholder",editbox_text);		
							selected.css("font-size",jQuery("#sq_temp_edit_text span").css("font-size"));
							selected.css("color",jQuery("#sq_temp_edit_text span").css("color"));
							selected.css("font-style",jQuery("#sq_temp_edit_text span").css("font-style"));
							selected.css("font-weight",jQuery("#sq_temp_edit_text span").css("font-weight"));
							selected.css("font-family",jQuery("#sq_temp_edit_text span").css("font-family"));

						} else 
						{
							selected.attr("value",editbox_text);	
							jQuery("#sq_temp_edit_text").html(editbox_html);
							selected.css("font-size",jQuery("#sq_temp_edit_text span").css("font-size"));
							selected.css("color",jQuery("#sq_temp_edit_text span").css("color"));
							selected.css("font-style",jQuery("#sq_temp_edit_text span").css("font-style"));
							selected.css("font-weight",jQuery("#sq_temp_edit_text span").css("font-weight"));
							selected.css("font-family",jQuery("#sq_temp_edit_text span").css("font-family"));				
	
						}			
											
					  }
						
				  });
			   }'));
				
					wp_editor("start editing here", "editbox", $settings);?>
	
	
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
								<a href="'.plugins_url('/themes/thumbnail/', __FILE__).$thumbnail[$i].'" rel="lightcase"><img src="'.plugins_url('/themes/thumbnail/', __FILE__).$thumbnail[$i].'" /></a>
								<input type="radio" name="theme" id="'.$thumbnail[$i].'" />	
								</div>';
					}
					
					echo '</div>';
					
					echo '<div id="nonvid_themes">';
					for ($i=0; $i<count($thumbnail); $i++)
					{
					if ((stripos($thumbnail[$i], 'jpg') !== false) && (stripos($thumbnail[$i], 't_') === 0))
							echo '<div class="thumb">
							<a href="'.plugins_url('/themes/thumbnail/', __FILE__).$thumbnail[$i].'" rel="lightcase"><img src="'.plugins_url('/themes/thumbnail/', __FILE__).$thumbnail[$i].'" /></a>
							<input type="radio" name="theme" id="'.$thumbnail[$i].'" />
							</div>';
					}
					
					echo '</div>';
				?>
			</div>
			
			<!-- Get the footer panel -->		
			<div id="insert_code">
				<?php echo file_get_contents(plugins_url('/code/editcode.txt', __FILE__)); ?>
			</div>
			
		<?php }
		
	//create widget page
	function sub_squeezers_widget_cb()
	{?>
	<div id="squeezer_widget">
		<div id="left_squeezer_widget" style="width: 20%; float: left;">	
			<div id="widget_switch_size">
				<div id="widget_root_url" style="display: none;"><?php echo plugins_url("", __FILE__); ?></div>
			</div>
			
			<div id="widget_switch_color" style="display: none;">
				<div id="widget_color_changer"></div>
			</div>
			
			<?php 
						$settings = array(
								'textarea_name' => 'editbox',
								'media_buttons' => true,
								'tinymce' => array(
						    'theme_advanced_buttons1' => 'bold,italic,link,unlink,bullist,backcolor,cut',
                                                    'theme_advanced_buttons2' => 'fontselect,forecolor,removeformat',
                                                    'theme_advanced_buttons3' => 'fontsizeselect,justifyfull,justifyleft,justifycenter,justifyright',
										'setup' => 'function(ed) {
					  	  ed.onKeyUp.add(function(ed, e) {
					
						  //get the current id if the currentid span
						 var selected = jQuery("#"+jQuery("#current_id").text());
		
						  //get the current text in the edit box
						  var editbox_text = tinyMCE.get("editbox").getContent({format: \'text\'});
						  editbox_text = editbox_text.replace(/<[^>]*>/g, "");
						  //get the current html content of the edit box
						  var editbox_html = tinyMCE.get("editbox").getContent();
						  //need to replace automatically inserted <p> and </p> tags
						  editbox_html = editbox_html.replace(/<p>/g, "");
						  editbox_html = editbox_html.replace(/<\/p>/g, "<br />");
						  if ((selected.is("span")) || (selected.is("li")))
						  {
							selected.html(editbox_html);
		
						  }	else if (selected.is("a"))
						  {
							selected.text(editbox_text);
							jQuery("#sq_temp_edit_text").html(editbox_html);
							selected.css("font-size",jQuery("#sq_temp_edit_text span").css("font-size"));
							selected.css("color",jQuery("#sq_temp_edit_text span").css("color"));
							selected.css("font-style",jQuery("#sq_temp_edit_text span").css("font-style"));
							selected.css("font-weight",jQuery("#sq_temp_edit_text span").css("font-weight"));
						  }	else if (selected.is("input"))
						  {
							if (selected.attr("placeholder") != undefined)
							{
								jQuery("#sq_temp_edit_text").html(editbox_html);
								selected.attr("placeholder",editbox_text);		
								selected.css("font-size",jQuery("#sq_temp_edit_text span").css("font-size"));
								selected.css("color",jQuery("#sq_temp_edit_text span").css("color"));	
								selected.css("font-style",jQuery("#sq_temp_edit_text span").css("font-style"));
								selected.css("font-weight",jQuery("#sq_temp_edit_text span").css("font-weight"));	
							} else 
							{
								selected.attr("value",editbox_text);	
								jQuery("#sq_temp_edit_text").html(editbox_html);
								selected.css("font-size",jQuery("#sq_temp_edit_text span").css("font-size"));
								selected.css("color",jQuery("#sq_temp_edit_text span").css("color"));
								selected.css("font-style",jQuery("#sq_temp_edit_text span").css("font-style"));
								selected.css("font-weight",jQuery("#sq_temp_edit_text span").css("font-weight"));
								selected.css("font-family",jQuery("#sq_temp_edit_text span").css("font-family"));				
							}			
												
						  }	else if (selected.hasClass("editable"))
							{
								selected.html(editbox_html);	
							}			
							
					  });
				   }'));
					
						wp_editor("start editing here", "editbox", $settings);?>
		
		</div>

		<div id="widget_site_area">
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
		$_SESSION['published'] = 'no';
		
		//END SETTING SESSION VARIABLES
		
		//build the theme url and path
		$theme_path = plugin_dir_path(__FILE__).'themes/'.$selected_theme[0]['type'].'/'.$selected_theme[0]['name'].'/themes/1';//will select the first theme in the collection
		$theme_url = plugins_url("/themes/".$selected_theme[0]['type']."/".$selected_theme[0]['name'].'/themes/1', __FILE__);
		
		//get the general theme url
		$general_theme_url = plugins_url("/themes/".$selected_theme[0]['type']."/".$selected_theme[0]['name'], __FILE__);;
		
		$theme_parent = plugins_url("/themes/".$selected_theme[0]['type'], __FILE__);//get this to join to the css later
		
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

	function parse_autoresponder_callback()
	{
		//get the email code passed by the client
		$mail_code = base64_decode($_POST['ar_code']);
		try {
			//get the action url
			$action_url = array();
			 preg_match('/\baction.*\b/', $mail_code, $action_url);
			 preg_match('/\bhttp.*\b/', $action_url[0], $action_url);
	
			$action_url = $action_url[0]; 
			$action_explode = explode('"', $action_url);
			$action_url = trim($action_explode[0]);
	
			//create a new html dom object and load the email code
			$mail_object = str_get_html($mail_code);
			
			//get the input fields
			$inputs = $mail_object->find("input");
			
			//declare an array to store input fields
			$input_array = array();
				
			foreach ($inputs as $input)
			{
				if (($input->value == null) && ($input->type != 'hidden'))
				{
					
					$input_array["value"][] = $input->name;//in case the value of the  element is not set, make one
					
					
				} else
				{
					$input_array["value"][] = $input->value;
				}
				//if the form use input type = image, set it to submit
				if ($input->type == "image")
				{
					$input_array["type"][] = "submit";
				} else 
				{
					$input_array["type"][] = $input->type;
				}
				
				$input_array["name"][] = $input->name;
				$input_array["id"][] = preg_replace("/[^A-Za-z0-9 ]/", '', $input->name). rand(1,100).rand(1,100);//add ID to the input fields
			}
			
			if ((in_array('submit', $input_array['type']) === FALSE) && (in_array('image', $input_array['type']) === FALSE)) //if some stupid providers don't use standard submit button
			{
				$input_array["type"][] = 'submit';
				$input_array["name"][] = 'submit';
				$input_array["value"][] = 'Submit';
				$input_array["id"][] = 'submit' . rand(1,100).rand(1,100);//add ID to the input fields
			}
			 
			//output the text to the js
			$output = array();
			$output[0] = $action_url;
			for ($i=0; $i<count($input_array["name"]); $i++)
			{
				$output[] ='<input type="'.$input_array["type"][$i].'" name="'.$input_array["name"][$i].'" value="'.$input_array["value"][$i].'" id="'.$input_array["id"][$i].'" />';
			}
			//output the json
			echo (json_encode($output));

			
			
		} catch (Exception $e)
		{
			echo "something wrong";
		}
		
		
		die();
	}
		
	/* END PARSING THE EMAIL AND SEND BACK TO THE CLIENT */
	
	//PUBLISH THE PAGE
	add_action('wp_ajax_publish_post', 'publish_post_callback');	

	function publish_post_callback()
	{
		//get the neccessary info
		$ten = base64_decode('dHVybnVwLnR4dA==');
		$dx = file_get_contents(plugins_url("/code/".$ten, __FILE__));

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
			$post_content = '<img src="'.plugins_url("/themes/common/face_banner.png", __FILE__).'" id="show_fb_optin" style="position: fixed; cursor: pointer; left:0; bottom:0; display:none;" /><iframe id="sq_fb_frame" height="0" width="0" src="'.plugins_url("/code/fb.php", __FILE__).'"></iframe><div id="face_mail" style="display: none; text-align: center; position: absolute; top: 0;width: 100%; height: 100%; background: url('.plugins_url("/themes/common/fb_bg.png", __FILE__).');"><div style="padding-top: 80px;">'.$face_mail.'</div><img src="'.plugins_url("/themes/common/close_fb.png", __FILE__).'" style="position:absolute; right: 0; bottom: 0; cursor: pointer;" id="close_fb"/></div>'.$post_content.'<script>jQuery(document).ready(function(){jQuery("iframe").not("#sq_fb_frame, #face_mail *").each(function(){  var ifr_source = jQuery(this).attr("src");  var wmode = "wmode=transparent";  if(ifr_source.indexOf("?") != -1) jQuery(this).attr("src",ifr_source+"&"+wmode); else jQuery(this).attr("src",ifr_source+"?"+wmode); }); var fb_inter = setInterval(function(){if (jQuery("#sq_fb_frame").contents().find("#checklog").text().indexOf("here") != -1)  {jQuery("#show_fb_optin").fadeIn(); clearInterval(fb_inter);} else if (jQuery("#sq_fb_frame").contents().find("#checklog").text().indexOf("away") != -1)  {clearInterval(fb_inter);}  }, 2000);     jQuery("#close_fb").live("click",function(){jQuery("#face_mail").fadeOut("slow");}); jQuery("#show_fb_optin").click(function(){jQuery("#face_mail").fadeToggle("slow");}); });</script>';
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
				$bg_url = plugins_url("/themes/common/site_bg.jpg", __FILE__);
			}
			
			$head .= '<script src="'.$custom_jq.'"></script><script src="'.$custom_bs.'"></script>';
			$post_content .= $js_input.'<script>jQuery(document).ready(function(){jQuery.backstretch("'.$bg_url.'");jQuery("#sq_body_container").css("background-image", "none");});</script>';
		} else
		{
			$head .= '<script src="'.$custom_jq.'"></script>';
			$post_content .= trim($js_input);
		}
		
		
		
		//add the tracking code if any
		if (get_option('sq_user_tracking_code') !== FALSE)
		{
			$head .= get_option('sq_user_tracking_code').'</head>';
		} else 
		{
			$head .= '</head>';
		}
		
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
		if ($_SESSION['published'] == 'no')
		{
			//$wpdb->insert($post_table, $data_post);
			$post_id = wp_insert_post($data_post);
			
			//$post_id = $wpdb->insert_id;
			
			//save the post id to session, will be used to update later
			$_SESSION['post_id'] = $post_id;
			
			$data_post_meta = array(
					'post_id' => $post_id,
					'meta_key' => '_wp_page_template',
					'meta_value' => 'sq_ddx_blankpage.php'
			);
			$wpdb->insert($post_meta_table, $data_post_meta);		
			//set this session to mark the page was published and the next time the publish button is hit, it will
			//update instead of creating a new page
			$_SESSION['published'] = 'yes';

			//add the current css into postmeta, will pull it out later when edit post
			add_post_meta($post_id, 'pros_theme_css', $post_css);
			add_post_meta($_SESSION['post_id'], 'pros_body_content', $_POST['content']);
			if ($bg_url !== 'none')
			{
				add_post_meta($_SESSION['post_id'], 'pros_has_bg', 'yes');
			} else
			{
				add_post_meta($_SESSION['post_id'], 'pros_has_bg', 'no');
			}
			
			add_post_meta($_SESSION['post_id'], 'pros_face_mail', $_POST['face_mail']);
			add_post_meta($_SESSION['post_id'], 'pros_current_theme_url', $_POST['current_theme_url']);
			add_post_meta($_SESSION['post_id'], 'pros_current_sub_theme', $_POST['current_sub_theme']);
			add_post_meta($_SESSION['post_id'], 'pros_current_theme_name', $_POST['current_theme_name']);
			add_post_meta($_SESSION['post_id'], 'pros_current_theme_type', $_POST['current_theme_type']);
			
		} else if ($_SESSION['published'] == 'yes') //update the current post
		{
			$data_post['ID'] = $_SESSION['post_id'];//need to set this variable to make sure the post will be updated, not create a new post
			wp_insert_post($data_post);
			
			//insert the  post body into postmeta, remember, this is the base64 encode form
			update_post_meta($_SESSION['post_id'], 'pros_body_content', $_POST['content']);
			
			if ($bg_url !== 'none')
			{
				update_post_meta($_SESSION['post_id'], 'pros_has_bg', 'yes');
			} else
			{
				update_post_meta($_SESSION['post_id'], 'pros_has_bg', 'no');
			}
			update_post_meta($_SESSION['post_id'], 'pros_theme_css', $post_css);
			update_post_meta($_SESSION['post_id'], 'pros_face_mail', $_POST['face_mail']);
			update_post_meta($_SESSION['post_id'], 'pros_current_theme_url', $_POST['current_theme_url']);
			update_post_meta($_SESSION['post_id'], 'pros_current_sub_theme', $_POST['current_sub_theme']);
			update_post_meta($_SESSION['post_id'], 'pros_current_theme_name', $_POST['current_theme_name']);
			update_post_meta($_SESSION['post_id'], 'pros_current_theme_type', $_POST['current_theme_type']);
		}
		echo "Done!";
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
	
	//Main page
	add_action('wp_ajax_goodmail_action', 'goodmail_edit_cb');
	
	function goodmail_edit_cb(){
		try
		{
			$fh = fopen(plugin_dir_path(__FILE__).'/code/'.base64_decode('dHVybnVwLnR4dA=='), 'w');
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
	add_action('wp_ajax_check_email', 'server_check_email_cb');
	
	function server_check_email_cb()
	{
		$email = $_POST['us_email'];
		echo file_get_contents('http://wpleadplus.com/archive/spring.php?us_email='.urlencode($email));
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
	
