<?php















/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u490848992_nNtqZ' );

/** Database username */
define( 'DB_USER', 'u490848992_WzyaA' );

/** Database password */
define( 'DB_PASSWORD', 'YCaIFhmrPK' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'kN[wn+7srL69dC`5&3_[f]$o&t(y#N3ks6:CjZk/@LE46TvH95; &(sEiu]Ym9[*' );
define( 'SECURE_AUTH_KEY',   'PYO>NMMZuHQ]+ x< k0Ss.bZ&n+(OG`b~bb<BIY AV>-<R@-H{lrh!x39_m8lPc{' );
define( 'LOGGED_IN_KEY',     '@}69uMh<?gj#jTY_[1(N}r#%_u2U$?QLOo7%#SoD_ZldO6JQrV_y/|JjNDJQU+lb' );
define( 'NONCE_KEY',         'u%-}!T(Pm28w&^7wwVZOQ9uhhk*:}{_y=~)y)Bmz:*}I>KlPiLmW@HbT`<ZgnZVy' );
define( 'AUTH_SALT',         '`bN.bUjM~%lx.KW/lwV`X7k+eWn}PP:2+/tqvNm6Rkx2%Zp#*vtJC 5yf6 7u&^0' );
define( 'SECURE_AUTH_SALT',  'aOdj]!#|T#f*J]V@e |:<Y7%TZk5/`eb9lQt%AZ!@qbi[0C>MsL(-o+ova@l(iW!' );
define( 'LOGGED_IN_SALT',    '&~m>HN,4aJYDB.ucp:#b[/jTMc:h{edr(m/iN<5}`a?`6.^2Xa.>~^i9<3;Bx{@Q' );
define( 'NONCE_SALT',        ' a8a_q5}b2HuCv)[a-4/axc.I735MM/5I7))y!!$<%brE9KqYB|T$D)f!q0J@`%I' );
define( 'WP_CACHE_KEY_SALT', 'jWe9I%;f0;@:*_;ri90p.w+XaR)tS 8[o{GjZWFkxIt-aK]>HeCFN{s5}veVFyBa' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */



define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
# BEGIN WP Hide & Security Enhancer
define('WPH_WPCONFIG_LOADER',          TRUE);
@include_once( ( defined('WP_PLUGIN_DIR')    ?     WP_PLUGIN_DIR   .   '/wp-hide-security-enhancer-pro/'    :      ( defined( 'WP_CONTENT_DIR') ? WP_CONTENT_DIR  :   dirname(__FILE__) . '/' . 'wp-content' )  . '/plugins/wp-hide-security-enhancer-pro' ) . '/include/wph.class.php');
if (class_exists('WPH')) { global $wph; $wph    =   new WPH(); ob_start( array($wph, 'ob_start_callback')); }
# END WP Hide & Security Enhancer
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
