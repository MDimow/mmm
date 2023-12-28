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

function legacy_css_fonts() {

    global $legacyadmin;

    $bodyfont = "'Open Sans', Arial, Helvetica, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif";
    $menufont = "'Roboto Condensed', Arial, Helvetica, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif ";
    $buttonfont = "'Roboto Condensed', Arial, Helvetica, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif ";
    $headingfont = "'Roboto Condensed',  Arial, Helvetica, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif";
    $pageheadfont = "'Source Sans Pro',  Arial, Helvetica, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif";

    $body_letter_spacing = $body_word_spacing = "";
    $heading_letter_spacing = $heading_word_spacing = "";
    $menu_letter_spacing = $menu_word_spacing = "";
    $button_letter_spacing = $button_word_spacing = "";
    $pagehead_letter_spacing = $pagehead_word_spacing = "";

    $body_font_weight = "font-weight:300; ";
    $menu_font_weight = "font-weight:300; ";
    $button_font_weight = "font-weight:300; ";
    $heading_font_weight = "font-weight:300; ";
    $pagehead_font_weight = "font-weight:700; ";

    $body_font_style = "font-style:normal; ";
    $menu_font_style = "font-style:normal; ";
    $button_font_style = "font-style:normal; ";
    $heading_font_style = "font-style:normal; ";
    $pagehead_font_style = "font-style:normal; ";


    $body_font_size = "font-size:15px; ";
    $body_line_height = "line-height:23px; ";

    $menu_font_size = "font-size:17px; ";
    $menu_line_height = "line-height:46px; ";

    $button_font_size = "font-size:15px; ";
    $button_line_height = "line-height:23px; ";

    $pagehead_font_size = "font-size:32px; ";
    $pagehead_line_height = "line-height:69px; ";


    if (isset($legacyadmin['google_body']) && sizeof($legacyadmin['google_body']) && trim($legacyadmin['google_body']['font-family']) != "") {
        $bodyfont = "'" . $legacyadmin['google_body']['font-family'] . "'";

        if (isset($legacyadmin['google_body']['font-backup'])) {
            $bodyfont .= ", " . $legacyadmin['google_body']['font-backup'];
        } else {
            $bodyfont .= ", -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif";
        }
        if (isset($legacyadmin['google_body']['letter-spacing']) && trim(($legacyadmin['google_body']['letter-spacing']) != "")) {
            $body_letter_spacing = "letter-spacing:" . $legacyadmin['google_body']['letter-spacing'] . "; ";
        }
        if (isset($legacyadmin['google_body']['word-spacing']) && trim(($legacyadmin['google_body']['word-spacing']) != "")) {
            $body_word_spacing = "word-spacing:" . $legacyadmin['google_body']['word-spacing'] . "; ";
        }
        if (isset($legacyadmin['google_body']['font-weight']) && trim(($legacyadmin['google_body']['font-weight']) != "")) {
            $body_font_weight = "font-weight:" . $legacyadmin['google_body']['font-weight'] . "; ";
        }
        if (isset($legacyadmin['google_body']['font-style']) && trim(($legacyadmin['google_body']['font-style']) != "")) {
            $body_font_style = "font-style:" . $legacyadmin['google_body']['font-style'] . "; ";
        }
        if (isset($legacyadmin['google_body']['font-size']) && trim(($legacyadmin['google_body']['font-size']) != "")) {
            $body_font_size = "font-size:" . $legacyadmin['google_body']['font-size'] . "; ";
        }
        if (isset($legacyadmin['google_body']['line-height']) && trim(($legacyadmin['google_body']['line-height']) != "")) {
            $body_line_height = "line-height:" . $legacyadmin['google_body']['line-height'] . "; ";
        }
    }




    if (isset($legacyadmin['google_nav']) && sizeof($legacyadmin['google_nav']) && trim($legacyadmin['google_nav']['font-family']) != "") {
        $menufont = "'" . $legacyadmin['google_nav']['font-family'] . "'";

        if (isset($legacyadmin['google_nav']['font-backup'])) {
            $menufont .= ", " . $legacyadmin['google_nav']['font-backup'];
        } else {
            $menufont .= ", -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif";
        }
        if (isset($legacyadmin['google_nav']['letter-spacing']) && trim(($legacyadmin['google_nav']['letter-spacing']) != "")) {
            $menu_letter_spacing = "letter-spacing:" . $legacyadmin['google_nav']['letter-spacing'] . "; ";
        }
        if (isset($legacyadmin['google_nav']['word-spacing']) && trim(($legacyadmin['google_nav']['word-spacing']) != "")) {
            $menu_word_spacing = "word-spacing:" . $legacyadmin['google_nav']['word-spacing'] . "; ";
        }
        if (isset($legacyadmin['google_nav']['font-weight']) && trim(($legacyadmin['google_nav']['font-weight']) != "")) {
            $menu_font_weight = "font-weight:" . $legacyadmin['google_nav']['font-weight'] . "; ";
        }
        if (isset($legacyadmin['google_nav']['font-style']) && trim(($legacyadmin['google_nav']['font-style']) != "")) {
            $menu_font_style = "font-style:" . $legacyadmin['google_nav']['font-style'] . "; ";
        }
        if (isset($legacyadmin['google_nav']['font-size']) && trim(($legacyadmin['google_nav']['font-size']) != "")) {
            $menu_font_size = "font-size:" . $legacyadmin['google_nav']['font-size'] . "; ";
        }
        if (isset($legacyadmin['google_nav']['line-height']) && trim(($legacyadmin['google_nav']['line-height']) != "")) {
            $menu_line_height = "line-height:" . $legacyadmin['google_nav']['line-height'] . "; ";
        }
    }




    if (isset($legacyadmin['google_button']) && sizeof($legacyadmin['google_button']) && trim($legacyadmin['google_button']['font-family']) != "") {
        $buttonfont = "'" . $legacyadmin['google_button']['font-family'] . "'";

        if (isset($legacyadmin['google_button']['font-backup'])) {
            $buttonfont .= ", " . $legacyadmin['google_button']['font-backup'];
        } else {
            $buttonfont .= ", -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif";
        }
        if (isset($legacyadmin['google_button']['letter-spacing']) && trim(($legacyadmin['google_button']['letter-spacing']) != "")) {
            $button_letter_spacing = "letter-spacing:" . $legacyadmin['google_button']['letter-spacing'] . "; ";
        }
        if (isset($legacyadmin['google_button']['word-spacing']) && trim(($legacyadmin['google_button']['word-spacing']) != "")) {
            $button_word_spacing = "word-spacing:" . $legacyadmin['google_button']['word-spacing'] . "; ";
        }
        if (isset($legacyadmin['google_button']['font-weight']) && trim(($legacyadmin['google_button']['font-weight']) != "")) {
            $button_font_weight = "font-weight:" . $legacyadmin['google_button']['font-weight'] . "; ";
        }
        if (isset($legacyadmin['google_button']['font-style']) && trim(($legacyadmin['google_button']['font-style']) != "")) {
            $button_font_style = "font-style:" . $legacyadmin['google_button']['font-style'] . "; ";
        }
        if (isset($legacyadmin['google_button']['font-size']) && trim(($legacyadmin['google_button']['font-size']) != "")) {
            $button_font_size = "font-size:" . $legacyadmin['google_button']['font-size'] . "; ";
        }
        if (isset($legacyadmin['google_button']['line-height']) && trim(($legacyadmin['google_button']['line-height']) != "")) {
            $button_line_height = "line-height:" . $legacyadmin['google_button']['line-height'] . "; ";
        }
    }




    if (isset($legacyadmin['google_headings']) && sizeof($legacyadmin['google_headings']) && trim($legacyadmin['google_headings']['font-family']) != "") {
        $headingfont = "'" . $legacyadmin['google_headings']['font-family'] . "'";

        if (isset($legacyadmin['google_headings']['font-backup'])) {
            $headingfont .= ", " . $legacyadmin['google_headings']['font-backup'];
        } else {
            $headingfont .= ", -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif";
        }
        if (isset($legacyadmin['google_headings']['letter-spacing']) && trim(($legacyadmin['google_headings']['letter-spacing']) != "")) {
            $heading_letter_spacing = "letter-spacing:" . $legacyadmin['google_headings']['letter-spacing'] . "; ";
        }
        if (isset($legacyadmin['google_headings']['word-spacing']) && trim(($legacyadmin['google_headings']['word-spacing']) != "")) {
            $heading_word_spacing = "word-spacing:" . $legacyadmin['google_headings']['word-spacing'] . "; ";
        }
        if (isset($legacyadmin['google_headings']['font-weight']) && trim(($legacyadmin['google_headings']['font-weight']) != "")) {
            $heading_font_weight = "font-weight:" . $legacyadmin['google_headings']['font-weight'] . "; ";
        }
        if (isset($legacyadmin['google_headings']['font-style']) && trim(($legacyadmin['google_headings']['font-style']) != "")) {
            $headings_font_style = "font-style:" . $legacyadmin['google_headings']['font-style'] . "; ";
        }
    }




    if (isset($legacyadmin['google_pagehead']) && sizeof($legacyadmin['google_pagehead']) && trim($legacyadmin['google_pagehead']['font-family']) != "") {
        $pageheadfont = "'" . $legacyadmin['google_pagehead']['font-family'] . "'";

        if (isset($legacyadmin['google_pagehead']['font-backup'])) {
            $pageheadfont .= ", " . $legacyadmin['google_pagehead']['font-backup'];
        } else {
            $pageheadfont .= ", -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif";
        }
        if (isset($legacyadmin['google_pagehead']['letter-spacing']) && trim(($legacyadmin['google_pagehead']['letter-spacing']) != "")) {
            $pagehead_letter_spacing = "letter-spacing:" . $legacyadmin['google_pagehead']['letter-spacing'] . "; ";
        }
        if (isset($legacyadmin['google_pagehead']['word-spacing']) && trim(($legacyadmin['google_pagehead']['word-spacing']) != "")) {
            $pagehead_word_spacing = "word-spacing:" . $legacyadmin['google_pagehead']['word-spacing'] . "; ";
        }
        if (isset($legacyadmin['google_pagehead']['font-weight']) && trim(($legacyadmin['google_pagehead']['font-weight']) != "")) {
            $pagehead_font_weight = "font-weight:" . $legacyadmin['google_pagehead']['font-weight'] . "; ";
        }
        if (isset($legacyadmin['google_pagehead']['font-style']) && trim(($legacyadmin['google_pagehead']['font-style']) != "")) {
            $pagehead_font_style = "font-style:" . $legacyadmin['google_pagehead']['font-style'] . "; ";
        }
        if (isset($legacyadmin['google_pagehead']['font-size']) && trim(($legacyadmin['google_pagehead']['font-size']) != "")) {
            $pagehead_font_size = "font-size:" . $legacyadmin['google_pagehead']['font-size'] . "; ";
        }
        if (isset($legacyadmin['google_pagehead']['line-height']) && trim(($legacyadmin['google_pagehead']['line-height']) != "")) {
            $pagehead_line_height = "line-height:" . $legacyadmin['google_pagehead']['line-height'] . "; ";
        }
    }


    $ret = array();
    $ret['body_font_css'] = "font-family: " . $bodyfont . ";" . $body_letter_spacing . " " . $body_word_spacing . " " . $body_font_weight . " " . $body_font_size . " " . $body_line_height . " " . $body_font_style;
    $ret['head_font_css'] = "font-family: " . $headingfont . ";" . $heading_letter_spacing . " " . $heading_word_spacing . " " . $heading_font_weight . " " . $heading_font_style;
    $ret['menu_font_css'] = " font-family: " . $menufont . ";" . $menu_letter_spacing . " " . $menu_word_spacing . " " . $menu_font_weight . " " . $menu_font_size . " " . $menu_line_height . " " . $menu_font_style;
    $ret['button_font_css'] = " font-family: " . $buttonfont . ";" . $button_letter_spacing . " " . $button_word_spacing . " " . $button_font_weight . " " . $button_font_size . " " . $button_line_height . " " . $button_font_style;
    $ret['pagehead_font_css'] = " font-family: " . $pageheadfont . ";" . $pagehead_letter_spacing . " " . $pagehead_word_spacing . " " . $pagehead_font_weight . " " . $pagehead_font_size . " " . $pagehead_line_height . " " . $pagehead_font_style;



    return $ret;
}

