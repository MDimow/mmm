<?php

/**
 * @Package: WordPress Plugin
 * @Subpackage: Legacy - White Label WordPress Admin Theme
 * @Since: Legacy 1.0
 * @WordPress Version: 4.0 or above
 * This file is part of Legacy - White Label WordPress Admin Theme Plugin.
 */
?>
<?php

/*
 * Function to select the CSS theme file based on option panel settings
 * Also it can regenerate custom CSS file and enqueue 
 *  
 */

function legacy_core() {

    global $legacy_css_ver;
    global $legacyadmin;

    $legacyadmin = legacyadmin_network($legacyadmin);

    $globalmsg = "";

    /* ----------- Check Permissions - Start --------------- */

    $get_admintheme_page = legacy_get_option("legacyadmin_admintheme_page", "enable");
    $get_logintheme_page = legacy_get_option("legacyadmin_logintheme_page", "enable");

    $adminside = true;
    if (isset($get_admintheme_page) && $get_admintheme_page == "disable") {
        $adminside = false;
    }

    $loginside = true;
    if (isset($get_logintheme_page) && $get_logintheme_page == "disable") {
        $loginside = false;
    }

    //echo $adminside; echo $loginside;

    /* ----------- Check Permissions - End--------------- */


    if ($legacy_css_ver != "") {

        /* Add Options */
        legacy_add_option("legacyadmin_menuorder", "");
        legacy_add_option("legacyadmin_submenuorder", "");
        legacy_add_option("legacyadmin_menurename", "");
        legacy_add_option("legacyadmin_submenurename", "");
        legacy_add_option("legacyadmin_menudisable", "");
        legacy_add_option("legacyadmin_submenudisable", "");

        add_action('admin_enqueue_scripts', 'legacy_disable_menu', 1);
        if ($adminside) {
            add_action('admin_enqueue_scripts', 'legacy_scripts', 1);
        }

        add_action('admin_enqueue_scripts', 'legacy_logo', 99);
        add_action('admin_enqueue_scripts', 'legacy_logo_url', 99);

        add_action('admin_enqueue_scripts', 'legacy_admintopbar', 1);
        add_action('admin_enqueue_scripts', 'legacy_admintopbar_links', 1);
        add_action('wp_enqueue_scripts', 'legacy_admintopbar_links', 1);
        add_action('wp_enqueue_scripts', 'legacy_wptopbar', 1);
        add_action('wp_before_admin_bar_render', 'legacy_topbar_logout_link');
        add_action('wp_before_admin_bar_render', 'legacy_topbar_menuids');
        add_action('admin_bar_menu', 'legacy_topbar_account_menu', 11);

        if ($adminside) {
            add_action('admin_enqueue_scripts', 'legacy_page_loader', 1);
            add_action('admin_enqueue_scripts', 'legacy_fonts', 99);
            add_action('admin_enqueue_scripts', 'legacy_admin_css', 99);
        }

        add_action('admin_enqueue_scripts', 'legacy_favicon', 99);
        add_action('admin_enqueue_scripts', 'legacy_custom_css', 99);

        add_action('admin_enqueue_scripts', 'legacy_extra_css', 99);

        /* add_action('admin_enqueue_scripts', 'legacyadmin_access', 99); */
        add_filter('admin_footer_text', 'legacy_footer_admin');
        add_action('init', 'legacy_email_settings');

        if ($adminside) {
            remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");
        }

        if ($loginside) {
            add_action('login_enqueue_scripts', 'legacy_custom_login', 99);
            add_filter('login_headerurl', 'legacy_custom_loginlogo_url');
            add_action('login_enqueue_scripts', 'legacy_login_options', 99);
            add_action('login_enqueue_scripts', 'legacy_login_custom_css', 99);
        }

        if ($adminside) {
            legacy_dynamic_css_settings();
        }

        if($adminside){ 
            add_action('admin_menu', 'legacy_screen_tabs');
        }

        $get_menumng_page = legacy_get_option( "legacyadmin_menumng_page","enable");
        if($get_menumng_page != "disable"){
            add_filter('admin_body_class', 'legacy_menumng_body_classes');
        }



    } else {
        echo "<script type='text/javascript'>console.log('Legacy WP Admin: WordPress Version Not Supported Yet!');</script>";
    }
}

function legacy_menumng_body_classes($classes) {
        return $classes." tp-menumng";
}

