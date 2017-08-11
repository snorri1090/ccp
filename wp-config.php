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
define('DB_PASSWORD', 'D!mmak68');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '.LjqgL-W8/D4uoi#,ZHBKYtv(R,nd}3l< ph|$b~Rl1a%a4mi*0-9{+phQr!L<)h');
define('SECURE_AUTH_KEY',  '#5FAk_FLv-:F<k:%@*+A%>O?_%UF9&^^_{ph<`*@Bm-wf[K%M(@x$l>(6rLa^dp@');
define('LOGGED_IN_KEY',    '4dYT({4-W;$]]=Vj^>1{Af$5ZZ3HU]T8RnFyEBlYaK<ve#+>..F:SFbRH#8aY+y|');
define('NONCE_KEY',        'Eo,-Ok+Q*4aR|.&N`qL+z8/)X`SB,O)nqCl- x+|%&Z4|QCP4<*?:k>5C|cq*z--');
define('AUTH_SALT',        '6u1k)T8BV-[$|hZ*GYw(ObtsWg|}+a{xma:?m-7rHPU/(Ih/ G9%*/@+o;>o#{ZH');
define('SECURE_AUTH_SALT', ',YAdxUu#@HCaezU!PrU^0uNr8?!<=omKR!O4O2`#Eu-?%U}V{UJOg/:]_x@I=maE');
define('LOGGED_IN_SALT',   ' M%=?Jeh5QI$*i$:H`+i|i3-t+ z2Cd[-(fs|u-uW/?e&Z 9+.G+f)1$Aj^[|)Tc');
define('NONCE_SALT',       'I;Tq3|EOYxA@c|OaB|w#.H!/|n-{uZ PSwg+w#1pr$5WA]yxsRZVko!c5|N^%d`p');

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
