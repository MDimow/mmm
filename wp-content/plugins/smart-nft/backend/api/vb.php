<?php

class Smartnft_VB_Manager
{
	function __construct () {
		add_action( "wp_ajax_smartnft_save_vb_form", array( $this, "save_vb_form" ) );

		add_action( "wp_ajax_smartnft_get_vb_form", array( $this, "get_vb_form" ) );
		add_action( "wp_ajax_nopriv_smartnft_get_vb_form", array( $this, "get_vb_form" ) );

		add_action( "wp_ajax_smartnft_get_pending_vb_request", array( $this, "get_pending_vb_request" ) );
		//add_action( "wp_ajax_nopriv_smartnft_get_pending_vb_request", array( $this, "get_pending_vb_request" ) );

		add_action( "wp_ajax_smartnft_get_verified_vb_request", array( $this, "get_verified_vb_request" ) );
		add_action( "wp_ajax_smartnft_decline_vb_verified_request", array( $this, "decline_vb_verified_request" ) );

		add_action( "wp_ajax_smartnft_save_form_fields_value", array( $this, "save_form_fields_value" ) );
		add_action( "wp_ajax_nopriv_smartnft_save_form_fields_value", array( $this, "save_form_fields_value" ) );

		add_action( "wp_ajax_smartnft_decline_vb_pending_request", array( $this, "decline_vb_pending_request" ) );
		add_action( "wp_ajax_smartnft_accept_vb_pending_request", array( $this, "accept_vb_pending_request" ) );

	}

	function save_vb_form () {
		//error check for the field
		if( !isset( $_POST['fields'] ) && empty( $_POST['fields'] ) ) {
			wp_send_json( array( "data" => "send proper data. (fields is missing)" ), 400 );
		}

		update_option( "smartnft_vb_form", $_POST['fields'], false );

		wp_send_json( array("data" => "Form saved."), 200 );
	}

	function get_vb_form () {
		$form = get_option( "smartnft_vb_form", false );
		wp_send_json( array("data" => $form), 200 );
	}

	function get_pending_vb_request () {
		$this->must_fields_error("type");
		$type = $_POST["type"];
		$data = get_option( "smartnft_vb_pending_" . $type, false );
		wp_send_json( array( "data" => $data ), 200 );
	}

	function get_verified_vb_request () {
		$this->must_fields_error("type");
		$type = $_POST["type"];
		$data = get_option( "smartnft_vb_accepted_request" . $type , false );
		wp_send_json( array( "data" => $data ), 200 );
	}

	function decline_vb_pending_request() {
		$this->must_fields_error( "id", "type" );

		$id = strtolower($_POST["id"]);
		$type = $_POST["type"];
		$data = get_option( "smartnft_vb_pending_" . $type, array() );
		unset( $data[ $id ] );
		update_option( "smartnft_vb_pending_" . $type, $data );

		$prev_accpt_req = get_option("smartnft_vb_accepted_request" . $type , array());
		unset( $prev_accpt_req[ $id ] );
		update_option( "smartnft_vb_accepted_request" . $type, $prev_accpt_req );

		if( $type == "profile" ) {
			$accountHash = "profile_" . $id;
			$profile = get_option( $accountHash, array() );
			$profile["verified"] = false;
			update_option( $accountHash, $profile );
		}

		//update collection meta that its verified if its collection type
		if( $type == "collection" ) {
			$coll_meta = get_term_meta( $id, "collection_meta", true );
			$coll_meta["verified"] = false;
			update_term_meta( $id, "collection_meta", $coll_meta );
		}

		wp_send_json( array( "data" => $data,  "status" => "success" ), 200 );
	}

