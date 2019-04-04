<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'hr-pksol' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'dhlvTXxD@b`UWq%-`7_P33u~]bLV(NVaT5GkJWc`1| Mj-87i7yhZVdcRT.ZF?gJ' );
define( 'SECURE_AUTH_KEY',  '<5gZA`%cSGu:ipI!klIwyD*`TE]PPHK9=b1_2,{gY[3!#b,2?qr4HKY;FL>e?caX' );
define( 'LOGGED_IN_KEY',    't6zv3Xm[|Brs^/Ioz$u6yh7*O{j>U#4sSF]3e<#W,ZJs@kfIQ8XjMO5V=uP5`>m}' );
define( 'NONCE_KEY',        '#/<VS&A+*$0{8S~p%7qI:~@&UWf3g?|DCV&?G9VCp:[hq@dfFdBNn?i}U&SJCA&t' );
define( 'AUTH_SALT',        't>}h346?Ls}b5*RmJJ4#G*9]Jq#&s)L?WenM.K_3THgy4`+orWrIht/8N[c3WZJQ' );
define( 'SECURE_AUTH_SALT', 'MhNM.XEw$z^nw8B~~V=%K<unKPGb8Q>zCL$5heuJIel{dqC7_K/t>k1}*EYLe6dC' );
define( 'LOGGED_IN_SALT',   '^Y&Sq3tweVXZ^k{CQP6W( *V?C@3QjONbTF,TOZdulep#GO#FH:fZR-8(V}R/!m@' );
define( 'NONCE_SALT',       '^FuNkJ ghLwRpl(sUAQ25.E&iSrAr!YA<79(WwMVJUHONk<@TLy4aDof.jw]V*rT' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
