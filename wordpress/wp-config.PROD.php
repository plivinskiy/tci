<?php

define('FS_METHOD', 'direct');
define('FORCE_SSL_ADMIN', true);


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
define( 'DB_NAME', 'db799667858' );

/** MySQL database username */
define( 'DB_USER', 'dbo799667858' );

/** MySQL database password */
define( 'DB_PASSWORD', 'RVQMBKeXSQrIBxhUywKH' );

/** MySQL hostname */
define( 'DB_HOST', 'db799667858.hosting-data.io' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'tzApc^>&|:.ut_-r)gEkzEj$`=n0X^B|Av,3}lp+MW2C|OyngWf .n1$?O{ N|ZP' );
define( 'SECURE_AUTH_KEY',   '{Bd~p+v#4c7]hBGO-hG8Q/!q/rm#q=%1SO.KI3ux,FA(E-GfeWc^Z)>Oxz=a#VX6' );
define( 'LOGGED_IN_KEY',     'V7)glf9Qt{+V2fr7aU~}1K.u1;Jo,V%]mFxS[E0Y tv?Z=f*0}:a#<(//I%|$Q:K' );
define( 'NONCE_KEY',         '0gPKQmFM|5ebxihSQoUN^fzaL`o[<Fc{?x^m4OW{Kpab#79fiELIY@_LlVT`h%B;' );
define( 'AUTH_SALT',         '@>uG%!a?J*`)5`ZE*eoAwO_|=RdP8p-G)R/b!#iM{De_tm >6lTQ{u(uCTY=1IXZ' );
define( 'SECURE_AUTH_SALT',  '9#kEg2*8LL?)xUENkm+kY 1e~NZ7>@<8|tJ|^E$S7Jc*B hnH%vT$r#UVg2HT/xS' );
define( 'LOGGED_IN_SALT',    '*qD/(=@iFL;w?)70]z<3CD?RN-[ioxK&Iuk~ZNyfM2PEKkzQK4}GrD+CAVV/`^M<' );
define( 'NONCE_SALT',        'nruWj1XeKHDTZ38$t^v[Kr)xhoS$s8?k8jRAT_k*z$@v/NO|FjYPnw$;^23M%A=!' );
define( 'WP_CACHE_KEY_SALT', '8?<T*xTZaP4>LRm%u5fX4--i KQ~1mo3nLnb>&[ik!q4_lEXc4 bDXO%V8_@AWIP' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wOzvRyiS';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
