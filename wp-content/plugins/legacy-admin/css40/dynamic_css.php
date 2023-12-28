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

/*---------------------------------------------
  Typography
 ---------------------------------------------*/

/* -------------------- Fonts -------------------- */
$legacy_fonts = legacy_css_fonts();

$legacystr = " h1,h2,h3,h4,h5,h6, "
			.".postbox .hndle, .stuffbox .hndle, "
			."#delete-action, "
			."#dashboard-widgets #dashboard_activity h4, "
			.".welcome-panel .about-description,"
			."#titlediv #title,"
			.".widefat tfoot tr th, .widefat thead tr th, th.manage-column a, th.sortable a,"
			.".form-wrap label,"
			.".theme-browser .theme .theme-name, .theme-browser .theme .more-details, "
			.".no-plugin-results , .no-plugin-results a,"
			.".form-table th, #ws_menu_editor .ws_item_title,"
			."#ws_menu_editor .ws_edit_field, .settings_page_menu_editor .ui-dialog-title, #poststuff h2, .metabox-holder h2.hndle, .postbox .hndle, .stuffbox .hndle";
echo $legacystr."{".$legacy_fonts['head_font_css']."}";


$legacystr = " body, p, a,"
		   .".postbox .inside, .stuffbox .inside,"
		   ."#activity-widget #the-comment-list .comment-item h4,"
		   ."#wpadminbar, .ws_edit_field-colors .ws_color_scheme_display, "
		   ."#ws_menu_editor .ws_main_container .ws_edit_field input, #ws_menu_editor .ws_main_container .ws_edit_field select, #ws_menu_editor .ws_main_container .ws_edit_field textarea";
echo $legacystr."{".$legacy_fonts['body_font_css']."}";

$legacystr = " #adminmenu .wp-submenu-head, #adminmenu a.menu-top, "
			."#adminmenu .wp-has-current-submenu ul>li>a, .folded #adminmenu li.menu-top .wp-submenu>li>a, "
			."#adminmenu .wp-not-current-submenu li>a, .folded #adminmenu .wp-has-current-submenu li>a,"
			."#collapse-menu";
echo $legacystr."{".$legacy_fonts['menu_font_css']."}";


if(isset($legacyadmin['submenu-line-height']) && $legacyadmin['submenu-line-height'] != ""){
	$legacystr = " #adminmenu .wp-has-current-submenu ul>li>a, .folded #adminmenu li.menu-top .wp-submenu>li>a, "
				."#adminmenu .wp-not-current-submenu .wp-submenu li>a, .folded #adminmenu .wp-has-current-submenu .wp-submenu li>a,"
				."#collapse-menu";
	echo $legacystr."{line-height:".$legacyadmin['submenu-line-height']." !important;;}";
}



$legacystr = " .wp-core-ui .button, .wp-core-ui .button-secondary,"
			.".wp-core-ui .button-primary,"
			.".upload-plugin .install-help, .upload-theme .install-help,"
			.".wordfenceWrap input[type='button'], .wordfenceWrap input[type='submit']";
echo $legacystr."{".$legacy_fonts['button_font_css']."}";


$legacystr = " .wrap h1";
echo $legacystr."{".$legacy_fonts['pagehead_font_css']."}";


/*---------------------------------------------
  Layout & Typography Section
 ---------------------------------------------*/

echo " \n/* -- Page BG -- */\n";
echo legacy_css_background("html,body, #wp-content-editor-tools, #ws_menu_editor .ws_editbox", "page-bg", "1.0") . "\n";


echo " \n/* -- Heading -- */\n";
$legacystr = " h1,h2,h3,h4,h5,h6, .wrap h2 , .wrap h1 , .welcome-panel .about-description";
echo legacy_css_color($legacystr, "heading-color", "1.0") . "\n";


echo " \n/* -- body text -- */\n";
$legacystr = " body, p,"
			."#dashboard_right_now li a:before, #dashboard_right_now li span:before, .welcome-panel .welcome-icon:before,"
			."#misc-publishing-actions label[for=post_status]:before, #post-body #visibility:before, #post-body .misc-pub-revisions:before, .curtime #timestamp:before, span.wp-media-buttons-icon:before,"
			.".misc-pub-section, input[type=radio]:checked+label:before, .view-switch>a:before,"
			.".no-plugin-results , .no-plugin-results a,"
			.".upload-plugin .install-help, .upload-theme .install-help,"
			.".form-wrap p, p.description,"
			."#screen-meta-links a, .contextual-help-tabs .active a";
