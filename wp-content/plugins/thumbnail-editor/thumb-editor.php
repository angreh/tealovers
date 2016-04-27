<?php
/*
Plugin Name: Thumbnail Editor
Plugin URI: http://avifoujdar.wordpress.com/category/my-wp-plugins/
Description: Manually <strong>Crop</strong> and <strong>Resize</strong> thumbnail images that are uploaded in the Media section.
Version: 2.0.0
Author: avimegladon
Author URI: http://avifoujdar.wordpress.com/
*/

/**
	  |||||   
	<(`0_0`)> 	
	()(afo)()
	  ()-()
**/


include_once dirname( __FILE__ ) . '/thumb-editor-process.php';
include_once dirname( __FILE__ ) . '/thumb-editor-function.php';

class thumbnail_editor_setup {
	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'additional_scripts' ) );
		add_filter( 'media_row_actions', array( $this, 'afo_media_row_actions' ), 10, 3); 
		add_filter( 'attachment_fields_to_edit', array( $this, 'media_thumbnail_edit_link' ), 10, 2 );
	}
	
	public function additional_scripts(){
		wp_enqueue_script( 'jquery.Jcrop', plugins_url( 'js/jquery.Jcrop.js' , __FILE__ ) );
		wp_enqueue_style( 'jquery.Jcrop', plugins_url( 'css/jquery.Jcrop.css' , __FILE__ ) );
		wp_enqueue_style( 'jquery-ui', plugins_url( 'css/jquery-ui.css' , __FILE__ ) );
		wp_enqueue_script( 'jquery-ui-resizable' );
	}
	
	function afo_media_row_actions($actions, $post, $detached){
		$actions['thued'] = '<a href="options-general.php?page=thumbnail_editor_setup_data&att_id='.$post->ID.'" title="Crop Thumbnail">Crop Thumbnail</a>';
		return $actions;
	}

	function media_thumbnail_edit_link( $form_fields, $post ) {
		$form_fields['th-editor-link'] = array(
			'label' => '',
			'input' => 'html',
			'html'  => '<a href="options-general.php?page=thumbnail_editor_setup_data&att_id='.$post->ID.'" title="Crop Thumbnail">Crop Thumbnail</a>',
		);
		return $form_fields;
	}
	
	function admin_menu () {
		add_options_page( 'Thumbnail Editor','Thumbnail Editor','manage_options','thumbnail_editor_setup_data', array( $this, 'settings_page' ) );
	}
	
	function  settings_page () {
		if(isset($GLOBALS['msg']))
        echo '<div class="updated notice notice-success is-dismissible" id="message"><p>'.$GLOBALS['msg'].'</p></div>';
		
		$att_id = '';
		$this->thumbnail_editor_pro_add();
		$this->help_support();
		$this->donate();
		
		if(isset($_REQUEST['att_id']))
		$att_id = $_REQUEST['att_id'];
		
		if($att_id == ''){
			echo '<p>Goto <a href="upload.php"><strong>Media Library</strong></a> and select <strong>Crop Thumbnail</strong> link to modify image thumbnails.</p>';
			return;
		}
		
		$full_image = wp_get_attachment_image_src( $att_id, 'full' );
		?>
        <script type="text/javascript">
			var jcrop_api;
			var crop = false;
			var rezize = false;
			
		   function ImageEditor(e){
			   var xsize = jQuery('#target').width();
			   var ysize = jQuery('#target').height();
			  			   
			   if(e == 'crop'){
				 if(rezize){
				 	jQuery( "#target" ).resizable( "destroy" );
				 }
				 crop = true;
			   	 jQuery('#target').Jcrop({
				  onChange:   showCoords,
				  onSelect:   showCoords,
				  onRelease:  clearCoords,
				  // aspectRatio: xsize / ysize
				  },function(){
					  jcrop_api = this;
				  });
			   } else {
				   if(crop){
					   jcrop_api.destroy();
				   }
				 rezize = true;
			   	 jQuery( "#target" ).resizable({
					aspectRatio: xsize / ysize,
					resize: function( event, ui ) {
						jQuery('#rw').val(ui.size.width);
            			jQuery('#rh').val(ui.size.height);
					}
				});
			   }
		   }
		   
		   function showCoords(c) {
            jQuery('#x1').val(c.x);
            jQuery('#y1').val(c.y);
            jQuery('#x2').val(c.x2);
            jQuery('#y2').val(c.y2);
            jQuery('#w').val(c.w);
            jQuery('#h').val(c.h);
          }
		  
		   function clearCoords(){
            jQuery('#x1').val('');
            jQuery('#y1').val('');
            jQuery('#x2').val('');
            jQuery('#y2').val('');
            jQuery('#w').val('');
            jQuery('#h').val('');
          }
  
        </script>
		<form id="coords" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="th_edit_option" id="th_edit_option" value="imageCrop">
        <?php wp_nonce_field( 'th_edit_save_action', 'th_edit_save_action_field' ); ?>
        <table width="100%" border="0" style="background-color:#fff; border:1px solid #CCCCCC; margin:2px; padding:5px;">
          <tbody>
          <tr>
              <td> Crop / Resize 
              <select name="crop_or_resize" onChange="ImageEditor(this.value);" required>
              		<option value=""> - </option>
					<option value="crop">Crop (Drag inside the image to crop)</option>
                    <option value="resize">Resize (Drag image from bottom/right corners to resize)</option>
            	</select><i>Select a option to start editing.</i>
              </td>
            </tr>
            <tr>
              <td><img src="<?php echo $full_image[0];?>" id="target" style="border:1px dashed #CCCCCC;"></td>
            </tr>
            <tr>
              <td>
                <input type="hidden" name="att_id" value="<?php echo $att_id;?>">
                <div class="inline-labels">
                <input type="hidden" size="4" id="x1" name="x1" />
                <input type="hidden" size="4" id="y1" name="y1" />
                <input type="hidden" size="4" id="x2" name="x2" />
                <input type="hidden" size="4" id="y2" name="y2" />
                <p>
                <label>Crop Width <input type="text" size="4" id="w" name="w" readonly /></label>
                <label>Crop Height <input type="text" size="4" id="h" name="h" readonly /></label>
                </p>
                <p>
                <label>Resize Width <input type="text" size="4" id="rw" name="rw" readonly /></label>
                <label>Resize Height <input type="text" size="4" id="rh" name="rh" readonly /></label>
                </p>
                <p>
                <select name="crop_type">
					<?php
                    $sizes = get_intermediate_image_sizes();
                    foreach($sizes as $key => $value){
						$s = $this->get_image_sizes($value);
                        echo '<option value="'.$value.'">'.$value.'-'.$s['width'].'x'.$s['height'].'</option>';
                    }
                    ?>
            	</select>
                <input type="submit" name="submit" value="Save" class="button button-primary">
                </p>
                </div>
              </td>
            </tr>
            <tr>
            <td><hr>Use <strong>Ctrl + F5</strong> to view updated image</td>
            </tr>
            
            
            
            <?php
			$sizes = get_intermediate_image_sizes();
			foreach($sizes as $key => $value){
				$full_image = wp_get_attachment_image_src( $att_id, $value );
				echo '<tr>
            		<td>
					<div style="border:1px dashed #ccc; padding:5px;">
						<p><strong>'.$value.'</strong></p>
						<img src="'.$full_image[0].'">
						<p><strong>Usage</strong> <br>
						<span style="color:#0000ff;">get_thumb_image_src( "attachment id", "thumbnail type" ); </span>
						<br>
						<br>
						<strong>Example</strong> 
						<br>
						// this will output the image src <br>
						<span style="color:#0000ff;">echo get_thumb_image_src( "'.$att_id.'", "'.$value.'" );</span> <br>
						<strong>Shortcode</strong> [thumb_image_src att_id="'.$att_id.'" type="'.$value.'"] 
						<br>
						<br>
						// this will output the image <br>
						<span style="color:#0000ff;"> echo get_thumb_image( "'.$att_id.'", "'.$value.'" ); </span>
						<br>
						<strong>Shortcode</strong> [thumb_image att_id="'.$att_id.'" type="'.$value.'"] <br>
						
						</p>
					</div>
					</td>
            	</tr>';
			}
			?>
          </tbody>
        </table>
         </form>
        <?php
	}

	function help_support(){ ?>
	<table width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #999999; padding:0px 0px 0px 10px; margin:2px 0px;">
	  <tr>
		<td align="right"><a href="http://www.aviplugins.com/support.php" target="_blank">Help and Support</a> <a href="http://www.aviplugins.com/rss/news.xml" target="_blank"><img src="<?php echo  plugin_dir_url( __FILE__ ) . '/images/rss.png';?>" style="vertical-align: middle;" alt="RSS"></a></td>
	  </tr>
	</table>
	<?php
	}
	
	function donate(){	?>
	<table width="98%" border="0" style="background-color:#FFF; border:1px solid #ccc; margin:2px 0px; padding-right:10px;">
	 <tr>
	 <td align="right"><a href="http://www.aviplugins.com/donate/" target="_blank">Donate</a> <img src="<?php echo  plugin_dir_url( __FILE__ ) . '/images/paypal.png';?>" style="vertical-align: middle;" alt="PayPal"></td>
	  </tr>
	</table>
	<?php
	}
	
	function thumbnail_editor_pro_add(){ ?>
	<table border="0" style="width:98%;background-color:#FFFFD2; border:1px solid #E6DB55; padding:0px 0px 0px 10px; margin:2px 0px;">
  <tr>
    <td><p>The <strong>PRO</strong> version of this plugin has additional features. <strong>1)</strong> Add Custom text to the images. <strong>2)</strong> Add custom image borders. <strong>3)</strong> Option to revert back the changes you make on the images. Get it <a href="http://www.aviplugins.com/thumbnail-editor-pro/" target="_blank">here</a> in <strong>USD 2.00</strong> </p></td>
  </tr>
</table>
	<?php }
	
	
	function get_image_sizes( $size = '' ) {
		global $_wp_additional_image_sizes;
		$sizes = array();
		$get_intermediate_image_sizes = get_intermediate_image_sizes();
		// Create the full array with sizes and crop info
		foreach( $get_intermediate_image_sizes as $_size ) {
				if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
						$sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
						$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
						$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
		
				} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
						$sizes[ $_size ] = array( 
								'width' => $_wp_additional_image_sizes[ $_size ]['width'],
								'height' => $_wp_additional_image_sizes[ $_size ]['height'],
								'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
						);
				}
		}
		if ( $size ) {
				if( isset( $sizes[ $size ] ) ) {
						return $sizes[ $size ];
				} else {
						return false;
				}
		}
		return $sizes;
	}

}

new thumbnail_editor_setup;
