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

function legacy_page_loader() {
    global $legacy_css_ver;

    global $legacyadmin;

    $legacyadmin = legacyadmin_network($legacyadmin);

    //print_r($legacyadmin);

    if (isset($legacyadmin['enable-pageloader']) && $legacyadmin['enable-pageloader'] == "1" && $legacyadmin['enable-pageloader'] != "0" && $legacyadmin['enable-pageloader']) {

        $url = plugins_url('/', __FILE__) . '../js/legacy-pace.min.js';
        wp_deregister_script('legacy-pace-js');
        wp_register_script('legacy-pace-js', $url);
        wp_enqueue_script('legacy-pace-js');

        $url = plugins_url('/', __FILE__) . '../js/legacy-pace-script.js';
        wp_deregister_script('legacy-pace-script-js');
        wp_register_script('legacy-pace-script-js', $url);
        wp_enqueue_script('legacy-pace-script-js');

        $url = plugins_url('/', __FILE__) . '../css/legacy-pace.min.css';
        wp_deregister_style('legacy-pace-css', $url);
        wp_register_style('legacy-pace-css', $url);
        wp_enqueue_style('legacy-pace-css');
    }
}

?>