echo legacy_css_color($legacystr, "body-text-color", "1.0") . "\n";


echo " \n/* -- link color -- */\n";
echo legacy_link_color("a, .no-plugin-results a, .row-actions span button.button-link", "link-color") . "\n";
 

/*---------------------------------------------
  Primary Color - Pick theme
 ---------------------------------------------*/

echo " \n/* -- primary -- */\n";

$primary_color_str = ".nav-tab, .nav-tab-active, .nav-tab-active:hover , .nav-tab:hover, input[type=checkbox]:checked:before,"
					."a.post-format-icon:hover:before, a.post-state-format:hover:before,"
					.".view-switch a.current:before,"
					.".theme-browser .theme.add-new-theme:focus span:after, .theme-browser .theme.add-new-theme:hover span:after,"
					.".theme-browser .theme.add-new-theme span:after,"
					.".nav-tab-active, .nav-tab-active:hover,"
					.".filter-links .current,"
					.".filter-links li>a:focus, .filter-links li>a:hover, .show-filters .filter-links a.current:focus, .show-filters .filter-links a.current:hover,"
					.".upload-plugin .wp-upload-form .button,"
					.".upload-plugin .wp-upload-form .button:disabled";
echo legacy_css_color($primary_color_str, "primary-color", "1.0") . "\n";



$primary_bgcolor_str = ".highlight, .highlight a, input[type=radio]:checked:before,"
					  ."#edit-slug-box .edit-slug.button, #edit-slug-box #view-post-btn .button,"
					  .".post-com-count:hover:after, .post-com-count:hover span,"
					  .".tablenav .tablenav-pages a:focus, .tablenav .tablenav-pages a:hover,"
					  .".media-item .bar,"
					  .".theme-browser .theme .more-details,"
					  .".theme-browser .theme.add-new-theme:focus:after, .theme-browser .theme.add-new-theme:hover:after,"
					  .".widgets-chooser li.widgets-chooser-selected,"
					  .".plugin-card .plugin-card-bottom,"
					  .".pace .pace-progress, #ws_menu_editor a.ws_button:hover,"
					  ."#ws_menu_editor .ws_main_container .ws_container";
echo legacy_css_bgcolor($primary_bgcolor_str, "primary-color", "1.0") . "\n";

$primary_border_str = "input[type=checkbox]:focus, input[type=color]:focus, input[type=date]:focus, input[type=datetime-local]:focus, input[type=datetime]:focus, input[type=email]:focus, input[type=month]:focus, input[type=number]:focus, input[type=password]:focus, input[type=radio]:focus, input[type=search]:focus, input[type=tel]:focus, input[type=text]:focus, input[type=time]:focus, input[type=url]:focus, input[type=week]:focus, select:focus, textarea:focus,"
					 ."input[type=checkbox]:hover, input[type=color]:hover, input[type=date]:hover, input[type=datetime-local]:hover, input[type=datetime]:hover, input[type=email]:hover, input[type=month]:hover, input[type=number]:hover, input[type=password]:hover, input[type=radio]:hover, input[type=search]:hover, input[type=tel]:hover, input[type=text]:hover, input[type=time]:hover, input[type=url]:hover, input[type=week]:hover, select:hover, textarea:hover,"
					 ."#titlediv #title:focus, #titlediv #title:hover,"
					 .".attachment-preview .thumbnail:hover,"
					 .".media-frame.mode-grid .attachment.details:focus .attachment-preview,"
					 .".media-frame.mode-grid .attachment:focus .attachment-preview,"
					 .".media-frame.mode-grid .selected.attachment:focus .attachment-preview,"
					 .".drag-drop.drag-over #drag-drop-area,"
					 .".theme-browser .theme:focus,"
					 ."#available-widgets .widget-top:hover, #widgets-left .widget-in-question .widget-top, #widgets-left .widget-top:hover, .widgets-chooser ul, div#widgets-right .widget-top:hover,"
					 .".widget-inside, .widget.open .widget-top, div#widgets-right .widgets-holder-wrap.widget-hover,"
					 .".filter-links .current,"
					 .".plugin-card:hover,"
					 .".contextual-help-tabs .active";
echo legacy_css_border_color($primary_border_str, "primary-color", "1.0","all") . "\n";
echo ".has-dfw .quicktags-toolbar{border-color:".$legacyadmin['primary-color']." !important;}";


