<?php
class SmartNftAllNft 
{
	function __construct() {
		register_activation_hook( PLUGIN_ROOT . "smart-nft.php",array( $this,"create_allnft_page" ) );
	}

	public function create_allnft_page () {
		$page_id = get_option( 'smartnft_all_nft_page_id' );
		if( !$page_id ) {
			$nft_page_id = wp_insert_post(
				[
					"post_title" 	=> esc_html__( "All Nft", WP_SMART_NFT ),
					"post_content"  => '[AllNftPage]',
					"post_name"	  	=> "all-nft",
					"post_type"     => "page",
					"post_status"   => "publish"
					]
				);
				add_option('smartnft_all_nft_page_id', $nft_page_id );
			}
			elseif( !get_post_status( $page_id ) ){
			$nft_page_id = wp_insert_post(
				[
					"post_title" 	=> esc_html__( "All Nft", WP_SMART_NFT ),
					"post_content"  => '[AllNftPage]',
					"post_name"	  	=> "all-nft",
					"post_type"     => "page",
					"post_status"   => "publish"
				]
			);
			update_option('smartnft_all_nft_page_id', $nft_page_id );
		}
	}

}

new SmartNftAllNft();