	function decline_vb_verified_request() {
		$this->must_fields_error( "id", "type" );
		$id = strtolower($_POST["id"]);
		$type = $_POST["type"];

		$data = get_option( "smartnft_vb_accepted_request" . $type, array() );
		unset( $data[ $id ] );
		update_option( "smartnft_vb_accepted_request" . $type, $data );

		if( $type == "profile" ) {
			$accountHash = "profile_" . $id;
			$profile = get_option( $accountHash, array() );
			$profile["verified"] = false;
			update_option( $accountHash, $profile );
		}

		//update collection meta that its verified if its collection type
		if( $type == "collection" ) {
			$coll_meta = get_term_meta( $id, "collection_meta", true );
			$coll_meta["verified"] = false;
			update_term_meta( $id, "collection_meta", $coll_meta );
		}

		wp_send_json( array( "data" => $data,  "status" => "success" ), 200 );
	}

	function accept_vb_pending_request() {
		$this->must_fields_error("id","type");
		$id = strtolower($_POST["id"]);
		$type = $_POST["type"];
		$data = get_option( "smartnft_vb_pending_" . $type, array() );
		$verified_request = $data[ $id ];

		$prev_accpt_req = get_option("smartnft_vb_accepted_request" . $type , array());
		$prev_accpt_req[ $id ] = $verified_request;
		update_option( "smartnft_vb_accepted_request" . $type, $prev_accpt_req );

		unset( $data[ $id ] );
		update_option( "smartnft_vb_pending_" . $type, $data );

		//update profile meta that its verified if its profile
		if( $type == "profile" ) {
			$accountHash = "profile_" . $id;
			$profile = get_option( $accountHash, array() );
			$profile["verified"] = true;
			update_option( $accountHash, $profile );
		}

		//update collection meta that its verified if its collection type
		if( $type == "collection" ) {
			$coll_meta = get_term_meta( $id, "collection_meta", true );
			$coll_meta["verified"] = true;
			update_term_meta( $id, "collection_meta", $coll_meta );
		}

		wp_send_json( array( "data" => $data,  "status" => "success" ), 200 );
	}

	private function must_fields_error( $must_fields ) {
		foreach( $must_fields as $field ) {
			if( !$_POST[ $field ] || empty( $_POST[ $field ] ) ) {
				wp_send_json( array( "data" => esc_html__("Send proper information.", WP_SMART_NFT) ), 400 );
			}
		}
	}

	function save_form_fields_value () {
		/*
		 * 1. get all fields type
		 * 2. upload img and file and get their url
		 * 3. pack the value as an array
		 * 4. get previous values and add new value to the array
		 * 5. save the array on database as an option
		 * 6. option key 'smartnft_vb_pending_profile'
		 */
		// necessary fields checks
		$this->must_fields_error( array( "user_address", "fields", "type" ) );
			
		$user_address = strtolower( $_POST["user_address"] );
		$fields = $_POST['fields']; // [ [ "type" => text | textarea | image | file , "value" => value, "file_id",=> id, "mimeType" => type] ];
		$fields = json_decode( stripslashes($fields), true );
		$type = $_POST["type"]; //either profile or collection


		//loop throw the fields and process the data
		foreach( $fields as $index => $field ) {
				if( $field["type"]  ==  "image" ) {
					$url = smartnft_upload_image_to_media_library( $field["value"], 'vb_avtar', $field["mimeType"] );
					$fields[$index]["value"] = $url;
				}
				if($field["type"] == "file") {
					$id = media_handle_upload( $field["file_id"], 0 );
					if( is_wp_error( $id ) ) {
						$fields[$index]["value"] = false;
					}else{
						$fields[$index]["value"] = wp_get_attachment_url( $id );
					}
				}
		}

		//get previous fields
		$prev_fields = get_option( "smartnft_vb_pending_" . $type, array() );
		$prev_fields[ $user_address ] = $fields;  

		update_option( "smartnft_vb_pending_" . $type, $prev_fields );
		wp_send_json( array( "data" => $prev_fields,  "status" => "success" ), 200 );
	}
}

new Smartnft_VB_Manager();