$primary_border_bottom = "div.mce-toolbar-grp>div, .plugin-install-php .wp-filter, #ws_menu_editor .ws_main_container .ws_toolbar,.edit-post-sidebar__panel-tab.is-active";
echo legacy_css_border_color($primary_border_bottom, "primary-color", "1.0","bottom") . "\n";

$primary_border_top = ".post-com-count:hover:after";
echo legacy_css_border_color($primary_border_top, "primary-color", "1.0","top") . "\n";

$primary_border_left = ".plugins .active th.check-column";
echo legacy_css_border_color($primary_border_left, "primary-color", "1.0","left") . "\n";


echo "#wp-fullscreen-buttons .mce-btn:focus, #wp-fullscreen-buttons .mce-btn:hover, .mce-toolbar .mce-btn-group .mce-btn:focus, .mce-toolbar .mce-btn-group .mce-btn:hover, .qt-fullscreen:focus, .qt-fullscreen:hover,"
	.".wrap .add-new-h2:hover, .wrap .page-title-action:hover { "
	."background: ".$legacyadmin['primary-color']." !important;"
	."border-color: ".$legacyadmin['primary-color']." !important;"
	."color: ".$legacyadmin['button-text-color']." !important;"
	."}";

echo ".wrap .add-new-h2, .wrap .page-title-action{"
	."background: ".$legacyadmin['button-secondary-bg']." !important;"
	."color: ".$legacyadmin['button-text-color']." !important;"
	."}";


echo ".toplevel_page__legacyoptions #redux-header{border-color:".$legacyadmin['primary-color']." !important;background-color:".$legacyadmin['primary-color']." !important;}";

/*----------Media library - bug fix ------------*/
echo "

.media-progress-bar div{
	background-color: ".$legacyadmin['primary-color'].";
}

.media-modal-content .attachment.details {
	-webkit-box-shadow: inset 0 0 0 3px #fff,inset 0 0 0 7px ".$legacyadmin['primary-color'].";
	box-shadow: inset 0 0 0 3px #fff,inset 0 0 0 7px ".$legacyadmin['primary-color'].";
	-moz-box-shadow: inset 0 0 0 3px #fff,inset 0 0 0 7px ".$legacyadmin['primary-color'].";
	-ms-box-shadow: inset 0 0 0 3px #fff,inset 0 0 0 7px ".$legacyadmin['primary-color'].";
	-o-box-shadow: inset 0 0 0 3px #fff,inset 0 0 0 7px ".$legacyadmin['primary-color'].";
}

.media-modal-content .attachments .attachment:focus{
	-webkit-box-shadow: inset 0 0 0 3px #fff,inset 0 0 0 7px ".$legacyadmin['primary-color'].";
	box-shadow: inset 0 0 0 3px #fff,inset 0 0 0 7px ".$legacyadmin['primary-color'].";
	-ms-box-shadow: inset 0 0 0 3px #fff,inset 0 0 0 7px ".$legacyadmin['primary-color'].";
	-moz-box-shadow: inset 0 0 0 3px #fff,inset 0 0 0 7px ".$legacyadmin['primary-color'].";
	-o-box-shadow: inset 0 0 0 3px #fff,inset 0 0 0 7px ".$legacyadmin['primary-color'].";
}
.wp-core-ui .attachment.details .check, .wp-core-ui .attachment.selected .check:focus, .wp-core-ui .media-frame.mode-grid .attachment.selected .check,
.attachment.details .check, .attachment.selected .check:focus, .media-frame.mode-grid .attachment.selected .check {
	background-color: ".$legacyadmin['primary-color'].";
	-webkit-box-shadow: 0 0 0 1px #fff,0 0 0 2px ".$legacyadmin['primary-color'].";
	box-shadow: 0 0 0 1px #fff,0 0 0 2px ".$legacyadmin['primary-color'].";
	-moz-box-shadow: 0 0 0 1px #fff,0 0 0 2px ".$legacyadmin['primary-color'].";
	-ms-box-shadow: 0 0 0 1px #fff,0 0 0 2px ".$legacyadmin['primary-color'].";
	-o-box-shadow: 0 0 0 1px #fff,0 0 0 2px ".$legacyadmin['primary-color'].";
}";


/*------------------ RTL ----------------------*/

