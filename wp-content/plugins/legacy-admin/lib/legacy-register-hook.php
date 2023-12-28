<?php

/**
 * @Package: WordPress Plugin
 * @Subpackage: Legacy - White Label WordPress Admin Theme
 * @Since: Legacy 1.0
 * @WordPress Version: 4.0 or above
 * This file is part of Legacy - White Label WordPress Admin Theme Plugin.
 */
//Activation Code
function legacy_admin_activation() {
}

//Deactivation Code
function legacy_admin_deactivation() {


	delete_option( "legacyadmin_plugin_access");
	delete_option( "legacyadmin_plugin_page");
	delete_option( "legacyadmin_plugin_userid");
	delete_option( "legacyadmin_menumng_page");
	delete_option( "legacyadmin_admin_menumng_page");
	delete_option( "legacyadmin_admintheme_page");
	delete_option( "legacyadmin_logintheme_page");
	delete_option( "legacyadmin_master_theme");

       delete_option("legacyadmin_menuorder");
       delete_option("legacyadmin_submenuorder");
       delete_option("legacyadmin_menurename");
       delete_option("legacyadmin_submenurename");
       delete_option("legacyadmin_menudisable");
       delete_option("legacyadmin_submenudisable");

    delete_site_option( "legacyadmin_plugin_access");
    delete_site_option( "legacyadmin_plugin_page");
    delete_site_option( "legacyadmin_plugin_userid");
    delete_site_option( "legacyadmin_menumng_page");
    delete_site_option( "legacyadmin_admin_menumng_page");
    delete_site_option( "legacyadmin_admintheme_page");
    delete_site_option( "legacyadmin_logintheme_page");
    delete_site_option( "legacyadmin_master_theme");

       delete_site_option("legacyadmin_menuorder");
       delete_site_option("legacyadmin_submenuorder");
       delete_site_option("legacyadmin_menurename");
       delete_site_option("legacyadmin_submenurename");
       delete_site_option("legacyadmin_menudisable");
       delete_site_option("legacyadmin_submenudisable");

}

?>