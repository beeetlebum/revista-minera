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
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          'BEXWS. *d0iX@@&aG=ps9_+I?j0P568|?+M!K+E-1x`Ss{&/6R<*t|P&FHI~F]QH' );
define( 'SECURE_AUTH_KEY',   'MX_0_[%Od43:*!JO3e@b@.u*]E[jaS~ki!hAa~7.g1/64O-*j,$T6cyINy6:quR+' );
define( 'LOGGED_IN_KEY',     'cd;yHN4_Wor<p(-<sxYv7~z)iMj19^9_tMaqFO)W.;E~M@)9JrstJpANd!4A I=7' );
define( 'NONCE_KEY',         'TP~6_SH1:bQN9~{CfnhD=Ap)KoR1.s3O`M^bE7a;nSVS?5*;<jN*r]cUO]|[tCw ' );
define( 'AUTH_SALT',         'L.`K=l>9bFf$/e=cg_@iYOmwP@|~$<(NoQGld%Esf^GIB.,Qx(GsEnC[b>#9YydG' );
define( 'SECURE_AUTH_SALT',  'AiX/|&_;LAABVz[#CX0`3F7y7S^}&UTBa?OnAo0w#o/f23O|^GL+vHk?zEM=1`;Z' );
define( 'LOGGED_IN_SALT',    'C [QYi79wn{~F+6j#Fxn5?!xZ3/t$fJ0=|K^o>{g^=5ow1_QBO-SZp[^fj9)vVq ' );
define( 'NONCE_SALT',        '1)uz7Ord,A=5U6@buqYTRP{I@C9{fIwHC;{IzaSm=WR$.f:|yCtApjjvCDS*2AM@' );
define( 'WP_CACHE_KEY_SALT', '7ilDK5+.BvkL7)S2=js@YS4a[<e/l4jGG9[1gRi1>J;^vTZC-mC=s1LTbCGhAV 5' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