echo ".rtl .folded #adminmenu li.menu-top .wp-submenu>li>a:hover, 
.rtl #adminmenu .wp-submenu a:focus, 
.rtl #adminmenu .wp-submenu a:hover, 
.rtl #adminmenu .wp-submenu li.current a, 
.rtl #adminmenu .wp-submenu li.current a:hover,
.rtl .folded #adminmenu li.menu-top .wp-submenu>li>a:hover, 
.rtl #adminmenu .wp-submenu a:focus, 
.rtl #adminmenu .wp-submenu a:hover, 
.rtl #adminmenu .wp-submenu li.current a, 
.rtl #adminmenu .wp-submenu li.current a:hover,
.rtl .plugins .active th.check-column,
.rtl #wpadminbar .quicklinks .menupop.hover ul li a:hover,
.rtl .contextual-help-tabs .active
{
	border-right-color: ".$legacyadmin['primary-color'].";
}



";


echo " #ws_menu_editor.ws_is_actor_view .ws_is_hidden_for_actor{background-color: ".$legacyadmin['primary-color']." !important;}";
echo " #ws_menu_editor.ws_is_actor_view .ws_is_hidden_for_actor.ws_active{background-color: ".$legacyadmin['box-head-bg']['background-color']." !important;}";


/*---------------------------------------------
  Menu Icons Section
 ---------------------------------------------*/

echo " \n/* -- Menu Icons -- */\n";
$legacystr = " #adminmenu:before, "
			.".folded #adminmenu .opensub .wp-submenu:before, #adminmenu .opensub .wp-submenu:before, "
			.".folded #adminmenu .opensub .wp-submenu:before, #adminmenu .opensub .wp-submenu:before";
echo legacy_css_background_gradient($legacystr,"menu-icon-line-bg","menu-bg","bottom","50%","100%", "0.9") . "\n";

$legacystr = " #adminmenu:after, "
			.".folded #adminmenu .opensub .wp-submenu:after, #adminmenu .opensub .wp-submenu:after, "
			.".folded #adminmenu .opensub .wp-submenu:after, #adminmenu .opensub .wp-submenu:after";
echo legacy_css_background_gradient($legacystr,"menu-icon-line-bg","menu-bg","top","50%","100%", "0.9") . "\n";


$legacystr = " #adminmenu div.wp-menu-image:before, #adminmenu li a:focus div.wp-menu-image:before, #adminmenu li.opensub div.wp-menu-image:before, #adminmenu li:hover div.wp-menu-image:before";
echo legacy_css_background($legacystr, "menu-icon-bg", "1.0") . "\n";
echo legacy_css_border_color($legacystr, $legacyadmin['menu-icon-line-bg']['background-color'],"0.7","all","string"). "\n";
echo legacy_css_color($legacystr, "menu-icon-color", "1.0") . "\n";

$legacystr = " .wp-menu-image:after";
echo legacy_css_background($legacystr, "menu-bg", "1.0") . "\n";


$legacystr = " #adminmenu .current div.wp-menu-image:before, "
			."#adminmenu .wp-has-current-submenu div.wp-menu-image:before, "
			."#adminmenu a.current:hover div.wp-menu-image:before, "
			."#adminmenu a.wp-has-current-submenu:hover div.wp-menu-image:before, "
			."#adminmenu li.wp-has-current-submenu:hover div.wp-menu-image:before, "
			."#adminmenu li.opensub:hover div.wp-menu-image:before, "
			."#adminmenu a:hover div.wp-menu-image:before, "
			.".folded #adminmenu li.opensub div.wp-menu-image:before";
echo legacy_css_background($legacystr, "menu-active-icon-bg", "1.0") . "\n";
echo legacy_css_color($legacystr, "menu-active-icon-color", "1.0") . "\n";


$legacystr = " #adminmenu .wp-has-current-submenu ul>li>a:before, .folded #adminmenu li.menu-top .wp-submenu>li>a:before,"
			."#adminmenu .wp-not-current-submenu ul>li>a:before";
echo legacy_css_background($legacystr, "menu-bg", "1.0") . "\n";
echo legacy_css_border_color($legacystr, $legacyadmin['menu-icon-line-bg']['background-color'],"0.7","all","string"). "\n";

$legacystr = ".folded #adminmenu li.menu-top .wp-submenu>li>a:hover:before,"
			."#adminmenu .wp-submenu a:focus:before, "
			."#adminmenu .wp-submenu a:hover:before, "
			."#adminmenu .wp-submenu li.current a:before," 
			."#adminmenu .wp-submenu li.current a:hover:before,"
			.".folded #adminmenu li.menu-top .wp-submenu>li>a.current:before";
echo legacy_css_background($legacystr, "submenu-active-icon-bg", "1.0") . "\n";

/*---------------------------------------------
  Menu Section
 ---------------------------------------------*/