function legacyadmin_network($default) {

    if (is_multisite() && legacy_network_active()) {
        global $blog_id;
        $current_blog_id = $blog_id;
        switch_to_blog(1);
        $site_specific_legacyadmin = get_option("legacy_demo");
        $legacyadmin = $site_specific_legacyadmin;
        switch_to_blog($current_blog_id);
    } else {
        $legacyadmin = $default;
    }

    return $legacyadmin;
}

function legacy_dynamic_css_settings() {

    global $legacy_css_ver;

    global $legacyadmin;
    $csstype = legacy_dynamic_css_type();

    //echo "csstype: ".$csstype;

    if (isset($csstype) && $csstype != "custom") {
        // enqueue default/ inbuilt CSS styles
        add_action('admin_enqueue_scripts', 'legacy_default_css_colors', 99);
    } else {

        // load custom CSS style generated dynamically

        $css_dir = trailingslashit(plugin_dir_path(__FILE__) . '../' . $legacy_css_ver);

        // if Not multisite
        if (!is_multisite()) {
            if (is_writable($css_dir)) {
                //write the file if isn't there
                if (!file_exists($css_dir . '/legacy-colors.css')) {
                    legacy_regenerate_dynamic_css_file();
                }
                add_action('admin_enqueue_scripts', 'legacy_dynamic_enqueue_style', 99);
            } else {
                add_action('admin_head', 'legacy_wp_head_css');
            }
        } else if (is_multisite() && legacy_network_active()) {
            // multisite and network active
            if (is_writable($css_dir)) {

                global $wpdb;
                global $blog_id;
                $current_blog_id = $blog_id;

                $current_site = 1;
                switch_to_blog(1);

                //write the file if isn't there
                if (!file_exists($css_dir . '/legacy-colors-site-' . $current_site . '.css')) {

                    $site_specific_legacyadmin = get_option("legacy_demo");
                    $filename = 'site-' . $current_site;
                    //print_r($site_specific_legacyadmin);
                    legacy_regenerate_dynamic_css_file($site_specific_legacyadmin, $filename);
                }
                add_action('admin_enqueue_scripts', 'legacy_dynamic_enqueue_style', 99);

                switch_to_blog($current_blog_id);
            } else {
                add_action('admin_head', 'legacy_wp_head_css');
            }
        } else {
            // multisite and not network active
            // regenerate css file for the individual site only and enqueue it.
            if (is_writable($css_dir)) {

                global $wpdb;
                $current_site = $wpdb->blogid;

                //write the file if isn't there
                if (!file_exists($css_dir . '/legacy-colors-site-' . $current_site . '.css')) {

                    $site_specific_legacyadmin = get_option("legacy_demo");
                    $filename = 'site-' . $current_site;
                    //print_r($site_specific_legacyadmin);
                    legacy_regenerate_dynamic_css_file($site_specific_legacyadmin, $filename);
                }
                add_action('admin_enqueue_scripts', 'legacy_dynamic_enqueue_style', 99);
            } else {
                add_action('admin_head', 'legacy_wp_head_css');
            }
        }
    }
}

function legacy_framework_settings_saved() {

    global $legacy_css_ver;
    global $legacyadmin;

    $css_dir = trailingslashit(plugin_dir_path(__FILE__) . '../' . $legacy_css_ver);

    // if Not multisite
    if (!is_multisite()) {

        if (is_writable($css_dir)) {
            legacy_regenerate_dynamic_css_file();
        }
    } else if (is_multisite() && legacy_network_active()) {
        global $wpdb;
        $current_blog_id = $wpdb->blogid;
        $current_site = 1;
        switch_to_blog(1);

        $site_specific_legacyadmin = get_option("legacy_demo");
        $filename = 'site-' . $current_site;
        //print_r($site_specific_legacyadmin);
        legacy_regenerate_dynamic_css_file($site_specific_legacyadmin, $filename);
        switch_to_blog($current_blog_id);
    } else {

        // multisite
        // regenerate css file for the individual site only

        if (is_writable($css_dir)) {

            global $wpdb;
            $current_site = $wpdb->blogid;

            $site_specific_legacyadmin = get_option("legacy_demo");
            $filename = 'site-' . $current_site;
            //print_r($site_specific_legacyadmin);
            legacy_regenerate_dynamic_css_file($site_specific_legacyadmin, $filename);
        }
    }
}

