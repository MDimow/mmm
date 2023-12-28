<?php 
/**
 * Plugin Name:       Smart Nft importer
 * Plugin URI:        https://smartnft.tophivetheme.com/
 * Description:       Smart NFT importer is a plugin to import nfts from other maketplace and form wallet address.
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.2
 * Author:            Tophive 
 * Author URI:        https://codecanyon.net/user/tophive
 * License:           Envato Licence 2.0
 * Text Domain:       smartnft_importer
 * Domain Path:       /languages
 */

class SMARTNFT_IMPORTER_BASE

{
	function __construct(){
		add_action("init",array($this,"init"));
	}

	function init () {
		if( class_exists( "SmartNftBase" ) ) {
			$this->define_constent();
			add_action( 'admin_menu', array($this,"smartnft_importer_add_admin_page") );
		}
	}

	function include_widgets(){}

	function define_constent () {
		define( "SNFT_IMPORTER_PLUGIN_ROOT",plugin_dir_path(__FILE__) ); //use plugin_dir_path(important)
	  	define( "SNFT_IMPORTER", 'smartnft_importer' );
	}


	function smartnft_importer_add_admin_page () {
		add_submenu_page(
			"smartnft",
			esc_html__("Importer", SNFT_IMPORTER ),
			esc_html__("Importer", SNFT_IMPORTER),
			"edit_theme_options",
			"smartnft-importer",
			array($this,"render_root_page"),
		);
	}

	function render_root_page () {
		echo "<div id='smartnft-root'></div>";
	}

	function smartnft_importer_enqueu_admin_scripts () {}
}

new SMARTNFT_IMPORTER_BASE();
