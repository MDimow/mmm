<?php 

class SMARTNFT_IMPORTER_AJAX_HANDLER
{
	function __construct(){
		add_action( 
			"wp_ajax_smartnft_importer_store_escrow_contract_info", 
			array($this,"smartnft_importer_store_escrow_contract_info")
	 	);	
		add_action( 
			"wp_ajax_smartnft_importer_fetch_token_uri", 
			array($this,"smartnft_importer_fetch_token_uri")
	 	);	
		add_action( 
			"wp_ajax_smartnft_importer_save_moralis_api_key", 
			array($this,"smartnft_importer_save_moralis_api_key")
	 	);	
		add_action( 
			"wp_ajax_smartnft_importer_update_post_title", 
			array($this,"smartnft_importer_update_post_title")
	 	);	
	}

	function smartnft_importer_store_escrow_contract_info () {
		//data error check
		if( 
			!isset( $_POST['contract_address'] ) || 
			empty(  $_POST['contract_address'] ) ||
			!isset( $_POST['contract_network'] ) || 
			empty(  $_POST['contract_network'] ) ||
			!isset( $_POST['chain_id'] ) || 
			empty(  $_POST['chain_id'] )
		)	{
					wp_send_json(
						array(
							"status"  => esc_html__( "fail", SNFT_IMPORTER ), 
							"message" => esc_html__("Please send contract info.", SNFT_IMPORTER), 
						),
						400
					);
			}

		//sanitize data
		$address = sanitize_text_field( $_POST[ "contract_address" ] );
		$network = sanitize_text_field( $_POST[ "contract_network" ] );
		$chain_id = sanitize_text_field( $_POST[ "chain_id" ] );

		$escrow_contract = array( 
			"address"  => $address, 
			"network"  => $network, 
			"chain_id" => $chain_id 
		);

		//save the clean data
		update_option( "smartnft_importer_escrow_contract_info", $escrow_contract );

		//send meaningful response
		wp_send_json(
			array(
				"status"  => esc_html__( "success", SNFT_IMPORTER ), 
				"message" => esc_html__("Escrow contract data saved successfully.", SNFT_IMPORTER), 
			)
		);

	}

	function smartnft_importer_fetch_token_uri () {
		//data error check
		if( 
			!isset( $_POST['uri'] ) || 
			empty(  $_POST['uri'] )
		)	{
					wp_send_json(
						array(
							"status"  => esc_html__( "fail", SNFT_IMPORTER ), 
							"message" => esc_html__("Please send uri", SNFT_IMPORTER), 
						),
						400 
					);
			}

		$response = wp_remote_get( esc_url( $_POST['uri'] ) );
		$body     = wp_remote_retrieve_body( $response );

		//send meaningful response
		wp_send_json(
			array(
				"status"  => esc_html__( "success", SNFT_IMPORTER ), 
				"data" 		=>  $body,
			)
		);

	}

	function smartnft_importer_save_moralis_api_key () {
		//data error check
		if( 
			!isset( $_POST['api_key'] ) || 
			empty(  $_POST['api_key'] )
		)	{
					wp_send_json(
						array(
							"status"  => esc_html__( "fail", SNFT_IMPORTER ), 
							"message" => esc_html__("Please send api key", SNFT_IMPORTER), 
						),
						400 
					);
			}

		$api_key = sanitize_text_field( $_POST[ "api_key" ] );

		//save the clean data
		update_option( "smartnft_importer_moralis_api_key", $api_key );

		//send meaningful response
		wp_send_json(
			array(
				"status"  => esc_html__( "success", SNFT_IMPORTER ), 
				"message" => esc_html__("APi key data saved successfully.", SNFT_IMPORTER), 
			)
		);

	}

	function smartnft_importer_update_post_title () {
		//data error check
		if( 
			!isset( $_POST['id'] ) || 
			empty(  $_POST['id'] ) ||
			!isset( $_POST['title'] ) || 
			empty(  $_POST['title'] )
		)	{
					wp_send_json(
						array(
							"status"  => esc_html__( "fail", SNFT_IMPORTER ), 
							"message" => esc_html__("Please send id and title", SNFT_IMPORTER), 
						),
						400 
					);
			}

		$id = sanitize_text_field( $_POST["id"] );
		$title = sanitize_text_field( $_POST["title"] );

		//update title
		wp_update_post( 
			array(
				'ID' => intval( $id ),
				'post_title' => $title
			)
		);

		//update meta title/name
		$meta = get_post_meta( $id, "smartnft_meta", false )[0];
		$meta['name'] = $title;
		update_post_meta( $id, "smartnft_meta", $meta );

		//send meaningful response
		wp_send_json(
			array(
				"status"  => esc_html__( "success", SNFT_IMPORTER ), 
				"message" => esc_html__("Post title updated.", SNFT_IMPORTER), 
			)
		);

	}

}

new SMARTNFT_IMPORTER_AJAX_HANDLER();
