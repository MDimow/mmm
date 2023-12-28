<?php
class Smartnft_Get_Profile 
{
	function __construct() {
		add_action( "wp_ajax_get_profile",array( $this,"smartnft_get_profile" ), 10 );
		add_action( "wp_ajax_nopriv_get_profile",array( $this,"smartnft_get_profile" ), 10 );
	}


	function smartnft_get_profile () {
		//check if account is send or not
		if( !isset( $_POST[ "account" ] ) || empty( $_POST[ "account" ] ) ) {
			wp_send_json(
				[
					"status" => esc_html__("fail", WP_SMART_NFT),
					"message" => esc_html__("No account is send. Please send valid account", WP_SMART_NFT),
				],
				400
			);
		}

		try {
			$accountHash = strtolower( sanitize_text_field( $_POST[ "account" ] ) );
			$accountHash = "profile_" . $accountHash;

			//get the profile
			$result = get_option( $accountHash, false);
			wp_send_json( [ "data" => $result ],200);
		} catch( Exception $e ) {
			wp_send_json( [ "status" => esc_html__("fail", WP_SMART_NFT), "message" => $e->getMessage() ], 400 );
		}

	}

}

new Smartnft_Get_Profile();

