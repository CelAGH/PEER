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

	     

	define('DROPBOX_KEY','ivm03gyf0sz14y6');
	define('DROPBOX_SECRET', 'p6fxiubt1843p5p');

	     define('WP_HOME','http://rose.cel.agh.edu.pl/peer_lang/peer_en/');
	     define('WP_SITEURL','http://rose.cel.agh.edu.pl/peer_lang/peer_en/');

             define('DB_NAME', 'peer_lang_en');
             
             /** MySQL database username */
             define('DB_USER', 'peerplatformen');
             
             /** MySQL database password */
             define('DB_PASSWORD', 'AstRo4Vx32');
             
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
                     define('AUTH_KEY',         'OhiG{~`V;1a|[:E5[C|;Jc]8`m>~R]CLHlN!Zls$@8{-VmP-y9jp2LHpV<]6K0oI');
                     define('SECURE_AUTH_KEY',  '}(;{_vW%+IXzj0f7)^(L:`W2^:gG82^-L|vrJ@li!hp+wjh(Xz3|e;;%2FX]?[bT');
                     define('LOGGED_IN_KEY',    ')~fGL,AhO^ZekFCO$- aH-#`l],bzIXjDJ~^6L|[m,vR@CD`6{6L(=*qF|J->eJ^');
                     define('NONCE_KEY',        '}0-(jUL/!2sXAS&xM:h%T/E]p ~-^I& 6Y78W|M#l~pilEa{1-oY!qWDu88/}Lca');
                     define('AUTH_SALT',        '9|kLtO(2q!M Z^++,w+/UwrMRKh-Ykuc_]_54QG]E.s+SVNXy|0?:mdJA|.,:F#8');
                     define('SECURE_AUTH_SALT', '~m!n$-_}fr]r_!Hf!-Pe,8n&S|p2R:l_gt>/gwnT6)`UPS A@fGJI(+^yXQ&%n;_');
                     define('LOGGED_IN_SALT',   '!]Q$BAE;|zboe#-Oyu0+[8uar,0/Ft2)|mT9-^L<I(ShM,PE5Sug=kZ[xugA4JMC');
                     define('NONCE_SALT',       'p+/,J1F%[$n;!nRs=HPyKK/Y(c*5V~yK?#P$WU0u4wz7Spu}NpF;,B;G^|c^-qDi');
                     
                     /**#@-*/
                     
                     /**
                      * WordPress Database Table prefix.
                       *
                        * You can have multiple installations in one database if you give each a unique
                         * prefix. Only numbers, letters, and underscores please!
                          */
                          $table_prefix  = 'wp_';
                          
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
					require_once(ABSPATH . 'lang.php'); 	
                                        
