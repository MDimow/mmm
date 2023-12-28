<?php 
/**
 * Plugin Name:       Smart Verification Badge - Add-ons
 * Plugin URI:        https://smartnft.tophivetheme.com/
 * Description:       Smart NFT is a plugin to create your own Marketplace for creating and settings NFTs. Deploy your contract, add NFTs and Sell
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.2
 * Author:            Tophive 
 * Author URI:        https://codecanyon.net/user/tophive
 * License:           Envato Licence 2.0
 * Text Domain:       smartnft-vb
 * Domain Path:       /languages
 */

class SmartNft_Verification_Badge 
{
	function __construct() {
		add_action("init",array($this,"init"));
	}

	function init () {
		if( class_exists( "SmartNftBase" ) ) {
			$this->define_constant();
			add_action( 'admin_menu', array($this,"add_setting_page") );
			add_action( "admin_enqueue_scripts", array($this,"smartnft_enqueue_backend_script"));
			add_action( "wp_enqueue_scripts", array($this,"smartnft_enqueue_frontend_script"));
		}
	}

	function define_constant () {
		define( "SMNFTVB_SCRIPT_URL",	plugins_url('assets/js/',__FILE__));
		define( "SMNFTVB_MEDIA_URL",	plugins_url('assets/images/',__FILE__));
		define( "SMNFTVB_PLUGIN_ROOT", plugin_dir_path(__FILE__) ); //use plugin_dir_path(important)

	  	define( "WP_SNFT_VB", 'smartnft-vb' );
	}

	function add_setting_page () {
		add_submenu_page(
			"smartnft",
			esc_html__("Verification Badge", WP_SNFT_VB ),
			esc_html__("Verification Badge", WP_SNFT_VB ),
			"edit_theme_options",
			"smartnft-vb",
			array($this,"render_root_page"),
		);
	}

	function render_root_page () {
		echo "<div id='smartnft-root'></div>";
	}

	function smartnft_get_local_variable () {
		$local = array(
			"MEDIA_URL"  	   => SMNFTVB_MEDIA_URL,
			"SCRIPT_URL"  	   => SMNFTVB_SCRIPT_URL,
			"BACKEND_AJAX_URL" => admin_url("admin-ajax.php"),
			"SITE_ROOT"		   => get_site_url(),
			"SITE_TITLE"       => get_bloginfo("name") ,
			"SETTINGS"         => get_option("smartnft_settings",false),
			"SLUG"			   => WP_SNFT_VB
		);

		return $local;
	}

	function smartnft_enqueue_backend_script () {}

	function smartnft_enqueue_frontend_script () {
			$smartnft_edit_profile_page_id = get_option('smartnft_edit_profile_page_id', false );
			if( is_page($smartnft_edit_profile_page_id) ) {
				wp_enqueue_script(
					"smartnft_front_edit_profile_page_vb_script_hook",
					SMNFTVB_SCRIPT_URL . 'edit-profile-addon.bundle.js',
					array("wp-i18n","jquery"),
					false,
					true
				);
			}

			if( is_tax("smartnft_collection") ){
				wp_enqueue_script(
					"smartnft_front_collection_page_vb_script_hook",
					SMNFTVB_SCRIPT_URL . 'single-coll-addon.bundle.js',
					array("wp-i18n","jquery"),
					false,
					true
				);
			}
	}

}

new SmartNft_Verification_Badge();