function legacy_scripts() {
    global $legacyadmin;
    $url = plugins_url('/', __FILE__) . '../js/legacy-scripts.js';
    wp_deregister_script('legacy-scripts-js');
    wp_register_script('legacy-scripts-js', $url);
    wp_enqueue_script('legacy-scripts-js');

    $url = plugins_url('/', __FILE__) . '../js/legacy-smoothscroll.min.js';
    wp_deregister_script('legacy-smoothscroll-js');
    wp_register_script('legacy-smoothscroll-js', $url);
    wp_enqueue_script('legacy-smoothscroll-js');
    
    wp_localize_script('legacy-scripts-js', 'legacy_vars', array(
        'legacy_nonce' => wp_create_nonce('legacy-nonce')
            )
    );


    if (file_exists(plugin_dir_path(__FILE__) . '../demo-settings/legacy-settings-panel-css.css')) {
        wp_deregister_style('legacy-settings-panel-css');
        wp_register_style('legacy-settings-panel-css', plugins_url('/', __FILE__) . "../demo-settings/legacy-settings-panel-css.css");
        wp_enqueue_style('legacy-settings-panel-css');
    }

    if (file_exists(plugin_dir_path(__FILE__) . '../demo-settings/legacy-settings-panel-js.js')) {
        wp_deregister_script('legacy-settings-panel-js');
        wp_register_script('legacy-settings-panel-js', plugins_url('/', __FILE__) . "../demo-settings/legacy-settings-panel-js.js");
        wp_enqueue_script('legacy-settings-panel-js');
    }
}

function legacy_admin_css() {
    global $legacy_css_ver;

    $url = plugins_url('/', __FILE__) . '../' . $legacy_css_ver . '/legacy-admin.min.css';
    wp_deregister_style('legacy-admin', $url);
    wp_register_style('legacy-admin', $url);
    wp_enqueue_style('legacy-admin');

    /* ame */
    $url = plugins_url('/', __FILE__) . '../css/legacy-ame.min.css';
    wp_deregister_style('legacy-ame', $url);
    wp_register_style('legacy-ame', $url);
    wp_enqueue_style('legacy-ame');

    /* wordfence */
    $url = plugins_url('/', __FILE__) . '../css/legacy-wordfence.min.css';
    wp_deregister_style('legacy-wordfence', $url);
    wp_register_style('legacy-wordfence', $url);
    wp_enqueue_style('legacy-wordfence');

    /* other plugins compatibility */
    $url = plugins_url('/', __FILE__) . '../css/legacy-external.min.css';
    wp_deregister_style('legacy-external', $url);
    wp_register_style('legacy-external', $url);
    wp_enqueue_style('legacy-external');
}


function legacy_dynamic_css_type() {

    //global $wpdb;
    //echo $wpdb->blogid;

    global $legacy_css_ver;
    global $legacyadmin;


    $csstype = "custom";

    if (is_multisite()) {

        global $blog_id;
        $current_blog_id = $blog_id;
        $network_active = legacy_network_active();

        //echo "<br><br>id:".$current_blog_id;

        if ($network_active) {
            //if network activate, switch to main blog
            switch_to_blog(1);
        }

        //echo $blog_id;
        // get current site framework options and thus gets it csstype value
        $current_site = get_option("legacy_demo");
        if (isset($current_site['dynamic-css-type'])) {
            $csstype = $current_site['dynamic-css-type'];
        }
        //print_r($current_site);
        //echo $csstype;
        if ($network_active) {
            // switch back to current blog again if network active
            switch_to_blog($current_blog_id);
        }
        //echo $blog_id;
    } else {


        if(!isset($legacyadmin) || (isset($legacyadmin) && is_array($legacyadmin) && sizeof($legacyadmin) == 0 )){
            $legacyadmin = get_option("legacy_demo");
        }

        if (isset($legacyadmin['dynamic-css-type'])) {
            $csstype = $legacyadmin['dynamic-css-type'];
        }
    }

    /* --------------- Legacy Settings Panel - for demo purposes ---------------- */
    if (!has_action('plugins_loaded', 'legacy_regenerate_all_dynamic_css_file') && has_action('admin_footer', 'legacy_admin_footer_function')) {
        if (file_exists(plugin_dir_path(__FILE__) . '../demo-settings/legacy-settings-panel-session.php')) {
            include( trailingslashit(dirname(__FILE__)) . '../demo-settings/legacy-settings-panel-session.php' );
        }
    }
    //echo $csstype;
    return $csstype;
}

