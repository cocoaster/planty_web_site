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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'planty' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '-p8_1OK`g5x 3u;|{@#CmhaE#G64zQ()5B(fD^[g$kb!IrSX_jCu0ESo5W2!L1=V' );
define( 'SECURE_AUTH_KEY',  'd3Ys`/wPQiqX)lKSIxxJk3yC>},/b<J/R U2x3lfTozb2y]W&plWNr}I1tMPlF8%' );
define( 'LOGGED_IN_KEY',    'h*YpR_1Vd;ldAqZ+M:e}5T&?Dx=]U3=-36I)C|l$?/c bW#bNoqFQNp<%+i6TPK8' );
define( 'NONCE_KEY',        '7LI:vnM*7^{1K<l=:l[^gx<K[26EFJUuCNz#I.8BIiBcRlzc?4]zTCG2MN==x*$s' );
define( 'AUTH_SALT',        'o!UHl ]7(4t;!Wl/2T5aBhTs%l|x%=OO{[kL/O*tuf<9=M@+T[he5WXk3IS{qR<T' );
define( 'SECURE_AUTH_SALT', '@Ew.`sH!;zofCF( pYy?|?39_/!Csr>+28YkG!U>h?/~9>E2y^:P_:Xo6lsf~HbE' );
define( 'LOGGED_IN_SALT',   'aI{z*0?*chHqlOZ+/k)X9wI6~ox$F}M||h&<SAaxU$-_k[QV]IB$lZg ,Kl7GDH}' );
define( 'NONCE_SALT',       '/_03%Dxpg$gg-|{K1eeI6xWNry(?s?3r05-@@L+?s_~D]Sy(Nb%.@uf/kV&Xd<?D' );
define('WP_MEMORY_LIMIT', '256M');

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);


/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
