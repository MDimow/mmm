<?php 

class SMARTNFT_IMPORTER_AJAX_HANDLER
{
	public $cursor_key = "smartnft_moralis_cursor";

	function __construct(){
		add_action( "wp_ajax_smartnft_importer_fetch_token_uri", [ $this,"smartnft_importer_fetch_token_uri" ] );	
		add_action( "wp_ajax_smartnft_importer_save_moralis_api_key", [ $this,"smartnft_importer_save_moralis_api_key" ] );	
		add_action( "wp_ajax_smartnft_save_moralis_cursor", [ $this,"smartnft_save_moralis_cursor" ] );	
		add_action( "wp_ajax_smartnft_get_moralis_cursor", [ $this,"smartnft_get_moralis_cursor" ] );	
		add_filter( 'http_request_timeout', [$this, '__extend_http_request_timeout'] );
	}

	function __extend_http_request_timeout( $timeout ) {
    	return 60; // seconds
	}

	function smartnft_save_moralis_cursor() {
		if( !isset( $_POST['address'] ) ||  
			empty(  $_POST['address'] ) ||
			!isset( $_POST['data'] ) ||  
			empty(  $_POST['data'] )
		) {
		wp_send_json(array("status" => "fail", "message" => "Please send address" ), 400 );
	  }

	 //cursor look like this--> [ address => [ page => [ page:,page_size:,cursor: ] ] ];
	 
	 $data = $_POST["data"];
	 $address = strtolower($_POST["address"]);
	 $page = intval( $data["page"] );
	 $page_size = intval( $data["page_size"] );
	 $cursor = $data["cursor"];
	 $prev_cursor = get_option( $this->cursor_key, []);

	 if( empty( $prev_cursor[$address][$page] ) && !empty($cursor) ) {
		$prev_cursor[$address][$page] = ["page" => $page, "page_size" => $page_size, "cursor" => $cursor];
	  }

	 update_option( $this->cursor_key, $prev_cursor );
	 
	 //send meaningful response
 	 wp_send_json(array( "status" => "success", "data" => $prev_cursor[$address] ), 200);
	}

	function smartnft_get_moralis_cursor() {
	 if( !isset( $_POST['address'] ) ||  empty(  $_POST['address'] ) ) {
		wp_send_json(array("status" => "fail", "message" => "Please send address" ), 400 );
	  }
	 $address = strtolower($_POST["address"]);
	 $prev_cursor = get_option( $this->cursor_key, []);

	 //send meaningful response
 	 wp_send_json(array( "status" => "success", "data" => !empty($prev_cursor[$address]) ? $prev_cursor[$address] : false ), 200);
	}

	function smartnft_importer_fetch_token_uri () {
 	 //data error check
	 if( !isset( $_POST['uri'] ) ||  empty(  $_POST['uri'] ) ) {
		wp_send_json(array("status" => "fail", "message" => "Please send uri" ), 400 );
	  }

	 $response = wp_remote_get( $_POST['uri'], ["timeout" => 60000] );
	 if( is_wp_error($response) ) {
		wp_send_json(array("status" => "fail", "res" => $response ), 400 );
	 }

	 if( $response["response"]["code"] != 200  ) {
		wp_send_json(array("status" => "fail", "res" => $response ), 400 );
	 }

     $body = json_decode(wp_remote_retrieve_body( $response ));

	 //send meaningful response
 	 wp_send_json(array( "status" => "success", "data" =>  $body ));

	}

	function smartnft_importer_save_moralis_api_key () {
		//data error check
		if( !isset( $_POST['api_key'] ) || empty($_POST['api_key']) ) {
			wp_send_json( array("status" => "fail", "message" => "Please send api key"), 400 );
		}

		$api_key = $_POST[ "api_key" ];
		//save the clean data
		update_option( "smartnft_importer_moralis_api_key", $api_key );
		//send meaningful response
		wp_send_json(array( "status" => "success", "message" => "APi key data saved successfully."), 200);
	}

}

new SMARTNFT_IMPORTER_AJAX_HANDLER();