function legacy_default_css_colors() {
    global $legacy_css_ver;
    global $legacyadmin;
    $csstype = legacy_dynamic_css_type();
    //echo "default:".$csstype;
    $css_path = trailingslashit(plugins_url('/', __FILE__) . '../' . $legacy_css_ver . '/colors');
    $css_dir = trailingslashit(plugin_dir_path(__FILE__) . '../' . $legacy_css_ver . '/colors');

    if (isset($csstype) && $csstype != "custom" && trim($csstype) != "") {

        $style_color = trim($csstype);

        if (file_exists($css_dir . 'legacy-colors-' . $style_color . '.css')) {
            wp_deregister_style('legacy-colors');
            wp_register_style('legacy-colors', $css_path . 'legacy-colors-' . $style_color . '.css');
            wp_enqueue_style('legacy-colors');
        } else {
            // enqueue the default legacy-colors.css file   
            legacy_dynamic_enqueue_style();
        }
    }
}

function legacy_dynamic_enqueue_style() {
    global $legacy_css_ver;

    if (!is_multisite()) {
        $url = plugins_url('/', __FILE__) . '../' . $legacy_css_ver . '/legacy-colors.css';
    } else if (is_multisite() && legacy_network_active()) {
        // IF NETWORK ACTIVE
        global $wpdb;
        $current_site = 1;
        $url = plugins_url('/', __FILE__) . '../' . $legacy_css_ver . '/legacy-colors-site-' . $current_site . '.css';
    } else {
        // IF NOT NETWORK ACTIVE - FOR INDIVIDUAL SITES ONLY
        global $wpdb;
        $current_site = $wpdb->blogid;
        $url = plugins_url('/', __FILE__) . '../' . $legacy_css_ver . '/legacy-colors-site-' . $current_site . '.css';
    }
    wp_deregister_style('legacy-colors');
    wp_register_style('legacy-colors', $url);
    wp_enqueue_style('legacy-colors');

    $style_type = 'custom';
}

function legacy_wp_head_css() {

    global $legacy_css_ver;
    global $legacyadmin;

    global $wpdb;
    $current_blog_id = $wpdb->blogid;

    if (is_multisite() && legacy_network_active()) {
        switch_to_blog(1);
        $site_specific_legacyadmin = get_option("legacy_demo");
        $legacyadmin = $site_specific_legacyadmin;
        switch_to_blog($current_blog_id);
    }
    //print_r($legacyadmin);

    echo '<style type="text/css">';

    $dynamic_css_file = trailingslashit(plugin_dir_path(__FILE__) . '../' . $legacy_css_ver) . 'dynamic_css.php';

    // buffer css 
    ob_start();
    require($dynamic_css_file); // Generate CSS
    $dynamic_css = ob_get_contents();
    ob_get_clean();

    // compress css
    $dynamic_css = legacy_compress_css($dynamic_css);

    echo $dynamic_css;
    echo '</style>';

    $style_type = 'custom';
}

/* ------------ Generate / Update dynamic CSS file on saving / changing plugin settings ---------- */

function legacy_regenerate_dynamic_css_file($newlegacyadmin = array(), $filename = "", $basedir = "") {

    global $legacy_css_ver;
    global $legacyadmin;
    if (sizeof($legacyadmin) == 0) {
        $legacyadmin = get_option("legacy_demo");
    }
    if (is_array($newlegacyadmin) && sizeof($newlegacyadmin) > 0) {
        $legacyadmin = $newlegacyadmin;
    }


    global $legacy_color;

    $newfilename = "legacy-colors";
    if (trim($filename) != "") {
        $newfilename = "legacy-colors-" . $filename;
    }

    $dynamic_css = trailingslashit(plugin_dir_path(__FILE__) . '../' . $legacy_css_ver) . 'dynamic_css.php';
    ob_start(); // Capture all output (output buffering)
    require($dynamic_css); // Generate CSS
    $css = ob_get_clean(); // Get generated CSS (output buffering)
    $css = legacy_compress_css($css);

    //echo "<hr>"; echo $css; echo "</div>";

    $css_dir = trailingslashit(plugin_dir_path(__FILE__) . '../' . $legacy_css_ver);

    if (isset($basedir) && $basedir != "") {
        $css_dir = $basedir;
    }

    require_once(ABSPATH . 'wp-admin/includes/file.php');
    WP_Filesystem();
    global $wp_filesystem;
    if (!$wp_filesystem->put_contents($css_dir . '/' . $newfilename . '.css', $css, 0644)) {
        return true;
    }
}

