<?php
/**
 * Plugin Name:       Smart NFT Bulk Minting - Addons
 * Plugin URI:        https://wpsmartnft.com/
 * Description:       Smart NFT bulk minting is a bulk minting tool for minting a large sized NFT collection. Add nfts to queue and give a collection name to it.
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.2
 * Author:            Tophive 
 * Author URI:        https://codecanyon.net/user/tophive
 * License:           Envato Licence 2.0
 * Text Domain:       nft_bulk_minting
 * Domain Path:       /languages
 */

class NFT_BULK_MINTING_BASE {

    function __construct() {
		$this->constant();
		add_action( 'admin_menu', array($this,"nft_bulk_minting_add_setting_page"), 11 );
	}

	function constant () {
		define( "NFT_BULK_MINTING_PLUGIN_ROOT",	plugin_dir_path(__FILE__) ); //use plugin_dir_path(important)
	  	define( "NFT_BULK_MINTING", 'nft_bulk_minting' );
	}

	function nft_bulk_minting_add_setting_page () {
		add_submenu_page(
			"smartnft",
			esc_html__("Bulk Minting", NFT_BULK_MINTING ),
			esc_html__("Bulk Minting", NFT_BULK_MINTING ),
			"edit_theme_options",
			"smartnft-bulk-minting",
			array($this,"render_root_page"),
		);
	}

	function render_root_page () {
		echo "<div id='smartnft-root'></div>";
	}
}

new NFT_BULK_MINTING_BASE();
