<?php
	include_once 'const.php';

	function sub_squeezers_manage_widget_cb()
	{
		/**
		* 1. get the list of widgets	
		* 2. set the options
		*/

		//GET THE LIST OF WIDGETS
		?>
		<!-- get the list of created widgets -->
		<h2>Manage Widgets</h2>

		<div id="sq_bgt_manage_widget_left">

			<h3>1. Select the widget you want to manage</h3>

			<select name="sq_bgt_created_widget">
				<?php
					global $wpdb;
					$table = BGT_WIDGET_TABLE; // $wpdb->get_blog_prefix().'sq_widget_code';
					$query = "SELECT * FROM ".$table;
					$widget_db = $wpdb->get_results($query, "ARRAY_A");

					for ($i = 0; $i < count($widget_db); $i++)
					{
						echo '<option value="'.$widget_db[$i]['id'].'" css_url = "'.$widget_db[$i]['css_url'].'" full_widget_code="'.$widget_db[$i]['full_widget_code'].'" >'.$widget_db[$i]['name'].'</option>';
					}

				?>
			</select> <a href="#" id="sq_bgt_preview_button">View widget</a>

			

			<h3>2. Which position do you want to display the widget?</h3>
				<select name="sq_bgt_display_widget_position">
					<option value="beginning_post">At the beginning of posts/pages</option>
					<option value="end_post">At the end of posts/pages</option>
				</select>

			<h3>3. Activated this option?</h3>
				<select name="sq_bgt_activate_option">
					<option value="activated">Activated</option>
					<option value="deactivated">Deactivated</option>
				</select>
			<div style="display: none;">
			<h3>4. How the widget will behave?</h3>
				<select name="sq_bgt_behave_option">
					<option value="keep_displaying">Display all the time</option>
					<option value="only_once">Stop displaying after visitors submit info</option>
				</select>
			</div>
			<h3>4. Advanced options</h3>
				<p>This part is for advanced users only!</p>
				<textarea id="sq_bgt_widget_custom_code_above">Custom code above</textarea>
				<textarea id="sq_bgt_widget_custom_code_below">Custom code below</textarea>

			<h3>5. Where to display?</h3>
				<select name="sq_bgt_where_to_display">
					<option value="every_page_post">Display on all pages and posts</option>
					<option value="on_pages_only">Display on pages only</option>
					<option value="on_posts_only">Display on posts only</option>
				</select>
		</div>

		<div id="sq_bgt_manage_widget_right">

		</div>

		<div style="clear:both;"></div>

		<button id="sq_bgt_save_widget_options" class="button button-primary button-hero load-customize hide-if-no-customize">Save option</button>
		

		<h2>Current options</h2>
		<div id="sq_bgt_current_widget_options">

			<?php sq_bgt_show_current_widget_options(); ?>

		</div>

	<?php include_once 'common.txt'; }

	function sq_bgt_show_current_widget_options()
	{

		global $wpdb;
		$table = BGT_WIDGET_MANAGE_TABLE; // $wpdb->get_blog_prefix().'sq_widget_code';
		$query = "SELECT * FROM ".$table;
		$widget_db = $wpdb->get_results($query, "ARRAY_A");
		if (count($widget_db) == 0)
		{
			echo '<h3>You haven\'t set any option yet!</h3>';
			return;
		}
		echo '<table id="created_option_table">';
		echo '<tr> <th>Widget ID</th>  <th>Display Position</th>  <th>Status</th>  <th>Where to display</th>  <th>Code above</th>  <th>Code below</th> <th>Save option</th> <th>Get code</th> <th>Delete option</th> </tr>';
		$count = count ($widget_db);
		for ($i = 0; $i < $count; $i++)
		{ ?>

			<tr>
				<td><?php echo $widget_db[$i]['widget_id']; ?></td>
				<td class="sq_bgt_created_position">
					<select>
						<option value="beginning_post">At the beginning of posts/pages</option>
						<option value="end_post">At the end of posts/pages</option>
					</select>
					<span class="wid_selected_option" style="display: none;"><?php echo $widget_db[$i]['position']; ?></span>
				</td>	
				<td  class="sq_bgt_created_status">
					<select>
						<option value="activated">Activated</option>
						<option value="deactivated">Deactivated</option>
					</select>
					<span class="wid_selected_option" style="display: none;"><?php echo $widget_db[$i]['status']; ?></span>
				</td>
				<td style="display: none;" class="sq_bgt_created_behavior">
					<select>
						<option value="keep_displaying">Display all the time</option>
						<option value="only_once">Stop displaying after visitors submit info</option>
					</select>
					<span class="wid_selected_option" style="display: none;"><?php echo $widget_db[$i]['behavior']; ?></span>					
				</td>

				<td class="sq_bgt_created_where_to">
					<select>
						<option value="every_page_post">Display on all pages and posts</option>
						<option value="on_pages_only">Display on pages only</option>
						<option value="on_posts_only">Display on posts only</option>
					</select>
					<span class="wid_selected_option" style="display: none;"><?php echo $widget_db[$i]['where_to_display']; ?></span>					
				</td>				
				<td class="created_custom_code_above"><textarea><?php echo base64_decode($widget_db[$i]['custom_code_above']); ?></textarea></td>
				<td class="created_custom_code_below"><textarea><?php echo base64_decode($widget_db[$i]['custom_code_below']); ?></textarea></td>
				<td><button class="button button-primary manage_widget_save" option_id= "<?php echo $widget_db[$i]['id']; ?>">Save</button></td>
				<td><a href="#widget_shortcode" rel="lightcase" class="button button-primary manage_widget_get_short_code" option_id= "<?php echo $widget_db[$i]['id']; ?>">Get Code</a></td>
				<td><button class="button button-primary manage_widget_delete" option_id= "<?php echo $widget_db[$i]['id']; ?>">Delete</button></td>
			</tr>
		<?php }

		echo '</table>';
		echo '<div id="widget_shortcode" style="display: none;"></div>';

	}

	add_action('wp_ajax_sq_bgt_widget_save_option', 'sq_bgt_widget_save_option_cb');

	function sq_bgt_widget_save_option_cb()
	{
		//prepare data to insert into db
		global $wpdb;

		if (isset($_POST['option_id']))
		{
			$data = array (
					'position'			=> $_POST['position'],
					'status'			=> $_POST['status'],
					'behavior' 			=> $_POST['behavior'],
					'where_to_display'	=> $_POST['where_to_display'],
					'custom_code_above' => $_POST['custom_code_above'],
					'custom_code_below' => $_POST['custom_code_below']
				);	

			try 
			{
				$wpdb->update(BGT_WIDGET_MANAGE_TABLE, $data, array('id' => $_POST['option_id']));	
			} catch (Exception $e)
			{
				var_dump($e);
			}				
		} else
		{
			$data = array (
				'widget_id' 		=> $_POST['widget_id'],
				'position'			=> $_POST['position'],
				'status'			=> $_POST['status'],
				'behavior' 			=> $_POST['behavior'],
				'where_to_display'	=> $_POST['where_to_display'],
				'custom_code_above' => $_POST['custom_code_above'],
				'custom_code_below' => $_POST['custom_code_below']
			);
			try 
			{
				$wpdb->insert(BGT_WIDGET_MANAGE_TABLE, $data);	
			} catch (Exception $e)
			{
				var_dump($e);
			}				
		}

		die();
		
	}

	add_action('wp_ajax_sq_bgt_widget_delete_option', 'sq_bgt_widget_delete_option_cb');

	function sq_bgt_widget_delete_option_cb()
	{
		global $wpdb;

		$wpdb->delete(BGT_WIDGET_MANAGE_TABLE, array('id' => $_POST['option_id']));

		echo 'done';

		die();
	}