echo " \n/* -- Menu BG -- */\n";
$legacystr = " #adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap";
echo legacy_css_background($legacystr, "menu-bg", "1.0") . "\n";


$legacystr = " .folded #adminmenu .wp-has-current-submenu .wp-submenu, "
			.".folded #adminmenu .wp-has-current-submenu .wp-submenu.sub-open, "
			.".folded #adminmenu .wp-has-current-submenu.opensub .wp-submenu, "
			.".folded #adminmenu a.wp-has-current-submenu:focus+.wp-submenu, "
			.".folded .no-js li.wp-has-current-submenu:hover .wp-submenu";
echo legacy_css_background($legacystr, "menu-bg", "1.0") . "\n";


echo " \n/* -- Menu Text color -- */\n";
echo legacy_css_color("#adminmenu a, #ws_menu_editor .ws_item_title", "menu-color", "1.0") . "\n";

$legacystr = " #adminmenu a:hover, #adminmenu li.menu-top>a:focus, #adminmenu li.menu-top:hover, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus, "
			."#adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu";
echo legacy_css_color($legacystr, "menu-hover-color", "1.0") . "\n";



echo " \n/* -- Menu primary bg -- */\n";
$legacystr = " #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu, "
			."#adminmenu li.wp-has-submenu.wp-not-current-submenu.menu-top:hover, #adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub>a.menu-top, #adminmenu li.wp-has-submenu.wp-not-current-submenu>a.menu-top:focus,"
			."#adminmenu li.wp-not-current-submenu.menu-top:hover, #adminmenu li.wp-not-current-submenu.opensub>a.menu-top, #adminmenu li.wp-not-current-submenu>a.menu-top:focus, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus";


echo legacy_css_bgcolor($legacystr, "menu-primary-bg", "1.0") . "\n";


echo " \n/* -- SubMenu -- */\n";
$legacystr = " .folded #adminmenu li.menu-top .wp-submenu>li>a:hover, #adminmenu .wp-submenu a:focus, #adminmenu .wp-submenu a:hover, #adminmenu .wp-submenu li.current a, #adminmenu .wp-submenu li.current a:hover";
echo legacy_css_bgcolor($legacystr, "menu-secondary-bg", "1.0") . "\n";
echo legacy_css_color($legacystr, "submenu-color", "1.0") . "\n";
//echo legacy_css_border_color($legacystr, "menu-primary-bg", "", "left") . "\n";

$legacystr = " #adminmenu .opensub .wp-submenu li.current a, #adminmenu .wp-submenu li.current, #adminmenu .wp-submenu li.current a, #adminmenu .wp-submenu li.current a:focus, #adminmenu .wp-submenu li.current a:hover, #adminmenu a.wp-has-current-submenu:focus+.wp-submenu li.current a,"
			."#adminmenu .wp-submenu a";
echo legacy_css_color($legacystr, "submenu-color", "1.0") . "\n";


echo " \n/* -- Floating SubMenu -- */\n";
$legacystr = " #adminmenu .wp-not-current-submenu li>a:hover, .folded #adminmenu .wp-has-current-submenu li>a:hover";
echo legacy_css_color($legacystr, "submenu-color", "1.0") . "\n";
echo legacy_css_bgcolor($legacystr, "menu-secondary-bg", "1.0") . "\n";
echo legacy_css_border_color($legacystr, "menu-primary-bg", "", "left") . "\n";


echo " \n/* -- Floating SubMenu arrow -- */\n";
$legacystr = " #adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after";
echo legacy_css_border_color($legacystr, $legacyadmin['menu-bg']['background-color'],"","right","string");


echo " \n/* -- Collapsed Submenu - Menu Text color -- */\n";
echo legacy_css_color(".folded #adminmenu .wp-submenu .wp-submenu-head", "menu-color", "1.0") . "\n";


echo " \n/* -- Collapsed Submenu - SubMenu Text color -- */\n";
$legacystr = " #collapse-menu, #collapse-menu:hover, #collapse-menu:hover #collapse-button div:after, #collapse-button div:after";
echo legacy_css_color($legacystr, "submenu-color", "1.0") . "\n";


echo " \n/* -- Collapsed SubMenu -- */\n";
$legacystr = " .folded #adminmenu li.menu-top .wp-submenu>li>a.current";
echo legacy_css_border_color($legacystr, "menu-primary-bg", "", "left") . "\n";



echo " \n/* -- Logo BG -- */\n";
$legacystr = " #adminmenuwrap:before, .folded #adminmenuwrap:before";
echo legacy_css_bgcolor($legacystr, "logo-bg", "1.0") . "\n";



