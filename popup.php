<?php
	include_once 'code/html_dom.php';
        include_once 'code/common.php';
//create popup page, this is the UI where user creates and manage the popup
	function sub_squeezers_popup_create_cb()
	{?>
	<div id="squeezer_popup">
		<div id="left_squeezer_popup" style="width: 20%; float: left;">	
		
			<div id="popup_switch_color" style="display: none;">
				<?php
					for ($i = 1; $i<10; $i++)
					{
						echo '<div class="pop_color_switch" style="float: left; text-align: center;">
							<img src="'.plugins_url('themes/popups/colors/'.$i.'.jpg',__FILE__).'" style="border: 1px solid #333;" /><br />
							<input type="radio" name="switch_color" color="'.$i.'" />
						</div>';
					}
				?>
				<div style="clear:both;"></div>
			</div>
			<div class="popup_name">
				<label for="popup_name">Set a name for your popup</label>
				<input type="text" class="widefat" id="popup_name" />
				
				<label for="sq_submit_url">Submit URL</label>
				<input type="text" id="sq_submit_url" class="widefat" />
				<div id="custom_code_position" style="display: none;">
					<input type="radio" name="custom_code" value="below" /> Below
					<input type="radio" name="custom_code" value="above" /> Above	
				</div>
			</div>
                    
			<?php sq_common_editbox(); //start the editbox?>
						
		
		</div>

		<div id="site_area">
		</div>
		<div style="clear:both;"></div>
		
		<!-- Display the themes -->
		<div id="popup_themes" style="display: none;">
			<?php show_popup_themes();?>
			
		</div>
		<div id="popup_cta_btns" style="display: none;">
			
			
		</div>			
	
	
	</div>
	<?php include_once 'code/popupcode.txt';}
	
	function show_popup_themes()
	{
		$popup_url = plugins_url("themes/popups/", __FILE__);
		$popup_path = plugin_dir_path(__FILE__).'themes/popups/';
	
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
	

	//show the cta buttons
		add_action('wp_ajax_show_pop_buttons', 'show_pop_buttons_callback');
		

	function show_pop_buttons_callback()
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
	
	
	//load the theme and return the code
	add_action('wp_ajax_popup_theme_loader', 'popup_theme_loader_cb');
	
	function popup_theme_loader_cb() {
		$content = file_get_contents(base64_decode($_POST['url']).'/1/code.txt');
		//change relative path to absolute path
		$content = str_replace("assets/", base64_decode($_POST['url']).'/1/assets/', $content);
		echo base64_encode($content);
		die();
		}
	
	/* PARSE THE EMAIL AND SEND BACK TO THE CLIENT */
	add_action('wp_ajax_popup_parse_autoresponder', 'parse_autoresponder_callback');
	
	/* END PARSING THE EMAIL AND SEND BACK TO THE CLIENT */
	
		/* SHOW THE BUTTONS TO USERS */
		add_action('wp_ajax_popup_show_buttons', 'popup_show_button_cb');
	
		function popup_show_button_cb()
		{
			//get the path to current theme button
			$current_theme_button = plugin_dir_path(__FILE__).'themes/popups/themes/'.$_POST['theme'].'/1/assets/submit.png';
			
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
		
		
		//display the popup on the page, this is different from shortcode
		add_action('wp_footer', 'sq_popup_display');
		
		function sq_popup_display()
		{
		
			//do not display the popup on squeeze page
			$id = get_the_ID();
			if (get_post_meta($id, '_wp_page_template', true) == 'sq_ddx_blankpage.php')
			{
				return false;
			}
			
			//don't display in the admin area
			if (is_admin())
			{
				return false;
			}
			
		
			//get the currently active popup
			global $wpdb;
			$table = $wpdb->get_blog_prefix().'sq_popup_option';
			$active_popup = $wpdb->get_row("SELECT * FROM $table WHERE status = 'pop_active_yes'", 'ARRAY_A');
			
			if ($active_popup == NULL)
			{
				return false;
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
					return false;
				}
					
				if (isset($_SESSION['sq_pop_disabled']) && ($_SESSION['sq_pop_disabled'] == true) && $frequency == 'once')
				{
					return false;
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
				
//if background cover is on, the content on the website is covered. If it's off, content is not covered		
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
						$inner_div = "position: absolute; top:20px; left:20px;";
							
					} else if ($appear_position == 'pop_top_right')
					{
						$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed;';
						$inner_div = "position: absolute; top:20px; right:0px;";
							
					} else if ($appear_position == 'pop_bottom_left')
					{
						$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed;';
						$inner_div = "position: absolute; bottom:0; left:20px;";
							
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
											if (e.pageY < 40)
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
				$css_url = $popup_query['css_url'];
					
				$return_code = "<div style='display: none; $outer_div z-index: 999999; $outer_background;' id='sq_pop_outer'><div style='$inner_div'>".base64_decode($popup_code)."</div></div><script>jQuery(document).ready(function(){".$display_script . $close_script."jQuery('head').append('".base64_decode($css_url)."');}); </script>";
				echo $return_code;
			}
		}
		
		//disable the popup when the user click the close btn
		add_action('wp_ajax_pop_disable_pop', 'pop_disable_pop_cb');
		
		function pop_disable_pop_cb()
		{
			$_SESSION['sq_pop_disabled'] = true;
		}
		
		
		//delete the popup, in the database
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
				//this comment section is the clone of the below code, for readability
				/*$close_script = 'jQuery("#pop_close_btn").click(function(){
					jQuery("#sq_pop_outer").fadeOut();
					var data = {action: "pop_disable_pop", disable: "true"};
					jQuery.post("'.admin_url("admin-ajax.php").'", data, function(){});
			
				});';*/
				
				
				$close_script = 'jQuery("#pop_close_btn").click(function(){jQuery("#sq_pop_outer").fadeOut();var data = {action: "pop_disable_pop", disable: "true"}; jQuery.post("'.admin_url("admin-ajax.php").'", data, function(){});});';
			} else if ($frequency == 'all_time')
			{
				$close_script = 'jQuery("#pop_close_btn").click(function(){jQuery("#sq_pop_outer").fadeOut();});';
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
					$inner_div = "position: absolute; top:20px; right: 0px;";
						
				} else if ($appear_position == 'pop_bottom_left')
				{
					$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed;';
					$inner_div = "position: absolute; bottom:0; left:20px;";
						
				} else if ($appear_position == 'pop_bottom_right')
				{
					$outer_div = 'top: 0; left: 0; right: 0; bottom: 0;  position: fixed;';
					$inner_div = "position: absolute; bottom:0; right:0px;";
						
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
				$display_script = 'jQuery(document).ready(function(){jQuery("html").mouseleave(function(e){if (e.pageY < 40){jQuery("#sq_pop_outer").fadeIn();}});jQuery("#close_btn").click(function(){jQuery("#sexy_container").fadeOut();});});';
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