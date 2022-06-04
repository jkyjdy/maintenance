<?php
define( 'WP_CACHE', true );
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
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
define( 'DB_NAME', 'u7718053_wp97' );

/** MySQL database username */
define( 'DB_USER', 'u7718053_wp97' );

/** MySQL database password */
define( 'DB_PASSWORD', '2.9pn7aS[e' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         'erq4xpumix8e3sygronh6drrsqs9gmeebvwbshchsyikruwjizstc4qyqdjvtbif' );
define( 'SECURE_AUTH_KEY',  'xcwi6pogiyrk9ibyd1ldzrwvtsuafgc1vbzket3qdjydpjream4jgeemrxc3d7vx' );
define( 'LOGGED_IN_KEY',    'lbbzoxblmumztfilqvymkyklysjq2ounehkp9dnmfg4shep7wkljv53qjczkd3qu' );
define( 'NONCE_KEY',        'ugiyyjgigbupwidifgh5beidhjfx7wsuz42llhrospikwwkxxolgufppopwnd174' );
define( 'AUTH_SALT',        'wckvw4kk72kz4tjpbo8rprmj1rzm9rjp4zkmshjsa2irmxgrcmdorfzgzovwi1py' );
define( 'SECURE_AUTH_SALT', 'zaodsuhbwv5rcsmzpkjeoe8kf8fsrmxrzygmivtf3ique3cr6t0avcn7a3xsbzjb' );
define( 'LOGGED_IN_SALT',   'u7gl6lxnkqw93xi6vsprpjw4xhughwtaciy2faq9x3pc3w5drbxdwpiapn0bz7wb' );
define( 'NONCE_SALT',       'ex7k9qztjye3ay08pvwdna6akw4t32l8q23xifi173zuspec11gy5pgpudoctpup' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpwo_';

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



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
