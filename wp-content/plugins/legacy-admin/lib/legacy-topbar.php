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

function legacy_admintopbar() {
    global $legacyadmin;
    $legacyadmin = legacyadmin_network($legacyadmin);

    if (isset($legacyadmin['enable-topbar']) && $legacyadmin['enable-topbar'] != "1" && $legacyadmin['enable-topbar'] == "0" && !$legacyadmin['enable-topbar']) {
        echo "<style type='text/css'>#wpadminbar{display: none !important;} html.wp-toolbar{padding-top:0px !important;} </style>";
    }
}

function legacy_wptopbar() {
    global $legacyadmin;
    $legacyadmin = legacyadmin_network($legacyadmin);

    if (isset($legacyadmin['enable-topbar-wp']) && $legacyadmin['enable-topbar-wp'] != "1" && $legacyadmin['enable-topbar-wp'] == "0" && !$legacyadmin['enable-topbar-wp']) {
        remove_action('wp_footer', 'wp_admin_bar_render', 9);
        add_filter('show_admin_bar', '__return_false');
    }
}

function legacy_admintopbar_style() {
    global $legacyadmin;
    $legacyadmin = legacyadmin_network($legacyadmin);

       $logomargintop = "-40px";

    if(isset($legacyadmin['enable-topbar']) && $legacyadmin['enable-topbar'] != "1" && $legacyadmin['enable-topbar'] == "0" && !$legacyadmin['enable-topbar']){
        $logomargintop = "0px";
    }



    if (isset($legacyadmin['topbar-style']) && $legacyadmin['topbar-style'] != "style1") {
        return " #adminmenuback{z-index: 99998 !important;} 
        #adminmenuwrap{margin-top: ".$logomargintop." !important;z-index: 99999 !important;} 
        .folded #wpadminbar{padding-left: 46px !important;} 
        #wpadminbar{padding-left: 245px !important;z-index: 9999 !important;}
        .menu-hidden #wpadminbar{padding-left: 0px !important;}         
        .menu-expanded #wpadminbar{padding-left: 245px !important;}         
        .menu-collapsed #wpadminbar{padding-left: 46px !important;} 
        
        .rtl #adminmenuback{z-index: 99998 !important;} 
        .rtl #adminmenuwrap{margin-top: ".$logomargintop." !important;z-index: 99999 !important;} 
        .rtl.folded #wpadminbar{padding-right: 46px !important;padding-left: 0px!important;} 
        .rtl #wpadminbar{padding-right: 245px !important;padding-left: 0px !important;z-index: 9999 !important;}
        .rtl.menu-hidden #wpadminbar{padding-left: 0px !important;padding-right: 0px !important;}         
        .rtl.menu-expanded #wpadminbar{padding-right: 245px !important;padding-left: 0px !important;}         
        .rtl.menu-collapsed #wpadminbar{padding-right: 46px !important;padding-left: 0px !important;}
        ";
    }
}

function legacy_admintopbar_links() {

    global $legacyadmin;

    $legacyadmin = legacyadmin_network($legacyadmin);

    //print_r($legacyadmin);

    $str = "";

    $element = 'enable-topbar-links-wp';
    if (isset($legacyadmin[$element]) && $legacyadmin[$element] != "1" && $legacyadmin[$element] == "0" && !$legacyadmin[$element]) {
        $str .= "#wp-admin-bar-wp-logo{display:none;}";
    }

    $element = 'enable-topbar-links-site';
    if (isset($legacyadmin[$element]) && $legacyadmin[$element] != "1" && $legacyadmin[$element] == "0" && !$legacyadmin[$element]) {
        $str .= "#wp-admin-bar-site-name{display:none;}";
    }

    $element = 'enable-topbar-links-comments';
    if (isset($legacyadmin[$element]) && $legacyadmin[$element] != "1" && $legacyadmin[$element] == "0" && !$legacyadmin[$element]) {
        $str .= "#wp-admin-bar-comments{display:none;}";
    }

    $element = 'enable-topbar-links-new';
    if (isset($legacyadmin[$element]) && $legacyadmin[$element] != "1" && $legacyadmin[$element] == "0" && !$legacyadmin[$element]) {
        $str .= "#wp-admin-bar-new-content{display:none;}";
    }

    $element = 'enable-topbar-links-legacyadmin';
    if (isset($legacyadmin[$element]) && $legacyadmin[$element] != "1" && $legacyadmin[$element] == "0" && !$legacyadmin[$element]) {
        $str .= "#wp-admin-bar-_legacyoptions{display:none;}";
    }

    $element = 'enable-topbar-myaccount';
    if (isset($legacyadmin[$element]) && $legacyadmin[$element] != "1" && $legacyadmin[$element] == "0" && !$legacyadmin[$element]) {
        $str .= "#wp-admin-bar-my-account{display:none;}";
    }

    echo "<style type='text/css'>" . $str . "</style>";
}

