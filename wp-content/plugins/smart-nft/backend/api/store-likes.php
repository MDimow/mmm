<?php

class SmartNft_Handle_Likes 
{
	function __construct () {
		//store 
		add_action( "wp_ajax_store_likes", array( $this, "smartnft_store_likes" ) );
		add_action( "wp_ajax_nopriv_store_likes", array( $this, "smartnft_store_likes" ) );

		//get
		add_action( "wp_ajax_get_likes", array( $this, "smartnft_get_likes" ) );
		add_action( "wp_ajax_nopriv_get_likes", array( $this, "smartnft_get_likes" ) );

		//remove
		add_action( "wp_ajax_remove_likes", array( $this, "smartnft_remove_likes" ) );
		add_action( "wp_ajax_nopriv_remove_likes", array( $this, "smartnft_remove_likes" ) );
	}

	function smartnft_store_likes () {

		try{
			//check if correct info is send
			if(! isset($_POST["like_info"]) || empty($_POST["like_info"] ) ){
				wp_send_json(["status" => false], 400);
			}

			$wallet_add 	= strtolower( sanitize_text_field($_POST["like_info"]["wallet_address"]) );
			$tokenId 		= intval($_POST["like_info"]["tokenId"]);
			$option_key 	= "smartnft_post_likes";

			//get previews likes
			$likes_all = get_post_meta($tokenId, $option_key, true);

			//if no likes then create blank hash array
			if( !$likes_all ) {
                $likes_all = [];
			}
			
			$likes_all[$wallet_add] = true;

			//save the new likes 
			update_post_meta( $tokenId, $option_key, $likes_all );
			wp_send_json(["status" => true,"data"  => array_keys( $likes_all )], 200 );

		}catch(Exception $e ) {
			wp_send_json( [ "message" => $e->getMessage()], 400);
		}
	}

	function smartnft_remove_likes () {	
		try{
			//check if correct info is send
			if(! isset($_POST["like_info"]) || empty($_POST["like_info"] ) ){
				wp_send_json(
					[
						"message" => esc_html__("No Like info is send. Please send valid like info", WP_SMART_NFT),
					],
					400
				);
			}
			//get token id and contract add
			$tokenId = intval($_POST["like_info"]["tokenId"]);
			$wallet_addr = strtolower( sanitize_text_field($_POST["like_info"]["wallet_address"]) );
			$option_key = "smartnft_post_likes";

			//get all all_likes	
			$all_likes = get_post_meta($tokenId, $option_key, true);

			if( !$all_likes ) {
				wp_send_json([ "message" => esc_html__("No Likes.", WP_SMART_NFT), "data" => [] ], 200);
			}	
			
			
			if (($key = array_search( $wallet_addr, $all_likes )) !== false) {
				unset($all_likes[$key]);
			}

			//save the new likes 
			update_post_meta( $tokenId, $option_key, $all_likes );

			wp_send_json(
				[
					"message"=> esc_html__("operation success full", WP_SMART_NFT),
					"data"  => array_keys( $all_likes ),
				],
				200
			);

		}catch(Exception $e ) {
			wp_send_json(["message" => $e->getMessage()],400);
		}
	}

	function smartnft_get_likes () {

		try{
			//check if correct info is send
			if( !isset( $_POST["like_info"] ) || empty( $_POST["like_info"] ) ){
				wp_send_json(["message" => esc_html__("No like info is send", WP_SMART_NFT)],400);
			}

			//get token id and contract add
			$tokenId 		= intval($_POST["like_info"]["tokenId"]);
			$option_key 	= "smartnft_post_likes";

			//get all all_likes	
			$all_likes = get_post_meta($tokenId, $option_key, true);

			if( !$all_likes ) {
				wp_send_json(["message" => esc_html__( "No Likes.", WP_SMART_NFT ),"data"=> [] ],200);
			}	

			wp_send_json(
				[
					"message"=> esc_html__( "operation success full", WP_SMART_NFT ),
					"data"  => array_keys( $all_likes ),
				],
				200
			);

		} catch( Exception $e ) {
			wp_send_json(["message" => $e->getMessage() ],400);
		}
		
	}

}

new SmartNft_Handle_Likes();