/* * *****************
 * legacy_regenerate_all_dynamic_css_file();
 * Generate all Colors CSS files Function
 * Function called in main plugin file
 * ******************* */

function legacy_regenerate_all_dynamic_css_file() {

    global $legacy_css_ver;
    global $legacyadmin;

    if (isset($legacyadmin) && is_array($legacyadmin) && sizeof($legacyadmin) == 0) {
        //switch_to_blog(1);
        $get_legacyadmin = get_option("legacy_demo");
        $legacyadmin = $get_legacyadmin;
    }

    $legacyadmin_backup = $legacyadmin;
    //echo "hi";
    //print_r($legacyadmin_backup);
    //die();

    global $legacy_color;

    $basedir = trailingslashit(plugin_dir_path(__FILE__) . '../' . $legacy_css_ver . '/colors');
    // loop through each color
    foreach ($legacy_color as $filename => $dyn_data) {
        $legacyadmin = legacy_newdata($dyn_data);
        //echo $filename."<pre>"; print_r($legacyadmin); echo "</pre>";
        //regenerate new css file
        legacy_regenerate_dynamic_css_file($legacyadmin, $filename, $basedir);
        $legacyadmin = $legacyadmin_backup;
    }

    // V. Imp to restore the original $data in variable back.
    $legacyadmin = $legacyadmin_backup;
    //die;
}

function legacy_newdata($dyn_data) {

    global $legacy_css_ver;
    global $legacyadmin;
 
    // loop through dynamic values
    foreach ($dyn_data as $type => $val) {
        // string type options
        if (!is_array($val) && trim($val) != "") {
            $legacyadmin[$type] = $val;
        }

        // array type options
        if (is_array($val) && sizeof($val) > 0) {
            foreach ($val as $type2 => $val2) {
                if (!is_array($val2) && trim($val2) != "") {
                    $legacyadmin[$type][$type2] = $val2;
                }
            }
        }
    }

    return $legacyadmin;
}

function legacy_compress_css($css) {

    /* remove comments */
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

    /* remove tabs, spaces, newlines, etc. */
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
    return $css;
}

function legacyadmin_access() {

    global $legacyadmin;
    $str = "";

    $element = 'enable-allusers-legacyadmin';
    if (isset($legacyadmin[$element]) && $legacyadmin[$element] != "1" && $legacyadmin[$element] == "0" && !$legacyadmin[$element]) {
        if (!is_admin()) {
            $str .= ".toplevel_page__legacyoptions{display:none;}";
            $str .= "#wp-admin-bar-_legacyoptions{display:none;}";
        }
    }

    echo "<style type='text/css'>" . $str . "</style>";
}

function legacy_custom_css() {

    global $legacyadmin;
    $legacyadmin = legacyadmin_network($legacyadmin);

    $str = "";

    //print_r($legacyadmin);

    $element = 'custom-css';
    if (isset($legacyadmin[$element]) && trim($legacyadmin[$element]) != "") {
        $str .= $legacyadmin[$element];
    }

    echo "<style type='text/css' id='legacy-custom-css'>" . $str . "</style>";
}


