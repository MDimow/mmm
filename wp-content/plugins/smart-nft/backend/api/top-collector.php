<?php

class SmartNft_Top_Collector 
{
	function __construct () {
		add_action( "wp_ajax_smartnft_get_top_collector", array( $this, "smartnft_get_top_collector" ) );
		add_action( "wp_ajax_nopriv_smartnft_get_top_collector", array( $this, "smartnft_get_top_collector" ) );
	}

	function smartnft_get_top_collector () {

			//post ids that use this term
			$post_ids = get_posts( array( 'post_type' => 'smartnft', 'fields'	=> 'ids') );

			//default value
			$owners = [];

			foreach( $post_ids as $id ) {
				$post_meta = get_post_meta( $id, 'smartnftData', true );

				if( $post_meta['standard']  == "Erc721" ) {
							$_owner =  strtolower( $post_meta['owners'][0] );

							if( empty( $owners[ $_owner ] ) ) {
								$owners[ $_owner ]['amount'] = 1;
							}else{
								$owners[ $_owner ]['amount'] = $owners[ $_owner ]['amount']  + 1;
							}
				}

				if( $post_meta['standard']  == "Erc1155" ) {
							$_owners = get_post_meta( $id, "smartnft_erc1155_token_owners", true );
							foreach( $_owners as $key =>  $owner ) { 
								$_key = strtolower( $key );
									if( empty( $owners[ $_key ] ) ) {
										$owners[ $_key ]['amount']  = 1;
									}else{
										$owners[ $_key ]['amount'] = $owners[ $_key ]['amount']  + 1;
									}
							}
				}

			}

			$owners_with_profile = array();

			foreach( $owners as $key => $value ) {
				$name = "profile_" . $key;
				$profile = get_option( $name, false );	
				$owners[$key]['profile'] = $profile;

				$owners_with_profile[] = array( "address" => $key, "amount" => $value['amount'], "profile" => $profile );
			}

			wp_send_json(
				[
					"status" => esc_html__("success", WP_SMART_NFT),
					"data"   => $owners_with_profile
				],
				200
			);
	}
}

new SmartNft_Top_Collector();
