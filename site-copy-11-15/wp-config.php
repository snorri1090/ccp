<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ccpilate_wor1');

/** MySQL database username */
define('DB_USER', 'ccpilate_wor1');

/** MySQL database password */
define('DB_PASSWORD', 'w0768KIg');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** Login for Mindbody
define('PKV_MINDBODY_CRYPTO_KEY', base64_decode('3fo4tpPvdQ6snlN5pWnONXv9SyE='));
define('PKV_MINDBODY_SOURCE_NAME', ColumbiaCityPilates);
define('PKV_MINDBODY_PASSWORD', D!mmak68);
define('PKV_MINDBODY_SITE_ID', 0);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'w,4%0a28! |BaA@nf xX*2o[A-7u^Yc0r?<a`+Nb]pOdw#A>KePRP3 M!75@I$-E');
define('SECURE_AUTH_KEY',  'f_Q`M2/uK[6~$-t&r2xqbG%q%@u#i]XiHEz%|VGy=~&}%KX0y7R&NLY++6n.3a-E');
define('LOGGED_IN_KEY',    'LE3/6Ntp6{V){ta!SOq L3<^*QCodYoh2DqM)mn0KP5>j/GN{Lt;?dD2YrH_|r n');
define('NONCE_KEY',        'l8)*VgW0EUmq=8Sm/@$ma(B#=3!.0:e(e7;oj46ABx1u1+[$<$=gK3vynBdTBi1(');
define('AUTH_SALT',        'vcH6]|hVhH9P,+!>rV3K06%V^[6sE+j=q;$<pd%;,yPf:9^^#>AXBUtxeo+[zG-=');
define('SECURE_AUTH_SALT', 'jT#P7R+9QEb]xuH!39^8Gc$5.iV88U|Tfh6i.|:/1IZXmUa$c8q@#8,.2;vi-HQr');
define('LOGGED_IN_SALT',   'XzO--Kwfj44RHj(vMm#$z^~{0P9XZF,WZ^Q-)<gTHa:}9(;+;CCcFV+yHZyfSx;7');
define('NONCE_SALT',       '<0GA!RZGzC&9dp%8=h*27 i)N~8:Kpq[1~T=+j8Cd`*EeQKY`Qp-~R|6x^t(K&&=');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'khk_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
