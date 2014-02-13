<?php
	/* Template name: Blank Template
	
	*/

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script> var sq_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';</script>

<title><?php
	echo the_title();

	?></title>
	<script>
			//function to do open the url
		function sq_bgt_open_me(url, self, pop, event)
	   {
		   event.preventDefault();
		   if (pop == true)
		   {
			   //in case the button clicked is from the popup, we have two cases, one case is the popup submit button is a link
			   //and the second one is the submit button is a submit button of a form.
			   //Case one, submit button is a link
			   if (url != "") {
				   //send the disable message
				   jQuery.post(sq_ajax_url, {action: "pop_disable_pop", disable: "true"}, function(){
					   window.open(url, self);	
				   });	
			   } else //case 2: submit button is a submit button, send the disable message then submit
			   {
				   jQuery.post(sq_ajax_url, {action: "pop_disable_pop", disable: "true"}, function(){
					   jQuery('#pop_bgt_container form').submit();
				   });	
			   }
			   
			   
		   } else // if the button is not from a popup, open the link
		   {
			   window.open(url, self);
		   }
	   }
	</script>
		<?php
		if (get_option('sq_user_tracking_code') !== false) 
		{
			
			echo get_option('sq_user_tracking_code');
		}
		?>
	<?php while ( have_posts() ) : the_post(); ?>

					<?php the_content(); ?>

				<?php endwhile; // end of the loop. ?>

	<div style="text-align: center;">created by <span style="font-style: italic;">WP Lead Plus</span> - a <span style="color: red;">MUST HAVE</span> Lead Capture plugin</div>
</body></html>