/*---------------------------------------------
  Boxes Section
 ---------------------------------------------*/

echo " \n/* -- Box BG -- */\n";
$legacystr = " .postbox, "
			."#screen-meta, #contextual-help-link-wrap, #screen-options-link-wrap, #ws_menu_editor .ws_main_container";
echo legacy_css_background($legacystr, "box-bg", "1.0") . "\n";

$legacystr = " .welcome-panel";
// echo legacy_css_background($legacystr, "box-bg", "1.0") . "\n";
echo legacy_css_bgcolor($legacystr, "primary-color", "1.0") . "\n";


echo " \n/* -- Box Head -- */\n";
$legacystr = " .postbox .hndle, .stuffbox .hndle, 
h2.hndle.ui-sortable-handle, #poststuff h2, .metabox-holder h2.hndle, .postbox .hndle, .stuffbox .hndle,
.settings_page_menu_editor .ui-dialog-titlebar, .postbox .postbox-header";
echo legacy_css_background($legacystr, "box-head-bg", "1.0") . "\n";
echo legacy_css_color($legacystr, "box-head-color", "1.0") . "\n";

$legacystr = " .welcome-panel h2";
echo legacy_css_color($legacystr, "#ffffff", "1.0","string") . "\n";

$legacystr = " #ws_menu_editor .ws_main_container .ws_container.ws_active";
echo legacy_css_background($legacystr, "box-head-bg", "1.0") . "\n";
$legacystr = " #ws_menu_editor .ws_item_title";
echo legacy_css_color($legacystr, "box-head-color", "1.0") . "\n";



echo " \n/* -- Data Tables Head -- */\n";
$legacystr = " table.widefat thead tr, table.widefat tfoot tr";
echo legacy_css_background($legacystr, "box-head-bg", "1.0") . "\n";

$legacystr = " table.widefat thead tr, table.widefat tfoot tr,"
		   ."th .comment-grey-bubble:before, th .sorting-indicator:before, .widefat tfoot tr th, .widefat thead tr th, th.manage-column a, th.sortable a:active, th.sortable a:focus, th.sortable a:hover";
echo legacy_css_color($legacystr, "box-head-color", "1.0") . "\n";


echo " \n/* --Admin Panel -> Menu section accordion title -- */\n";
$legacystr = " .js .control-section .accordion-section-title:focus, .js .control-section .accordion-section-title:hover, .js .control-section.open .accordion-section-title, .js .control-section:hover .accordion-section-title";
echo legacy_css_background($legacystr, "box-head-bg", "1.0") . "\n";
echo legacy_css_color($legacystr, "box-head-color", "1.0") . "\n";


echo " \n/* --Plugin Upload -- */\n";
$legacystr = " .upload-plugin .wp-upload-form, .upload-theme .wp-upload-form";
echo legacy_css_background($legacystr, "box-head-bg", "1.0") . "\n";
echo legacy_css_color($legacystr, "box-head-color", "1.0") . "\n";


echo " \n/* --Tools -> Importer -- */\n";
$legacystr = " .importers tr:hover td";
echo legacy_css_background($legacystr, "box-head-bg", "1.0") . "\n";
echo legacy_css_color($legacystr, "box-head-color", "1.0") . "\n";
$legacystr = " .importers tr:hover td a";
echo legacy_css_color($legacystr, "box-head-color", "1.0") . "\n";



echo " \n/* -- Box Head toggle arrow - Using opacity-- */\n";
$legacystr = " .js .meta-box-sortables .postbox .handlediv:before, .js .sidebar-name .sidebar-name-arrow:before, "
			.".welcome-panel .welcome-panel-close, #welcome-panel.welcome-panel .welcome-panel-close:before,"
			.".accordion-section-title:after, .handlediv, .item-edit, .sidebar-name-arrow, .widget-action,"
			.".accordion-section-title:focus:after, .accordion-section-title:hover:after, "
			."#ws_menu_editor a.ws_edit_link:before";
echo legacy_css_color($legacystr, "box-head-color", "0.7") . "\n";
    
echo " \n/* -- Box Head toggle arrow - Using opacity-- !important */\n";
echo "#bulk-titles div a:before, #welcome-panel.welcome-panel .welcome-panel-close:before, .tagchecklist span a:before{color: ".legacy_colorcode($legacyadmin['box-head-color'],"0.7","!important")."} ";
echo ".accordion-section-title:focus:after, .accordion-section-title:hover:after{border-color: ".legacy_colorcode($legacyadmin['box-head-color'],"0.7"," transparent")."}";



