<?php
class SmartNftProfile 
{
	
	function __construct() {
		register_activation_hook( PLUGIN_ROOT . "smart-nft.php",array( $this,"create_profile_page" ) );
		add_action("pre_get_document_title", array($this, "show_proper_title"), 100);
	}

	public function create_profile_page () {
		$page_id = get_option( 'smartnft_profile_page_id' );
		if( !$page_id ) {
			$nft_page_id = wp_insert_post(
				[
					"post_title" 		=> esc_html__("Profile", WP_SMART_NFT),
					"post_content"  => '[ProfilePage]',
					"post_name"	  	=> "profile",
					"post_type"     => "page",
					"post_status"   => "publish"
					]
				);
				add_option('smartnft_profile_page_id', $nft_page_id );
			}
			elseif( !get_post_status( $page_id ) ){
			$nft_page_id = wp_insert_post(
				[
					"post_title" 		=> esc_html__("Profile", WP_SMART_NFT),
					"post_content"  => '[ProfilePage]',
					"post_name"	  	=> "profile",
					"post_type"     => "page",
					"post_status"   => "publish"
				]
			);
			update_option('smartnft_profile_page_id', $nft_page_id );
		}
	}

	function show_proper_title( $title ) {
		if(
			!is_page("profile") && 
			preg_match('/\/profile\//',$_SERVER["REQUEST_URI"] ) &&
			!is_buddypress_page()
		  )
		{
			return esc_html__("Profile", WP_SMART_NFT) . " – " . get_bloginfo("name");
		}
	}

}

new SmartNftProfile();
