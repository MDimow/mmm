<?php

class Smartnft_Bulk_Minting_APIS
{
	function __construct() {
		add_action("wp_ajax_nbm_add_to_queue", array( $this, "nbm_add_to_queue" ), 10);
		add_action("wp_ajax_nopriv_nbm_add_to_queue", array( $this, "nbm_add_to_queue" ), 10);

		add_action("wp_ajax_nbm_get_queue", array( $this, "nbm_get_queue" ), 10);
		add_action("wp_ajax_nopriv_nbm_get_queue", array( $this, "nbm_get_queue" ), 10);

		add_action("wp_ajax_nbm_delete_queue", array( $this, "nbm_delete_queue" ), 10);
		add_action("wp_ajax_nopriv_nbm_delete_queue", array( $this, "nbm_delete_queue" ), 10);

		add_action("wp_ajax_nbm_delete_all_queue", array( $this, "nbm_delete_all_queue" ), 10);
		add_action("wp_ajax_nopriv_nbm_delete_all_queue", array( $this, "nbm_delete_all_queue" ), 10);
	}

	function nbm_add_to_queue() {
		$this->must_fields_error( array( "account", "chainId", "standard", "meta" ) );

		$account = strtolower( sanitize_text_field( $_POST["account"] ) );
		$chainId =  sanitize_text_field( $_POST["chainId"]  );
		$standard =  sanitize_text_field( $_POST["standard"]  );
		$meta = map_deep( $_POST["meta"], "sanitize_text_field" );

		$key = "nbm_" . $account . "_" . $chainId . "_" . $standard . "my_queue";

		$prev_queue = get_option( $key, array() );
		$prev_queue[uniqid()] = $meta;

		update_option( $key, $prev_queue, false );

		wp_send_json( array( "data" => $prev_queue, "message" => esc_html__("Success", WP_SMART_NFT) ), 200 );
	}

	function nbm_get_queue() {
		$this->must_fields_error( array( "account", "chainId", "standard" ) );

		$account = strtolower( sanitize_text_field( $_POST["account"] ) );
		$chainId =  sanitize_text_field( $_POST["chainId"]  );
		$standard = sanitize_text_field( $_POST["standard"]  );

		$key = "nbm_" . $account . "_" . $chainId . "_" . $standard . "my_queue";

		$queue = get_option( $key, array() );
		wp_send_json( array( "data" => $queue, "message" => esc_html__("Success", WP_SMART_NFT) ), 200 );

	}

	function nbm_delete_queue() {
		$this->must_fields_error( array( "account", "chainId", "id", "standard" ) );

		$account = strtolower( sanitize_text_field( $_POST["account"] ) );
		$chainId =  sanitize_text_field( $_POST["chainId"]  );
		$standard =  sanitize_text_field( $_POST["standard"]  );
		$id =  sanitize_text_field( $_POST["id"]  );

		$key = "nbm_" . $account . "_" . $chainId . "_" . $standard . "my_queue";
		$queue = get_option( $key, array() );

		unset( $queue[ $id ] );

		update_option( $key, $queue, false );

		wp_send_json( array( "data" => $queue, "message" => esc_html__("Success", WP_SMART_NFT) ), 200 );
	}

	function nbm_delete_all_queue() {
		$this->must_fields_error( array( "account", "chainId", "standard" ) );

		$account = strtolower( sanitize_text_field( $_POST["account"] ) );
		$chainId =  sanitize_text_field( $_POST["chainId"]  );
		$standard =  sanitize_text_field( $_POST["standard"]  );

		$key = "nbm_" . $account . "_" . $chainId . "_" . $standard . "my_queue";

		delete_option( $key );
		wp_send_json( array( "message" => esc_html__("Success", WP_SMART_NFT) ), 200 );
	}



	private function must_fields_error( $must_fields ) {
		foreach( $must_fields as $field ) {
			if( !$_POST[ $field ] || empty( $_POST[ $field ] ) ) {
				wp_send_json( array( "data" => esc_html__("Send proper informatics.", WP_SMART_NFT) ), 400 );
			}
		}
	}

}

new Smartnft_Bulk_Minting_APIS();

