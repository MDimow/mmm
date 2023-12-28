<?php

class SmartNft_Handle_Transection_Hash 
{
	private $option_key = "smartnft_tx_hash";

	function __construct () {
		//store 
		add_action( "wp_ajax_smartnft_store_tx_hash", array( $this, "smartnft_store_tx_hash" ) );
		add_action( "wp_ajax_nopriv_smartnft_store_tx_hash", array( $this, "smartnft_store_tx_hash" ) );

		//get
		add_action( "wp_ajax_smartnft_get_tx_hash", array( $this, "smartnft_get_tx_hash" ) );
		add_action( "wp_ajax_nopriv_smartnft_get_tx_hash", array( $this, "smartnft_get_tx_hash" ) );
	}

	function smartnft_store_tx_hash () {
		try{
			//check if correct info is send
			if(! isset($_POST["txInfo"]) || empty($_POST["txInfo"] ) ){
				wp_send_json(
					[
						"message" => esc_html__("No TX info is send. Please send valid TX info", WP_SMART_NFT),
					],
					400
				);
			}
			
			$data = $_POST[ 'txInfo' ];
			//get previews tx_hash
			$tx_hashes = get_post_meta( $data["postId"], $this->option_key, true);

			//add new tx_hash
			if( empty( $tx_hashes ) ) {
				$tx_hashes = array();
				array_push( $tx_hashes, $data );
			}else{
				array_push( $tx_hashes, $data );
			}
			//save the new hashes 
			update_post_meta( $data[ 'postId' ],  $this->option_key, $tx_hashes );

			wp_send_json(["data" => $tx_hashes], 200 );

		}catch(Exception $e ) {
				wp_send_json( [ "message" => $e->getMessage()], 400);
		}
	}

	function smartnft_get_tx_hash () {
		try{
			//check if correct info is send
			if( !isset( $_POST["postId"] ) || empty( $_POST["postId"] ) ){
				wp_send_json( [ "status" => esc_html__( "fail", WP_SMART_NFT ) ], 400 );
			}

			//get token id and contract add
			$post_id = intval( $_POST["postId"]);

			//get all tx_hashes	
			$tx_hash = get_post_meta( $post_id, $this->option_key, true);

			wp_send_json( [ "data"  => $tx_hash ], 200 );

		} catch( Exception $e ) {
				wp_send_json( [ "message" => $e->getMessage() ],400 );
		}
	}
	
}

new SmartNft_Handle_Transection_Hash();
