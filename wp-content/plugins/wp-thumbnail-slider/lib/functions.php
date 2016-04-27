<?php
// Add Functions Page
function registerwptPage() {
	add_submenu_page( 'edit.php?post_type=wptpost', 'WPT Slider Settings', 'Slider Settings', 'manage_options', 'wptpost', 'wptPageFunction' ); 
}
add_action('admin_menu', 'registerwptPage');

function wptPageFunction() {
	
	echo '<div class="newsWrap">';
		echo '<h1>WPT Slider Configurations</h1>';
?>
   <div id="nhtLeft">  
    <form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
		        
		<div class="inside">
        <h3>Insert your text & background color</h3>
        <p>WP Thumbnail Slider is a fully responsive wordpress image slider plugin with thumbnail. "PLEASE DON'T USE VARIOUS SIZE IMAGE ON SLIDER. USE SAME SIZE IMAGE". <br />
        Just copy and paste "<strong>if(function_exists('wptThumbnailSlider')){wPTPostLoop();}</strong> in the template code or <strong>[WPT-SLIDER]</strong> in the post/page" where you want to display imgae slider.</p>
        

			<table class="form-table">
				<tr>
					<th><label for="wpt_border_radius">Border Radius</label></th>
					<td><input type="text" name="wpt_border_radius" value="<?php $wpt_border_radius = get_option('wpt_border_radius'); if(!empty($wpt_border_radius)) {echo $wpt_border_radius;} else {echo "0";}?>">px;</td>
				</tr>
				<tr>
					<th><label for="wpt_border">Slider Border </label></th>
					<td><input type="text" name="wpt_border" value="<?php $wpt_border = get_option('wpt_border'); if(!empty($wpt_border)) {echo $wpt_border;} else {echo "0";}?>">px;</td>
				</tr>
				<tr>
					<th><label for="wpt_bg_color">Slider Border Color </label></th>
					<td><input type="text" name="wpt_bg_color" id="wpt_bg_color" value="<?php $wpt_bg_color = get_option('wpt_bg_color'); if(!empty($wpt_bg_color)) {echo $wpt_bg_color;} else {echo "#569625";}?>" class="color-picker nht_bg_color" /></td>
				</tr>				<tr>
					<th><label for="wpt_thumb_border">Thumbnail Border Color</label></th>
					<td><input type="text" name="wpt_thumb_border" id="wpt_thumb_border" value="<?php $wpt_thumb_border = get_option('wpt_thumb_border'); if(!empty($wpt_thumb_border)) {echo $wpt_thumb_border;} else {echo "#0a0a0a";}?>" class="color-picker wpt_thumb_border" /></td>
				</tr>
				<tr>
					<th><label for="wpt_thumb_hover">Thumbnail Border Hover</label></th>
					<td><input type="text" name="wpt_thumb_hover" id="wpt_thumb_hover" value="<?php $wpt_thumb_hover = get_option('wpt_thumb_hover'); if(!empty($wpt_thumb_hover)) {echo $wpt_thumb_hover;} else {echo "#ae66b0";}?>" class="color-picker wpt_thumb_hover" /></td>
				</tr>
				<tr>
				<th><label for="wpt_auto_play" class="wpt_auto_play">Auto Play</label></th> 
					<td><select name="wpt_auto_play" id="wpt_auto_play">
						<option value="wpt_play_on" <?php if( get_option('wpt_auto_play') == 'wpt_play_on'){ echo 'selected="selected"'; } ?>>On</option>
						<option value="wpt_play_off" <?php if( get_option('wpt_auto_play') == 'wpt_play_off'){ echo 'selected="selected"'; } ?>>Off</option>
					</select></td>
				</tr>
				<th><label for="wpt_developer_url" class="wpt_developer_url">Display Developers Link</label></th> 
					<td><select name="wpt_developer_url" id="wpt_developer_url">
						<option value="wpt_url_off" <?php if( get_option('wpt_developer_url') == 'wpt_url_off'){ echo 'selected="selected"'; } ?>>Hide</option>
						<option value="wpt_url_on" <?php if( get_option('wpt_developer_url') == 'wpt_url_on'){ echo 'selected="selected"'; } ?>>Display</option>
					</select></td>
				</tr>
				
		</table>
	
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="wpt_border_radius, wpt_border, wpt_thumb_size, wpt_bg_color, wpt_thumb_border, wpt_thumb_hover, wpt_auto_play, wpt_developer_url" />
		<p class="submit"><input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" class="button button-primary" /></p>
	</form>
    
  </div>
  </div>
 
  
  <div id="nhtRight">

  <div class="nhtWidget">
    <h3>About the Plugin</h3>
    <p><a href="https://wordpress.org/plugins/wp-thumbnail-slider/" target="_blank"><strong>News Headline Ticker</strong></a> is a wordpress plugin to show your recent news headline as typing style slider on your website!</p>
    <p>You can also make my day by submitting a positive review on <a href="https://wordpress.org/support/view/plugin-reviews/wp-thumbnail-slider" target="_blank"><strong>WordPress.org!</strong> <img src="<?php bloginfo('url' ); echo"/wp-content/plugins/news-headline-ticker/img/review.png"; ?>" alt="review" class="review"/></a></p>
    <p> With your help I can make Simple Fields even better! $5, $10, $50 â€“ any amount is fine! :)
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
      <input type="hidden" name="cmd" value="_s-xclick">
      <input type="hidden" name="hosted_button_id" value="82C6LTLMFLUFW">
      <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal â€“ The safer, easier way to pay online.">
      <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
    </form>
    </p>
    <div class="clrFix"></div>
  </div>
  
  <div class="nhtWidget">
    <div class="clrFix"></div>
    <h3>About the Author</h3>
    <p>My name is <strong><a href="https://www.upwork.com/freelancers/~01bf79370d989b2033" target="_blank">S M Hasibul Islam</a></strong> and I am a <strong>Web Developer, Expert WordPress Developer</strong>. I am regularly available for interesting freelance projects. If you ever need my help, do not hesitate to get in touch <a href="https://www.upwork.com/freelancers/~01bf79370d989b2033" target="_blank">me</a>.<br />
	  <strong>Skype:</strong> cse.hasib<br />
	  <strong>Email:</strong> cse.hasib@gmail.com<br />
	  <strong>Web:</strong> <a href="http://www.e2soft.com/"/>www.e2soft.com</a><br />
    <div class="clrFix"></div>
  </div>
  
 
  </div>
 <div class="clrFix"></div> 
  </div>
<div class="clrFix"></div>
<?php		
	echo '</div>';
}