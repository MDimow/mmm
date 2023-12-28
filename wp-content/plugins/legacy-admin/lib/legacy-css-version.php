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

function legacy_css_version() {
    global $wp_version;

    $version = $wp_version;
    if (strlen($version) == 3) {
        $version = $version . ".0";
    }

    if (version_compare($version, '4.0.0', '>=')) {
        return 'css40';
    } else {
        return '';
    }
}

$GLOBALS['legacy_css_ver'] = legacy_css_version();
?>