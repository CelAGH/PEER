<?php
/**
 * In dieser Datei werden die Grundeinstellungen für WordPress vorgenommen.
 *
 * Zu diesen Einstellungen gehören: MySQL-Zugangsdaten, Tabellenpräfix,
 * Secret-Keys, Sprache und ABSPATH. Mehr Informationen zur wp-config.php gibt es auf der {@link http://codex.wordpress.org/Editing_wp-config.php
 * wp-config.php editieren} Seite im Codex. Die Informationen für die MySQL-Datenbank bekommst du von deinem Webhoster.
 *
 * Diese Datei wird von der wp-config.php-Erzeugungsroutine verwendet. Sie wird ausgeführt, wenn noch keine wp-config.php (aber eine wp-config-sample.php) vorhanden ist,
 * und die Installationsroutine (/wp-admin/install.php) aufgerufen wird.
 * Man kann aber auch direkt in dieser Datei alle Eingaben vornehmen und sie von wp-config-sample.php in wp-config.php umbenennen und die Installation starten.
 *
 * @package WordPress
 */

define('WP_HOME','http://rose.cel.agh.edu.pl/peer_lang/peer_de/');
define('WP_SITEURL','http://rose.cel.agh.edu.pl/peer_lang/peer_de/');

define('DROPBOX_KEY','fyl8w9ai47had0f');
define('DROPBOX_SECRET', 'ro8se48up1eu3kn');


/**  MySQL Einstellungen - diese Angaben bekommst du von deinem Webhoster. */
/**  Ersetze database_name_here mit dem Namen der Datenbank, die du verwenden möchtest. */
define('DB_NAME', 'peer_lang_de');

/** Ersetze username_here mit deinem MySQL-Datenbank-Benutzernamen */
define('DB_USER', 'peerplatformen');

/** Ersetze password_here mit deinem MySQL-Passwort */
define('DB_PASSWORD', 'AstRo4Vx32');

/** Ersetze localhost mit der MySQL-Serveradresse */
define('DB_HOST', 'localhost');

/** Der Datenbankzeichensatz der beim Erstellen der Datenbanktabellen verwendet werden soll */
define('DB_CHARSET', 'utf8');

/** Der collate type sollte nicht geändert werden */
define('DB_COLLATE', '');

/**#@+
 * Sicherheitsschlüssel
 *
 * Ändere jeden KEY in eine beliebige, möglichst einzigartige Phrase. 
 * Auf der Seite {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service} kannst du dir alle KEYS generieren lassen.
 * Bitte trage für jeden KEY eine eigene Phrase ein. Du kannst die Schlüssel jederzeit wieder ändern, alle angemeldeten Benutzer müssen sich danach erneut anmelden.
 *
 * @seit 2.6.0
 */
define('AUTH_KEY',         '^Q_~}~ZC#L% ;.A}z*C5PBX+DzYX*tc!Dj$K&qb}-.un$J}{e7s|VFAzT8:0Oi+K');
define('SECURE_AUTH_KEY',  '%2&l]0[^0=M!h:>AE:]L,Y,`P- +rPZWuDXx=!zCK|fqL|-4+AoJ]a3:T1)t!dFl');
define('LOGGED_IN_KEY',    'WmB7fOX@&7IkRhCuMy~D489(cZCnP@Y+c0*<Zr[fW^3|7!9X[H;Y8`YjR/XVinwp');
define('NONCE_KEY',        '-3~q&M65wR,d[{etQ%w3_RKAhl+ecusx+W(!G9d++3$(fI!R]lPKgW@mjVu.iw&#');
define('AUTH_SALT',        '^]E<6sEZ895O[!5.@t5F#%aJ<X> |?` #Zx&V{5rEpkLwYHO#E[5T:TTVXaT}N4N');
define('SECURE_AUTH_SALT', 'm-!}/SGmaoq;|w=?`-a1gI-mK4saxqrF_X$ecq[a/`!XB^&!d,NRB=)[{,6?o:7f');
define('LOGGED_IN_SALT',   'qo/50*eX*76m-/yiW~MLANUWN)vm,f&H0I_wZ?YtTv-P(bfqgwYj6>2r<.|,YRIv');
define('NONCE_SALT',       'uF.F r+;AL^v1j?Iz?yA|P%CZ0Vr[6|S[x8k$;R{jq8J}*:3}wBi>S[l$%vE>)+-');

/**#@-*/

/**
 * WordPress Datenbanktabellen-Präfix
 *
 *  Wenn du verschiedene Präfixe benutzt, kannst du innerhalb einer Datenbank
 *  verschiedene WordPress-Installationen betreiben. Nur Zahlen, Buchstaben und Unterstriche bitte!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Sprachdatei
 *
 * Hier kannst du einstellen, welche Sprachdatei benutzt werden soll. Die entsprechende
 * Sprachdatei muss im Ordner wp-content/languages vorhanden sein, beispielsweise de_DE.mo
 * Wenn du nichts einträgst, wird Englisch genommen.
 */
define('WPLANG', 'de_DE');

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