function legacy_login_custom_css(){

       global $legacyadmin;
       $legacyadmin = legacyadmin_network($legacyadmin);

       $str = "";

        $element = 'custom-login-css';
        if(isset($legacyadmin[$element]) && trim($legacyadmin[$element]) != ""){
                $str .= $legacyadmin[$element];
        }

        echo "<style type='text/css' id='legacy-custom-login-css'>".$str."</style>";
}
function legacy_extra_css() {

    global $legacyadmin;

    $legacyadmin = legacyadmin_network($legacyadmin);
    //print_r($legacyadmin);

    $transform = "uppercase";
    $style = "";
    $upgrade = "inline";


    /* ----------------- */
    /* Check admin side theme permission */
    $get_admintheme_page = legacy_get_option("legacyadmin_admintheme_page", "enable");

    $adminside = true;
    if (isset($get_admintheme_page) && $get_admintheme_page == "disable") {
        $adminside = false;
    }
    //echo $adminside;

    if ($adminside) {
        $element = 'menu-transform-text';
        if (isset($legacyadmin[$element]) && trim($legacyadmin[$element]) != "") {
            $transform = $legacyadmin[$element];
        }
        $style .= " #adminmenu .wp-submenu-head, #adminmenu a.menu-top { text-transform:" . $transform . " !important; } ";
    }

    /* ----------------- */


    $element = 'footer_version';
    if (isset($legacyadmin[$element]) && trim($legacyadmin[$element]) != "") {
        if ($legacyadmin[$element] == "0") {
            $upgrade = "none";
        }
    }
    $style .= " #wpfooter #footer-upgrade { display:" . $upgrade . " !important; } ";

    echo "<style type='text/css' id='legacy-extra-css'>" . $style . "</style>";
}

function legacy_disable_menu() {

    $str = "";
    $menudisable = get_option("legacyadmin_menudisable", "");
    $exp = array_unique(array_filter(explode("|", $menudisable)));
    foreach ($exp as $menuid) {
        $str .= "#" . $menuid . ", ";
    }

    $str = substr($str, 0, -2);

}

function legacyprint($name, $arr) {

    echo "<div style='max-height:400px;overflow:auto;width:500px;'>";
    echo $name;
    echo "<pre>";
    print_r($arr);
    echo "</pre></div>";
}

//change admin footer text
function legacy_footer_admin() {

    global $legacyadmin;

    $legacyadmin = legacyadmin_network($legacyadmin);

    $str = 'Thank you for creating with <a href="https://wordpress.org/">WordPress</a> and <a target="_blank" href="http://codecanyon.net/item/legacy-wordpress-admin-theme/9220673">Legacy - White Label WordPress Admin Theme</a>';
    //print_r($legacyadmin);


    $element = 'footer_text';
    if (isset($legacyadmin[$element]) && trim($legacyadmin[$element]) != "") {
        $str = $legacyadmin[$element];
    }

    echo $str;

    $get_menumng_page = legacy_get_option( "legacyadmin_menumng_page","enable");
    if($get_menumng_page != "disable"){
        legacy_menu_management_counts();
    }

}

function legacy_menu_management_counts(){

    //echo "<div style='float:right;position:fixed;background:#fff;top:80px;right:10px;'><pre>";

    $counts = wp_get_update_data();
    $str = "<script type='text/javascript'>";
    foreach ($counts as $key => $value) {
        if($key == "counts" && is_array($value) && sizeof($value) > 0){
            //print_r($value);
            foreach ($value as $ele => $no) {
                if($ele == "plugins"){
                    $str .= "jQuery('#menu-plugins .wp-menu-name').append('<div class=\'tpcount count-".$no." \'>".$no."</div>');
                    ";
                }
                if($ele == "total"){
                    $str .= "jQuery('#menu-dashboard a[href=\'update-core.php\']').append('<div class=\'tpcount count-".$no." \'>".$no."</div>');
                    ";
                }
                //menu-dashboard
            }
        }
        //echo $key;
    }

    $comment = wp_count_comments();
    foreach ($comment as $key => $value) {
        if($key == "moderated"){
            $str .= "jQuery('#menu-comments .wp-menu-name').append('<div class=\'tpcount awaiting-mod count-".$value."\'>".$value."</div>');
                    ";
        }
        //echo $key.$value." | ";
    }


    echo $str;
    echo "</script>";
    //echo "</pre></div>";
    //die();

}


function legacy_multisite_allsites() {

    $arr = array();
    // get all blogs
    $blogs = get_blog_list(0, 'all');

    if (0 < count($blogs)) :
        foreach ($blogs as $blog) :
            switch_to_blog($blog['blog_id']);

            if (get_theme_mod('show_in_home', 'on') !== 'on') {
                continue;
            }

            $blog_details = get_blog_details($blog['blog_id']);
            //print_r($blog_details);
            //echo "<div style='height:200px; overflow:auto;width:100%;'>"; print_r(get_blog_option( $blog[ 'blog_id' ], 'legacy_demo' )); echo "</div>";

            $id = $blog['blog_id'];
            $name = $blog_details->blogname;
            $arr[$id] = $name;

        endforeach;
    endif;

    return $arr;
}

