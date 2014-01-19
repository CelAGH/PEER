<?php
/**
 * Podstawowa konfiguracja WordPressa.
 *
 * Ten plik zawiera konfiguracje: ustawień MySQL-a, prefiksu tabel
 * w bazie danych, tajnych kluczy, używanej lokalizacji WordPressa
 * i ABSPATH. Więćej informacji znajduje się na stronie
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Kodeksu. Ustawienia MySQL-a możesz zdobyć
 * od administratora Twojego serwera.
 *
 * Ten plik jest używany przez skrypt automatycznie tworzący plik
 * wp-config.php podczas instalacji. Nie musisz korzystać z tego
 * skryptu, możesz po prostu skopiować ten plik, nazwać go
 * "wp-config.php" i wprowadzić do niego odpowiednie wartości.
 *
 * @package WordPress
 */



define('WP_HOME','http://rose.cel.agh.edu.pl/peer_lang/peer_pl/');
define('WP_SITEURL','http://rose.cel.agh.edu.pl/peer_lang/peer_pl/');

define('DROPBOX_KEY','htvepdtgito5osg');
define('DROPBOX_SECRET', 'r944eyglm2yyjf2');

// ** Ustawienia MySQL-a - możesz uzyskać je od administratora Twojego serwera ** //
/** Nazwa bazy danych, której używać ma WordPress */
define('DB_NAME', 'peer_lang_pl');

/** Nazwa użytkownika bazy danych MySQL */
define('DB_USER', 'peerplatformen');

/** Hasło użytkownika bazy danych MySQL  */
define('DB_PASSWORD', 'AstRo4Vx32');

/** Nazwa hosta serwera MySQL */
define('DB_HOST', 'localhost');

/** Kodowanie bazy danych używane do stworzenia tabel w bazie danych. */
define('DB_CHARSET', 'utf8');

/** Typ porównań w bazie danych. Nie zmieniaj tego ustawienia, jeśli masz jakieś wątpliwości. */
define('DB_COLLATE', '');

/**#@+
 * Unikatowe klucze uwierzytelniania i sole.
 *
 * Zmień każdy klucz tak, aby był inną, unikatową frazą!
 * Możesz wygenerować klucze przy pomocy {@link https://api.wordpress.org/secret-key/1.1/salt/ serwisu generującego tajne klucze witryny WordPress.org}
 * Klucze te mogą zostać zmienione w dowolnej chwili, aby uczynić nieważnymi wszelkie istniejące ciasteczka. Uczynienie tego zmusi wszystkich użytkowników do ponownego zalogowania się.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'X_rF4e:BP[347S3v95^Y!+~GY|.l(D)!UjfDto<&91+-2;lQ[C@wUsKU(;HsY#M;');
define('SECURE_AUTH_KEY',  '+DJ@T;-+!hHuMtcN5EORy^,oIV-<8sDP2Hjw}ucWq&Kkj YGs$0!/BpKq#NIRYY0');
define('LOGGED_IN_KEY',    '2haJ(9:|494Xf~yU|B4,-W4f]0;$My8LQS=!8M.2f{E#@CRv03Kb+}1&%aoaTo,|');
define('NONCE_KEY',        'M#6q #bGh%afJVTqgt8-]M|5~{W ~-=u~2e70z7QxNM(&G#_`VA]6-+I(`ZN-4uk');
define('AUTH_SALT',        'vv*xx@v(_,-u+lvn%|n*-8,%8-{2J0~WHDcIKd,)p#;17qIfX.BA8KJ-RGdh)6+o');
define('SECURE_AUTH_SALT', '}Kc>dj%!:v+bO(`.IBpi8dga1&:{0h=aK.#x*{I`|4Fl|<(Z-N<NJ0$6zB$=&p6N');
define('LOGGED_IN_SALT',   '1QvK19wV@X`pl^TW uFw>IT;f&|N+eIeO^n<xzg[u(0(SoSz~#;t~uGIf}%wp_oG');
define('NONCE_SALT',       'f53 A;cH}s6-QzD8u,W.]$4.b+-iv@okYfSoeOv[[Z2#-P*7gL_tK/S!&SrM)tU;');

/**#@-*/

/**
 * Prefiks tabel WordPressa w bazie danych.
 *
 * Możesz posiadać kilka instalacji WordPressa w jednej bazie danych,
 * jeżeli nadasz każdej z nich unikalny prefiks.
 * Tylko cyfry, litery i znaki podkreślenia, proszę!
 */
$table_prefix  = 'wp_';

/**
 * Kod lokalizacji WordPressa, domyślnie: angielska.
 *
 * Zmień to ustawienie, aby włączyć tłumaczenie WordPressa.
 * Odpowiedni plik MO z tłumaczeniem na wybrany język musi
 * zostać zainstalowany do katalogu wp-content/languages.
 * Na przykład: zainstaluj plik de_DE.mo do katalogu
 * wp-content/languages i ustaw WPLANG na 'de_DE', aby aktywować
 * obsługę języka niemieckiego.
 */
define('WPLANG', 'pl_PL');

/**
 * Dla programistów: tryb debugowania WordPressa.
 *
 * Zmień wartość tej stałej na true, aby włączyć wyświetlanie ostrzeżeń
 * podczas modyfikowania kodu WordPressa.
 * Wielce zalecane jest, aby twórcy wtyczek oraz motywów używali
 * WP_DEBUG w miejscach pracy nad nimi.
 */
define('WP_DEBUG', false);

/* To wszystko, zakończ edycję w tym miejscu! Miłego blogowania! */

/** Absolutna ścieżka do katalogu WordPressa. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Ustawia zmienne WordPressa i dołączane pliki. */
require_once(ABSPATH . 'wp-settings.php');
require_once(ABSPATH . 'lang.php'); 	
