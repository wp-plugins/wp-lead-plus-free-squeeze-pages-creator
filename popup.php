<?php
	include_once 'code/html_dom.php';
//create popup page
	function sub_squeezers_popup_create_cb()
	{?>
	<div id="squeezer_popup">
		<div id="left_squeezer_popup" style="width: 20%; float: left;">	
		
			<div id="popup_switch_color" style="display: none;">
				<div id="popup_color_changer"></div>
			</div>
			<div class="popup_name">
				<h4>Set a name for your popup</h4>
				<input type="text" class="widefat" id="popup_name" />
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
												
						  }	else if (selected.hasClass("editable"))
							{
								selected.html(editbox_html);	
							}			
							
					  });
				   }'));
					
					wp_editor("start editing here", "editbox", $settings);?>
		
		</div>

		<div id="popup_site_area">
		</div>
		<div style="clear:both;"></div>
		
		<!-- Display the themes -->
		<div id="popup_themes" style="display: none;">
			<?php show_popup_themes();?>
			
		</div>
		<div id="popup_cta_btns" style="display: none;"></div>			
	
	
	</div>
	<?php include_once 'code/popupcode.txt';}
	
	function show_popup_themes()
	{
		$popup_url = plugins_url("/themes/popups/", __FILE__);
		$popup_path = plugin_dir_path(__FILE__).'/themes/popups/';
	
		$thumbnail = scandir($popup_path.'thumbnail');
	
		for ($i=0; $i<count($thumbnail); $i++)
		{
		if (stripos($thumbnail[$i], '.jpg') !== FALSE)
		{
		$id = str_replace(".jpg", '', $thumbnail[$i]);
		echo '<div class="thumb">
				<a href="'.$popup_url.'thumbnail/'.$thumbnail[$i].'" rel="lightcase"><img src="'.$popup_url.'thumbnail/'.$thumbnail[$i].'" /></a>
					<input type="radio" name="popup_theme" id="'.$id.'" url="'.$popup_url."themes/$id".'" />
					</div>';
			}
		
		}
	
		echo '<div style="clear:both;"></div>';
	
	
	}
	
	//load the theme and return the code
	add_action('wp_ajax_popup_theme_loader', 'popup_theme_loader_cb');
	
	function popup_theme_loader_cb() {
		$content = file_get_contents(base64_decode($_POST['url']).'/code.txt');
		echo base64_encode($content);
		die();
		}
	
	/* PARSE THE EMAIL AND SEND BACK TO THE CLIENT */
	add_action('wp_ajax_popup_parse_autoresponder', 'popup_parse_autoresponder_callback');
	
	function popup_parse_autoresponder_callback()
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
		$action_url = trim($action_url[0]);
	
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
	
		/* SHOW THE BUTTONS TO USERS */
		add_action('wp_ajax_popup_show_buttons', 'popup_show_button_cb');
	
		function popup_show_button_cb()
		{
		$buttons = scandir(plugin_dir_path(__FILE__).'themes/popups/buttons/'.$_POST['size']);
		
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
		
		//save the code to db
		add_action('wp_ajax_popup_save_to_db', 'popup_save_to_db_cb');
		
		function popup_save_to_db_cb() {
			$content = $_POST['popup_code'];
			$css_url = $_POST['css_url'];
			$popup_id = $_POST['popup_id'];
			$popup_name = $_POST['name'];
			
			$data = array(
				'popup_id' => $popup_id,
				'css_url' => $css_url,
				'code' => $content,
				'name' => $popup_name	
			);

			$update_data = array(
				'css_url' => $css_url,
				'code' => $content,
				'name' => $popup_name	
			);
			
			try {

				//global the wpdb
				global $wpdb;
				$popup_table = $wpdb->get_blog_prefix().'sq_popup_code';
				//test if the id exists already
				$test_id = $wpdb->get_row("SELECT * FROM $popup_table WHERE popup_id = '$popup_id'");
				if ($test_id == NULL)//in case the row does not exists, insert new row
				{
 					$wpdb->insert($popup_table, $data);
					echo "popup created";
					
				} else 
				{
					$wpdb->update($popup_table, $update_data, array('popup_id' => $popup_id));
					echo "popup updated!";
				}
				
			} catch (Exception $e)
			{
				echo "something wrong";
			}
			
			die();
			
		}
		
		
		//MANAGE POPUP FUNCTIONS
		//show the UI of popup manage
		function sub_squeezers_popup_manage_cb()
		{
			//get the id from  the db, display in a list. radio button to preview. Delete option
			?>
			<div id="popup_manage_container">
				<!-- First row, where user selects the popup -->
				<div id="popup_manage_first_row" class="popup_manage_row" >
					<div id="popup_manage_left" style="float:left; width: 40%;">
					<h4>Your popups</h4>
					<?php popup_manage_show_pop();?>					
					</div>
					<div id="popup_manage_right" style="width: width: 58%;">
					
					</div>
					<div style="clear: both;"></div>
				</div>
				
			
			
			<div id="popup_manage_second_row" class="popup_manage_row">
			<h4>Position to appear</h4>
			<ul>
				<li><input type="radio" name="display_pos"  id="pop_top_left"/> Top Left</li>
				<li><input type="radio" name="display_pos"  id="pop_top_right"/> Top Right</li>
				<li><input type="radio" name="display_pos"  id="pop_bottom_left"/> Bottom Left</li>
				<li><input type="radio" name="display_pos"  id="pop_bottom_right"/> Bottom Right</li>
				<li><input type="radio" name="display_pos"  id="pop_center"/> Center</li>
				
			</ul>
			</div>
			
			<div id="popup_manage_3rd_row" class="popup_manage_row">
			<h4>How to appear</h4>
				<ul>
					<li><input type="radio" name="how_appear" id="pop_on_exit"/> On Exit Intention</li>
					<li><input type="radio" name="how_appear" id="pop_timer" /> Timer</li>
				</ul>
				<div id="timer_div" style="display:none;">Set your time (in seconds)<br />
				<input type="text" id="pop_timer_time" />
				</div>
				
			</div>
			
			<div id="popup_manage_4th_row" class="popup_manage_row">
			<h4>Background Color</h4>
			<ul>
					<li><input type="radio" name="pop_bg_color" id="pop_bg_white"/> White</li>
					<li><input type="radio" name="pop_bg_color" id="pop_bg_black"/> Black</li>
					<li><input type="radio" name="pop_bg_color" id="pop_bg_transparent_black"/> Transparent Black</li>
					<li><input type="radio" name="pop_bg_color" id="pop_bg_transparent"/> Transparent</li>
				</ul>	
			</div>
			
			<div id="popup_manage_5th_row" class="popup_manage_row">
				<h4>Where to appear</h4>
				<ul>
					<li><input type="radio" name="display_where" id="pop_display_all"/> Every Page/Post</li>
					<li><input type="radio" name="display_where" id="pop_display_particular"/> On particular posts</li>
				</ul>			
			</div>
			
			<div id="popup_manage_6th_row" class="popup_manage_row">
				<h4>Cover all background?</h4>
				<ul>
					<li><input type="radio" name="pop_cover_bg" id="pop_cover_yes"/> Yes</li>
					<li><input type="radio" name="pop_cover_bg" id="pop_cover_no"/> No</li>
				</ul>
			</div>
			
			<div id="popup_manage_7h_row" class="popup_manage_row">
				<h4>Activate this option? (this will deactivate current active option)</h4>
				<ul>
					<li><input type="radio" name="pop_active" id="pop_active_yes"/> Yes</li>
					<li><input type="radio" name="pop_active" id="pop_active_no"/> No</li>
				</ul>
			</div>
			
			<div id="popup_manage_8th_row" class="popup_manage_row">
				<h4>Display frequency (once or repeatedly)</h4>
				<ul>
					<li><input type="radio" name="pop_display_freq" id="once"/> Once</li>
					<li><input type="radio" name="pop_display_freq" id="all_time"/> All the time (your users may get angry)</li>
					
				</ul>
			</div>
	
			<div id="popup_manage_9th_row" class="popup_manage_row">
				<input type="button" id="save_manage_popup_option" class="button button-primary" value="Save Option" />
			</div>
			
			<div id="popup_manage_10th_row" class="popup_manage_row">
			<h4>Current Options</h4>
				<?php 
				//display current popup options
				global $wpdb;
				$table = $wpdb->get_blog_prefix().'sq_popup_option';
				
				//get all the options
				$pop_options = $wpdb->get_results("SELECT * FROM $table", 'ARRAY_A');
				//display the options
				?>
				<table id="pop_listing_options" style="border: 1px solid;">
				<tr>
					<th>Preview</th>
					<th>Appear Position</th>
					<th>Appear Behavior</th>
					<th>Background Color</th>
					<th>Where To Display</th>
					<th>Cover All Background?</th>
					<th>Delay</th>
					<th>Status</th>
					<th>Once or All</th>
					<th>Delete</th>
					<th>Save</th>
					<th>Get Shortcode</th>
					
				</tr>
				<?php for ($i=0; $i < count($pop_options); $i++)
				{?>
					<div>
						<tr>
							<td>
								<a class="listing_view_popup" href="#pop_theme_preview" rel="lightcase" op_id="<?php echo $pop_options[$i]['id']; ?>" pop_id="<?php echo $pop_options[$i]['popup_id']; ?>">View</a>
							</td>
							<td>
								<select name="listing_appear_position">
									<option value="pop_top_left">Top Left</option>
									<option value="pop_top_right">Top Right</option>
									<option value="pop_bottom_left">Bottom Left</option>
									<option value="pop_bottom_right">Bottom Right</option>
									<option value="pop_center">Center</option>
								</select>
								<span class="pop_selected_option" style="display: none;"><?php echo $pop_options[$i]['appear_position'];?></span>
							</td>
							
							<td>
								<select name="listing_appear_behavior">
									<option value="pop_on_exit">On Exit Behavior</option>
									<option value="pop_timer">Timer</option>
								</select>
								<span class="pop_selected_option" style="display: none;"><?php echo $pop_options[$i]['appear_behavior'];?></span>
							</td>
							
							<td>
								<select name="listing_background_color">
									<option value="pop_bg_white">White</option>
									<option value="pop_bg_black">Black</option>
									<option value="pop_bg_transparent_black">Transparent Black</option>
									<option value="pop_bg_transparent">Transparent</option>
								</select>
								<span class="pop_selected_option" style="display: none;"><?php echo $pop_options[$i]['background_color'];?></span>
							</td>
							
							<td>
								<select name="listing_display_area">
									<option value="pop_display_all">All Post/Page</option>
									<option value="pop_display_particular">On Particular Post</option>
								</select>
								<span class="pop_selected_option" style="display: none;"><?php echo $pop_options[$i]['display_area'];?></span>
							</td>
							
							<td>
								<select name="listing_background_cover">
									<option value="pop_cover_yes">Yes</option>
									<option value="pop_cover_no">No</option>
								</select>
								<span class="pop_selected_option" style="display: none;"><?php echo $pop_options[$i]['background_cover'];?></span>
							</td>
							
							<td>
								<input type="text" class="pop_listing_delay" value="<?php echo $pop_options[$i]['delay'];?>" />
							</td>
							
							<td>
								<select name="listing_status">
									<option value="pop_active_yes">Active</option>
									<option value="pop_active_no">Not Active</option>

								</select>
								<span class="pop_selected_option" style="display: none;"><?php echo $pop_options[$i]['status'];?></span>
							</td>
							
							<td>
								<select name="listing_frequency">
									<option value="once">Once</option>
									<option value="all_time">All Time</option>
								</select>
								<span class="pop_selected_option" style="display: none;"><?php echo $pop_options[$i]['frequency'];?></span>
							</td>
							
							<td><a href="" class="button button-primary pop_delete_btn">Delete</a></td>
							<td><a href="" class="button button-primary pop_save_btn">Save</a></td>
							<td><a href="#pop_shortcode" class="button button-primary pop_getcode_btn" rel="lightcase">Get Code</a></td>
						</tr>
					</div>
				<?php }
				
				?>
				</table>
				<!-- PREVIEW THE THEME/VIEW THE SHORTCODE -->
				<div id="pop_shortcode" style="display: none;"></div>
				<div id="pop_theme_preview" style="display: none;"></div>
				
			</div>
			<br /><br />
			</div>
		<?php }

		//function to display available popup in the db
		function popup_manage_show_pop()
		{
			//get the available popup in the db
			global $wpdb;
			
			//get the table
			$popup_table = $wpdb->get_blog_prefix().'sq_popup_code';
			
			//get the id
			$array_id = $wpdb->get_results("SELECT popup_id, name FROM $popup_table", 'ARRAY_A');
			echo "<ul>";
			for ($i=0; $i<count($array_id); $i++)
			{
				//set a generic name
				if (trim($array_id[$i]['name']) == "")
				{
					$array_id[$i]['name'] = "You didn't set a name for this";
				}
				?>
				<li><input type="radio" name="popup_listing" class="popup_manage_listing" pop_id="<?php echo $array_id[$i]['popup_id']?>"> <?php echo $array_id[$i]['name']?>  <a href="" class="pop_manage_delete_pop" style="margin-left: 30px;">Delete</a> <a href="#pop_theme_preview" rel="lightcase" class="pop_manage_preview_pop" pop_id="<?php echo $array_id[$i]['popup_id']?>" style="margin-left: 30px;">View</a></li>
			<?php }
			echo "</ul>";
			
		}
		
		//function to show the preview
		
		add_action('wp_ajax_popup_manage_show_preview', 'popup_manage_show_preview_cb');
		
		function popup_manage_show_preview_cb()
		{
			$popup_id = $_POST['popup_id'];
			
			//get data from db
			global $wpdb;
			
			//get the table
			$popup_table = $wpdb->get_blog_prefix().'sq_popup_code';
			
			$popup = $wpdb->get_results("SELECT * FROM $popup_table WHERE popup_id = '$popup_id'", 'ARRAY_A');
			
			$return_data = array();
			$return_data['code'] = $popup[0]['code'];
			$return_data['css_url'] = $popup[0]['css_url'];
			
			echo json_encode($return_data);
			die();
		}
		
		
		//save the popup option
		add_action('wp_ajax_popup_save_option', 'popup_save_option_cb');
		
		function popup_save_option_cb()
		{

			global $wpdb;
			
			$popup_id = $_POST['selected_popup'];
			$appear_position = $_POST['appear_position'];
			$appear_behavior = $_POST['appear_behavior'];
			$bg_color = $_POST['bg_color'];
			$bg_cover = $_POST['bg_cover'];
			$appear_where = $_POST['appear_where'];
			$active = $_POST['active'];
			$delay = $_POST['delay'];
			$frequency = $_POST['frequency'];

			
		
			$data = array(
				'popup_id' => $popup_id,
				'appear_position' => $appear_position,
				'appear_behavior' => $appear_behavior,
				'background_color' => $bg_color,
				'display_area' => $appear_where,
				'background_cover' => $bg_cover,
				'status' => $active,
				'delay' => $delay,
				'frequency' => $frequency
			);
			
			try {

				//if $active == yes, disable all other options
				if ($active == 'pop_active_yes')
				{
					$table = $wpdb->get_blog_prefix().'sq_popup_option';
					$update_data = array('status' => 'pop_active_no');
					$update_where = array('status' => 'pop_active_yes');
					$wpdb->update($table, $update_data, $update_where);
				}
				
				//insert the new data into the db
				$table = $wpdb->get_blog_prefix().'sq_popup_option';

				
				$wpdb->insert($table, $data);
				echo "Done";
			} catch (Exception $e)
			{
				echo "something wrong!!!";
			}
			
			die();

		}
		
		
		//save/update the popup listing option popup_save_listing_option
		add_action('wp_ajax_popup_save_listing_option', 'popup_save_listing_option_cb');
		
		function popup_save_listing_option_cb()
		{
			global $wpdb;
				
			$op_id = $_POST['op_id'];
			$appear_position = $_POST['appear_position'];
			$appear_behavior = $_POST['appear_behavior'];
			$background_color = $_POST['background_color'];
			$background_cover = $_POST['background_cover'];
			$display_area = $_POST['display_area'];
			$active = $_POST['active'];
			$delay = $_POST['delay'];
			$frequency = $_POST['frequency'];
			
				
			
			$data = array(
					'appear_position' => $appear_position,
					'appear_behavior' => $appear_behavior,
					'background_color' => $background_color,
					'display_area' => $display_area,
					'background_cover' => $background_cover,
					'status' => $active,
					'delay' => $delay,
					'frequency' => $frequency,
					'status' => $active
			);
				
			try {
			
				//if $active == yes, disable all other options
				if ($active == 'pop_active_yes')
				{
					$table = $wpdb->get_blog_prefix().'sq_popup_option';
					$update_data = array('status' => 'pop_active_no');
					$update_where = array('status' => 'pop_active_yes');
					$wpdb->update($table, $update_data, $update_where);
				}
			
				//insert the new data into the db
				$table = $wpdb->get_blog_prefix().'sq_popup_option';
			
			
				$wpdb->update($table, $data, array('id' => $op_id));
				echo "Done";
			} catch (Exception $e)
			{
				echo "something wrong!!!";
			}
				
			die();			
		}
		
		
		//display the popup
		
		add_filter('the_content', 'sq_popup_display');
		
		function sq_popup_display($content)
		{
			//get the currently active popup
			global $wpdb;
			$table = $wpdb->get_blog_prefix().'sq_popup_option';
			$active_popup = $wpdb->get_row("SELECT * FROM $table WHERE status = 'pop_active_yes'", 'ARRAY_A');
			
			if ($active_popup == NULL)
			{
				return $content;
			} else 
			{
				$popup_id = $active_popup['popup_id'];
				$appear_position  = $active_popup['appear_position'];
				$appear_behavior  = $active_popup['appear_behavior'];
				$background_color  = $active_popup['background_color'];
				$display_area  = $active_popup['display_area'];
				$background_cover = $active_popup['background_cover'];
				$delay = $active_popup['delay'];
				$frequency = $active_popup['frequency'];
					
				//if the user only wants it to display on particular post, no need to execute more
				if ($display_area == 'pop_display_particular')
				{
					return $content;
				}
					
				if (isset($_SESSION['sq_pop_disabled']) && ($_SESSION['sq_pop_disabled'] == true) && $frequency == 'once')
				{
					return $content;
				}
					
				//set the behavior of the close button, this relate to the frequency. if frequency == once, closing button will set the session value to true
				$close_script = "";
				if ($frequency == 'once')
				{
					$close_script = 'jQuery("#pop_close_btn").click(function(){
					jQuery("#sq_pop_outer").fadeOut();
					var data = {action: "pop_disable_pop", disable: "true"};
					jQuery.post("'.admin_url("admin-ajax.php").'", data, function(){});
				
				});';
				} else if ($frequency == 'all_time')
				{
					$close_script = 'jQuery("#pop_close_btn").click(function(){
					jQuery("#sq_pop_outer").fadeOut();
				
				});';
				}
					
				//declare some variables to insert to the return code
				$outer_div = "";
				$inner_div = "";
					
				if ($background_cover == 'pop_cover_no')
				{
					if ($appear_position == 'pop_top_left')
					{
						$outer_div = 'top: 10px; left: 0; position: fixed;';
							
					} else if ($appear_position == 'pop_top_right')
					{
						$outer_div = 'top: 10px; right: 10px; position: fixed;';
							
					} else if ($appear_position == 'pop_bottom_left')
					{
						$outer_div = 'bottom: 0; left: 0; position: fixed;';
							
					} else if ($appear_position == 'pop_bottom_right')
					{
						$outer_div = 'bottom: 0; right: 10px; position: fixed;';
							
					} else if ($appear_position == 'pop_center')
					{
						$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed; text-align: center;';
						$inner_div = 'margin-top: 200px; position: relative;';
						
					}
				} else if ($background_cover == 'pop_cover_yes')
				{
					if ($appear_position == 'pop_top_left')
					{
						$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed;';
						$inner_div = "position: absolute; top:0; left:0;";
							
					} else if ($appear_position == 'pop_top_right')
					{
						$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed;';
						$inner_div = "position: absolute; top:0px; right:10px;";
							
					} else if ($appear_position == 'pop_bottom_left')
					{
						$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed;';
						$inner_div = "position: absolute; bottom:0; left:0;";
							
					} else if ($appear_position == 'pop_bottom_right')
					{
						$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed;';
						$inner_div = "position: absolute; bottom:0; right:10px;";
							
					} else if ($appear_position == 'pop_center')
					{
						$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed; text-align: center;';
						$inner_div = 'margin-top: 200px; position: relative;';
					}
				}
					
				//appear behavior
				$display_script = "";
				if ($appear_behavior == 'pop_on_exit')
				{
					$display_script = '
									jQuery(document).ready(function(){
										jQuery("html").mouseleave(function(e){
											if (e.pageY < 10)
											{
												jQuery("#sq_pop_outer").fadeIn();
											}
										});
				
									  jQuery("#close_btn").click(function(){
											jQuery("#sexy_container").fadeOut();
										});
									});';
				} else if ($appear_behavior == 'pop_timer')
				{
					$delay = $delay*1000;
					$display_script = 'setTimeout(function(){jQuery("#sq_pop_outer").fadeIn();}, '.$delay.');';
				}
					
				//configure the background option
				if($background_color == 'pop_bg_black')
				{
					$outer_background = "background: black;";
				
				} else if ($background_color == 'pop_bg_white')
				{
					$outer_background = "background: white;";
				
				} else if ($background_color == 'pop_bg_transparent_black')
				{
					$outer_background = "background: url(".plugins_url("", __FILE__).'/themes/common/trans_bg.png'.");";
				
				} else if ($background_color == 'pop_bg_transparent')
				{
					$outer_background = "background: transparent;";
				}
					
					
				//get the popup code
				$popup_table = $wpdb->get_blog_prefix().'sq_popup_code';
				$popup_query = $wpdb->get_row("SELECT code, css_url FROM $popup_table WHERE popup_id = $popup_id", 'ARRAY_A');
				$popup_code = $popup_query['code'];
				$css_url = base64_decode($popup_query['css_url']);
					
				$return_code = "<div style='display: none; $outer_div z-index: 999999; $outer_background' id='sq_pop_outer'><div style='$inner_div'>".base64_decode($popup_code)."</div></div><script>$display_script $close_script jQuery('head').append('$css_url');</script>";
				return $content.$return_code;
			}
			

		}
		
		//disable the popup when the user click the close btn
		add_action('wp_ajax_pop_disable_pop', 'pop_disable_pop_cb');
		
		function pop_disable_pop_cb()
		{
			$_SESSION['sq_pop_disabled'] = true;
		}
		
		
		//delete the popup
		add_action('wp_ajax_pop_delete_pop', 'pop_delete_pop_cb');
		function pop_delete_pop_cb()
		{
			$popup_id = $_POST['popup_id'];
			//perform the delete
			global $wpdb;
			$table = $wpdb->get_blog_prefix().'sq_popup_code';
			$popup_table = $wpdb->get_blog_prefix().'sq_popup_option';
			$wpdb->get_results("DELETE FROM $table WHERE popup_id = $popup_id");
			//delete popup in the popup option too
			$wpdb->get_results("DELETE FROM $popup_table WHERE popup_id = $popup_id");
			
			die();
		}
		
		//delete the option pop_delete_pop_option
		add_action('wp_ajax_pop_delete_pop_option', 'pop_delete_pop_option_cb');
		function pop_delete_pop_option_cb()
		{
			$op_id = $_POST['op_id'];
			//perform the delete
			global $wpdb;
			$table = $wpdb->get_blog_prefix().'sq_popup_option';
			$wpdb->get_results("DELETE FROM $table WHERE id = $op_id");
				
			die();
		}		
		
 		//get popup shortcode
		add_shortcode('sq_pop_shortcode', 'pop_gen_shortcode');
		function pop_gen_shortcode($atts)
		{
			extract($atts); 
			
			//get the currently active popup
			global $wpdb;
			$table = $wpdb->get_blog_prefix().'sq_popup_option';
			$active_popup = $wpdb->get_row("SELECT * FROM $table WHERE popup_id = $popup_id", 'ARRAY_A');
			
			if ($active_popup == NULL)
			{
				return;
			}
				
			//set the behavior of the close button, this relate to the frequency. if frequency == once, closing button will set the session value to true
			$close_script = "";
			if ($frequency == 'once')
			{
				$close_script = 'jQuery("#pop_close_btn").click(function(){
					jQuery("#sq_pop_outer").fadeOut();
					var data = {action: "pop_disable_pop", disable: "true"};
					jQuery.post("'.admin_url("admin-ajax.php").'", data, function(){});
			
				});';
			} else if ($frequency == 'all_time')
			{
				$close_script = 'jQuery("#pop_close_btn").click(function(){
					jQuery("#sq_pop_outer").fadeOut();
			
				});';
			}
				
			//declare some variables to insert to the return code
			$outer_div = "";
			$inner_div = "";
				
			if ($background_cover == 'pop_cover_no')
			{
				if ($appear_position == 'pop_top_left')
				{
					$outer_div = 'top: 15px; left: 0px; position: fixed;';
						
				} else if ($appear_position == 'pop_top_right')
				{
					$outer_div = 'top: 15px; right: 15px; position: fixed;';
						
				} else if ($appear_position == 'pop_bottom_left')
				{
					$outer_div = 'bottom: 0; left: 0; position: fixed;';
						
				} else if ($appear_position == 'pop_bottom_right')
				{
					$outer_div = 'bottom: 0; right: 15px; position: fixed;';
						
				} else if ($appear_position == 'pop_center')
				{
					$outer_div = 'margin: auto; text-align: center;';						
				}
			} else if ($background_cover == 'pop_cover_yes')
			{
				if ($appear_position == 'pop_top_left')
				{
					$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed;';
					$inner_div = "position: absolute; top:10px; left:0;";
						
				} else if ($appear_position == 'pop_top_right')
				{
					$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed;';
					$inner_div = "position: absolute; top:10px; right:10px;";
						
				} else if ($appear_position == 'pop_bottom_left')
				{
					$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed;';
					$inner_div = "position: absolute; bottom:0; left:0;";
						
				} else if ($appear_position == 'pop_bottom_right')
				{
					$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed;';
					$inner_div = "position: absolute; bottom:0; right:10px;";
						
				} else if ($appear_position == 'pop_center')
				{
					$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed; text-align: center;';
					$inner_div = 'margin-top: 200px; position: relative;';
				}
			}
				
			//appear behavior
			$display_script = "";
			if ($appear_behavior == 'pop_on_exit')
			{
				$display_script = '
									jQuery(document).ready(function(){
										jQuery("html").mouseleave(function(e){
											if (e.pageY < 10)
											{
												jQuery("#sq_pop_outer").fadeIn();
											}
										});
			
									  jQuery("#close_btn").click(function(){
											jQuery("#sexy_container").fadeOut();
										});
									});';
			} else if ($appear_behavior == 'pop_timer')
			{
				$delay = $delay*1000;
				$display_script = 'setTimeout(function(){jQuery("#sq_pop_outer").fadeIn();}, '.$delay.');';
			}
				
			//configure the background option
			if($background_color == 'pop_bg_black')
			{
				$outer_background = "background: black;";
			
			} else if ($background_color == 'pop_bg_white')
			{
				$outer_background = "background: white;";
			
			} else if ($background_color == 'pop_bg_transparent_black')
			{
				$outer_background = "background: url(".plugins_url("", __FILE__).'/themes/common/trans_bg.png'.");";
			
			} else if ($background_color == 'pop_bg_transparent')
			{
				$outer_background = "background: transparent;";
			}
				
				
			//get the popup code
			$popup_table = $wpdb->get_blog_prefix().'sq_popup_code';
			$popup_query = $wpdb->get_row("SELECT code, css_url FROM $popup_table WHERE popup_id = $popup_id", 'ARRAY_A');
			$popup_code = $popup_query['code'];
			$css_url = base64_decode($popup_query['css_url']);
				
			$return_code = "<div style='display: none; $outer_div z-index: 999999; $outer_background' id='sq_pop_outer'><div style='$inner_div'>".base64_decode($popup_code)."</div></div><script>$display_script $close_script jQuery('head').append('$css_url');</script>";
			return $return_code;
		}
		
		
		
		

		
		
		
		
		
		
		
		
		
		
		
		
		