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

define('WP_MEMORY_LIMIT', '2M');
define('DB_NAME', 'latitude');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'payoda@123');

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
/*define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');*/

define('AUTH_KEY',         '-|97^^[;+].h+rWTX&a,0+vN#+wUcMQqPh=ove)sP}:3}|g,oUL u{a]>5_2@NiR');
define('SECURE_AUTH_KEY',  'Xm##(ArvSQ3W`N06<CI.Urn,ST)5+bfL-L&>&2kUsQV6&(P*SfYM_!3+:8cM@>8B');
define('LOGGED_IN_KEY',    '0{~dB|8{PdG SQbmgHfL4#Z9aM@%_&`E-a+;IxC0X}>I/bDp5<_>[v/m_F8+|Qog');
define('NONCE_KEY',        'n(I0DuuH%D_9VgNG5 {(;|IgDxHQk=wy4?#H*MJ5<-zYsdbc)iPw/SyH?E3R[2[u');
define('AUTH_SALT',        'LO)fl5bbS 4O)|l_ZTM;}uan--ZbBim|.YbeZ*$wSaE>N&+|XQX%wFz&yf:6in=1');
define('SECURE_AUTH_SALT', 'H7 YSJ*A<Cl/_qSFj?3KX^7XsnO~|=4t<B*]+DK!Tin@.}kVUP},NJN<&]R]@Y-}');
define('LOGGED_IN_SALT',   '+XvPhv XOb987^ |sKS?Cz[={enL~6H[l#Y0iVyYinnJ-$;>DaSr,S(b+vn}]3Om');
define('NONCE_SALT',       ')~;~RzmgkI<5f&T[/:HY15kQ+7U3.g64`d ?dgVP$.?-0):rzrBEU1J-xY@<Qk?`');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
