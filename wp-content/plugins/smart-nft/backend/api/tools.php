<?php

class Smartnft_Tools_Manager 
{
	function __construct () {
		add_action( "wp_ajax_smartnft_delete_deployed_contracts", array( $this, "delete_deployed_contracts" ) );
	}

	function delete_deployed_contracts() {
		$is_deleted = delete_option("smartnft_deployed_network_contract");
		if( $is_deleted ){
			wp_send_json("contracts deleted", 200);
		}else{
			wp_send_json("contracts deletion fail!", 400);
		} 	
	}

	private function must_fields_error( $must_fields ) {
		foreach( $must_fields as $field ) {
			if( !$_POST[ $field ] || empty( $_POST[ $field ] ) ) {
				wp_send_json( array( "data" => esc_html__("Send proper information.", WP_SMART_NFT) ), 400 );
			}
		}
	}

}

new Smartnft_Tools_Manager();
