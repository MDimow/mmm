<?php

//write now this file is not using

class Smartnft_Update_DB_Data {
	function __construct() {
		register_activation_hook( PLUGIN_ROOT . "smart-nft.php", array( $this, "update_latest_data" ) );
	}

	function update_latest_data() {
		//get last update date
		$is_updated = get_option("smartnft_latest_update_4_3_23");

		if( !$is_updated ) {
			//get all post 
			$post_ids = get_posts([
				"post_type"	=> "smartnft",
				"numberposts" => -1,
				"fields" => "ids",
			]);

			foreach( $post_ids as $id ) {
				//update their respective meta
				$prev_val = get_post_meta( $id, "price", true );

				if( !empty( $prev_val ) ) {
					//update the value	
					update_post_meta( $id, "price", floatval( $prev_val ) );
				}
			}

			//Done update the option
			update_option( "smartnft_latest_update_4_3_23", true );
		}
	}
}

new Smartnft_Update_DB_Data();
