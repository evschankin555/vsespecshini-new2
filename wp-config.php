<?php
define( 'WP_CACHE', false );

//ini_set('display_errors', 1);







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
define( 'DB_NAME', 'cjPNpNv4RwLXcG' );

/** MySQL database username */
define( 'DB_USER', 'cjPNpNv4RwLXcG' );

/** MySQL database password */
define( 'DB_PASSWORD', 'UGS3AvqlLpcndE' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          '^TBWfkXHj;[ld_t@Sw@??v~lN[5{I?t|`I+^ExY6hHV`N/_IhR$}f)w:r>p2y/hv' );
define( 'SECURE_AUTH_KEY',   'q*<9{i/hyJ#R@j<EH>*qz}r{Q4dC+vjA9OM%)$i*.i^z~]%xK3HL10ZeP^)Ww5!+' );
define( 'LOGGED_IN_KEY',     ')p9xWk*(Zy/#ly %*iI2f[eHaRZ{29W186lJfUb.:m<y=-<+/AEvs2R%!yELGljA' );
define( 'NONCE_KEY',         'Oq!;>{qEwad  <FccR_.r/j_X/nl;mo3oy0Xt<nl{iwV1BJqeW(^7]S/8HEGr%4Q' );
define( 'AUTH_SALT',         'E:a~YDW@NDhw?=H^6mB +,^^O3|W|NPe%{49m7bFdmTY~GB zB +Yh}KedIwkTVF' );
define( 'SECURE_AUTH_SALT',  '>X)q^aT1|}M}[>:6 K@:ZCCaVIDz[3*j}]_S@AZr.&!MZ|pFS}suO:zR1b A<lt_' );
define( 'LOGGED_IN_SALT',    'aDp[Vu9[Dsp-?~iQQ6ITF#+1z#<hK85f4av5PnWPw%%}IBGgEyK9Sq6)rW&Ez%.d' );
define( 'NONCE_SALT',        'p{+yT]~Fm,=r6-@)a.n,ZrJ[Z1BEvct>nc|*kA1GK_+cZ4e}kXGqse;zK4pgb.2+' );
define( 'WP_CACHE_KEY_SALT', 'mBh{vnpB @#LH*]kG@vlV]}JZT/$!d9oV9bG%q^BrYA|U?]mn&Yzm5FQ*!,ngPW1' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
