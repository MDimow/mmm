/**
 * @Package: WordPress Plugin
 * @Subpackage: Legacy - White Label WordPress Admin Theme
 * @Since: Legacy 1.0
 * @WordPress Version: 4.0 or above
 * This file is part of Legacy - White Label WordPress Admin Theme Plugin.
 */


jQuery(function($) {

    'use strict';

    var LEGACY_MENUMNG_SETTINGS = window.LEGACY_MENUMNG_SETTINGS || {};



    LEGACY_MENUMNG_SETTINGS.iconPanel = function(e) {

        $(document).on("click",".legacyicon",function(e) {
            e.stopPropagation();
            var panel = $(this).parent().find(".legacyiconpanel");
            var iconstr = $(".legacyicons").html();
            panel.html("");
            panel.append(iconstr);
            panel.show();
        });


    };




    LEGACY_MENUMNG_SETTINGS.menuToggle = function() {

        $(document).on("click",".legacytoggle",function(e) {

            var id = $(this).parent().attr("data-id");

            if ($(this).hasClass("plus")) {
                $(this).removeClass("plus dashicons-plus").addClass("minus dashicons-minus");
                //$(this).html("-");
                $(this).parent().parent().find(".legacymenupanel").removeClass("closed").addClass("opened");
            } else if ($(this).hasClass("minus")) {
                $(this).removeClass("minus dashicons-minus").addClass("plus dashicons-plus");
                //$(this).html("+");
                $(this).parent().parent().find(".legacymenupanel").removeClass("opened").addClass("closed");
            }

        });


        $(document).on("click",".legacysubtoggle",function(e) {

            var id = $(this).parent().attr("data-id");

            if ($(this).hasClass("plus")) {
                $(this).removeClass("plus dashicons-plus").addClass("minus dashicons-minus");
                //$(this).html("-");
                $(this).parent().parent().find(".legacysubmenupanel").removeClass("closed").addClass("opened");
            } else if ($(this).hasClass("minus")) {
                $(this).removeClass("minus dashicons-minus").addClass("plus dashicons-plus");
                //$(this).html("+");
                $(this).parent().parent().find(".legacysubmenupanel").removeClass("opened").addClass("closed");
            }

        });


    };

    LEGACY_MENUMNG_SETTINGS.saveMenu = function() {

    $(document).on('click', '#legacy-savemenu', function(e){
            var neworder = "";
            var newsuborder = "";
            var menurename = "";
            var submenurename = "";
            var menudisable = "";
            var submenudisable = "";

            $(".legacymenu").each(function() {
                var id = $(this).attr("data-id");
                var menuid = $(this).attr("data-menu-id");
                neworder += menuid + "|";
                if ($(this).hasClass("disabled")) {
                    menudisable += menuid + "|";
                }
            });

            $(".legacysubmenu").each(function() {
                var id = $(this).attr("data-id");
                var parentpage = $(this).attr("data-parent-page");
                newsuborder += parentpage + ":" + id + "|";
                if ($(this).hasClass("disabled")) {
                    submenudisable += parentpage + ":" + id + "|";
                }
            });

            $(".legacy-menurename").each(function() {
                var id = $(this).attr("data-id");
                var sid = $(this).attr("data-menu-id");
                var val = $(this).val();
                var icon = $(this).parent().parent().find(".legacy-menuicon").attr("value");
                //console.log(icon);
                menurename += id + ":" + sid + "@!@%@" + val + "[$!&!$]" + icon + "|#$%*|";
            });


            $(".legacy-submenurename").each(function() {
                var id = $(this).attr("data-id");
                var parent = $(this).attr("data-parent-id");
                var parentpage = $(this).attr("data-parent-page");
                var val = $(this).val();
                submenurename += parentpage + "[($&)]" + parent + ":" + id + "@!@%@" + val + "|#$%*|";
            });



            var action = 'legacy_savemenu';
            var data = {
                neworder: neworder,
                newsuborder: newsuborder,
                menurename: menurename,
                submenurename: submenurename,
                menudisable: menudisable,
                submenudisable: submenudisable,
                action: action,
                legacy_nonce: legacy_vars.legacy_nonce
            };

            $.post(ajaxurl, data, function(response) {
                location.reload();
                //console.log(response);
            });

            return false;

        });

    };


    LEGACY_MENUMNG_SETTINGS.resetMenu = function() {


        $(document).on('click', '#legacy-resetmenu', function(e){

            var action = 'legacy_resetmenu';
            var data = {
                action: action,
                legacy_nonce: legacy_vars.legacy_nonce
            };

            $.post(ajaxurl, data, function(response) {
                location.reload();
                //console.log(response);
            });

            return false;

        });

    };






    LEGACY_MENUMNG_SETTINGS.menuDisplay = function() {

        $(document).on('click', '.legacydisplay, .legacysubdisplay', function(e){

            //var id = $(this).parent().attr("data-id");

            if ($(this).hasClass("disable")) {
                $(this).removeClass("disable").addClass("enable");
                //$(this).html("show");
                $(this).parent().parent().removeClass("enabled").addClass("disabled");
            } else if ($(this).hasClass("enable")) {
                $(this).removeClass("enable").addClass("disable");
                //$(this).html("hide");
                $(this).parent().parent().removeClass("disabled").addClass("enabled");
            }

        });

    };


    /******************************
     initialize respective scripts 
     *****************************/
    $(document).ready(function() {
        
        LEGACY_MENUMNG_SETTINGS.menuToggle();
        LEGACY_MENUMNG_SETTINGS.saveMenu();
        LEGACY_MENUMNG_SETTINGS.menuDisplay();
        LEGACY_MENUMNG_SETTINGS.iconPanel();
        LEGACY_MENUMNG_SETTINGS.resetMenu();
    });

    $(window).resize(function() {
        
    });

    $(window).load(function() {
        
    });

});


jQuery(function($) {
    if ($.isFunction($.fn.sortable)) {
        $("#legacy-enabled, #legacy-disabled").sortable({
            connectWith: ".legacy-connectedSortable",
            handle: ".legacymenu-wrap",
            cancel: ".legacytoggle",
            placeholder: "ui-state-highlight",
        }).disableSelection();
    }
});


jQuery(function($) {
    if ($.isFunction($.fn.sortable)) {
        $(".legacysubmenu-wrap").sortable({
            placeholder: "ui-state-highlight",
        }).disableSelection();
    }
});


jQuery(function($) {
    $(document).ready(function() {
        $(document).on('click', ".pickicon", function() {
            var clss = $(this).attr("data-class");
            var prnt = $(this).parent().parent();
            //console.log(clss);
            prnt.find("input").attr("value", clss);
            prnt.find("input").val(clss);
            var main = prnt.find(".legacymenuicon");
            main.removeClass(main.attr("data-class")).addClass(clss);
            main.attr("data-class", clss);
            return false;
        });

        $(document).on('click', "body", function() {
            $(".legacyiconpanel").hide();
            //return false;
        });




    });
});
