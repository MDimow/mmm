<?php 
/**
 * Plugin Name:       Smart NFT 1155 - Add-ons
 * Plugin URI:        https://smartnft.tophivetheme.com/
 * Description:       Smart NFT is a plugin to create your own Marketplace for creating and settings NFTs. Deploy your contract, add NFTs and Sell
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.2
 * Author:            Tophive 
 * Author URI:        https://codecanyon.net/user/tophive
 * License:           Envato Licence 2.0
 * Text Domain:       smartnft-1155
 * Domain Path:       /languages
 */

class SmartNft_1155_Base 
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
		define( "SMNFT_SCRIPT_URL",	plugins_url('assets/js/',__FILE__));
		define( "SMNFT_MEDIA_URL",	plugins_url('assets/images/',__FILE__));
		define( "SMNFT_PLUGIN_ROOT", plugin_dir_path(__FILE__) ); //use plugin_dir_path(important)

	  	define( "WP_SNFT_1155", 'smartnft-1155' );
	}

	function smartnft_get_local_variable () {
		$local = array(
			"MEDIA_URL"  	   => SMNFT_MEDIA_URL,
			"SCRIPT_URL"  	   => SMNFT_SCRIPT_URL,
			"BACKEND_AJAX_URL" => admin_url("admin-ajax.php"),
			"SITE_ROOT"		   => get_site_url(),
			"SITE_TITLE"       => get_bloginfo("name") ,
			"SETTINGS"         => get_option("smartnft_settings",false),
			"SLUG"			   => WP_SNFT_1155
		);

		return $local;
	}

	function smartnft_enqueue_backend_script () {
			$local = $this->smartnft_get_local_variable();

			if(
				isset( $_GET["page"] ) && $_GET["page"] === "smartnft_new"  ||
				isset( $_GET["page"] ) && $_GET["page"] === "smartnft" 
		   	  ) {
					wp_enqueue_script(
						"smartnft_1155_addon_js_for_create_nft",
						SMNFT_SCRIPT_URL . 'createnft-1155-addon.bundle.js',
						array("wp-i18n","jquery"),
						false,
						true
					);
					wp_set_script_translations( 'smartnft_1155_addon_js_for_create_nft', WP_SNFT_1155 );
					wp_localize_script( "smartnft_1155_addon_js_for_create_nft", "local_1155", $local );
				}

				if( isset( $_GET["page"] ) && $_GET["page"] === "smartnft-contracts" ) {
					wp_enqueue_script(
						"smartnft_1155_addon_js_for_deploy_contract",
						SMNFT_SCRIPT_URL . 'deploy-contract-1155-addon.bundle.js',
						array("wp-i18n","jquery"),
						false,
						true
					);
					wp_set_script_translations( 'smartnft_1155_addon_js_for_deploy_contract', WP_SNFT_1155);
					wp_localize_script( "smartnft_1155_addon_js_for_deploy_contract", "local_1155", $local );
				}

				if( isset( $_GET["page"] ) && $_GET["page"] === "smartnft-collections" ) {
					wp_enqueue_script(
						"smartnft_1155_addon_js_for_coll_page",
						SMNFT_SCRIPT_URL . 'createcoll-1155-addon.bundle.js',
						array("wp-i18n","jquery"),
						false,
						true
					);
					wp_set_script_translations( 'smartnft_1155_addon_js_for_coll_page', WP_SNFT_1155);
					wp_localize_script( "smartnft_1155_addon_js_for_coll_page", "local_1155", $local );
				}


	}

	function smartnft_enqueue_frontend_script () {
			$local = $this->smartnft_get_local_variable();

			$smartnft_create_collection_page_id = get_option('smartnft_create_collection_page_id', false );
			if( is_page($smartnft_create_collection_page_id) ) {
				wp_enqueue_script(
					"smartnft_1155_addon_js_for_coll_page",
					SMNFT_SCRIPT_URL . 'createcoll-1155-addon.bundle.js',
					array("wp-i18n","jquery"),
					false,
					true
				);
				wp_set_script_translations( 'smartnft_1155_addon_js_for_coll_page', WP_SNFT_1155);
				wp_localize_script( "smartnft_1155_addon_js_for_coll_page", "local_1155", $local );
			}

			wp_enqueue_script(
				"smartnft_1155_addon_js_for_create_nft",
				SMNFT_SCRIPT_URL . 'createnft-1155-addon.bundle.js',
				array("wp-i18n","jquery"),
				false,
				true
			);
			wp_set_script_translations( 'smartnft_1155_addon_js_for_create_nft', WP_SNFT_1155 );
			wp_localize_script( "smartnft_1155_addon_js_for_create_nft", "local_1155", $local );


	}

}

new SmartNft_1155_Base();