function legacy_network_active() {


    if (!function_exists('is_plugin_active_for_network')) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

    // Makes sure the plugin is defined before trying to use it
    if (is_plugin_active_for_network('legacy-admin/legacy-core.php')) {
        return true;
    }

    return false;
}

function legacy_add_option($variable, $default) {
    if (legacy_network_active()) {
        add_site_option($variable, $default);
    } else {
        add_option($variable, $default);
    }
}

function legacy_get_option($variable, $default) {
    if (legacy_network_active()) {
        return get_site_option($variable, $default);
    } else {
        return get_option($variable, $default);
    }
}

function legacy_update_option($variable, $default) {
    if (legacy_network_active()) {
        update_site_option($variable, $default);
    } else {
        update_option($variable, $default);
    }
}

function legacy_get_user_type() {
    $get_admin_menumng_page = legacy_get_option("legacyadmin_admin_menumng_page", "enable");

    $enablemenumng = true;
    if ((is_super_admin() || current_user_can('manage_options')) && $get_admin_menumng_page == "disable") {
        $enablemenumng = false;
    }
    return $enablemenumng;
}

function legacy_generate_inbuilt_theme_import_file() {
    global $legacy_color;
    foreach ($legacy_color as $key => $value) {
        $str = "";
        $str .= '{"dynamic-css-type":"custom","primary-color":"' . $value['primary-color'] . '",';
        $str .= '"page-bg":{"background-color":"' . $value['page-bg']['background-color'] . '"},';
        $str .= '"heading-color":"' . $value['heading-color'] . '",';
        $str .= '"body-text-color":"' . $value['body-text-color'] . '",';
        $str .= '"link-color":{"regular":"' . $value['link-color']['regular'] . '","hover":"' . $value['link-color']['hover'] . '"},';
        $str .= '"menu-bg":{"background-color":"' . $value['menu-bg']['background-color'] . '"},';
        $str .= '"menu-color":"' . $value['menu-color'] . '",';
        $str .= '"menu-hover-color":"' . $value['menu-hover-color'] . '",';
        $str .= '"submenu-color":"' . $value['submenu-color'] . '",';
        $str .= '"menu-primary-bg":"' . $value['menu-primary-bg'] . '",';
        $str .= '"menu-secondary-bg":"' . $value['menu-secondary-bg'] . '",';
        $str .= '"menu-icon-line-bg":{"background-color":"' . $value['menu-icon-line-bg']['background-color'] . '"},';
        $str .= '"menu-icon-color":"' . $value['menu-icon-color'] . '",';
        $str .= '"menu-icon-bg":{"background-color":"' . $value['menu-icon-bg']['background-color'] . '"},';
        $str .= '"menu-active-icon-color":"' . $value['menu-active-icon-color'] . '",';
        $str .= '"menu-active-icon-bg":{"background-color":"' . $value['menu-active-icon-bg']['background-color'] . '"},';
        $str .= '"submenu-active-icon-bg":{"background-color":"' . $value['submenu-active-icon-bg']['background-color'] . '"},';
        $str .= '"logo-bg":"' . $value['logo-bg'] . '",';
        $str .= '"box-bg":{"background-color":"' . $value['box-bg']['background-color'] . '"},';
        $str .= '"box-head-bg":{"background-color":"' . $value['box-head-bg']['background-color'] . '"},';
        $str .= '"box-head-color":"' . $value['box-head-color'] . '",';
        $str .= '"button-primary-bg":"' . $value['button-primary-bg'] . '",';
        $str .= '"button-primary-hover-bg":"' . $value['button-primary-hover-bg'] . '",';
        $str .= '"button-secondary-bg":"' . $value['button-secondary-bg'] . '",';
        $str .= '"button-secondary-hover-bg":"' . $value['button-secondary-hover-bg'] . '",';
        $str .= '"button-text-color":"' . $value['button-text-color'] . '",';
        $str .= '"form-bg":"' . $value['form-bg'] . '",';
        $str .= '"form-text-color":"' . $value['form-text-color'] . '",';
        $str .= '"form-border-color":"' . $value['form-border-color'] . '",';
        $str .= '"topbar-menu-color":"' . $value['topbar-menu-color'] . '",';
        $str .= '"topbar-menu-bg":{"background-color":"' . $value['topbar-menu-bg']['background-color'] . '"},';
        $str .= '"topbar-submenu-color":"' . $value['topbar-submenu-color'] . '",';
        $str .= '"topbar-submenu-bg":"' . $value['topbar-submenu-bg'] . '",';
        $str .= '"topbar-submenu-hover-bg":"' . $value['topbar-submenu-hover-bg'] . '","redux_import_export":"","redux-backup":1}';

        legacy_inbuilttheme_file_create($key, $str);
    }
}