/*---------------------------------------------
  Form Section
 ---------------------------------------------*/

echo " \n/* -- Form element -- */\n";
$legacystr = " input[type=checkbox], input[type=color], input[type=date], input[type=datetime-local], input[type=datetime], input[type=email], input[type=month], input[type=number], input[type=password], input[type=radio], input[type=search], input[type=tel], input[type=text], input[type=time], input[type=url], input[type=week], select, textarea, .wp-core-ui select, body.wp-admin div select, .wp-core-ui select:hover";
echo legacy_css_bgcolor($legacystr, "form-bg", "1.0") . "\n";
echo legacy_css_border_color($legacystr, "form-border-color", "", "all") . "\n";
echo legacy_css_color($legacystr, "form-text-color", "1.0") . "\n";


echo " \n/* -- Post Title -- */\n";
$legacystr = " #titlediv #title";
echo legacy_css_border_color($legacystr, "form-border-color", "", "all") . "\n";


/*---------------------------------------------
  Button Section
 ---------------------------------------------*/

echo " \n/* -- Button text color -- */\n";
$legacystr = " .wp-core-ui .button, .wp-core-ui .button-secondary, "
		   .".wp-media-buttons .add_media span.wp-media-buttons-icon:before, "
 		   .".wp-core-ui .button-secondary:focus, .wp-core-ui .button-secondary:hover, .wp-core-ui .button.focus, .wp-core-ui .button.hover, .wp-core-ui .button:focus, .wp-core-ui .button:hover,"
 		   .".wp-core-ui .button-primary,"
 		   .".wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover,"
 		   ."#wp-fullscreen-buttons .mce-btn:focus .mce-ico, #wp-fullscreen-buttons .mce-btn:hover .mce-ico, .mce-toolbar .mce-btn-group .mce-btn:focus .mce-ico, .mce-toolbar .mce-btn-group .mce-btn:hover .mce-ico, .qt-fullscreen:focus .mce-ico, .qt-fullscreen:hover .mce-ico,"
 		   .".media-frame a.button, .media-frame a.button:hover,"
 		   .".wordfenceWrap input[type='button'], .wordfenceWrap input[type='submit'], .wordfenceWrap input[type='button']:hover, .wordfenceWrap input[type='submit']:hover, .wordfenceWrap input[type='button']:focus, .wordfenceWrap input[type='submit']:focus,.components-button.is-primary";
echo legacy_css_color($legacystr, "button-text-color", "1.0") . "\n";


echo " \n/* -- Button secondary bg color -- */\n";
$legacystr = " .wp-core-ui .button, .wp-core-ui .button-secondary, .wordfenceWrap input[type='button'], .wordfenceWrap input[type='submit']";
echo legacy_css_bgcolor($legacystr, "button-secondary-bg", "1.0") . "\n";


echo " \n/* -- Button secondary hover bg color -- */\n";
$legacystr = " .wp-core-ui .button-secondary:focus, .wp-core-ui .button-secondary:hover, .wp-core-ui .button.focus, .wp-core-ui .button.hover, .wp-core-ui .button:focus, .wp-core-ui .button:hover,"
		   ."#edit-slug-box .edit-slug.button:hover, #edit-slug-box #view-post-btn .button:hover";
echo legacy_css_bgcolor($legacystr, "button-secondary-hover-bg", "1.0") . "\n";


echo " \n/* -- Button primary bg color -- */\n";
$legacystr = " .wp-core-ui .button-primary,"
		   .".row-actions span a:hover,.row-actions span button.button-link:hover,"
		   .".plugin-card .install-now.button, .plugin-card .button,"
		   .".wordfenceWrap input[type='button'], .wordfenceWrap input[type='submit'],.components-button.is-primary";
echo legacy_css_bgcolor($legacystr, "button-primary-bg", "1.0") . "\n";


echo " \n/* -- Button primary hover bg color -- */\n";
$legacystr = " .wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover,"
		   ."#adminmenu .awaiting-mod, #adminmenu .update-plugins, #sidemenu li a span.update-plugins,"
		   .".wordfenceWrap input[type='button']:hover, .wordfenceWrap input[type='submit']:hover, .wordfenceWrap input[type='button']:focus, .wordfenceWrap input[type='submit']:focus,.components-button.is-primary:hover";
echo legacy_css_bgcolor($legacystr, "button-primary-hover-bg", "1.0") . "\n";


