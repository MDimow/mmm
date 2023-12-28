<?php 
/**
 * Plugin Name:       Smart Nft Auction
 * Plugin URI:        https://smartnft.tophivetheme.com/
 * Description:       Smart NFT is a plugin to create your own Marketplace for creating and settings NFTs. Deploy your contract, add NFTs and Sell
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.2
 * Author:            Tophive 
 * Author URI:        https://codecanyon.net/user/tophive
 * License:           Envato Licence 2.0
 * Text Domain:       smartnft-auction
 * Domain Path:       /languages
 */

class SmartNft_Auction_Base 
{
	function __construct() {
		add_action("init",array($this,"init"));
	}

	function init () {
		if( class_exists( "SmartNftBase" ) ) {
			$this->define_constant();
			add_action( "admin_enqueue_scripts", array($this,"smartnft_enqueue_backend_script"));
			add_action( "wp_enqueue_scripts", array($this,"smartnft_enqueue_frontend_script"));
		}
	}

	function define_constant () {
		define( "SMNFT_AC_SCRIPT_URL",	plugins_url('assets/js/',__FILE__));
		define( "SMNFT_AC_MEDIA_URL",	plugins_url('assets/images/',__FILE__));
		define( "SMNFT_AC_PLUGIN_ROOT", plugin_dir_path(__FILE__) ); //use plugin_dir_path(important)
	  	define( "WP_SNFT_AUCTION", 'smartnft-auction' );
	}


	function smartnft_get_local_variable () {
		$local = array(
			"MEDIA_URL"  	   => SMNFT_AC_MEDIA_URL,
			"SCRIPT_URL"  	   => SMNFT_AC_SCRIPT_URL,
			"BACKEND_AJAX_URL" => admin_url("admin-ajax.php"),
			"SITE_ROOT"		   => get_site_url(),
			"SITE_TITLE"       => get_bloginfo("name") ,
			"SETTINGS"         => get_option("smartnft_settings",false),
			"SLUG"			   => WP_SNFT_AUCTION
		);

		return $local;
	}

	function smartnft_enqueue_backend_script () {
			$local = $this->smartnft_get_local_variable();

			if( isset( $_GET["page"] ) && $_GET["page"] === "smartnft_new" || 
				isset( $_GET["page"] ) && $_GET["page"] === "smartnft"
		      )
			  {
					wp_enqueue_script(
						"smartnft_auction_addon_js_for_create_nft",
						SMNFT_AC_SCRIPT_URL . 'createnft-auction-addon.bundle.js',
						array("wp-i18n","jquery"),
						false,
						true
					);
					wp_set_script_translations( 'smartnft_auction_addon_js_for_create_nft', WP_SNFT_AUCTION );
					wp_localize_script( "smartnft_auction_addon_js_for_create_nft", "local_auction", $local );
			  }

			if( isset( $_GET["page"] ) && $_GET["page"] === "smartnft-contracts")
			  {
					wp_enqueue_script(
						"smartnft_auction_addon_js_for_deploy_contract",
						SMNFT_AC_SCRIPT_URL . 'deploy-contract-auction-addon.bundle.js',
						array("wp-i18n","jquery"),
						false,
						true
					);
					wp_set_script_translations( 'smartnft_auction_addon_js_for_deploy_contract', WP_SNFT_AUCTION );
					wp_localize_script( "smartnft_auction_addon_js_for_deploy_contract", "local_auction", $local );
			  }

	}

	function smartnft_enqueue_frontend_script () {
			$local = $this->smartnft_get_local_variable();

			wp_enqueue_script(
				"smartnft_auction_addon_js_for_create_nft",
				SMNFT_AC_SCRIPT_URL . 'createnft-auction-addon.bundle.js',
				array("wp-i18n","jquery"),
				false,
				true
			);
			wp_set_script_translations( 'smartnft_auction_addon_js_for_create_nft', WP_SNFT_AUCTION );
			wp_localize_script( "smartnft_auction_addon_js_for_create_nft", "local_auction", $local );
	}

}

new SmartNft_Auction_Base();

