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

function legacy_css_variables($legacyadmin,$csstype){

    global $legacy_color;

    $cssarr = array();

    if($csstype == "custom"){
        //print_r($legacyadmin);
        $cssarr = $legacyadmin;
    } else {
        //print_r($legacy_color[$csstype]);
        $cssarr = $legacy_color[$csstype];
    }

    $allkeys = array(
        'primary-color' => '',
        'page-bg' => array('background-color'),
        'heading-color' => '',
        'body-text-color' => '',
        'link-color' => array('regular','hover'),
        'menu-bg' => array('background-color'),
        'menu-color' => '',
        'menu-hover-color' => '',
        'submenu-color' => '',
        'menu-primary-bg' => '',
        'menu-secondary-bg' => '',
        'box-bg' => array('background-color'),
        'box-head-bg' => array('background-color'),
        'box-head-color' => '',
        'button-primary-bg' => '',
        'button-primary-hover-bg' => '',
        'button-secondary-bg' => '',
        'button-secondary-hover-bg' => '',
        'button-text-color' => '',
        'form-bg' => '',
        'form-text-color' => '',
        'form-border-color' => '',
        'logo-bg' => '', 
        'topbar-menu-color' => '', 
        'topbar-menu-bg' => array('background-color'), 
        'topbar-submenu-color' => '',
        'topbar-submenu-bg' => '',
        'topbar-submenu-hover-bg' => '',
    );


    $css = "";
        echo "<style type='text/css' id='legacy-css-variables'>:root{";

        if(sizeof($cssarr) > 0){

            foreach ($allkeys as $key => $value) {
                if($value == ""){
                    if(isset($cssarr[$key])){
                        $css .= "--tp-".$key.": ".$cssarr[$key].";";
                    }
                } else if(is_array($value)){
                    foreach ($value as $inkey) {
                        if(isset($cssarr[$key][$inkey])){
                            $css .= "--tp-".$key."-".$inkey.": ".$cssarr[$key][$inkey].";";
                        }
                    }
                }
            }
        }
        echo $css;
        echo "}</style>";

}

function legacy_logo($rettype = "") {
    global $legacyadmin;

    $legacyadmin = legacyadmin_network($legacyadmin);

    //print_r($legacyadmin);


    $csstype = legacy_dynamic_css_type();

    legacy_css_variables($legacyadmin,$csstype);
    


    $str = "";
    if (isset($legacyadmin['enable-logo']) && $legacyadmin['enable-logo'] != "1" && $legacyadmin['enable-logo'] == "0" && !$legacyadmin['enable-logo']) {

        // hide logo
        if ($rettype != "1") {
            $str .= "<style type='text/css' data-display='hide' id='legacy-admin-logo-hide'>";
        }
        $str .= "#adminmenuwrap .logo-overlay{display:none !important;} #adminmenuwrap:before, .folded #adminmenuwrap:before{display: none !important;} .auto-fold #adminmenuwrap:before{display: none !important;}  #adminmenu{margin-top:0px !important;}";
        if ($rettype != "1") {
            $str .= "</style>";
        }
    } else {

        // show logo
        $logo = $logo_folded = "";


        if ($logo == "") {
            if (isset($legacyadmin['logo']['url'])) {
                $logo = trim($legacyadmin['logo']['url']);
            }
        }
        if ($logo_folded == "") {
            if (isset($legacyadmin['logo_folded']['url'])) {
                $logo_folded = trim($legacyadmin['logo_folded']['url']);
            }
        }

        if ($rettype != "1") {
            $str .= "<style type='text/css' data-display='show' data-csstype='" . $csstype . "' id='legacy-admin-logo-show'>";
        }
        $str .= "#adminmenuwrap:before{background-image: url('" . $logo . "');} 
        .folded #adminmenuwrap:before{background-image: url('" . $logo_folded . "');} 
        .auto-fold #adminmenuwrap:before{background-image: url('" . $logo_folded . "');} 
        .menu-expanded #adminmenuwrap:before{background-image: url('" . $logo . "') !important;} 
        .menu-collapsed #adminmenuwrap:before{background-image: url('" . $logo_folded . "') !important;}";
        if ($rettype != "1") {
            $str .= "</style>";
        }
    }

    if ($rettype != "1") {
        echo $str;
    } else {
        return $str;
    }
}

function legacy_favicon() {
    ?>

    <?php
    global $legacyadmin;


    $legacyadmin = legacyadmin_network($legacyadmin);

    //print_r($legacyadmin);
    ?>

    <?php if ($legacyadmin['favicon']['url']): ?>
        <link rel="shortcut icon" href="<?php echo $legacyadmin['favicon']['url']; ?>" type="image/x-icon" />
    <?php endif; ?>

    <?php if ($legacyadmin['iphone_icon']['url']): ?>
        <!-- For iPhone -->
        <link rel="apple-touch-icon-precomposed" href="<?php echo $legacyadmin['iphone_icon']['url']; ?>">
    <?php endif; ?>

    <?php if ($legacyadmin['iphone_icon_retina']['url']): ?>
        <!-- For iPhone 4 Retina display -->
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $legacyadmin['iphone_icon_retina']['url']; ?>">
    <?php endif; ?>

    <?php if ($legacyadmin['ipad_icon']['url']): ?>
        <!-- For iPad -->
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $legacyadmin['ipad_icon']['url']; ?>">
    <?php endif; ?>

    <?php if ($legacyadmin['ipad_icon_retina']['url']): ?>
        <!-- For iPad Retina display -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $legacyadmin['ipad_icon_retina']['url']; ?>">
    <?php endif; ?>
    <?php
}

function legacy_logo_url() {

    global $legacyadmin;

    $legacyadmin = legacyadmin_network($legacyadmin);

    //print_r($legacyadmin);

    $logourl = "";
    if (isset($legacyadmin['logo-url']) && trim($legacyadmin['logo-url']) != "") {
        $logourl = $legacyadmin['logo-url'];
        echo "<style type='text/css' id='legacy-logo-url'> #adminmenuwrap .logo-overlay { cursor:hand;cursor:pointer; }</style>";
    }

    echo "<meta type='info' id='legacy-logourl' data-value='" . $logourl . "'>";
}
?>