function legacy_inbuilttheme_file_create($filename, $str) {

    if (trim($filename) != "" && trim($str) != "") {
        $css_dir = trailingslashit(plugin_dir_path(__FILE__) . '../inbuilt_themes_import');

        require_once(ABSPATH . 'wp-admin/includes/file.php');
        WP_Filesystem();
        global $wp_filesystem;
        if (!$wp_filesystem->put_contents($css_dir . '/' . $filename . '.txt', $str, 0644)) {
            return true;
        }
    }
}

function legacy_admin_footer_function() {


    /* --------------- Settings Panel ----------------- */
    if (!has_action('plugins_loaded', 'legacy_regenerate_all_dynamic_css_file')) {
        if (file_exists(plugin_dir_path(__FILE__) . '../demo-settings/legacy-settings-panel.php')) {
            require_once( trailingslashit(dirname(__FILE__)) . '../demo-settings/legacy-settings-panel.php' );
        }
    }
}


//add_action('wp_ajax_legacy_alternate_save', 'legacy_alternate_save_logic');

function legacy_alternate_save_logic() {
    
    if (!isset($_POST['legacy_nonce']) || !wp_verify_nonce($_POST['legacy_nonce'], 'legacy-nonce')) {
        die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
    }

    //print_r($_POST);
    $values = $_POST['values'];
    
    $values = stripslashes($_POST["values"]);
    
    $values='';

    //echo $values;

    //legacy_update_option("legacy_demo", $values);
    die();
}



function legacy_screen_tabs(){


    global $legacy_css_ver;
    global $legacyadmin;

    $legacyadmin = legacyadmin_network($legacyadmin);

            /*Remove Screen Option & Help Tabs*/
    
            $screenoption = true;
            $element = 'screen_option_tab';

            //echo $legacyadmin[$element];

            if(isset($legacyadmin[$element]) && trim($legacyadmin[$element]) != ""){
                if($legacyadmin[$element] == "0"){
                    $screenoption = false;
            }}

            $screenhelp = true;
            $element = 'screen_help_tab';
            if(isset($legacyadmin[$element]) && trim($legacyadmin[$element]) != ""){
                if($legacyadmin[$element] == "0"){
                    $screenhelp = false;
            }}

            if(!$screenoption){
                add_filter('screen_options_show_screen', '__return_false');
            }

            if(!$screenhelp){
                add_action('admin_head', 'legacy_remove_help_tabs');
            }

}

function legacy_remove_help_tabs() {
    $screen = get_current_screen();
    $screen->remove_help_tabs();
}



add_filter('admin_title', 'legacy_admin_title_update', 10, 2);

function legacy_admin_title_update($admin_title, $title)
{
    return get_bloginfo('name').' &bull; '.$title;
}



function legacy_email_settings(){
    global $legacyadmin;
    $legacyadmin = legacyadmin_network($legacyadmin);       

    if(isset($legacyadmin['from-mail-email']) && trim($legacyadmin['from-mail-email']) != ""){
       // wp_mail_from
        add_filter('wp_mail_from', 'legacy_from_mail');
    
    }     

    if(isset($legacyadmin['from-mail-name']) && trim($legacyadmin['from-mail-name']) != ""){
        // wp_mail_from_name
        add_filter('wp_mail_from_name', 'legacy_from_mail_name');
    }


}

function legacy_from_mail($original_email_address) {

    global $legacyadmin;
    $legacyadmin = legacyadmin_network($legacyadmin);       
    $ret = $legacyadmin['from-mail-email'];
    // $ret = "info@domain.com";
    return $ret;
}

function legacy_from_mail_name($original_email_address_name) {
     global $legacyadmin;
     $legacyadmin = legacyadmin_network($legacyadmin);       
     $ret = $legacyadmin['from-mail-name'];
    //  $ret = "info";
     return $ret;
 }

?>