function legacy_topbar_logout_link() {
    global $legacyadmin;
    $legacyadmin = legacyadmin_network($legacyadmin);

    $element = 'enable-topbar-directlogout';

    if (isset($legacyadmin[$element]) && trim($legacyadmin[$element]) != "") {

        if ($legacyadmin[$element] == "1") {

            global $wp_admin_bar;
            $wp_admin_bar->add_menu(array(
                'id' => 'wp-custom-logout',
                'title' => 'Logout',
                'parent' => 'top-secondary',
                'href' => wp_logout_url()
            ));
        }
    }
}

function legacy_topbar_menuids() {

    global $wp_admin_bar;
    global $legacyadmin;
    $legacyadmin = legacyadmin_network($legacyadmin);

    $element = 'enable-topbar-links-wp';
    if (isset($legacyadmin[$element]) && $legacyadmin[$element] != "1" && $legacyadmin[$element] == "0" && !$legacyadmin[$element]) {
        $wp_admin_bar->remove_menu('wp-logo');
    }

    $element = 'enable-topbar-links-site';
    if (isset($legacyadmin[$element]) && $legacyadmin[$element] != "1" && $legacyadmin[$element] == "0" && !$legacyadmin[$element]) {
        $wp_admin_bar->remove_menu('site-name');
    }

    $element = 'enable-topbar-links-comments';
    if (isset($legacyadmin[$element]) && $legacyadmin[$element] != "1" && $legacyadmin[$element] == "0" && !$legacyadmin[$element]) {
        $wp_admin_bar->remove_menu('comments');
    }

    $element = 'enable-topbar-links-new';
    if (isset($legacyadmin[$element]) && $legacyadmin[$element] != "1" && $legacyadmin[$element] == "0" && !$legacyadmin[$element]) {
        $wp_admin_bar->remove_menu('new-content');
    }

    $element = 'enable-topbar-links-legacyadmin';
    if (isset($legacyadmin[$element]) && $legacyadmin[$element] != "1" && $legacyadmin[$element] == "0" && !$legacyadmin[$element]) {
        $wp_admin_bar->remove_menu('_legacyoptions');
    }

    $element = 'enable-topbar-myaccount';
    if (isset($legacyadmin[$element]) && $legacyadmin[$element] != "1" && $legacyadmin[$element] == "0" && !$legacyadmin[$element]) {
        $wp_admin_bar->remove_menu('my-account');
    }


    $element = 'topbar-removeids';
    if (isset($legacyadmin[$element]) && trim($legacyadmin[$element]) != "") {
        $exp = explode(",", $legacyadmin[$element]);
        $exp = array_unique(array_filter($exp));

        foreach ($exp as $nodeid) {
            if (trim($nodeid) != "") {
                $wp_admin_bar->remove_menu($nodeid);
            }
        }
    }
}

function legacy_topbar_account_menu($wp_admin_bar) {

    global $legacyadmin;
    $legacyadmin = legacyadmin_network($legacyadmin);
    $greet = 'Howdy';

    $element = 'myaccount_greet';
    if (isset($legacyadmin[$element]) && trim($legacyadmin[$element]) != "Howdy") {

        $greet = $legacyadmin[$element];
        if ($greet != "") {
            $greet .= ', ';
        }

        $user_id = get_current_user_id();
        $current_user = wp_get_current_user();
        $profile_url = get_edit_profile_url($user_id);

        if (0 != $user_id) {

            /* Add the "My Account" menu */
            $avatar = get_avatar($user_id, 28);
            $howdy = $greet . '' . sprintf(__('%1$s','legacy_framework'), $current_user->display_name);
            $class = empty($avatar) ? '' : 'with-avatar';

            $wp_admin_bar->add_menu(array(
                'id' => 'my-account',
                'parent' => 'top-secondary',
                'title' => $howdy . $avatar,
                'href' => $profile_url,
                'meta' => array(
                    'class' => $class,
                ),
            ));
        }
    }
}

?>