/**
 * @Package: WordPress Plugin
 * @Subpackage: Legacy - White Label WordPress Admin Theme
 * @Since: Legacy 1.0
 * @WordPress Version: 4.0 or above
 * This file is part of Legacy - White Label WordPress Admin Theme Plugin.
 */


jQuery(function($) {

    'use strict';

    var LEGACY_LOGIN_SETTINGS = window.LEGACY_LOGIN_SETTINGS || {};


    LEGACY_LOGIN_SETTINGS.placeholderFields = function() {

        $('#user_login').attr('placeholder', 'Username');
        $('#user_email').attr('placeholder', 'Email');
        $('#user_pass').attr('placeholder', 'Password');

    };



    /******************************
     initialize respective scripts 
     *****************************/
    $(document).ready(function() {
        LEGACY_LOGIN_SETTINGS.placeholderFields();

    });

    $(window).resize(function() {
    });

    $(window).load(function() {
    });

});