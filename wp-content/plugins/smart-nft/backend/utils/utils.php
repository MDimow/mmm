<?php


/**
 * Save the image on the server.
 */
function smartnft_upload_image_to_media_library( $base64_img, $title, $mymeType ) {
	
	if( empty( $base64_img ) ) {
		return "";
	}

	// Upload dir.
	$upload_dir  = wp_upload_dir();
	$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
    
  	$type = 'data:' . $mymeType . ';base64,';

	$img             = str_replace( $type, '', $base64_img );
	$img             = str_replace( ' ', '+', $img );
	$decoded         = base64_decode( $img );
	$filename        = $title . '.' . explode("/", $mymeType)[1];
	$file_type       = $mymeType;
	$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

	// Save the image in the uploads directory.
	$upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );

	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
		'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
	);

	$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );

  	return wp_get_attachment_url( $attach_id );
}

function smartnft_upload_base64_file_to_media_library() {
  if( !isset( $_POST["base64_file"] ) ||
	  empty( $_POST["base64_file"] ) ||
	  !isset( $_POST["title"] ) ||
	  empty( $_POST["title"] ) ||
	  !isset( $_POST["mimeType"] ) ||
	  empty( $_POST["mimeType"] ) 
	)
	{
		wp_send_json(array("data" => esc_html__("Send proper data",WP_SMART_NFT)), 400);
	}

  $base64_file = $_POST["base64_file"];
  $title = sanitize_title( $_POST["title"] );
  $mimeType = sanitize_text_field( $_POST["mimeType"] );
  $url = smartnft_upload_image_to_media_library( $base64_file, $title, $mimeType );

  wp_send_json(array("data" => $url), 200);
}

add_action("wp_ajax_nopriv_smartnft_upload_base64_file_to_media_library","smartnft_upload_base64_file_to_media_library");
add_action("wp_ajax_smartnft_upload_base64_file_to_media_library","smartnft_upload_base64_file_to_media_library");


/**
 * Save the NFT image on the server.
 */
function smartnft_store_nft_image_to_media_library( $url ) {

	$attachment_id = media_sideload_image( $url, 0 );
	        
	if ( is_wp_error( $attachment_id ) ) { 
		$response['response'] = "ERROR";
		$response['error'] = $fileErrors[ $data['upload_file']['error'] ];
	} else {
		$fullsize_path = get_attached_file( $attachment_id );
		$pathinfo = pathinfo( $fullsize_path );
		$url = wp_get_attachment_url( $attachment_id );
		$response['response'] = "SUCCESS";
		$response['filename'] = $pathinfo['filename'];
		$response['id'] = $attachment_id;
		$response['url'] = $url;
		$response['html'] = $this->get_media_upload_thumb_html( $url );
		$type = $pathinfo['extension'];
		if( $type == "jpeg"
		|| $type == "jpg"
		|| $type == "png"
		|| $type == "gif" ) {
			$type = "image/" . $type;
		}
		$response['type'] = $type;
	}

	$attach_id 	= wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );

  	$attach_url = wp_get_attachment_image_url( $attach_id, 'thumbnail' );

	return [
		'attach_id' 	=> $attach_id,
		'attach_url' 	=> $attach_url,
	];
}


function sanitize_texts_field( $data ){
    if( is_array($data) ){
        $newData = $data;
        return $data;
    }else{
        return $data;
    }
    return $data;
}


/**
 * return categories of the current active contract
 */

function smartnft_get_categories () {
	$terms = get_terms( array(
			'taxonomy' => 'smartnft_category',
			'hide_empty' => false,
		)
 	);	

	if( is_wp_error( $terms ) ) {
		return [];
	}

	return $terms;
}

function smartnft_get_collections () {
	$terms = get_terms( array(
			'taxonomy' => 'smartnft_collection',
			'hide_empty' => false,
		)
 	);	

	if( is_wp_error( $terms ) ) {
		return [];
	}

	return $terms;
}

add_action('wp_ajax_convert_price', 'convert_price');
add_action('wp_ajax_nopriv_convert_price', 'convert_price');

function convert_price(){
	if( !isset( $_POST['coins'] ) || empty($_POST['coins']) ){
		wp_send_json( [
			'message' => esc_html__( "You need to send coins", WP_SMART_NFT )
		], 400 );
	}

	try{
		$res = [];
		$coins = $_POST['coins'];
		$symbol = $coins[0]['principleSymbol'];
		$main_symbol_url = "https://api.coinconvert.net/convert/". $symbol ."/USD?amount=1";
		$response = wp_remote_get( $main_symbol_url );

		$main_symbol_res = $response['body'];

		$res['main_symbol_convert'] = json_decode($main_symbol_res);
		wp_send_json( $res, 200 );

	}catch( Exception $e ){
		wp_send_json(
			[
				"message" => $e->getMessage()
			],
			400
		);
	}
}
function is_buddypress_page(){
	if( class_exists('BuddyPress') ){
		if( bp_is_user() ){
			return true;
		}
	}
	return false;
}
