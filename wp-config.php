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
define('DB_NAME', $_SERVER['DB_NAME']);

/** MySQL database username */
define('DB_USER', $_SERVER['DB_USER']);

/** MySQL database password */
define('DB_PASSWORD', $_SERVER['DB_PASSWORD']);

/** MySQL hostname */
define('DB_HOST', $_SERVER['DB_HOST']);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'q)[fG[M.nEDvh*4:T};r=Bqt.%t@XHz4VAeg+YR)j_25$?dA2Cu[M!h]WB*,!hx1');
define('SECURE_AUTH_KEY',  'OQ5{Ni:Jj9K6+KLwsV?C3@Sl|6m7x0lzDv.w?)jcqkrpqq!J3=G<]eU6W0=K)twQ');
define('LOGGED_IN_KEY',    'tuIcdCLGDvKud7j3g*5sK3*dYA-JBy7M44oy[z8/%?C([e5|aN$6j/qD/hVnT5|A');
define('NONCE_KEY',        'BrbJ[!y>S=^ow:>ks;MM[MfyoUasmJH&s6zg`fC%)`7*q$,Q~E*Rlf}pOE6d 5_.');
define('AUTH_SALT',        '|-NdnSB8/vH9;N{f^Q2yqQWGt5vPP^ :Pu@rYYJi$D)HzBG(T7RH Fjnh*,/G@9W');
define('SECURE_AUTH_SALT', '] [TYXh`K>meA?l$E44MR4u@L@0L|)~N0tRW;*; `r2aL?jQ(Sz8ms45C3[bk+Hv');
define('LOGGED_IN_SALT',   '+X4sB?>oO:U{yU 6<i_yv(r_sHo;h2x1oXvkgdt%(sZf3:eF7U%<9lpIC@(tUq!,');
define('NONCE_SALT',       ')y~Tj^US+pPe0va{t3>LC&jPj>-h~dCL:3YQ`=(60oh/sveo{}pCu-Vn,2|}/VFD');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', $_SERVER['WP_DEBUG']);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
