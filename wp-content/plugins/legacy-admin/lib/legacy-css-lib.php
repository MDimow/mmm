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

function legacy_css_element_color($type) {
    global $legacyadmin;
    return ".btn-" . $type . ", .btn-" . $type . ".inverted:hover { background-color: " . $legacyadmin[$type . "-color"] . "; border-color: transparent;}
.btn-" . $type . ":hover, .btn-" . $type . ":focus, .btn-" . $type . ".inverted { border-color: " . $legacyadmin[$type . "-color"] . "; background-color:transparent; color: " . $legacyadmin[$type . "-color"] . ";}
.btn-" . $type . ":hover .fa, .btn-" . $type . ":focus .fa, .btn-" . $type . ".inverted .fa { color: " . $legacyadmin[$type . "-color"] . ";}
.btn-" . $type . ".inverted:hover, .btn-" . $type . ".inverted:hover .fa {color: #ffffff;} 
.alert-" . $type . "{ background-color: " . $legacyadmin[$type . "-color"] . "; color: white;}
.alert-" . $type . " .close .fa{color:white;}
.progress-bar-" . $type . " { background-color: " . $legacyadmin[$type . "-color"] . ";}

";
}

function legacy_css_color($selector, $id, $opacity = "", $valuetype = "") {
    global $legacyadmin;
    if ($valuetype == "string") {
        $value = $id;
    } else {
        $value = $legacyadmin[$id];
        if (is_array($value) && sizeof($value) == 0) {
            return;
        } else if (is_array($value) && sizeof($value) > 0) {
            $value = $legacyadmin[$id]['regular'];
        }
        if ($value == "") {
            return;
        }
    }
    return " " . $selector . "{color:" . legacy_hextorgba($value, $opacity) . " /*" . $value . "*/;} ";
}

function legacy_css_shadow($selector, $id, $opacity = "", $side, $width, $string = "", $valuetype = "") {
    if ($width == "") {
        $width = "1px";
    }

    if ($side == "") {
        $side = "bottom";
    }

    if ($side == "top") {
        $side_css = "0px " . $width . " 0px 0px color inset, 
	0px 0px 0px 0px color inset, 
	0px 0px 0px 0px color inset, 
	0px 0px 0px 0px color inset";
    }
    if ($side == "right") {
        $side_css = "0px 0px 0px 0px color inset, 
	0px 0px 0px 0px color inset, 
	0px 0px 0px 0px color inset, 
	-" . $width . " 0px 0px 0px color inset";
    }
    if ($side == "bottom") {
        $side_css = "0px 0px 0px 0px color inset, 
	0px -" . $width . " 0px 0px color inset, 
	0px 0px 0px 0px color inset, 
	0px 0px 0px 0px color inset";
    }
    if ($side == "left") {
        $side_css = "0px 0px 0px 0px color inset, 
	0px 0px 0px 0px color inset, 
	" . $width . " 0px 0px 0px color inset, 
	0px 0px 0px 0px color inset";
    }

    if ($side == "left-right" || $side == "right-left") {
        $side_css = "0px 0px 0px 0px color inset, 
	0px 0px 0px 0px color inset, 
	" . $width . " 0px 0px 0px color inset, 
	-" . $width . " 0px 0px 0px color inset";
    }

    if ($side == "top-bottom" || $side == "bottom-top") {
        $side_css = "0px " . $width . " 0px 0px color inset, 
	0px -" . $width . " 0px 0px color inset, 
	0px 0px 0px 0px color inset, 
	0px 0px 0px 0px color inset";
    }

    if ($side == "all" || $side == "top-right-bottom-left") {
        $side_css = "0px " . $width . " 0px 0px color inset, 
	0px -" . $width . " 0px 0px color inset, 
	" . $width . " 0px 0px 0px color inset, 
	-" . $width . " 0px 0px 0px color inset";
    }


    if ($side == "multiple") {
        $side_css = $string;
    }

    global $legacyadmin;

    if ($string == "string") {
        $value = $id;
    } else if ($valuetype == "string") {
        $value = $id;
    } else {
        $value = $legacyadmin[$id];
        if (is_array($value) && sizeof($value) == 0) {
            return;
        } else if (is_array($value) && sizeof($value) > 0) {
            $value = $legacyadmin[$id]['regular'];
        }
    }

    if ($value == "") {
        return;
    }


    /* Relative color code */
    /*     * * Darken Color - In box shadow the original color gets lighter ** */
    //    echo $value;
    $hex = $value;
    /*
      //    echo "0. ".$hex . "[HEX]\n";
      $rgb = HTMLToRGB($hex);
      //    echo "1. ".$rgb . "[HEX to RGB]\n";
      $new_color = ChangeLuminosity($rgb, 63);
      //    echo "2. ".$new_color . "[Dark RGB (rgb-hsl-dark hsl-rgb)]\n";
      $new_hex = RGBToHTML($new_color);
      //    echo "3. ".$new_hex . "[HEX]\n";
      $value = $new_hex;
      //    echo "===========\n";
     */


//    if($side == "multiple"){
    if ($hex != "transparent") {
        $color = legacy_hextorgba($hex, $opacity);
    } else {
        $color = "transparent";
    } // same color as separator - no darker version
    $side_css = str_replace("color", $color, $side_css);
    return " " . $selector . "{box-shadow: " . $side_css . " ;\n"
            . "-webkit-box-shadow: " . $side_css . " ;\n"
            . "-o-box-shadow: " . $side_css . " ;\n"
            . "-moz-box-shadow: " . $side_css . " ;\n"
            . "-ms-box-shadow: " . $side_css . " /*" . $hex . "*/;} \n";
}

function legacy_link_color($selector, $id, $opacity = "", $type = "", $valuetype = "") {
    global $legacyadmin;
    if ($valuetype == "array") {
        $value = $id;
    } else {
        $value = $legacyadmin[$id];
    }

    if (sizeof($value) == 0) {
        return;
    }

    $selector_visited = $selector_hover = $selector_active = "";
    $exp = explode(",", $selector);
    foreach ($exp as $single) {
        $selector_visited .= trim($single) . ":visited, ";
        $selector_hover .= trim($single) . ":hover, ";
        $selector_active .= trim($single) . ":active, ";
    }

    $selector_visited = substr($selector_visited, 0, -2);
    $selector_hover = substr($selector_hover, 0, -2);
    $selector_active = substr($selector_active, 0, -2);

    $regular = (isset($value['regular']) && $value['regular'] != "") ? $value['regular'] : $legacyadmin['primary-color'];
    $hover = (isset($value['hover']) && $value['hover'] != "") ? $value['hover'] : $regular;
    $active = (isset($value['active']) && $value['active'] != "") ? $value['active'] : $hover;
    $visited = (isset($value['visited']) && $value['visited'] != "") ? $value['visited'] : $regular;

    if (isset($type) && $type == "hover") {
        return $selector . "{color:" . legacy_hextorgba($value['hover'], $opacity) . " /*" . $value['hover'] . "*/;} ";
    } else {
        return $selector . "{color:" . legacy_hextorgba($regular, $opacity) . " /*" . $regular . "*/;} " .
//                $selector_visited . " {color:" . legacy_hextorgba($visited, $opacity) . ";} " .
                $selector_hover . " {color:" . legacy_hextorgba($hover, $opacity) . " /*" . $hover . "*/;} " .
                $selector_active . " {color:" . legacy_hextorgba($active, $opacity) . " /*" . $active . "*/;} \n";
    }
}

function legacy_css_bgcolor($selector, $id, $opacity = "", $valuetype = "",$important = "") {
    global $legacyadmin;

    $imp = "";
    if($important == "imp"){
        $imp = "!important";
    }

    if ($valuetype == "string") {
        $value = $id;
    } else if ($valuetype == "luminosity") {
        $value = $legacyadmin[$id];
        $hex = $value;  /* HEX */
        $rgb = legacy_HTMLToRGB($hex); /* HEX to RGB */
        $new_color = legacy_ChangeLuminosity($rgb, $opacity); /* rgb-hsl-new hsl-rgb */
        $new_hex = legacy_RGBToHTML($new_color); /* HEX */
        $value = $new_hex;
    } else {
        $value = $legacyadmin[$id];
        if (is_array($value) && sizeof($value) == 0) {
            return;
        } else if (is_array($value) && sizeof($value) > 0) {
            $value = $legacyadmin[$id]['regular'];
        }
        if ($value == "") {
            return;
        }
    }
    $color = "";
    if ($value == "transparent") {
        $color = "transparent";
    } else if (strpos($value, "rgba") !== false) {
        $color = $value;
    } else {
        $color = legacy_hextorgba($value, $opacity);
    }
    return " " . $selector . "{background-color:" . $color .$imp." /*" . $value . "*/;} ";
}

function legacy_css_border_color($selector, $id, $opacity = "", $bordertype, $valuetype = "") {
    global $legacyadmin;

    if ($valuetype == "string") {
        $value = $id;
    } else {
        $value = $legacyadmin[$id];
        if (is_array($value) && sizeof($value) == 0) {
            return;
        } else if (is_array($value) && sizeof($value) > 0) {
            if (isset($legacyadmin[$id]['regular'])) {
                $value = $legacyadmin[$id]['regular'];
            }
        }
    }
    if ($value == "") {
        return;
    }


    if ($bordertype == "all") {
        $css_property = "border-color";
    } else if ($bordertype == "top") {
        $css_property = "border-top-color";
    } else if ($bordertype == "right") {
        $css_property = "border-right-color";
    } else if ($bordertype == "bottom") {
        $css_property = "border-bottom-color";
    } else if ($bordertype == "left") {
        $css_property = "border-left-color";
    }

    $color = "";
    if ($value != "transparent") {
        $color = legacy_hextorgba($value, $opacity);
    } else {
        $color = "transparent";
    }

    return " " . $selector . "{" . $css_property . ":" . $color . " /*" . $value . "*/;}\n ";
}

function legacy_css_background_gradient($selector, $id1, $id2, $direction, $percent1, $percent2, $opacity = "", $type = "") {
    global $legacyadmin;

    if ($type == "string") {
        $value1 = $id1;
        $value2 = $id2;
    } else {

        $value1 = "#aeb2b7";
        if (isset($legacyadmin[$id1]['background-color']) && trim($legacyadmin[$id1]['background-color']) != "") {
            $value1 = $legacyadmin[$id1]['background-color'];
        }

        $value2 = $value1;
        if (isset($legacyadmin[$id2]['background-color']) && trim($legacyadmin[$id2]['background-color']) != "") {
            $value2 = $legacyadmin[$id2]['background-color'];
        }
    }

    $color1 = legacy_colorcode($value1, $opacity, "", "1");
    $color2 = legacy_colorcode($value2, $opacity, "", "1");

    if ($direction == "") {
        $direction = "top";
    }

    if ($percent1 == "") {
        $percent1 = "50%";
    }

    if ($percent2 == "") {
        $percent2 = "50%";
    }

    $str = "linear-gradient(" . $direction . ", " . $color1 . " " . $percent1 . ", " . $color2 . " " . $percent2 . ");";

    $ret = "";

    $ret .= " " . $selector . "{";
    $ret .= "background: -moz-" . $str;
    $ret .= "background: -webkit-" . $str;
    $ret .= "background: -o-" . $str;
    $ret .= "background: -ms-" . $str;
    $ret .= "background: " . $str;
    $ret .= "} ";

    return $ret;
}

function legacy_css_background($selector, $id, $opacity = "", $type = "",$important = "") {
    global $legacyadmin;
    if ($type == "array") {
        $value = $id;
    } else {
        $value = $legacyadmin[$id];
    }

    $imp = "";
    if($important == "imp"){
        $imp = "!important";
    }


    if (!isset($value['background-image'])) {
        $value['background-image'] = "";
    }
    if (!isset($value['background-repeat'])) {
        $value['background-repeat'] = "";
    }
    if (!isset($value['background-color'])) {
        $value['background-color'] = "";
    }
    if (!isset($value['background-size'])) {
        $value['background-size'] = "";
    }
    if (!isset($value['background-attachment'])) {
        $value['background-attachment'] = "";
    }
    if (!isset($value['background-position'])) {
        $value['background-position'] = "";
    }


    $bg_image = "";
    $legacyadminID = $value['background-image'];
    if (isset($legacyadminID) && trim($legacyadminID) != "") {
        $bg_image = "background-image:url(" . $legacyadminID . ")".$imp."; ";
    }

    $bg_color = "";
    $legacyadminID = $value['background-color'];
    $colorcode = legacy_colorcode($legacyadminID, $opacity, $imp);
    $bg_color = "background-color: " . $colorcode . ";";

    $bg_repeat = "";
    $legacyadminID = $value['background-repeat'];
    if (isset($legacyadminID) && trim($legacyadminID) != "") {
        $bg_repeat = "background-repeat:" . $legacyadminID . "".$imp."; ";
    }

    $bg_size = "";
    $legacyadminID = $value['background-size'];
    if (isset($legacyadminID) && trim($legacyadminID) != "") {
        $bg_size = "-webkit-background-size:" . $legacyadminID . "".$imp."; "
                . "-moz-background-size:" . $legacyadminID . "".$imp."; "
                . "-o-background-size:" . $legacyadminID . "".$imp."; "
                . "background-size:" . $legacyadminID . "".$imp."; ";
    }

    $bg_attach = "";
    $legacyadminID = $value['background-attachment'];
    if (isset($legacyadminID) && trim($legacyadminID) != "") {
        $bg_attach = "background-attachment:" . $legacyadminID . "".$imp."; ";
    }

    $bg_pos = "";
    $legacyadminID = $value['background-position'];
    if (isset($legacyadminID) && trim($legacyadminID) != "") {
        $bg_pos = "background-position:" . $legacyadminID . "".$imp."; ";
    }


    return " " . $selector . "{" . $bg_color . $bg_image . $bg_pos . $bg_attach . $bg_size . $bg_repeat . "} ";
}

function legacy_hextorgba($value, $opacity) {
    if ($opacity == "" || !isset($opacity)) {
        $opacity = 1;
    }
    $rgb = legacy_hex2rgb($value);
    return "rgba(" . $rgb[0] . "," . $rgb[1] . "," . $rgb[2] . ",$opacity)";
}

function legacy_colorcode($color, $opacity = "", $addstr = "", $onlycode = "0") {
    $ret = $color;
    $code = "";
    if ($opacity == "") {
        $opacity = "1.0";
    }
    global $legacyadmin;
    //$legacyadmin = legacy_color();

    if (isset($color) && trim($color) != "" && trim($color) != "#") {
        if ($color == "transparent") {
            $ret = "transparent" . $addstr;
        } else if ($color == "primary") {
            $ret = $legacyadmin['primary-color'] . $addstr;
        } else if ($color == "primary2") {
            $ret = $legacyadmin['primary2-color'] . $addstr;
        } else if ($color == "secondary") {
            $ret = $legacyadmin['secondary-color'] . $addstr;
        } else if (strpos($color, "rgb") !== false) {
            $ret = $color . $addstr;
        } else if (strpos($color, "/") !== false) {
            $colorexp = explode("/", $color);
            if (trim($colorexp[0]) == "primary") {
                $code = $legacyadmin['primary-color'];
            } else if (trim($colorexp[0]) == "primary2") {
                $code = $legacyadmin['primary2-color'];
            } else if (trim($colorexp[0]) == "secondary") {
                $code = $legacyadmin['secondary-color'];
            } else {
                $code = trim($colorexp[0]);
            }
            if (trim($colorexp[1]) != "") {
                $opacity = trim($colorexp[1]);
            }
            $ret = legacy_hextorgba($code, $opacity) . $addstr;
            if ($onlycode != "1") {
                $ret .= " /*" . $code . "*/";
            }
            if ($onlycode != "1") {
                $ret .= "; ";
            }
        } else {
            $ret = legacy_hextorgba($color, $opacity) . $addstr;
            if ($onlycode != "1") {
                $ret .= " /*" . $color . "*/";
            }
            if ($onlycode != "1") {
                $ret .= "; ";
            }
//            $ret = $color ." /*".$color."*/; ";
        }
    }

    return $ret;
}

?>