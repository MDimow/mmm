<?php
class SmartNftAllCollection 
{
	function __construct() {
		register_activation_hook( PLUGIN_ROOT . "smart-nft.php",array( $this,"create_all_collection_page" ) );
	}

	public function create_all_collection_page () {
		$page_id = get_option( 'smartnft_all_collection_page_id' );
		if( !$page_id ) {
			$collection_page_id = wp_insert_post(
				[
					"post_title" 	=> esc_html__( "All Collections", WP_SMART_NFT ),
					"post_content"  => '[AllNftCollections]',
					"post_name"	  	=> "all-collections",
					"post_type"     => "page",
					"post_status"   => "publish"
                ]
            );
				add_option('smartnft_all_collection_page_id', $collection_page_id );
			}
			elseif( !get_post_status( $page_id ) ){
			$collection_page_id = wp_insert_post(
				[
					"post_title" 	=> esc_html__( "All Collections", WP_SMART_NFT ),
					"post_content"  => '[AllNftCollections]',
					"post_name"	  	=> "all-collections",
					"post_type"     => "page",
					"post_status"   => "publish"
				]
			);
			update_option('smartnft_all_collection_page_id', $collection_page_id );
		}
	}

}

new SmartNftAllCollection();

