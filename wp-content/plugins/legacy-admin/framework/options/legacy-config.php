<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }


    // This is your option name where all the Redux data is stored.
    $opt_name = "legacy_demo";



    // If Redux is running as a plugin, this will remove the demo notice and links
    //add_action( 'redux/loaded', 'remove_demo' );

    // Function to test the compiler hook and demo CSS output.
    // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
    //add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

    // Change the arguments after they've been declared, but before the panel is created
    //add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

    // Change the default value of a field after it's been set, but before it's been useds
    //add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

    // Dynamically add a section. Can be also used to modify sections/fields
    //add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');


    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */




            /*ADDED in Legacy Admin - START */
                // manageoptions and super admin

                $legacyadmin_permissions = legacy_get_option( "legacyadmin_plugin_access","manage_options");
                if($legacyadmin_permissions == "super_admin" && is_super_admin()){
                    $legacyadmin_permissions = 'manage_options';
                }

                // specific user
                $legacyadmin_userid = legacy_get_option( "legacyadmin_plugin_userid","");
                if($legacyadmin_permissions == "specific_user" && $legacyadmin_userid == get_current_user_id()){
                    $legacyadmin_permissions = 'read';
                }

                // if plugin is network activated, then hide it from inner sites.
                $menu_type = 'menu';

                if ( ! function_exists( 'is_plugin_active_for_network' ) ){
                    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
                }

                // Makes sure the plugin is defined before trying to use it
                    if ( is_plugin_active_for_network( 'legacy-admin/legacy-core.php' )){
                        if(!is_main_site()){
                            $menu_type = 'hidden';
                        }
                    }