function legacy_fonts() {
    global $legacyadmin;
    $gfont = array();

    if (isset($legacyadmin['google_body']) && sizeof($legacyadmin['google_body']) && trim($legacyadmin['google_body']['font-family']) != "") {
        $font = $legacyadmin['google_body']['font-family'];
        $font = str_replace(", " . $legacyadmin['google_body']['font-backup'], "", $font);
        $gfont[urlencode($font)] = '"' . urlencode($font) . ':400,300,300italic,400italic,600,600italic,700,700italic:latin"';
    }

    if (isset($legacyadmin['google_nav']) && sizeof($legacyadmin['google_nav']) && trim($legacyadmin['google_nav']['font-family']) != "" && $legacyadmin['google_nav']['font-family'] != $legacyadmin['google_body']['font-family']) {
        $font = $legacyadmin['google_nav']['font-family'];
        $font = str_replace(", " . $legacyadmin['google_nav']['font-backup'], "", $font);
        $gfont[urlencode($font)] = '"' . urlencode($font) . ':400,300,300italic,400italic,600,600italic,700,700italic:latin"';
    }

    if (isset($legacyadmin['google_headings']) && sizeof($legacyadmin['google_headings']) && trim($legacyadmin['google_headings']['font-family']) != "" && $legacyadmin['google_headings']['font-family'] != $legacyadmin['google_body']['font-family'] && $legacyadmin['google_headings']['font-family'] != $legacyadmin['google_nav']['font-family']) {
        $font = $legacyadmin['google_headings']['font-family'];
        $font = str_replace(", " . $legacyadmin['google_headings']['font-backup'], "", $font);
        $gfont[urlencode($font)] = '"' . urlencode($font) . ':400,300,300italic,400italic,600,600italic,700,700italic:latin"';
    }

    if (isset($legacyadmin['google_button']) && sizeof($legacyadmin['google_button']) && trim($legacyadmin['google_button']['font-family']) != "" && $legacyadmin['google_button']['font-family'] != $legacyadmin['google_body']['font-family'] && $legacyadmin['google_button']['font-family'] != $legacyadmin['google_headings']['font-family'] && $legacyadmin['google_button']['font-family'] != $legacyadmin['google_nav']['font-family']) {
        $font = $legacyadmin['google_button']['font-family'];
        $font = str_replace(", " . $legacyadmin['google_button']['font-backup'], "", $font);
        $gfont[urlencode($font)] = '"' . urlencode($font) . ':400,300,300italic,400italic,600,600italic,700,700italic:latin"';
    }

    if (isset($legacyadmin['google_pagehead']) && sizeof($legacyadmin['google_pagehead']) && trim($legacyadmin['google_pagehead']['font-family']) != "" && $legacyadmin['google_pagehead']['font-family'] != $legacyadmin['google_body']['font-family'] && $legacyadmin['google_pagehead']['font-family'] != $legacyadmin['google_headings']['font-family'] && $legacyadmin['google_pagehead']['font-family'] != $legacyadmin['google_nav']['font-family'] && $legacyadmin['google_pagehead']['font-family'] != $legacyadmin['google_button']['font-family']
    ) {
        $font = $legacyadmin['google_pagehead']['font-family'];
        $font = str_replace(", " . $legacyadmin['google_pagehead']['font-backup'], "", $font);
        $gfont[urlencode($font)] = '"' . urlencode($font) . ':400,300,300italic,400italic,600,600italic,700,700italic:latin"';
    }

    $gfonts = "";
    if ($gfont) {
        if (is_array($gfont) && !empty($gfont)) {
            $gfonts = implode(', ', $gfont);
        }
    }
    ?>

    <!-- Fonts - Start -->        
    <script type="text/javascript">
        WebFontConfig = {
    <?php if (!empty($gfonts)): ?>google: {families: [<?php echo $gfonts; ?>]},<?php endif; ?>
            custom: {}
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
                    '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();
    </script>
    <!-- Fonts - End -->        

    <?php
}
?>