echo " \n/* ---- disabled button - !important ----- */\n";
$legacystr = " .wp-core-ui .button-primary-disabled, .wp-core-ui .button-primary.disabled, .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary[disabled]";
echo $legacystr." {color: ".legacy_colorcode($legacyadmin['button-text-color'],"1.0","!important")."}";
echo $legacystr." {background-color: ".legacy_colorcode($legacyadmin['button-primary-bg'],"1.0","!important")."}";

/*----------------------------------
 Admin Top bar
-----------------------------------*/

echo " \n/* -- Top bar BG - like menu bg-- */\n";
$legacystr = " #wpadminbar";
echo legacy_css_background($legacystr, "topbar-menu-bg", "1.0") . "\n";


$legacystr = " #wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar .ab-top-menu>li:hover>.ab-item, #wpadminbar .ab-top-menu>li>.ab-item:focus, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus,"
			."#wpadminbar .menupop .ab-sub-wrapper, #wpadminbar .shortlink-input,"
			."#wp-admin-bar-my-account .ab-sub-wrapper .ab-submenu li,"
			."#wpadminbar .quicklinks .menupop.hover ul li .ab-item,"
			."#wpadminbar .quicklinks .ab-empty-item:hover, #wpadminbar .quicklinks a:hover, #wpadminbar .shortlink-input:hover";
echo legacy_css_bgcolor($legacystr, "topbar-submenu-bg", "1.0") . "\n";



$legacystr = " #wpadminbar #wp-admin-bar-user-info:hover a,"
			."#wpadminbar .quicklinks .menupop.hover ul li a:hover"
			."";
echo legacy_css_bgcolor($legacystr, "topbar-submenu-hover-bg", "1.0") . "\n";


$legacystr = " #wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar .ab-top-menu>li:hover>.ab-item, #wpadminbar .ab-top-menu>li>.ab-item:focus, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, "
			."#wpadminbar .ab-submenu .ab-item, #wpadminbar .quicklinks .menupop ul li a, #wpadminbar .quicklinks .menupop ul li a strong, #wpadminbar .quicklinks .menupop.hover ul li a, #wpadminbar.nojs .quicklinks .menupop:hover ul li a,"
			."#wpadminbar .quicklinks .ab-empty-item, #wpadminbar .quicklinks a, #wpadminbar .shortlink-input,"
			."#wpadminbar .quicklinks .menupop ul li a:focus, #wpadminbar .quicklinks .menupop ul li a:focus strong, #wpadminbar .quicklinks .menupop ul li a:hover, #wpadminbar .quicklinks .menupop ul li a:hover strong, #wpadminbar .quicklinks .menupop.hover ul li a:focus, #wpadminbar .quicklinks .menupop.hover ul li a:hover, #wpadminbar li .ab-item:focus:before, #wpadminbar li a:focus .ab-icon:before, #wpadminbar li.hover .ab-icon:before, #wpadminbar li.hover .ab-item:before, #wpadminbar li:hover #adminbarsearch:before, #wpadminbar li:hover .ab-icon:before, #wpadminbar li:hover .ab-item:before, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover,"
			."#wpadminbar>#wp-toolbar a:focus span.ab-label, #wpadminbar>#wp-toolbar li.hover span.ab-label, #wpadminbar>#wp-toolbar li:hover span.ab-label";
echo legacy_css_color($legacystr, "topbar-submenu-color", "1.0") . "\n";


$legacystr = " #wpadminbar .quicklinks .menupop.hover ul li a:hover";
echo legacy_css_border_color($legacystr, "primary-color", "", "left") . "\n";

$legacystr = " #wpadminbar a.ab-item, #wpadminbar>#wp-toolbar span.ab-label, #wpadminbar>#wp-toolbar span.noticon,"
			."#wpadminbar #adminbarsearch:before, #wpadminbar .ab-icon:before, #wpadminbar .ab-item:before, "
			."#wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, #wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item, #wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus, "
			."#wpadminbar:not(.mobile)>#wp-toolbar a:focus span.ab-label, #wpadminbar:not(.mobile)>#wp-toolbar li:hover span.ab-label, #wpadminbar>#wp-toolbar li.hover span.ab-label, #wpadminbar .ab-top-menu:hover .ab-item:before, #wpadminbar .ab-item:hover .ab-icon:before ";
echo legacy_css_color($legacystr, "topbar-menu-color", "1.0") . "\n";

echo " \n/* -- Top bar Style -- */\n";
echo legacy_admintopbar_style();


?>