/*ADDED in Legacy Admin - END*/




    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => 'Legacy Admin',
        // Name that appears at the top of your panel
        'display_version'      => '', //'9.2',
        // Version that appears at the top of your panel
        'menu_type'            => $menu_type,
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => __( 'Legacy Admin', 'legacy_framework' ),
        'page_title'           => __( 'Legacy Admin', 'legacy_framework' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => true,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-art',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => 'legacyadmin',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => true,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => false,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => $legacyadmin_permissions,
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '_legacyoptions',
        // Page slug used to denote the panel
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
         'footer_credit'     => '&nbsp;',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'system_info'          => false,
        // REMOVE

        //'compiler'             => true,

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'light',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */


    /*
     * ---> START HELP TABS
     */
/*
    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'Theme Information 1', 'legacy_framework' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'legacy_framework' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2', 'legacy_framework' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'legacy_framework' )
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'legacy_framework' );
    Redux::setHelpSidebar( $opt_name, $content );
*/

    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */

    // -> START Basic Fields
            $standard_fonts = array(
                '0' => 'Select Font',
                'Arial, Helvetica, sans-serif' => 'Arial, Helvetica, sans-serif',
                "'Arial Black', Gadget, sans-serif" => "'Arial Black', Gadget, sans-serif",
                "'Bookman Old Style', serif" => "'Bookman Old Style', serif",
                "'Comic Sans MS', cursive" => "'Comic Sans MS', cursive",
                "Courier, monospace" => "Courier, monospace",
                "Garamond, serif" => "Garamond, serif",
                "Georgia, serif" => "Georgia, serif",
                "Impact, Charcoal, sans-serif" => "Impact, Charcoal, sans-serif",
                "'Lucida Console', Monaco, monospace" => "'Lucida Console', Monaco, monospace",
                "'Lucida Sans Unicode', 'Lucida Grande', sans-serif" => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
                "'MS Sans Serif', Geneva, sans-serif" => "'MS Sans Serif', Geneva, sans-serif",
                "'MS Serif', 'New York', sans-serif" => "'MS Serif', 'New York', sans-serif",
                "'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "'Palatino Linotype', 'Book Antiqua', Palatino, serif",
                "Tahoma, Geneva, sans-serif" => "Tahoma, Geneva, sans-serif",
                "'Times New Roman', Times, serif" => "'Times New Roman', Times, serif",
                "'Trebuchet MS', Helvetica, sans-serif" => "'Trebuchet MS', Helvetica, sans-serif",
                "Verdana, Geneva, sans-serif" => "Verdana, Geneva, sans-serif"
            );


            $google_fonts = array(
                "0" => "Select Font",
                "ABeeZee" => "ABeeZee",
                "Abel" => "Abel",
                "Abril Fatface" => "Abril Fatface",
                "Aclonica" => "Aclonica",
                "Acme" => "Acme",
                "Actor" => "Actor",
                "Adamina" => "Adamina",
                "Advent Pro" => "Advent Pro",
                "Aguafina Script" => "Aguafina Script",
                "Akronim" => "Akronim",
                "Aladin" => "Aladin",
                "Aldrich" => "Aldrich",
                "Alef" => "Alef",
                "Alegreya" => "Alegreya",
                "Alegreya SC" => "Alegreya SC",
                "Alegreya Sans" => "Alegreya Sans",
                "Alegreya Sans SC" => "Alegreya Sans SC",
                "Alex Brush" => "Alex Brush",
                "Alfa Slab One" => "Alfa Slab One",
                "Alice" => "Alice",
                "Alike" => "Alike",
                "Alike Angular" => "Alike Angular",
                "Allan" => "Allan",
                "Allerta" => "Allerta",
                "Allerta Stencil" => "Allerta Stencil",
                "Allura" => "Allura",
                "Almendra" => "Almendra",
                "Almendra Display" => "Almendra Display",
                "Almendra SC" => "Almendra SC",
                "Amarante" => "Amarante",
                "Amaranth" => "Amaranth",
                "Amatic SC" => "Amatic SC",
                "Amethysta" => "Amethysta",
                "Anaheim" => "Anaheim",
                "Andada" => "Andada",
                "Andika" => "Andika",
                "Angkor" => "Angkor",
                "Annie Use Your Telescope" => "Annie Use Your Telescope",
                "Anonymous Pro" => "Anonymous Pro",
                "Antic" => "Antic",
                "Antic Didone" => "Antic Didone",
                "Antic Slab" => "Antic Slab",
                "Anton" => "Anton",
                "Arapey" => "Arapey",
                "Arbutus" => "Arbutus",
                "Arbutus Slab" => "Arbutus Slab",
                "Architects Daughter" => "Architects Daughter",
                "Archivo Black" => "Archivo Black",
                "Archivo Narrow" => "Archivo Narrow",
                "Arimo" => "Arimo",
                "Arizonia" => "Arizonia",
                "Armata" => "Armata",
                "Artifika" => "Artifika",
                "Arvo" => "Arvo",
                "Asap" => "Asap",
                "Asset" => "Asset",
                "Astloch" => "Astloch",
                "Asul" => "Asul",
                "Atomic Age" => "Atomic Age",
                "Aubrey" => "Aubrey",
                "Audiowide" => "Audiowide",
                "Autour One" => "Autour One",
                "Average" => "Average",
                "Average Sans" => "Average Sans",
                "Averia Gruesa Libre" => "Averia Gruesa Libre",
                "Averia Libre" => "Averia Libre",
                "Averia Sans Libre" => "Averia Sans Libre",
                "Averia Serif Libre" => "Averia Serif Libre",
                "Bad Script" => "Bad Script",
                "Balthazar" => "Balthazar",
                "Bangers" => "Bangers",
                "Basic" => "Basic",
                "Battambang" => "Battambang",
                "Baumans" => "Baumans",
                "Bayon" => "Bayon",
                "Belgrano" => "Belgrano",
                "Belleza" => "Belleza",
                "BenchNine" => "BenchNine",
                "Bentham" => "Bentham",
                "Berkshire Swash" => "Berkshire Swash",
                "Bevan" => "Bevan",
                "Bigelow Rules" => "Bigelow Rules",
                "Bigshot One" => "Bigshot One",
                "Bilbo" => "Bilbo",
                "Bilbo Swash Caps" => "Bilbo Swash Caps",
                "Bitter" => "Bitter",
                "Black Ops One" => "Black Ops One",
                "Bokor" => "Bokor",
                "Bonbon" => "Bonbon",
                "Boogaloo" => "Boogaloo",
                "Bowlby One" => "Bowlby One",
                "Bowlby One SC" => "Bowlby One SC",
                "Brawler" => "Brawler",
                "Bree Serif" => "Bree Serif",
                "Bubblegum Sans" => "Bubblegum Sans",
                "Bubbler One" => "Bubbler One",
                "Buda" => "Buda",
                "Buenard" => "Buenard",
                "Butcherman" => "Butcherman",
                "Butterfly Kids" => "Butterfly Kids",
                "Cabin" => "Cabin",
                "Cabin Condensed" => "Cabin Condensed",
                "Cabin Sketch" => "Cabin Sketch",
                "Caesar Dressing" => "Caesar Dressing",
                "Cagliostro" => "Cagliostro",
                "Calligraffitti" => "Calligraffitti",
                "Cambo" => "Cambo",
                "Candal" => "Candal",
                "Cantarell" => "Cantarell",
                "Cantata One" => "Cantata One",
                "Cantora One" => "Cantora One",
                "Capriola" => "Capriola",
                "Cardo" => "Cardo",
                "Carme" => "Carme",
                "Carrois Gothic" => "Carrois Gothic",
                "Carrois Gothic SC" => "Carrois Gothic SC",
                "Carter One" => "Carter One",
                "Caudex" => "Caudex",
                "Cedarville Cursive" => "Cedarville Cursive",
                "Ceviche One" => "Ceviche One",
                "Changa One" => "Changa One",
                "Chango" => "Chango",
                "Chau Philomene One" => "Chau Philomene One",
                "Chela One" => "Chela One",
                "Chelsea Market" => "Chelsea Market",
                "Chenla" => "Chenla",
                "Cherry Cream Soda" => "Cherry Cream Soda",
                "Cherry Swash" => "Cherry Swash",
                "Chewy" => "Chewy",
                "Chicle" => "Chicle",
                "Chivo" => "Chivo",
                "Cinzel" => "Cinzel",
                "Cinzel Decorative" => "Cinzel Decorative",
                "Clicker Script" => "Clicker Script",
                "Coda" => "Coda",
                "Coda Caption" => "Coda Caption",
                "Codystar" => "Codystar",
                "Combo" => "Combo",
                "Comfortaa" => "Comfortaa",
                "Coming Soon" => "Coming Soon",
                "Concert One" => "Concert One",
                "Condiment" => "Condiment",
                "Content" => "Content",
                "Contrail One" => "Contrail One",
                "Convergence" => "Convergence",
                "Cookie" => "Cookie",
                "Copse" => "Copse",
                "Corben" => "Corben",
                "Courgette" => "Courgette",
                "Cousine" => "Cousine",
                "Coustard" => "Coustard",
                "Covered By Your Grace" => "Covered By Your Grace",
                "Crafty Girls" => "Crafty Girls",
                "Creepster" => "Creepster",
                "Crete Round" => "Crete Round",
                "Crimson Text" => "Crimson Text",
                "Croissant One" => "Croissant One",
                "Crushed" => "Crushed",
                "Cuprum" => "Cuprum",
                "Cutive" => "Cutive",
                "Cutive Mono" => "Cutive Mono",
                "Damion" => "Damion",
                "Dancing Script" => "Dancing Script",
                "Dangrek" => "Dangrek",
                "Dawning of a New Day" => "Dawning of a New Day",
                "Days One" => "Days One",
                "Delius" => "Delius",
                "Delius Swash Caps" => "Delius Swash Caps",
                "Delius Unicase" => "Delius Unicase",
                "Della Respira" => "Della Respira",
                "Denk One" => "Denk One",
                "Devonshire" => "Devonshire",
                "Didact Gothic" => "Didact Gothic",
                "Diplomata" => "Diplomata",
                "Diplomata SC" => "Diplomata SC",
                "Domine" => "Domine",
                "Donegal One" => "Donegal One",
                "Doppio One" => "Doppio One",
                "Dorsa" => "Dorsa",
                "Dosis" => "Dosis",
                "Dr Sugiyama" => "Dr Sugiyama",
                "Droid Sans" => "Droid Sans",
                "Droid Sans Mono" => "Droid Sans Mono",
                "Droid Serif" => "Droid Serif",
                "Duru Sans" => "Duru Sans",
                "Dynalight" => "Dynalight",
                "EB Garamond" => "EB Garamond",
                "Eagle Lake" => "Eagle Lake",
                "Eater" => "Eater",
                "Economica" => "Economica",
                "Electrolize" => "Electrolize",
                "Elsie" => "Elsie",
                "Elsie Swash Caps" => "Elsie Swash Caps",
                "Emblema One" => "Emblema One",
                "Emilys Candy" => "Emilys Candy",
                "Engagement" => "Engagement",
                "Englebert" => "Englebert",
                "Enriqueta" => "Enriqueta",
                "Erica One" => "Erica One",
                "Esteban" => "Esteban",
                "Euphoria Script" => "Euphoria Script",
                "Ewert" => "Ewert",
                "Exo" => "Exo",
                "Exo 2" => "Exo 2",
                "Expletus Sans" => "Expletus Sans",
                "Fanwood Text" => "Fanwood Text",
                "Fascinate" => "Fascinate",
                "Fascinate Inline" => "Fascinate Inline",
                "Faster One" => "Faster One",
                "Fasthand" => "Fasthand",
                "Fauna One" => "Fauna One",
                "Federant" => "Federant",
                "Federo" => "Federo",
                "Felipa" => "Felipa",
                "Fenix" => "Fenix",
                "Finger Paint" => "Finger Paint",
                "Fjalla One" => "Fjalla One",
                "Fjord One" => "Fjord One",
                "Flamenco" => "Flamenco",
                "Flavors" => "Flavors",
                "Fondamento" => "Fondamento",
                "Fontdiner Swanky" => "Fontdiner Swanky",
                "Forum" => "Forum",
                "Francois One" => "Francois One",
                "Freckle Face" => "Freckle Face",
                "Fredericka the Great" => "Fredericka the Great",
                "Fredoka One" => "Fredoka One",
                "Freehand" => "Freehand",
                "Fresca" => "Fresca",
                "Frijole" => "Frijole",
                "Fruktur" => "Fruktur",
                "Fugaz One" => "Fugaz One",
                "GFS Didot" => "GFS Didot",
                "GFS Neohellenic" => "GFS Neohellenic",
                "Gabriela" => "Gabriela",
                "Gafata" => "Gafata",
                "Galdeano" => "Galdeano",
                "Galindo" => "Galindo",
                "Gentium Basic" => "Gentium Basic",
                "Gentium Book Basic" => "Gentium Book Basic",
                "Geo" => "Geo",
                "Geostar" => "Geostar",
                "Geostar Fill" => "Geostar Fill",
                "Germania One" => "Germania One",
                "Gilda Display" => "Gilda Display",
                "Give You Glory" => "Give You Glory",
                "Glass Antiqua" => "Glass Antiqua",
                "Glegoo" => "Glegoo",
                "Gloria Hallelujah" => "Gloria Hallelujah",
                "Goblin One" => "Goblin One",
                "Gochi Hand" => "Gochi Hand",
                "Gorditas" => "Gorditas",
                "Goudy Bookletter 1911" => "Goudy Bookletter 1911",
                "Graduate" => "Graduate",
                "Grand Hotel" => "Grand Hotel",
                "Gravitas One" => "Gravitas One",
                "Great Vibes" => "Great Vibes",
                "Griffy" => "Griffy",
                "Gruppo" => "Gruppo",
                "Gudea" => "Gudea",
                "Habibi" => "Habibi",
                "Hammersmith One" => "Hammersmith One",
                "Hanalei" => "Hanalei",
                "Hanalei Fill" => "Hanalei Fill",
                "Handlee" => "Handlee",
                "Hanuman" => "Hanuman",
                "Happy Monkey" => "Happy Monkey",
                "Headland One" => "Headland One",
                "Henny Penny" => "Henny Penny",
                "Herr Von Muellerhoff" => "Herr Von Muellerhoff",
                "Holtwood One SC" => "Holtwood One SC",
                "Homemade Apple" => "Homemade Apple",
                "Homenaje" => "Homenaje",
                "IM Fell DW Pica" => "IM Fell DW Pica",
                "IM Fell DW Pica SC" => "IM Fell DW Pica SC",
                "IM Fell Double Pica" => "IM Fell Double Pica",
                "IM Fell Double Pica SC" => "IM Fell Double Pica SC",
                "IM Fell English" => "IM Fell English",
                "IM Fell English SC" => "IM Fell English SC",
                "IM Fell French Canon" => "IM Fell French Canon",
                "IM Fell French Canon SC" => "IM Fell French Canon SC",
                "IM Fell Great Primer" => "IM Fell Great Primer",
                "IM Fell Great Primer SC" => "IM Fell Great Primer SC",
                "Iceberg" => "Iceberg",
                "Iceland" => "Iceland",
                "Imprima" => "Imprima",
                "Inconsolata" => "Inconsolata",
                "Inder" => "Inder",
                "Indie Flower" => "Indie Flower",
                "Inika" => "Inika",
                "Irish Grover" => "Irish Grover",
                "Istok Web" => "Istok Web",
                "Italiana" => "Italiana",
                "Italianno" => "Italianno",
                "Jacques Francois" => "Jacques Francois",
                "Jacques Francois Shadow" => "Jacques Francois Shadow",
                "Jim Nightshade" => "Jim Nightshade",
                "Jockey One" => "Jockey One",
                "Jolly Lodger" => "Jolly Lodger",
                "Josefin Sans" => "Josefin Sans",
                "Josefin Slab" => "Josefin Slab",
                "Joti One" => "Joti One",
                "Judson" => "Judson",
                "Julee" => "Julee",
                "Julius Sans One" => "Julius Sans One",
                "Junge" => "Junge",
                "Jura" => "Jura",
                "Just Another Hand" => "Just Another Hand",
                "Just Me Again Down Here" => "Just Me Again Down Here",
                "Kameron" => "Kameron",
                "Kantumruy" => "Kantumruy",
                "Karla" => "Karla",
                "Kaushan Script" => "Kaushan Script",
                "Kavoon" => "Kavoon",
                "Kdam Thmor" => "Kdam Thmor",
                "Keania One" => "Keania One",
                "Kelly Slab" => "Kelly Slab",
                "Kenia" => "Kenia",
                "Khmer" => "Khmer",
                "Kite One" => "Kite One",
                "Knewave" => "Knewave",
                "Kotta One" => "Kotta One",
                "Koulen" => "Koulen",
                "Kranky" => "Kranky",
                "Kreon" => "Kreon",
                "Kristi" => "Kristi",
                "Krona One" => "Krona One",
                "La Belle Aurore" => "La Belle Aurore",
                "Lancelot" => "Lancelot",
                "Lato" => "Lato",
                "League Script" => "League Script",
                "Leckerli One" => "Leckerli One",
                "Ledger" => "Ledger",
                "Lekton" => "Lekton",
                "Lemon" => "Lemon",
                "Libre Baskerville" => "Libre Baskerville",
                "Life Savers" => "Life Savers",
                "Lilita One" => "Lilita One",
                "Lily Script One" => "Lily Script One",
                "Limelight" => "Limelight",
                "Linden Hill" => "Linden Hill",
                "Lobster" => "Lobster",
                "Lobster Two" => "Lobster Two",
                "Londrina Outline" => "Londrina Outline",
                "Londrina Shadow" => "Londrina Shadow",
                "Londrina Sketch" => "Londrina Sketch",
                "Londrina Solid" => "Londrina Solid",
                "Lora" => "Lora",
                "Love Ya Like A Sister" => "Love Ya Like A Sister",
                "Loved by the King" => "Loved by the King",
                "Lovers Quarrel" => "Lovers Quarrel",
                "Luckiest Guy" => "Luckiest Guy",
                "Lusitana" => "Lusitana",
                "Lustria" => "Lustria",
                "Macondo" => "Macondo",
                "Macondo Swash Caps" => "Macondo Swash Caps",
                "Magra" => "Magra",
                "Maiden Orange" => "Maiden Orange",
                "Mako" => "Mako",
                "Marcellus" => "Marcellus",
                "Marcellus SC" => "Marcellus SC",
                "Marck Script" => "Marck Script",
                "Margarine" => "Margarine",
                "Marko One" => "Marko One",
                "Marmelad" => "Marmelad",
                "Marvel" => "Marvel",
                "Mate" => "Mate",
                "Mate SC" => "Mate SC",
                "Maven Pro" => "Maven Pro",
                "McLaren" => "McLaren",
                "Meddon" => "Meddon",
                "MedievalSharp" => "MedievalSharp",
                "Medula One" => "Medula One",
                "Megrim" => "Megrim",
                "Meie Script" => "Meie Script",
                "Merienda" => "Merienda",
                "Merienda One" => "Merienda One",
                "Merriweather" => "Merriweather",
                "Merriweather Sans" => "Merriweather Sans",
                "Metal" => "Metal",
                "Metal Mania" => "Metal Mania",
                "Metamorphous" => "Metamorphous",
                "Metrophobic" => "Metrophobic",
                "Michroma" => "Michroma",
                "Milonga" => "Milonga",
                "Miltonian" => "Miltonian",
                "Miltonian Tattoo" => "Miltonian Tattoo",
                "Miniver" => "Miniver",
                "Miss Fajardose" => "Miss Fajardose",
                "Modern Antiqua" => "Modern Antiqua",
                "Molengo" => "Molengo",
                "Molle" => "Molle",
                "Monda" => "Monda",
                "Monofett" => "Monofett",
                "Monoton" => "Monoton",
                "Monsieur La Doulaise" => "Monsieur La Doulaise",
                "Montaga" => "Montaga",
                "Montez" => "Montez",
                "Montserrat" => "Montserrat",
                "Montserrat Alternates" => "Montserrat Alternates",
                "Montserrat Subrayada" => "Montserrat Subrayada",
                "Moul" => "Moul",
                "Moulpali" => "Moulpali",
                "Mountains of Christmas" => "Mountains of Christmas",
                "Mouse Memoirs" => "Mouse Memoirs",
                "Mr Bedfort" => "Mr Bedfort",
                "Mr Dafoe" => "Mr Dafoe",
                "Mr De Haviland" => "Mr De Haviland",
                "Mrs Saint Delafield" => "Mrs Saint Delafield",
                "Mrs Sheppards" => "Mrs Sheppards",
                "Muli" => "Muli",
                "Mystery Quest" => "Mystery Quest",
                "Neucha" => "Neucha",
                "Neuton" => "Neuton",
                "New Rocker" => "New Rocker",
                "News Cycle" => "News Cycle",
                "Niconne" => "Niconne",
                "Nixie One" => "Nixie One",
                "Nobile" => "Nobile",
                "Nokora" => "Nokora",
                "Norican" => "Norican",
                "Nosifer" => "Nosifer",
                "Nothing You Could Do" => "Nothing You Could Do",
                "Noticia Text" => "Noticia Text",
                "Noto Sans" => "Noto Sans",
                "Noto Serif" => "Noto Serif",
                "Nova Cut" => "Nova Cut",
                "Nova Flat" => "Nova Flat",
                "Nova Mono" => "Nova Mono",
                "Nova Oval" => "Nova Oval",
                "Nova Round" => "Nova Round",
                "Nova Script" => "Nova Script",
                "Nova Slim" => "Nova Slim",
                "Nova Square" => "Nova Square",
                "Numans" => "Numans",
                "Nunito" => "Nunito",
                "Odor Mean Chey" => "Odor Mean Chey",
                "Offside" => "Offside",
                "Old Standard TT" => "Old Standard TT",
                "Oldenburg" => "Oldenburg",
                "Oleo Script" => "Oleo Script",
                "Oleo Script Swash Caps" => "Oleo Script Swash Caps",
                "Open Sans" => "Open Sans",
                "Open Sans Condensed" => "Open Sans Condensed",
                "Oranienbaum" => "Oranienbaum",
                "Orbitron" => "Orbitron",
                "Oregano" => "Oregano",
                "Orienta" => "Orienta",
                "Original Surfer" => "Original Surfer",
                "Oswald" => "Oswald",
                "Over the Rainbow" => "Over the Rainbow",
                "Overlock" => "Overlock",
                "Overlock SC" => "Overlock SC",
                "Ovo" => "Ovo",
                "Oxygen" => "Oxygen",
                "Oxygen Mono" => "Oxygen Mono",
                "PT Mono" => "PT Mono",
                "PT Sans" => "PT Sans",
                "PT Sans Caption" => "PT Sans Caption",
                "PT Sans Narrow" => "PT Sans Narrow",
                "PT Serif" => "PT Serif",
                "PT Serif Caption" => "PT Serif Caption",
                "Pacifico" => "Pacifico",
                "Paprika" => "Paprika",
                "Parisienne" => "Parisienne",
                "Passero One" => "Passero One",
                "Passion One" => "Passion One",
                "Pathway Gothic One" => "Pathway Gothic One",
                "Patrick Hand" => "Patrick Hand",
                "Patrick Hand SC" => "Patrick Hand SC",
                "Patua One" => "Patua One",
                "Paytone One" => "Paytone One",
                "Peralta" => "Peralta",
                "Permanent Marker" => "Permanent Marker",
                "Petit Formal Script" => "Petit Formal Script",
                "Petrona" => "Petrona",
                "Philosopher" => "Philosopher",
                "Piedra" => "Piedra",
                "Pinyon Script" => "Pinyon Script",
                "Pirata One" => "Pirata One",
                "Plaster" => "Plaster",
                "Play" => "Play",
                "Playball" => "Playball",
                "Playfair Display" => "Playfair Display",
                "Playfair Display SC" => "Playfair Display SC",
                "Podkova" => "Podkova",
                "Poiret One" => "Poiret One",
                "Poller One" => "Poller One",
                "Poly" => "Poly",
                "Pompiere" => "Pompiere",
                "Pontano Sans" => "Pontano Sans",
                "Port Lligat Sans" => "Port Lligat Sans",
                "Port Lligat Slab" => "Port Lligat Slab",
                "Prata" => "Prata",
                "Preahvihear" => "Preahvihear",
                "Press Start 2P" => "Press Start 2P",
                "Princess Sofia" => "Princess Sofia",
                "Prociono" => "Prociono",
                "Prosto One" => "Prosto One",
                "Puritan" => "Puritan",
                "Purple Purse" => "Purple Purse",
                "Quando" => "Quando",
                "Quantico" => "Quantico",
                "Quattrocento" => "Quattrocento",
                "Quattrocento Sans" => "Quattrocento Sans",
                "Questrial" => "Questrial",
                "Quicksand" => "Quicksand",
                "Quintessential" => "Quintessential",
                "Qwigley" => "Qwigley",
                "Racing Sans One" => "Racing Sans One",
                "Radley" => "Radley",
                "Raleway" => "Raleway",
                "Raleway Dots" => "Raleway Dots",
                "Rambla" => "Rambla",
                "Rammetto One" => "Rammetto One",
                "Ranchers" => "Ranchers",
                "Rancho" => "Rancho",
                "Rationale" => "Rationale",
                "Redressed" => "Redressed",
                "Reenie Beanie" => "Reenie Beanie",
                "Revalia" => "Revalia",
                "Ribeye" => "Ribeye",
                "Ribeye Marrow" => "Ribeye Marrow",
                "Righteous" => "Righteous",
                "Risque" => "Risque",
                "Roboto" => "Roboto",
                "Roboto Condensed" => "Roboto Condensed",
                "Roboto Slab" => "Roboto Slab",
                "Rochester" => "Rochester",
                "Rock Salt" => "Rock Salt",
                "Rokkitt" => "Rokkitt",
                "Romanesco" => "Romanesco",
                "Ropa Sans" => "Ropa Sans",
                "Rosario" => "Rosario",
                "Rosarivo" => "Rosarivo",
                "Rouge Script" => "Rouge Script",
                "Ruda" => "Ruda",
                "Rufina" => "Rufina",
                "Ruge Boogie" => "Ruge Boogie",
                "Ruluko" => "Ruluko",
                "Rum Raisin" => "Rum Raisin",
                "Ruslan Display" => "Ruslan Display",
                "Russo One" => "Russo One",
                "Ruthie" => "Ruthie",
                "Rye" => "Rye",
                "Sacramento" => "Sacramento",
                "Sail" => "Sail",
                "Salsa" => "Salsa",
                "Sanchez" => "Sanchez",
                "Sancreek" => "Sancreek",
                "Sansita One" => "Sansita One",
                "Sarina" => "Sarina",
                "Satisfy" => "Satisfy",
                "Scada" => "Scada",
                "Schoolbell" => "Schoolbell",
                "Seaweed Script" => "Seaweed Script",
                "Sevillana" => "Sevillana",
                "Seymour One" => "Seymour One",
                "Shadows Into Light" => "Shadows Into Light",
                "Shadows Into Light Two" => "Shadows Into Light Two",
                "Shanti" => "Shanti",
                "Share" => "Share",
                "Share Tech" => "Share Tech",
                "Share Tech Mono" => "Share Tech Mono",
                "Shojumaru" => "Shojumaru",
                "Short Stack" => "Short Stack",
                "Siemreap" => "Siemreap",
                "Sigmar One" => "Sigmar One",
                "Signika" => "Signika",
                "Signika Negative" => "Signika Negative",
                "Simonetta" => "Simonetta",
                "Sintony" => "Sintony",
                "Sirin Stencil" => "Sirin Stencil",
                "Six Caps" => "Six Caps",
                "Skranji" => "Skranji",
                "Slackey" => "Slackey",
                "Smokum" => "Smokum",
                "Smythe" => "Smythe",
                "Sniglet" => "Sniglet",
                "Snippet" => "Snippet",
                "Snowburst One" => "Snowburst One",
                "Sofadi One" => "Sofadi One",
                "Sofia" => "Sofia",
                "Sonsie One" => "Sonsie One",
                "Sorts Mill Goudy" => "Sorts Mill Goudy",
                "Source Code Pro" => "Source Code Pro",
                "Source Sans Pro" => "Source Sans Pro",
                "Special Elite" => "Special Elite",
                "Spicy Rice" => "Spicy Rice",
                "Spinnaker" => "Spinnaker",
                "Spirax" => "Spirax",
                "Squada One" => "Squada One",
                "Stalemate" => "Stalemate",
                "Stalinist One" => "Stalinist One",
                "Stardos Stencil" => "Stardos Stencil",
                "Stint Legacy Condensed" => "Stint Legacy Condensed",
                "Stint Legacy Expanded" => "Stint Legacy Expanded",
                "Stoke" => "Stoke",
                "Strait" => "Strait",
                "Sue Ellen Francisco" => "Sue Ellen Francisco",
                "Sunshiney" => "Sunshiney",
                "Supermercado One" => "Supermercado One",
                "Suwannaphum" => "Suwannaphum",
                "Swanky and Moo Moo" => "Swanky and Moo Moo",
                "Syncopate" => "Syncopate",
                "Tangerine" => "Tangerine",
                "Taprom" => "Taprom",
                "Tauri" => "Tauri",
                "Telex" => "Telex",
                "Tenor Sans" => "Tenor Sans",
                "Text Me One" => "Text Me One",
                "The Girl Next Door" => "The Girl Next Door",
                "Tienne" => "Tienne",
                "Tinos" => "Tinos",
                "Titan One" => "Titan One",
                "Titillium Web" => "Titillium Web",
                "Trade Winds" => "Trade Winds",
                "Trocchi" => "Trocchi",
                "Trochut" => "Trochut",
                "Trykker" => "Trykker",
                "Tulpen One" => "Tulpen One",
                "Ubuntu" => "Ubuntu",
                "Ubuntu Condensed" => "Ubuntu Condensed",
                "Ubuntu Mono" => "Ubuntu Mono",
                "Legacy" => "Legacy",
                "Uncial Antiqua" => "Uncial Antiqua",
                "Underdog" => "Underdog",
                "Unica One" => "Unica One",
                "UnifrakturCook" => "UnifrakturCook",
                "UnifrakturMaguntia" => "UnifrakturMaguntia",
                "Unkempt" => "Unkempt",
                "Unlock" => "Unlock",
                "Unna" => "Unna",
                "VT323" => "VT323",
                "Vampiro One" => "Vampiro One",
                "Varela" => "Varela",
                "Varela Round" => "Varela Round",
                "Vast Shadow" => "Vast Shadow",
                "Vibur" => "Vibur",
                "Vidaloka" => "Vidaloka",
                "Viga" => "Viga",
                "Voces" => "Voces",
                "Volkhov" => "Volkhov",
                "Vollkorn" => "Vollkorn",
                "Voltaire" => "Voltaire",
                "Waiting for the Sunrise" => "Waiting for the Sunrise",
                "Wallpoet" => "Wallpoet",
                "Walter Turncoat" => "Walter Turncoat",
                "Warnes" => "Warnes",
                "Wellfleet" => "Wellfleet",
                "Wendy One" => "Wendy One",
                "Wire One" => "Wire One",
                "Yanone Kaffeesatz" => "Yanone Kaffeesatz",
                "Yellowtail" => "Yellowtail",
                "Yeseva One" => "Yeseva One",
                "Yesteryear" => "Yesteryear",
                "Zeyada" => "Zeyada",
            );


            $more_settings_msg = 'You have chosen an inbuilt theme. One or more setting options in this section are applicable for custom theme only.';


                global $legacy_color_list;

                foreach($legacy_color_list as $colorid => $colorname){
                    $legacy_color_thumbs[$colorid]['title'] = esc_attr($colorname, 'legacy_framework');
                    $legacy_color_thumbs[$colorid]['img'] = ReduxFramework::$_url . '../../images/assets/'.$colorid.'.jpg';
                }

                //plugins_url('/', __FILE__)."../../images/logo.png"
                $modbaseurl = plugins_url('/', __FILE__);
                $modbaseurl = str_replace("framework/options/","",$modbaseurl);


    Redux::setSection( $opt_name, array(
        'id'         => 'pick-theme',
                'title' => __('Pick Theme', 'legacy_framework'),
                'icon' => 'el-icon-css',
                'fields' => array(
                    array(
                        'id' => 'dynamic-css-type',
                        'type' => 'image_select',
                        'title' => __('Choose Theme', 'legacy_framework'),
                        'desc' => __('Select the default themes inbuilt with the plugin or choose custom theme options defined below.', 'legacy_framework'),
                        'options' => $legacy_color_thumbs,
                        'default' => 'custom'
                    ),



                    array(
                        'id' => 'primary-color',
                        'type' => 'color',
                        'title' => __('Primary Color of theme', 'legacy_framework'),
                        'subtitle' => __('Pick primary color for theme.', 'legacy_framework'),
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'default' => '#f45246',
                        'validate' => 'color',
                    ),
                ),
    ) );

    Redux::setSection( $opt_name, array(
                'title' => __('Layout', 'legacy_framework'),
                'icon' => 'el-icon-css',
                'fields' => array(
                    array(
                        'id' => 'page-bg',
                        'type' => 'background',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Page Background', 'legacy_framework'),
                        'subtitle' => __('Background image, color and settings etc. for admin page', 'legacy_framework'),
                        'default' => array(
                            'background-color' => '#f0f1f2',
                        )
                    ),
                    array(
                        'id' => 'heading-color',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Heading Color', 'legacy_framework'),
                        'subtitle' => __('Pick color for headings and titles in the theme.', 'legacy_framework'),
                        'default' => '#7d8a9d',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'body-text-color',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Body Text Color', 'legacy_framework'),
                        'subtitle' => __('Pick color for page text.', 'legacy_framework'),
                        'default' => '#7d8a9d',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'link-color',
                        'type' => 'link_color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Anchor/URL Links Color', 'legacy_framework'),
                        'subtitle' => __('Pick colors for different states of URL links', 'legacy_framework'),
                        'active' => false, // Disable Active Color
                        'default' => array(
                            'regular' => '#2a3e4c',
                            'hover' => '#f45246',
                        )
                    ),
                    array(
                        'id' => 'opt1-info-field',
                        'type' => 'info',
                        'desc' => __($more_settings_msg, 'legacy_framework'),
                        'required' => array( 'dynamic-css-type', 'not', 'custom' ),
                    ),

                ),
    ) );

    Redux::setSection( $opt_name, array(
                'title' => __('Typography Fonts', 'legacy_framework'),
                'icon' => 'el-icon-font',
                'fields' => array(
                    array(
                        'id' => 'google_body',
                        'type' => 'typography',
                        'title' => __('Body Font', 'legacy_framework'),
                        'subtitle' => __('Specify the body font properties.', 'legacy_framework'),
                        'google' => true,
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'font-size' => true,
                        'line-height' => true,
                        'text-align' => false,
                        'word-spacing' => false, // Defaults to false
                        'letter-spacing' => false, // Defaults to false
                        'color' => false,
                        'default' => array(
                            'font-family' => 'Open Sans',
                            'font-weight' => '400',
                            'font-backup' => "Arial, Helvetica, sans-serif",
                            'font-size' => '14px',
                            'line-height' => '23px',
                        ),
                    ),
                    array(
                        'id' => 'google_nav',
                        'type' => 'typography',
                        'title' => __('Main Menu Font', 'legacy_framework'),
                        'subtitle' => __('Specify the main menu font properties.', 'legacy_framework'),
                        'google' => true,
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'font-size' => true,
                        'line-height' => true,
                        'text-align' => false,
                        'word-spacing' => true, // Defaults to false
                        'letter-spacing' => true, // Defaults to false
                        'color' => false,
                        'default' => array(
                            'font-family' => 'Roboto Condensed',
                            'font-weight' => '300',
                            'font-backup' => "Arial, Helvetica, sans-serif",
                            'font-size' => '17px',
                            'line-height' => '46px',
                        ),
                    ),
                    array(
                        'id' => 'google_headings',
                        'type' => 'typography',
                        'title' => __('Headings Font', 'legacy_framework'),
                        'subtitle' => __('Specify the heading font properties.', 'legacy_framework'),
                        'google' => true,
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'font-size' => false,
                        'line-height' => false,
                        'text-align' => false,
                        'word-spacing' => true, // Defaults to false
                        'letter-spacing' => true, // Defaults to false
                        'color' => false,
                        'default' => array(
                            'font-family' => 'Roboto Condensed',
                            'font-weight' => '300',
                            'font-backup' => "Arial, Helvetica, sans-serif"
                        ),
                    ),
                    array(
                        'id' => 'google_button',
                        'type' => 'typography',
                        'title' => __('Button Font', 'legacy_framework'),
                        'subtitle' => __('Specify the button font properties.', 'legacy_framework'),
                        'google' => true,
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'font-size' => true,
                        'line-height' => true,
                        'text-align' => false,
                        'word-spacing' => true, // Defaults to false
                        'letter-spacing' => true, // Defaults to false
                        'color' => false,
                        'default' => array(
                            'font-family' => 'Roboto Condensed',
                            'font-weight' => '300',
                            'font-backup' => "Arial, Helvetica, sans-serif",
                            'font-size' => '15px',
                            'line-height' => '23px',
                        ),
                    ),

                    array(
                        'id' => 'google_pagehead',
                        'type' => 'typography',
                        'title' => __('Page Heading Font', 'legacy_framework'),
                        'subtitle' => __('Specify the page heading font properties.', 'legacy_framework'),
                        'google' => true,
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'font-size' => true,
                        'line-height' => true,
                        'text-align' => false,
                        'word-spacing' => true, // Defaults to false
                        'letter-spacing' => true, // Defaults to false
                        'color' => false,
                        'default' => array(
                            'font-family' => 'Roboto Condensed',
                            'font-weight' => '400',
                            'font-backup' => "Arial, Helvetica, sans-serif",
                            'font-size' => '36px',
                            'line-height' => '55px',
                        ),
                    ),



                    array(
                            'id'       => 'submenu-line-height',
                            'type'     => 'text',
                            'title'    => __( 'Submenu Line Height', 'legacy_framework' ),
                            'subtitle' => __( 'Line height of submenu items in admin menu. Font size will be same as menu item filled above.', 'legacy_framework' ),
                            'default'  => '40px',
                    ),



                ),
    ) );

    Redux::setSection( $opt_name, array(
                'title' => __('Admin Menu', 'legacy_framework'),
                'icon' => 'el-icon-css',
                'fields' => array(

                    /*array(
                        'id' => 'enable-allusers-legacyadmin',
                        'type' => 'switch',
                        'title' => __('Enable Legacy Admin Panel for users', 'legacy_framework'),
                        'desc' => __('Check ON to show Legacy Admin Settings Panel to all Users, OFF to show only to Admin User.', 'legacy_framework'),
                        'default' => '0'// 1 = on | 0 = off
                    ),*/


                    array(
                        'id' => 'menu-bg',
                        'type' => 'background',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Menu Background', 'legacy_framework'),
                        'subtitle' => __('Background image, color and settings etc. for Admin Menu', 'legacy_framework'),
                        'default' => array(
                            'background-color' => '#2a3e4c',
                        )
                    ),
                    array(
                        'id' => 'menu-color',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Menu Text Color', 'legacy_framework'),
                        'subtitle' => __('Pick link/text color for admin menu.', 'legacy_framework'),
                        'default' => '#ffffff',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'menu-hover-color',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Menu Hover Text Color', 'legacy_framework'),
                        'subtitle' => __('Pick link/text color for admin menu on hover.', 'legacy_framework'),
                        'default' => '#ffffff',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'submenu-color',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('SubMenu Text Color', 'legacy_framework'),
                        'subtitle' => __('Pick link/text color for admin submenu.', 'legacy_framework'),
                        'default' => '#aeb2b7',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'menu-primary-bg',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Menu Primary Background Color', 'legacy_framework'),
                        'subtitle' => __('Pick background color for admin menu. It is the hover or default background color of Menu link', 'legacy_framework'),
                        'default' => '#f45246',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'menu-secondary-bg',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Menu Secondary Background Color', 'legacy_framework'),
                        'subtitle' => __('Pick background color for admin submenu. It is the hover or default background color of Submenu link', 'legacy_framework'),
                        'default' => '#141c22',
                        'validate' => 'color',
                    ),

                    array(
                        'id' => 'menu-icon-line-bg',
                        'type' => 'background',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Menu Icon Connection Line Background', 'legacy_framework'),
                        'subtitle' => __('Color of icons connecting Line in admin menu', 'legacy_framework'),
                        'default' => array(
                            'background-color' => '#aeb2b7',
                        )
                    ),
                    array(
                        'id' => 'menu-icon-color',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Menu Icon Color', 'legacy_framework'),
                        'subtitle' => __('Pick icon color for admin menu.', 'legacy_framework'),
                        'default' => '#aeb2b7',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'menu-icon-bg',
                        'type' => 'background',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Menu Icon Background', 'legacy_framework'),
                        'subtitle' => __('Background color Admin Menu icons', 'legacy_framework'),
                        'default' => array(
                            'background-color' => '#2a3e4c',
                        )
                    ),

                    array(
                        'id' => 'menu-active-icon-color',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Active Menu Icon Color', 'legacy_framework'),
                        'subtitle' => __('Pick icon color for active state or hover state of admin menu item.', 'legacy_framework'),
                        'default' => '#f45246',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'menu-active-icon-bg',
                        'type' => 'background',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Active Menu Icon Background', 'legacy_framework'),
                        'subtitle' => __('Icon background color for active state or hover state of admin menu item', 'legacy_framework'),
                        'default' => array(
                            'background-color' => '#f0f1f2',
                        )
                    ),
                    array(
                        'id' => 'submenu-active-icon-bg',
                        'type' => 'background',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Active SubMenu Icon Background', 'legacy_framework'),
                        'subtitle' => __('Icon background color for active state or hover state of admin submenu item', 'legacy_framework'),
                        'default' => array(
                            'background-color' => '#f45246',
                        )
                    ),


                    array(
                        'id' => 'menu-transform-text',
                        'type' => 'select',
                        'title' => __('Menu Text transform', 'legacy_framework'),
                        'subtitle' => __('Pick to convert menu item to uppercase, lowercase, capitalize each word or use original.', 'legacy_framework'),
                        "default" => "uppercase",
                        "options" => array('uppercase' => 'Uppercase','lowercase' =>'Lowercase','capitalize' => 'Capitalize', 'none' => 'Use Original')
                    ),
                    array(
                        'id' => 'opt2-info-field',
                        'type' => 'info',
                        'desc' => __($more_settings_msg, 'legacy_framework'),
                        'required' => array( 'dynamic-css-type', 'not', 'custom' ),
                    ),

                ),
    ) );

    Redux::setSection( $opt_name, array(
                'title' => __('Logo Settings', 'legacy_framework'),
                'icon' => 'el-icon-star',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields' => array(
                    array(
                        'id' => 'enable-logo',
                        'type' => 'switch',
                        'title' => __('Show Logo', 'legacy_framework'),
                        'desc' => __('Check ON to show logo in menu and favicon, OFF to disable.', 'legacy_framework'),
                        'default' => '1'// 1 = on | 0 = off
                    ),

                    array(
                            'id'       => 'logo-url',
                            'type'     => 'text',
                            'title'    => __( 'Logo URL', 'legacy_framework' ),
                            'subtitle' => __( 'On clicking logo, user will be redirected to this mentioned url.', 'legacy_framework' ),
                            'validate' => 'url',
                            'default'  => '',
                            //                        'text_hint' => array(
                            //                            'title'     => '',
                            //                            'content'   => 'Please enter a valid <strong>URL</strong> in this field.'
                            //                        )
                    ),

                    array(
                        'id' => 'logo-bg',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Logo Background', 'legacy_framework'),
                        'subtitle' => __('Background image, color and settings etc. for logo in Admin Menu', 'legacy_framework'),
                        'default' => '#2a3e4c',
                        'validate' => 'color',
                    ),

                    array(
                        'id' => 'logo',
                        'type' => 'media',
                        'url' => true,
                        'readonly' => false,
                        //'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Logo', 'legacy_framework'),
                        'subtitle' => __('Upload your own logo of 230px (width) * 69px (height)', 'legacy_framework'),
                        'default' => array('url' => $modbaseurl."images/logo.png")
                    ),
                    array(
                        'id' => 'logo_folded',
                        'type' => 'media',
                        'url' => true,
                        'readonly' => false,
                        //'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Collapsed Menu Logo', 'legacy_framework'),
                        'subtitle' => __('Upload your own logo of 46px (width) * 69px (height)', 'legacy_framework'),
                        'default' => array('url' => $modbaseurl."images/logo-folded.png")
                    ),


                    array(
                        'id' => 'login-logo',
                        'type' => 'media',
                        'url' => true,
                        'readonly' => false,
                        'title' => __('Login Page Logo', 'legacy_framework'),
                        'subtitle' => __('Upload your login page logo.', 'legacy_framework'),
                        'default' => array('url' => $modbaseurl."images/login-logo.png")
                    ),
                    
                    array(
                        'id' => 'favicon',
                        'type' => 'media',
                        'url' => true,
                        'readonly' => false,
                        'compiler' => 'true',
                        'title' => __('Favicon', 'legacy_framework'),
                        'subtitle' => __('image that will be used as favicon (16px x 16px).', 'legacy_framework'),
                        'default' => array('url' => $modbaseurl."images/favicon.png")
                    ),
                    array(
                        'id' => 'iphone_icon',
                        'type' => 'media',
                        'readonly' => false,
                        'url' => true,
                        'compiler' => 'true',
                        'title' => __('Apple Iphone Icon', 'legacy_framework'),
                        'subtitle' => __('Apple Iphone Icon (57px x 57px).', 'legacy_framework'),
                        'default' => array('url' => $modbaseurl."images/apple-touch-icon-57-precomposed.png")
                    ),
                    array(
                        'id' => 'iphone_icon_retina',
                        'type' => 'media',
                        'url' => true,
                        'readonly' => false,
                        'compiler' => 'true',
                        'title' => __('Apple Iphone Retina Icon', 'legacy_framework'),
                        'subtitle' => __('Apple Iphone Retina Icon (114px x 114px).', 'legacy_framework'),
                        'default' => array('url' => $modbaseurl."images/apple-touch-icon-114-precomposed.png")
                    ),
                    array(
                        'id' => 'ipad_icon',
                        'type' => 'media',
                        'url' => true,
                        'readonly' => false,
                        'compiler' => 'true',
                        'title' => __('Apple iPad Icon', 'legacy_framework'),
                        'subtitle' => __('Apple Iphone Retina Icon (72px x 72px).', 'legacy_framework'),
                        'default' => array('url' => $modbaseurl."images/apple-touch-icon-72-precomposed.png")
                    ),
                    array(
                        'id' => 'ipad_icon_retina',
                        'type' => 'media',
                        'url' => true,
                        'readonly' => false,
                        'compiler' => 'true',
                        'title' => __('Apple iPad Retina Icon', 'legacy_framework'),
                        'subtitle' => __('Apple iPad Retina Icon (144px x 144px).', 'legacy_framework'),
                        'default' => array('url' => $modbaseurl."images/apple-touch-icon-144-precomposed.png")
                    ),
                ),
    ) );

    Redux::setSection( $opt_name, array(
                'title' => __('Content Box', 'legacy_framework'),
                'icon' => 'el-icon-css',
                'fields' => array(

                    array(
                        'id' => 'box-bg',
                        'type' => 'background',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Content Box Background', 'legacy_framework'),
                        'subtitle' => __('Background image, color and settings etc. for Content Boxes. Eg: Welcome box on dashboard', 'legacy_framework'),
                        'default' => array(
                            'background-color' => '#ffffff',
                        )
                    ),
                    array(
                        'id' => 'box-head-bg',
                        'type' => 'background',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Content Box Header Background', 'legacy_framework'),
                        'subtitle' => __('Background image, color and settings etc. for Content Box Header area. Eg: Welcome box on dashboard', 'legacy_framework'),
                        'default' => array(
                            'background-color' => '#64ca92',
                        )
                    ),

                    array(
                        'id' => 'box-head-color',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Box Head Text Color', 'legacy_framework'),
                        'subtitle' => __('Pick text color for content box header area.', 'legacy_framework'),
                        'default' => '#ffffff',
                        'validate' => 'color',
                    ),                    
                    array(
                        'id' => 'opt3-info-field',
                        'type' => 'info',
                        'desc' => __($more_settings_msg, 'legacy_framework'),
                        'required' => array( 'dynamic-css-type', 'not', 'custom' ),
                    ),
                ),
    ) );

    Redux::setSection( $opt_name, array(
                'title' => __('Button', 'legacy_framework'),
                'icon' => 'el-icon-css',
                'fields' => array(
                    array(
                        'id' => 'button-primary-bg',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Primary Button Background Color', 'legacy_framework'),
                        'subtitle' => __('Pick background color for primary button', 'legacy_framework'),
                        'default' => '#f45246',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'button-primary-hover-bg',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Primary Button Hover Background Color', 'legacy_framework'),
                        'subtitle' => __('Pick hover background color for primary button', 'legacy_framework'),
                        'default' => '#18b3dc',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'button-secondary-bg',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Secondary Button Background Color', 'legacy_framework'),
                        'subtitle' => __('Pick background color for secondary button', 'legacy_framework'),
                        'default' => '#a9a9a9',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'button-secondary-hover-bg',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Secondary Button Hover Background Color', 'legacy_framework'),
                        'subtitle' => __('Pick hover background color for secondary button', 'legacy_framework'),
                        'default' => '#999999',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'button-text-color',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Button Text Color', 'legacy_framework'),
                        'subtitle' => __('Pick text color for a button.', 'legacy_framework'),
                        'default' => '#ffffff',
                        'validate' => 'color',
                    ),                    
                    array(
                        'id' => 'opt4-info-field',
                        'type' => 'info',
                        'desc' => __($more_settings_msg, 'legacy_framework'),
                        'required' => array( 'dynamic-css-type', 'not', 'custom' ),
                    ),

                ),
    ) );

    Redux::setSection( $opt_name, array(
                'title' => __('Form', 'legacy_framework'),
                'icon' => 'el-icon-css',
                'fields' => array(

                    array(
                        'id' => 'form-bg',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'type' => 'color',
                        'title' => __('Form Elements Background Color', 'legacy_framework'),
                        'subtitle' => __('Pick background color for form elements', 'legacy_framework'),
                        'default' => '#ffffff',
                        'validate' => 'color',
                    ),

                    array(
                        'id' => 'form-text-color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'type' => 'color',
                        'title' => __('Form Elements Text Color', 'legacy_framework'),
                        'subtitle' => __('Pick text color for form elements', 'legacy_framework'),
                        'default' => '#7d8a9d',
                        'validate' => 'color',
                    ),

                    array(
                        'id' => 'form-border-color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'type' => 'color',
                        'title' => __('Form Elements Border Color', 'legacy_framework'),
                        'subtitle' => __('Pick border color for form elements.', 'legacy_framework'),
                        'default' => '#e2e2e4',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'opt5-info-field',
                        'type' => 'info',
                        'desc' => __($more_settings_msg, 'legacy_framework'),
                        'required' => array( 'dynamic-css-type', 'not', 'custom' ),
                    ),

                ),
    ) );

    Redux::setSection( $opt_name, array(
                'title' => __('Login Page', 'legacy_framework'),
                'icon' => 'el-icon-css',
                'fields' => array(

                    /*array(
                        'id' => 'enable-login',
                        'type' => 'switch',
                        'title' => __('Enable Custom Login Screen', 'legacy_framework'),
                        'desc' => __('Check ON to show custom login screen. OFF for default WordPress Login Page.', 'legacy_framework'),
                        'default' => '1'// 1 = on | 0 = off
                    ),*/


                    array(
                        'id' => 'login-background',
                        'tiles' => true,
                        //'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'type' => 'background',
                        'title' => __('Login Page Background', 'legacy_framework'),
                        'subtitle' => __('Background image, color and settings etc. for login page (Eg: http://www.yourdomain.com/wp-login.php)', 'legacy_framework'),
                        'preview' => false,
                        'preview_media' => true,
                        'default' => array(
                            'background-color' => '#a1a4a6',
                            'background-position' => 'center center',
                            'background-image' => $modbaseurl."images/login-bg.png",
                        )
                    ),
                    
            array(
                'id'       => 'login-form-background',
                'type'     => 'color_gradient',
                'title'    => __( 'Header Gradient Color Option', 'legacy_framework' ),
                'subtitle' => __( 'Only color validation can be done on this field type', 'legacy_framework' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'legacy_framework' ),
                'default'  => array(
                    'from' => '#181845',
                    'to'   => '#000000'
                )
            ),

                    /*
                    array(
                        'id' => 'login-form-background',
                        'tiles' => true,
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'type' => 'background',
                        'title' => __('Login Form Box Background', 'legacy_framework'),
                        'subtitle' => __('Background image, color and settings etc. for login form box', 'legacy_framework'),
                        'preview' => false,
                        'preview_media' => true,
                        'default' => array(
                            'background-color' => 'transparent',
                        )
                    ),
                    */
                    array(
                        'id' => 'login-text-color',
                        'type' => 'color',
                        //'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Login Page Text color', 'legacy_framework'),
                        'subtitle' => __('Pick color for login page text.', 'legacy_framework'),
                        'validate' => 'color',
                        'default' => '#9a9a9a',
                    ),

                    array(
                        'id' => 'login-link-color',
                        'type' => 'link_color',
                       // 'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Login Page Anchor/URL Links Color', 'legacy_framework'),
                        'subtitle' => __('Pick colors for different states of login page links', 'legacy_framework'),
                        //'regular'   => false, // Disable Regular Color
                        //'hover'     => false, // Disable Hover Color
                        'active' => false, // Disable Active Color
                        //'visited'   => true,  // Enable Visited Color
                        'default' => array(
                            'regular' => '#9a9a9a',
                            'hover' => '#aaaaaa',
                        )
                    ),

                    array(
                        'id' => 'login-input-text-color',
                        'type' => 'color',
                       // 'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Login Input Fields Text color', 'legacy_framework'),
                        'subtitle' => __('Pick text color for login page input fields.', 'legacy_framework'),
                        'validate' => 'color',
                        'default' => '#f5f5f5',
                    ),

                    array(
                        'id' => 'login-input-bg-color',
                        'type' => 'color',
                       // 'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Login Input Fields background color', 'legacy_framework'),
                        'subtitle' => __('Pick background color for login page input fields.', 'legacy_framework'),
                        'validate' => 'color',
                        'default' => 'transparent',
                    ),
                    array(
                        'id' => 'login-input-bg-opacity',
                        'type' => 'text',
                        'title' => __('Login Input Fields background Opacity', 'legacy_framework'),
                        'subtitle' => __('Define opacity in range from 0.1 to 1.0. Eg: 0.8', 'legacy_framework'),
                        'default' => '0.5',
                    ),
                    array(
                        'id' => 'login-input-bg-hover-opacity',
                        'type' => 'text',
                        'title' => __('Login Input Fields Focus/Hover background Opacity', 'legacy_framework'),
                        'subtitle' => __('Define opacity in range from 0.1 to 1.0. Eg: 0.8', 'legacy_framework'),
                        'default' => '0.8',
                    ),

                    array(
                        'id' => 'login-input-border-color',
                        'type' => 'color',
                       // 'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Login Input Fields bottom border color', 'legacy_framework'),
                        'subtitle' => __('Pick bottom border color for login page input fields.', 'legacy_framework'),
                        'validate' => 'color',
                        'default' => '#8b8c91',
                    ),



                    array(
                        'id' => 'login-button-bg',
                        'type' => 'color',
                      //  'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Login Button Background Color', 'legacy_framework'),
                        'subtitle' => __('Pick background color for login button', 'legacy_framework'),
                        'default' => '#f45246',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'login-button-hover-bg',
                        'type' => 'color',
                       // 'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Login Button Hover Background Color', 'legacy_framework'),
                        'subtitle' => __('Pick hover background color for login button', 'legacy_framework'),
                        'default' => '#18b3dc',
                        'validate' => 'color',
                    ),

                    array(
                        'id' => 'login-button-text-color',
                        'type' => 'color',
                       // 'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Login button Text Color', 'legacy_framework'),
                        'subtitle' => __('Pick text color for a login button.', 'legacy_framework'),
                        'default' => '#ffffff',
                        'validate' => 'color',
                    ),                    

                    
                    array("title" => "Back to main site Link",
                        "desc" => "Select to show or hide back to main site link on login page.",
                        "id" => "backtosite_login_link",
                        "default" => '0',
                        "type" => "switch"),

                    array("title" => "Forgot Password Link",
                        "desc" => "Select to show or hide Forgot Password link on login page.",
                        "id" => "forgot_login_link",
                        "default" => '1',
                        "type" => "switch"),

                    array(
                        'id' => 'login-logo-title',
                        'type' => 'text',
                        'title' => __('Login Logo Title', 'legacy_framework'),
                        'subtitle' => __('The title tooltip shown on login logo when mouse is taken over it.', 'legacy_framework'),
                        'default' => 'Legacy - White Lable WordPress Admin Theme',
                    ),


                    /*array(
                        'id' => 'opt6-info-field',
                        'type' => 'info',
                        'desc' => __($more_settings_msg, 'legacy_framework'),
                        'required' => array( 'dynamic-css-type', 'not', 'custom' ),
                    ),*/
                ),
    ) );

    Redux::setSection( $opt_name, array(
                'title' => __('Admin Top Bar', 'legacy_framework'),
                'icon' => 'el-icon-css',
                'fields' => array(

                    array(
                        'id' => 'enable-topbar',
                        'type' => 'switch',
                        'title' => __('Enable Admin Top Bar in admin panel', 'legacy_framework'),
                        'desc' => __('Check ON to show admin panel top bar in the menu, OFF to hide.', 'legacy_framework'),
                        'default' => '1'// 1 = on | 0 = off
                    ),

                    array(
                        'id' => 'enable-topbar-wp',
                        'type' => 'switch',
                        'title' => __('Enable Top Bar on front End (on website)', 'legacy_framework'),
                        'desc' => __('Check ON to show admin panel top bar on the website for logged in users, OFF to hide.', 'legacy_framework'),
                        'default' => '1'// 1 = on | 0 = off
                    ),

                    // array("title" => "Admin Top Bar Style",
                    //     "desc" => "Select the default style to display the top bar menu.",
                    //     "id" => "topbar-style",
                    //     "default" => "style2",
                    //     "type" => "select",
                    //     "options" => array('style1' => '100% Width Style','style2' =>'Besides Main Menu'),
                    // ),

                    array(
                        'id' => 'topbar-menu-color',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Top Bar Menu Text Color', 'legacy_framework'),
                        'subtitle' => __('Pick link/text color for top bar menu.', 'legacy_framework'),
                        'default' => '#ffffff',
                        'validate' => 'color',
                    ),


                    array(
                        'id' => 'topbar-menu-bg',
                        'type' => 'background',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Top Bar Menu Background', 'legacy_framework'),
                        'subtitle' => __('Background image, color and settings etc. for top bar Menu', 'legacy_framework'),
                        'default' => array(
                            'background-color' => '#18b3dc',
                        )
                    ),

                    array(
                        'id' => 'topbar-submenu-color',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Top Bar SubMenu Text Color', 'legacy_framework'),
                        'subtitle' => __('Pick link/text color for top bar submenu.', 'legacy_framework'),
                        'default' => '#aeb2b7',
                        'validate' => 'color',
                    ),
                    array(
                        'id' => 'topbar-submenu-bg',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Top Bar SubMenu Primary Background Color', 'legacy_framework'),
                        'subtitle' => __('Pick background color for top bar menu. It is the hover or default background color of Menu link', 'legacy_framework'),
                        'default' => '#2a3e4c',
                        'validate' => 'color',
                    ),


                    array(
                        'id' => 'topbar-submenu-hover-bg',
                        'type' => 'color',
                        'required' => array('dynamic-css-type', 'equals', 'custom'),
                        'title' => __('Top Bar SubMenu Secondary Background Color', 'legacy_framework'),
                        'subtitle' => __('Pick background color for top bar submenu. It is the hover or default background color of Submenu link', 'legacy_framework'),
                        'default' => '#141c22',
                        'validate' => 'color',
                    ),

                    array(
                        'id' => 'enable-topbar-links-wp',
                        'type' => 'switch',
                        'title' => __('Top Bar WordPress Menu', 'legacy_framework'),
                        'desc' => __('Check ON to show  top bar wordpress menu and submenu links, OFF to hide.', 'legacy_framework'),
                        'default' => '1'// 1 = on | 0 = off
                    ),


                    array(
                        'id' => 'enable-topbar-links-site',
                        'type' => 'switch',
                        'title' => __('Top Bar Site Menu', 'legacy_framework'),
                        'desc' => __('Check ON to show  top bar site menu and submenu links, OFF to hide.', 'legacy_framework'),
                        'default' => '1'// 1 = on | 0 = off
                    ),

                    array(
                        'id' => 'enable-topbar-links-comments',
                        'type' => 'switch',
                        'title' => __('Top Bar Comments link', 'legacy_framework'),
                        'desc' => __('Check ON to show  top bar comments link, OFF to hide.', 'legacy_framework'),
                        'default' => '1'// 1 = on | 0 = off
                    ),

                    array(
                        'id' => 'enable-topbar-links-new',
                        'type' => 'switch',
                        'title' => __('Top Bar Add New Menu', 'legacy_framework'),
                        'desc' => __('Check ON to show  top bar add new menu and submenu links, OFF to hide.', 'legacy_framework'),
                        'default' => '1'// 1 = on | 0 = off
                    ),

                    array(
                        'id' => 'enable-topbar-links-legacyadmin',
                        'type' => 'switch',
                        'title' => __('Top Bar Legacy Admin Menu', 'legacy_framework'),
                        'desc' => __('Check ON to show  top bar Legacy Admin menu and submenu links, OFF to hide.', 'legacy_framework'),
                        'default' => '1'// 1 = on | 0 = off
                    ),

                    array(
                        'id' => 'enable-topbar-myaccount',
                        'type' => 'switch',
                        'title' => __('Top Bar My Account', 'legacy_framework'),
                        'desc' => __('Check ON to show top bar My Account Menu, OFF to hide.', 'legacy_framework'),
                        'default' => '1'// 1 = on | 0 = off
                    ),


                    array(
                        'id' => 'enable-topbar-directlogout',
                        'type' => 'switch',
                        'title' => __('Direct Logout Link', 'legacy_framework'),
                        'desc' => __('Check ON to show logout link in the top bar besides user account menu, OFF to hide.', 'legacy_framework'),
                        'default' => '0'// 1 = on | 0 = off
                    ),

                    array(
                        'id' => 'topbar-removeids',
                        'type' => 'text',
                        'title' => __('Remove Top bar Link Node ids', 'legacy_framework'),
                        'desc' => __('Enter node ids of link top bar to remove. Separate by comma(,) Eg: new-content,comments', 'legacy_framework'),
                        'default' => '',
                    ),

                    array(
                        'id' => 'myaccount_greet',
                        'type' => 'text',
                        'title' => __('Greet User with', 'legacy_framework'),
                        'desc' => __('Enter the greeting word for logged in user. Default is Howdy. Eg: Welcome ', 'legacy_framework'),
                        'default' => 'Howdy',
                    ),



                    array(
                        'id' => 'opt7-info-field',
                        'type' => 'info',
                        'desc' => __($more_settings_msg, 'legacy_framework'),
                        'required' => array( 'dynamic-css-type', 'not', 'custom' ),
                    ),

                ),
    ) );

    Redux::setSection( $opt_name, array(
                'title' => __('Page Loader', 'legacy_framework'),
                'icon' => 'el-icon-css',
                'fields' => array(

                    array(
                        'id' => 'enable-pageloader',
                        'type' => 'switch',
                        'title' => __('Enable Page Loading Top Bar', 'legacy_framework'),
                        'desc' => __('Check ON to enable progress bar on top, OFF to disable.', 'legacy_framework'),
                        'default' => '1'// 1 = on | 0 = off
                    ),


                ),
    ) );


    Redux::setSection( $opt_name, array(
        'title' => __('Email Settings', 'legacy_framework'),
        'icon' => 'el-icon-css',
        'fields' => array(

            array(
                'id' => 'from-mail-email',
                'type' => 'text',
                'title' => __('From Mail Email Address', 'legacy_framework'),
                'desc' => __('Change the from email address used in an email sent using the wp_mail function. (Default is wordpress@domain.com). Change it to your personal brand or domain name. To avoid being marked as spam make sure the email is from the same domain as your website.', 'legacy_framework'),
                'default' => '',
            ),

            array(
                'id' => 'from-mail-name',
                'type' => 'text',
                'title' => __('From Mail Name', 'legacy_framework'),
                'desc' => __('Change the from name used in an email sent using the wp_mail function. (Default is Wordpress). Change it to your personal brand or domain name.', 'legacy_framework'),
                'default' => '',
            ),



        ),
) );


    Redux::setSection( $opt_name, array(
                'title' => __('Extra Settings', 'legacy_framework'),
                'icon' => 'el-icon-css',
                'fields' => array(

                    array("title" => "Footer Text",
                        "desc" => "Enter the text that displays in the footer bar. HTML markup can be used.",
                        "id" => "footer_text",
                        "default" => 'Thank you for creating with <a href="https://wordpress.org/">WordPress</a> and <a target="_blank" href="http://codecanyon.net/user/themepassion/portfolio">Legacy White Label WordPress Admin Theme</a>',
                        "type" => "editor"),

                    array("title" => "Footer Version",
                        "desc" => "Select to show WordPress version info in footer area or not.",
                        "id" => "footer_version",
                        "default" => '1',
                        "type" => "switch"),


                    array("title" => "Screen Option Tab",
                        "desc" => "Select to show screen option tab in top right corner of admin screen",
                        "id" => "screen_option_tab",
                        "default" => '1',
                        "type" => "switch"),

                    array("title" => "Screen Help Tab",
                        "desc" => "Select to show screen help tab in top right corner of admin screen",
                        "id" => "screen_help_tab",
                        "default" => '1',
                        "type" => "switch"),


                        array(
                            'id'       => 'custom-css',
                            'type'     => 'ace_editor',
                            'title'    => __( 'Custom CSS Code', 'legacy_framework' ),
                            'subtitle' => __( 'Paste your CSS code here. This code will be applied in all your admin panel', 'legacy_framework' ),
                            'mode'     => 'css',
                            'theme'    => 'monokai',
                            'desc'     => 'In case if you need to overwrite any CSS setting, you can add !important at the end of CSS property. eg: color: #da2234 !important; 
<br/>
You can use dynamic css variables in custom css code. 
<pre>
Eg:
.css-selector{
    background: var(--tp-primary-color);
    border-color: var(--tp-primary-color);
}
</pre>
Following is the list of defined css variables based on your settings configured.
<pre>
:root {
  --tp-primary-color: #f45246;
  --tp-page-bg-background-color: #f0f1f2;
  --tp-heading-color: #7d8a9d;
  --tp-body-text-color: #7d8a9d;
  --tp-link-color-regular: #2a3e4c;
  --tp-link-color-hover: #f45246;
  --tp-menu-bg-background-color: #2a3e4c;
  --tp-menu-color: #ffffff;
  --tp-menu-hover-color: #ffffff;
  --tp-submenu-color: #aeb2b7;
  --tp-menu-primary-bg: #f45246;
  --tp-menu-secondary-bg: #141c22;
  --tp-box-bg-background-color: #ffffff;
  --tp-box-head-bg-background-color: #64ca92;
  --tp-box-head-color: #ffffff;
  --tp-button-primary-bg: #f45246;
  --tp-button-primary-hover-bg: #18b3dc;
  --tp-button-secondary-bg: #a9a9a9;
  --tp-button-secondary-hover-bg: #999999;
  --tp-button-text-color: #ffffff;
  --tp-form-bg: #ffffff;
  --tp-form-text-color: #7d8a9d;
  --tp-form-border-color: #e2e2e4;
  --tp-logo-bg: #2a3e4c;
  --tp-topbar-menu-color: #ffffff;
  --tp-topbar-menu-bg-background-color: #18b3dc;
  --tp-topbar-submenu-color: #aeb2b7;
  --tp-topbar-submenu-bg: #2a3e4c;
  --tp-topbar-submenu-hover-bg: #141c22;
}
</pre>
                            ',
                            'default'  => ""
                        ),

                        array(
                            'id'       => 'custom-login-css',
                            'type'     => 'ace_editor',
                            'title'    => __( 'Custom Login Page CSS Code', 'legacy_framework' ),
                            'subtitle' => __( 'Paste your CSS code here. This code will be applied on your admin login page.', 'legacy_framework' ),
                            'mode'     => 'css',
                            'theme'    => 'monokai',
                            'desc'     => 'In case if you need to overwrite any CSS setting, you can add !important at the end of CSS property. eg: color: #da2234 !important; ',
                            'default'  => ""
                        ),

                ),
    ) );

    /*
     * <--- END SECTIONS
     */

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $return['error'] = $field;
                $field['msg']    = 'your custom error message';
            }

            if ( $warning == true ) {
                $return['warning'] = $field;
                $field['msg']      = 'your custom warning message';
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    function dynamic_section_delete_legacy_conflict( $sections ) {
        //$sections = array();
        $sections[] = array(
            'title'  => __( 'Section via hook', 'legacy_framework' ),
            'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'legacy_framework' ),
            'icon'   => 'el el-paper-clip',
            // Leave this as a blank section, no options just some intro text set above.
            'fields' => array()
        );

        return $sections;
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    function change_arguments_delete_legacy_conflict( $args ) {
        //$args['dev_mode'] = true;

        return $args;
    }

    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    function change_defaults_delete_legacy_conflict( $defaults ) {
        $defaults['str_replace'] = 'Testing filter hook!';

        return $defaults;
    }

    // Remove the demo link and the notice of integrated demo from the legacy_framework plugin
    function remove_demo_delete_legacy_conflict() {

        // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
        if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
            remove_filter( 'plugin_row_meta', array(
                ReduxFrameworkPlugin::instance(),
                'plugin_metalinks'
            ), null, 2 );

            // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
            remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
        }
    }
