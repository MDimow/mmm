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

function legacy_login_custom_label() {
    add_filter('gettext', 'legacy_username_change', 20, 3);
    add_filter('gettext', 'legacy_password_change', 20, 3);
}

add_action('login_head', 'legacy_login_custom_label');

function legacy_username_change($translated_text, $text, $domain) {
    if ($text === 'Username') {
        $translated_text = '';
    }
    return $translated_text;
}

function legacy_password_change($translated_text, $text, $domain) {
    if ($text === 'Password') {
        $translated_text = '';
    }
    return $translated_text;
}

function legacy_custom_login() {
    global $legacyadmin;
    $legacyadmin = legacyadmin_network($legacyadmin);
    //echo "<pre>"; print_r($legacyadmin); echo "</pre>"; 

    global $legacy_css_ver;

    $url = plugins_url('/', __FILE__) . '../' . $legacy_css_ver . '/legacy-login.min.css';
    wp_deregister_style('legacy-login');
    wp_register_style('legacy-login', $url);
    wp_enqueue_style('legacy-login');

    $url = plugins_url('/', __FILE__) . '../js/legacy-login-scripts.js';
    wp_deregister_script('legacy-login-scripts-js');
    wp_register_script('legacy-login-scripts-js', $url);
    wp_enqueue_script('legacy-login-scripts-js');


    echo "\n<style type='text/css'>";

    /* text, backgrounds, link color */
    echo legacy_css_background("body, #wp-auth-check-wrap #wp-auth-check", "login-background","","","imp") . "\n";
    //echo legacy_css_background("#login", "login-form-background") . "\n";
    echo legacy_css_background_gradient("#login", $legacyadmin['login-form-background']['from'], $legacyadmin['login-form-background']['to'], "top", "0%", "100%", "0.7", "string") . "\n";


    echo legacy_link_color("body.login #backtoblog a, body.login #nav a, body.login a", "login-link-color") . "\n";
    echo legacy_css_color(".login, .login form label, .login form, .login #login_error, .login .message", "login-text-color") . "\n";

    /* login button */
    echo legacy_css_bgcolor(".login.wp-core-ui .button-primary", "login-button-bg") . "\n";
    echo legacy_css_bgcolor(".login.wp-core-ui .button-primary:hover, .login.wp-core-ui .button-primary:focus", "login-button-hover-bg") . "\n";
    echo legacy_css_color(".login.wp-core-ui .button-primary", "login-button-text-color") . "\n";


    /* form input fields - text and checkbox */
    echo legacy_css_bgcolor(".login form .input, .login form input[type=checkbox], .login input[type=text]", "login-input-bg-color", ($legacyadmin['login-input-bg-opacity']) == "" ? "0.5" : $legacyadmin['login-input-bg-opacity'],"","imp") . "\n";
    echo legacy_css_bgcolor(".login form .input:hover, .login form input[type=checkbox]:hover, .login input[type=text]:hover, .login form .input:focus, .login form input[type=checkbox]:focus, .login input[type=text]:focus", "login-input-bg-color", ($legacyadmin['login-input-bg-hover-opacity']) == "" ? "0.8" : $legacyadmin['login-input-bg-hover-opacity'],"","imp") . "\n";
    echo legacy_css_color(".login form .input, .login form input[type=checkbox], .login input[type=text]", "login-input-text-color") . "\n";
    echo legacy_css_color(".login.wp-core-ui input[type=checkbox]:checked:before", "login-input-text-color") . "\n";

    echo legacy_css_border_color(".login form .input, .login input[type=text]", "login-input-border-color", "1.0", "bottom") . "\n";
    echo legacy_css_border_color(".login form input[type=checkbox]", "login-input-border-color", "1.0", "all") . "\n";

    /* input fields icons */
    echo legacy_css_color(".login label[for='user_login']:before, .login label[for='user_pass']:before, .login label[for='user_email']:before", "login-input-border-color") . "\n";

    /* form input fields - other fields - for future use */
    echo legacy_css_bgcolor("input[type=checkbox], input[type=color], input[type=date], input[type=datetime-local], input[type=datetime], input[type=email], input[type=month], input[type=number], input[type=password], input[type=radio], input[type=search], input[type=tel], input[type=text], input[type=time], input[type=url], input[type=week], select, textarea", "login-input-bg-color", ($legacyadmin['login-input-bg-opacity']) == "" ? "0.5" : $legacyadmin['login-input-bg-opacity']) . "\n";
    echo legacy_css_color("input[type=checkbox], input[type=color], input[type=date], input[type=datetime-local], input[type=datetime], input[type=email], input[type=month], input[type=number], input[type=password], input[type=radio], input[type=search], input[type=tel], input[type=text], input[type=time], input[type=url], input[type=week], select, textarea", "login-input-text-color") . "\n";


    /* login error message */
    echo legacy_css_bgcolor(".login #login_error, .login .message", "login-input-bg-color", ($legacyadmin['login-input-bg-opacity']) == "" ? "0.5" : $legacyadmin['login-input-bg-opacity'],"","imp") . "\n";
    //echo legacy_css_color(" .login #login_error, .login .message, .login .message,  .login .message a, .login #login_error, .login #login_error a", "login-input-text-color") . "\n";


    /* login logo */
    $logo_url = "";
    if (isset($legacyadmin['login-logo']['url']) && $legacyadmin['login-logo']['url'] != "") {
        $logo_url = $legacyadmin['login-logo']['url'];
    } else {
        $logo_url = $legacyadmin['logo']['url'];
    }

    echo '.login h1 a { background-image: url("' . $logo_url . '") !important;}';


    echo "</style>\n";


}

function legacy_custom_loginlogo_url() {

    global $legacyadmin;
    $legacyadmin = legacyadmin_network($legacyadmin);

    $logourl = "https://wordpress.org/";

    if (isset($legacyadmin['logo-url']) && trim($legacyadmin['logo-url']) != "") {
        $logourl = $legacyadmin['logo-url'];
    }
    return $logourl;
}

function legacy_login_options() {

    global $legacyadmin;
    $legacyadmin = legacyadmin_network($legacyadmin);

    // back to blog
    $backtoblog = "block";
    $element = 'backtosite_login_link';

    if (isset($legacyadmin[$element]) && trim($legacyadmin[$element]) != "") {
        if ($legacyadmin[$element] == "0") {
            $backtoblog = "none";
        }
    }

    $style = "";
    $style .= " #backtoblog { display:" . $backtoblog . " !important; } ";


    // forgot password

    $forgot = "block";
    $element = 'forgot_login_link';

    if (isset($legacyadmin[$element]) && trim($legacyadmin[$element]) != "") {
        if ($legacyadmin[$element] == "0") {
            $forgot = "none";
        }
    }

    $style .= " #nav { display:" . $forgot . " !important; } ";

    echo "<style type='text/css' id='legacy-login-extra-css'>" . $style . "</style>";
}

// change title
function legacy_loginlogo_title() {
    global $legacyadmin;
    $legacyadmin = legacyadmin_network($legacyadmin);

    $logourl = "";

    if (isset($legacyadmin['login-logo-title']) && trim($legacyadmin['login-logo-title']) != "") {
        $logourl = $legacyadmin['login-logo-title'];
    }
    return $logourl;
}

add_filter('login_headertext', 'legacy_loginlogo_title');



?>