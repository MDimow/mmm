<?php

class Verify_SmartNFT{
    function __construct(){
        add_action( "wp_ajax_licence_data",array( $this,"smartnft_licence_data" ), 10 );
		add_action( "wp_ajax_nopriv_licence_data",array( $this,"smartnft_licence_data" ), 10 );
		
        add_action( "wp_ajax_update_licence",array( $this,"smartnft_update_licence" ), 10 );
		add_action( "wp_ajax_nopriv_update_licence",array( $this,"smartnft_update_licence" ), 10 );

    }
    public function smartnft_update_licence(){
		$purchasekey = $_POST['purchase_key'];
		$licencekey = $_POST['licence_key'];
		if( !isset( $purchasekey ) || empty( $purchasekey ) ) {
			wp_send_json(
				[
					"verified"  => false,
					"message"   => esc_html__("Invalid purchase key", WP_SMART_NFT),
				],
				200
			);
		}
		if( !isset( $licencekey ) || empty( $licencekey ) ) {
			wp_send_json(
				[
					"verified"  => false,
					"message"   => esc_html__("Invalid licence key.", WP_SMART_NFT),
				],
				200
			);
		}
		$purchasekeyupdated = update_option( 'snft_activation_key', $purchasekey);
		$licencekeyupdated = update_option( 'snft_licence_key', $licencekey);
		
		if( $purchasekey && $licencekey ){
			$response = [
				"message" => __("Successfully updated"),
				"updated" => true
			];
			wp_send_json( $response, 200 );
		}
	}
    public function smartnft_licence_data(){
        //check if key is send or not
        $purchasekey = get_option( 'snft_activation_key', false );
        $licencekey = get_option( 'snft_licence_key', false );
        
		if( !isset( $purchasekey ) || empty( $purchasekey ) ) {
			wp_send_json(
				[
					"verified"  => false,
					"message"   => esc_html__("Invalid purchase key", WP_SMART_NFT),
				],
				200
			);
		}
		if( !isset( $licencekey ) || empty( $licencekey ) ) {
			wp_send_json(
				[
					"verified"  => false,
					"message"   => esc_html__("Invalid licence key.", WP_SMART_NFT),
				],
				200
			);
		}

        try{
            wp_send_json( [
                'purchase_key' => $purchasekey, 
                'licence_key' => $licencekey
            ], 200 );
        }catch( Exception $e ) {
			wp_send_json(
				[
					"message" => $e->getMessage()
				],
				400
			);
		}

    }
}

new Verify_SmartNFT();
