<?php
class thumbnail_editor_process{
	
	function __construct() {
		add_action( 'admin_init', array( $this, 'thumb_data_update' ) );
	}
	
	function thumb_data_update(){
		if(isset($_POST['th_edit_option']) and sanitize_text_field($_POST['th_edit_option']) == "imageCrop"){
			$att_id = sanitize_text_field($_REQUEST['att_id']);
			if($att_id == '')
			wp_die('Image not found.');
			
			if ( ! isset( $_POST['th_edit_save_action_field'] ) || ! wp_verify_nonce( $_POST['th_edit_save_action_field'], 'th_edit_save_action' ) ) {
			   wp_die( 'Sorry, your nonce did not verify.');
			} 
			
			$crop_type = sanitize_text_field($_REQUEST['crop_type']);
			$x1 = sanitize_text_field($_REQUEST['x1']);
			$y1 = sanitize_text_field($_REQUEST['y1']);
			$x2 = sanitize_text_field($_REQUEST['x2']);
			$y2 = sanitize_text_field($_REQUEST['y2']);
			$w = round(sanitize_text_field($_REQUEST['w']));
			$h = round(sanitize_text_field($_REQUEST['h']));
			
			$rw = round(sanitize_text_field($_REQUEST['rw']));
			$rh = round(sanitize_text_field($_REQUEST['rh']));
			
			$full_image = wp_get_attachment_image_src( $att_id, 'full' );
			$info = pathinfo(  $full_image[0] );
			$dirname = dirname( get_attached_file( $att_id ) );
			
			// resize image //
			if( $rw != '' and $rh != '' ){
				$file_name 	  = $info['filename'].'-up-'.time().'.'.$info['extension'];
				$image_file   = $dirname.'/'.$file_name;
				$image = wp_get_image_editor( $full_image[0] );
				if ( ! is_wp_error( $image ) ) {
					$image->set_quality(100);
					$image->resize( $rw, $rh, false );
					$image->save( $image_file );
					$up_th = wp_get_attachment_metadata( $att_id );
					$up_th['sizes'][$crop_type]['file'] = $file_name;
					wp_update_attachment_metadata( $att_id, $up_th ); 
				}
			}
			// resize image //
			
			
			// crop image //
			if( $rw == '' and $rh == '' ){
				$parent_image = $full_image[0];
				$file_name = $info['filename'].'-up-'.time().'.'.$info['extension'];
				$image_file   = $dirname.'/'.$file_name;
			} else {
				$parent_image = $image_file;
			}
			
			if( $w != '' and $h != '' ){
				$image = wp_get_image_editor( $parent_image );
				if ( ! is_wp_error( $image ) ) {
					
					$image->set_quality(100);
					$image->crop( $x1, $y1, $w , $h );
					$image->save( $image_file );
					$up_th = wp_get_attachment_metadata( $att_id );
					$up_th['sizes'][$crop_type]['file'] = $file_name;
					wp_update_attachment_metadata( $att_id, $up_th ); 

				}
			}
			// crop image //	

			$GLOBALS['msg'] = "<strong>{$crop_type}</strong> image successfully updated.";
		}
	}
}
new thumbnail_editor_process;