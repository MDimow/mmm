/**
 * @Package: WordPress Plugin
 * @Subpackage: Legacy - White Label WordPress Admin Theme
 * @Since: Legacy 1.0
 * @WordPress Version: 4.0 or above
 * This file is part of Legacy - White Label WordPress Admin Theme Plugin.
 */


jQuery(function($) {

    'use strict';

    var LEGACY_SETTINGS = window.LEGACY_SETTINGS || {};


    /******************************
     Menu resizer
     *****************************/
    LEGACY_SETTINGS.menuResizer = function() {
        var menuWidth = $("#adminmenuwrap").width();
        if ($("#adminmenuwrap").is(":hidden")) {
            $("body").addClass("menu-hidden");
            $("body").removeClass("menu-expanded");
            $("body").removeClass("menu-collapsed");
        }
        else if (menuWidth > 46) {
            $("body").addClass("menu-expanded");
            $("body").removeClass("menu-hidden");
            $("body").removeClass("menu-collapsed");
        } else {
            $("body").addClass("menu-collapsed");
            $("body").removeClass("menu-expanded");
            $("body").removeClass("menu-hidden");
        }

        LEGACY_SETTINGS.menuConnectionLine();

    };

    LEGACY_SETTINGS.menuClickResize = function() {
        $(document).on('click', '#collapse-menu, #wp-admin-bar-menu-toggle', function(e){
            var menuWidth = $("#adminmenuwrap").width();
            if ($("#adminmenuwrap").is(":hidden")) {
                $("body").addClass("menu-hidden");
                $("body").removeClass("menu-expanded");
                $("body").removeClass("menu-collapsed");
            }
            else if (menuWidth > 46) {
                $("body").addClass("menu-expanded");
                $("body").removeClass("menu-hidden");
                $("body").removeClass("menu-collapsed");
            } else {
                $("body").addClass("menu-collapsed");
                $("body").removeClass("menu-expanded");
                $("body").removeClass("menu-hidden");
            }
        });
    };

    LEGACY_SETTINGS.logoURL = function() {

        $("#adminmenuwrap").prepend("<div class='logo-overlay'></div>");

        $(document).on('click', '#adminmenuwrap .logo-overlay', function(e){      
            var logourl = $("#legacy-logourl").attr("data-value");
            if (logourl != "") {
                window.location = logourl;
            }
        });
    };



    LEGACY_SETTINGS.menuConnectionLinecall = function() {


        $("#wp-admin-bar-menu-toggle").click(function(e) {
            LEGACY_SETTINGS.menuConnectionLine();
        });

    };



    LEGACY_SETTINGS.menuConnectionLine = function() {



        var mainmenu = ($("#adminmenu").height() - $("li#collapse-menu").height()) / 2;
        //$("#adminmenu:before, #adminmenu:after").css('height', +mainmenu + 'px');
        $('<style>#adminmenu:before, #adminmenu:after {height: ' + mainmenu + 'px;} #adminmenu:after {top: ' + mainmenu + 'px;}</style>').appendTo('head');
        //console.log(mainmenu);
        $("li.wp-has-submenu").each(function() {
            var id = $(this).attr("id");

            if ($("body").hasClass("folded") || $("body").hasClass("menu-collapsed")) {
                var subheight = ($(this).find(".wp-submenu").height() - 49) / 2;
            } else {
                var subheight = ($(this).find(".wp-submenu").height()) / 2;
            }

            var str = "";
            str += "li#" + id + " .wp-submenu:before, li#" + id + " .wp-submenu:after { height: " + subheight + "px !important;} ";
            str += "li#" + id + " .wp-submenu:after { top: " + subheight + "px !important;} ";
            str += ".folded li#" + id + " .wp-submenu:before, .folded li#" + id + " .wp-submenu:after { height: " + subheight + "px !important;} ";
            str += ".folded li#" + id + " .wp-submenu:after { top: " + (subheight + 49) + "px !important;} ";

            $('<style>' + str + '</style>').appendTo('head');
        });

    };



    LEGACY_SETTINGS.alternateSave = function() {
        $(".legacy_save_new").on('click', function(e) {
            legacy_ajaxsavestep(1);
            console.log("alternate save it");
            //e.preventDefault();
            return false;
        });

    };



    function legacy_ajaxsavestep(stepid) {

        var opt1 = $("#legacy_demo-dynamic-css-type .redux-image-select-selected input").val();
        var str = $("#legacy_demo-primary-color").find(".wp-color-result").attr("style") + ";" +$("#login-input-bg-opacity").val() +";"+ opt1;

        //alert(str); exit;
        var action = 'legacy_alternate_save';
        var data = {
            values: str,
            action: action,
            legacy_nonce: legacy_vars.legacy_nonce
        };

        $.post(ajaxurl, data, function(response) {
            //alert(response);
            $('.alternate_save_response').html(response);
            
        });

        return false;
    }

    /******************************
     initialize respective scripts 
     *****************************/
    $(document).ready(function() {
        LEGACY_SETTINGS.menuResizer();
        LEGACY_SETTINGS.menuClickResize();
        LEGACY_SETTINGS.logoURL();
        LEGACY_SETTINGS.menuConnectionLinecall();
        LEGACY_SETTINGS.menuConnectionLine();
    });

    $(window).resize(function() {
        LEGACY_SETTINGS.menuResizer();
        LEGACY_SETTINGS.menuClickResize();
    });

    $(window).load(function() {
        LEGACY_SETTINGS.menuResizer();
        LEGACY_SETTINGS.menuClickResize();
    });

});