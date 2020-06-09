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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'fetcher_wp_db' );

/** MySQL database username */
define( 'DB_USER', 'homestead' );

/** MySQL database password */
define( 'DB_PASSWORD', 'secret' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY',         'vOtp~Ls]^w3[Ro.46U8Fz0uYzim-~K*ZF-s|K<o-42+9@d{HdWOX)|dPawhy=mrL');
define('SECURE_AUTH_KEY',  'sSAy+r]f:* iy4/JR5~yZput+2)XR&lu_y3=Gmp~, |&kQbGET@LY~=~T6}$N<--');
define('LOGGED_IN_KEY',    'UZ>c#}+2>|T7f#HU-m+ZQYvR$<a0,U;C444U^tqzfZjN#E02Ra9s+JK]qeRyu4/G');
define('NONCE_KEY',        'ljY^rd0XM6CP}!SJLHaEA*@^_&Gn>Dz-FhMi>BDIa&F~|epu++`|BP+1EgR_M2Vy');
define('AUTH_SALT',        'P0`;4|rA!H->(pNosNRGxf+~ft^+`uJw@|LI:O7DSU<EpftQ!zh*y}H=1-Zt.cfG');
define('SECURE_AUTH_SALT', 'A[wzpDp!B/[Fi;52=](;|U1F-Z2Q9-WsMNR|l_ld/:i/gx.4l&~diop>s{vX&lR6');
define('LOGGED_IN_SALT',   't9XAIL(y$+ncV#n+F*l)>a--b{9BLUMq I]/p=doE#=F90:x!>7`y3dLLyxh2)d1');
define('NONCE_SALT',       '}hr0UxxT~y>|HB/b9![CPrI~DikJh-5C)uJ{K#?@i$|C]/Sb^mdVc8bj*>F@~J+c');

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
