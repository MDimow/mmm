<?php

/*
  Plugin Name: Legacy - White Label WordPress Admin Theme
  Plugin URI: http://codecanyon.net/user/themepassion/portfolio
  Description: Advanced Admin Theme with White Label Branding for WordPress.
  Author: themepassion
  Version: 9.6
  Author URI: http://codecanyon.net/user/themepassion/portfolio
 */

/* --------------- Load Custom functions ---------------- */
require_once( trailingslashit(dirname(__FILE__)) . 'lib/legacy-functions.php' );

/* --------------- Legacy CSS based on WP Version ---------------- */
require_once( trailingslashit(dirname(__FILE__)) . 'lib/legacy-css-version.php' );

/* --------------- Custom colors ---------------- */
require_once( trailingslashit(dirname(__FILE__)) . 'lib/legacy-custom-colors.php' );

/* --------------- Color Library ---------------- */
require_once( trailingslashit(dirname(__FILE__)) . 'lib/legacy-color-lib.php' );

/* --------------- Legacy Fonts ---------------- */
require_once( trailingslashit(dirname(__FILE__)) . 'lib/legacy-fonts.php' );

/* --------------- CSS Library ---------------- */
require_once( trailingslashit(dirname(__FILE__)) . 'lib/legacy-css-lib.php' );

/* --------------- Logo and Favicon Settings ---------------- */
require_once( trailingslashit(dirname(__FILE__)) . 'lib/legacy-logo.php' );

/* --------------- Login  ---------------- */
require_once( trailingslashit(dirname(__FILE__)) . 'lib/legacy-login.php' );

/* --------------- Top Bar ---------------- */
require_once( trailingslashit(dirname(__FILE__)) . 'lib/legacy-topbar.php' );

/* --------------- Page Loader ---------------- */
require_once( trailingslashit(dirname(__FILE__)) . 'lib/legacy-pageloader.php' );

/* --------------- Admin Settings ---------------- */
require_once( trailingslashit(dirname(__FILE__)) . 'lib/legacy-settings.php' );


/* --------------- Load  framework ---------------- */

function legacy_load_framework() {


    if (!class_exists('ReduxFramework') && file_exists(dirname(__FILE__) . '/framework/core/framework.php')) {
        require_once( dirname(__FILE__) . '/framework/core/framework.php' );
    }
    if (!isset($legacy_demo) && file_exists(dirname(__FILE__) . '/framework/options/legacy-config.php')) {
        require_once( dirname(__FILE__) . '/framework/options/legacy-config.php' );
    }
}

add_action('plugins_loaded', 'legacy_load_framework', 11);


/* ---------------- Dynamic CSS - after plugins loaded ------------------ */
add_action('plugins_loaded', 'legacy_core', 12);
add_action('admin_menu', 'legacy_panel_settings', 12);


/* ---------------- On Options saved hook ------------------ */
add_action('redux/options/legacy_demo/saved', 'legacy_framework_settings_saved');




/* ------------------------------------------------
Regenerate All Color Files again - 
------------------------------------------------- */
$legacy_regenerate_css = false;
if($legacy_regenerate_css){
  add_action('plugins_loaded', 'legacy_regenerate_all_dynamic_css_file', 12);
}


/* ------------------------------------------------
Load Settings Panel only if demo_settings is present.
------------------------------------------------- */

$legacy_demo_settings = false;
if($legacy_demo_settings){
  add_action('admin_footer', 'legacy_admin_footer_function');
}

/* ------------------------------------------------
Regenerate All Inbuilt Theme import Files - 
------------------------------------------------- */

$legacy_generate_import = false;
if($legacy_generate_import){
  add_action('plugins_loaded', 'legacy_generate_inbuilt_theme_import_file', 12);
}


/* ------------------------------------------------
      Auto Update Envato Plugins using Envato WordPress toolkit and 
      Envato Automatic Plugin Update
  ------------------------------------------------- */
add_action( 'plugins_loaded', 'legacy_my_envato_updates_init' );

function legacy_my_envato_updates_init() {

    include plugin_dir_path( __FILE__ ) . 'lib/envato-plugin-update.php';

    PresetoPluginUpdateEnvato::instance()->add_item( array(
            'id' => 11272219,
            'basename' => plugin_basename( __FILE__ )
        ) );

}



/* --------------- Registration Hook Library---------------- */
require_once( trailingslashit(dirname(__FILE__)) . 'lib/legacy-register-hook.php' );
register_activation_hook(__FILE__, 'legacy_admin_activation');
register_deactivation_hook(__FILE__, 'legacy_admin_deactivation');
?>