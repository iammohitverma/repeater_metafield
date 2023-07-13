<?php
add_action( 'admin_init', 'cxc_single_rapater_meta_boxes', 2 );
function cxc_single_rapater_meta_boxes() {
	add_meta_box( 'cxc-single-repeater-data', 'Single Repeater', 'cxc_single_repeatable_meta_box_callback', 'page', 'normal', 'default');
}

function cxc_single_repeatable_meta_box_callback( $post ) {
	$custom_repeater_item = get_post_meta( $post->ID, 'custom_repeater_item', true );
	wp_nonce_field( 'repeterBox', 'formType' );
	?>
	<script type="text/javascript">		
		jQuery(document).ready(function($){
			jQuery(document).on('click', '.wc-remove-item', function() {
				jQuery(this).parents('tr.wc-sub-row').remove();
			}); 				
			jQuery(document).on('click', '.wc-add-item', function() {
				var row_no = jQuery('.wc-item-table tr.wc-sub-row').length;    
				var p_this = jQuery(this);
				row_no = parseFloat(row_no);
				var row_html = jQuery('.wc-item-table .wc-hide-tr').html().replace(/rand_no/g, row_no).replace(/hide_custom_repeater_item/g, 'custom_repeater_item');
				jQuery('.wc-item-table > tbody').append('<tr class="wc-sub-row">' + row_html + '</div>');    
			});
		});
	</script>
	<style>
		table{
			border-spacing: 0;
		}
		.upload_image_button {
			position: relative;
			font-size: 14px;
			background: #4f94d4;
			padding: 5px 10px;
			border: none;
			color: #fff;
			cursor: pointer;
			border-radius: 5px;
			vertical-align: top !important;
		}
		.preview_image{
			position: relative;
			max-width: 200px;
			margin-top: 10px;
			display: none;
		}
	</style>
	<table class="wc-item-table" width="100%">
		<tbody>
			<?php 
			if( $custom_repeater_item ){
				foreach( $custom_repeater_item as $item_key => $item_value ){
					?>
					<tr class="wc-sub-row" style="vertical-align: top;">				
						<td>
							<input name="custom_repeater_item[<?php echo $item_key; ?>][title]" type="text" value="<?php echo (isset($item_value['title'])) ? $item_value['title'] : ''; ?>" style="width:98%;" placeholder="Heading">		
						</td>
						<td>
							<input type="text" name="custom_repeater_item[<?php echo $item_key; ?>][desc]" value="<?php echo (isset($item_value['desc'])) ? $item_value['desc'] : ''; ?>" style="width:98%;" placeholder="Description"/>
						</td>
						<td>
							<input type="text" name="custom_repeater_item[<?php echo $item_key; ?>][message]" value="<?php echo (isset($item_value['message'])) ? $item_value['message'] : ''; ?>" style="width:98%;" placeholder="Message"/>
						</td>
						<td>
							<table>
								<tr style="vertical-align: top;">
									<td>
										<input class="upload_image_button" type="button" value="Upload Image" />
									</td>
								</tr>
								<tr>
									<td>
										<img src="" class="preview_image" alt="preview image">
									</td>
								</tr>
								<tr>
									<td><input type="hidden" class="hidden_img_field" name="custom_repeater_item[<?php echo $item_key; ?>][image_field]" value="<?php echo (isset($item_value['image_field'])) ? $item_value['image_field'] : ''; ?>" /></td>
								</tr>
							</table>
						</td>
						<td>
							<button class="wc-remove-item button" type="button">Remove</button>
						</td>
					</tr>
					<?php
				}
			}
			?>			
			<tr class="wc-hide-tr" style="display: none;">				
				<td>
					<input name="hide_custom_repeater_item[rand_no][title]" type="text" value=""  placeholder="Heading" style="width:98%;" >		
				</td>
				<td>
					<input type="text" name="hide_custom_repeater_item[rand_no][desc]" style="width:98%;" placeholder="Description"/>
				</td>
				<td>
					<input type="text" name="hide_custom_repeater_item[rand_no][message]" style="width:98%;" placeholder="Message"/>
				</td>
				<!-- <td>
					<input type="text" name="hide_custom_repeater_item[rand_no][image_field]" style="width:98%;" placeholder="image_field"/>
				</td> -->
				<td>
					<table>
						<tr>
							<td>
								<input class="upload_image_button" type="button" value="Upload Image" />
							</td>
							<tr>
								<td>
									<img src="" class="preview_image" alt="preview image">
								</td>
							</tr>
						</tr>
						<tr>
							<td><input type="hidden" class="hidden_img_field" name="hide_custom_repeater_item[rand_no][image_field]"/></td>
						</tr>
					</table>
				</td>
				<td>
					<button class="wc-remove-item button" type="button">Remove</button>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4"><button class="wc-add-item button" type="button">Add Field</button></td>
			</tr>
		</tfoot>
	</table>	
	<?php
}

add_action( 'save_post', 'cxc_single_repeatable_meta_box_save' );
function cxc_single_repeatable_meta_box_save( $post_id ) {

	if ( !isset( $_POST['formType'] ) && !wp_verify_nonce( $_POST['formType'], 'repeterBox' ) ){
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return;
	}

	if ( !current_user_can( 'edit_post', $post_id ) ){
		return;
	}

	if ( isset($_POST['custom_repeater_item']) ){
		update_post_meta( $post_id, 'custom_repeater_item', $_POST['custom_repeater_item'] );
	} else {
		update_post_meta( $post_id, 'custom_repeater_item', '' );
	}	
}



// for upload button
function my_admin_scripts() {    
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_register_script('my-upload', WP_PLUGIN_URL.'/my-script.js', array('jquery','media-upload','thickbox'));
    wp_enqueue_script('my-upload');
}

function my_admin_styles() {

    wp_enqueue_style('thickbox');
}

// better use get_current_screen(); or the global $current_screen
if (isset($_GET['page']) && $_GET['page'] == 'my_plugin_page') {

    add_action('admin_print_scripts', 'my_admin_scripts');
    add_action('admin_print_styles', 'my_admin_styles');
}

?>
<script
  src="https://code.jquery.com/jquery-3.7.0.min.js"
  integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
  crossorigin="anonymous"></script>
  
<script>
	// for upload button
	jQuery(document).ready( function( $ ) {
		let currRow, hidden_img_field, preview_image, imgObj, innerLinkImg, imgurl, imgLink, imgAlt;
		$(document).on("click",".upload_image_button",function() {
			currRow = $(this).closest("table");
			hidden_img_field = $(currRow).find(".hidden_img_field");
			preview_image = $(currRow).find(".preview_image");
			tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
			window.send_to_editor = function(html) {
			imgObj = $(html)[0];
			if(imgObj.localName == "a"){
				innerLinkImg  = $(html).children("img")[0];
				imgurl  = innerLinkImg.src;
				imgAlt = innerLinkImg.alt;
				imgLink = $(html).attr('href');
			} else {
				innerLinkImg  = imgObj;
				imgurl  = innerLinkImg.src;
				imgAlt = innerLinkImg.alt;
				imgLink = "";
			}
			$(hidden_img_field).val(imgurl);
			$(preview_image).attr("src", imgurl);
			$(preview_image).show();
			tb_remove();
			}
			return false;
		});

		// check if image is already set 
		let hidden_img_field_val;
		$( ".hidden_img_field" ).each(function() {
			hidden_img_field_val = $(this).val();
			if(hidden_img_field_val){
				currRow = $(this).closest("table");
				preview_image = $(currRow).find(".preview_image");
				$(preview_image).attr("src", hidden_img_field_val);
				$(preview_image).show();
			}
		});

	});
</script>