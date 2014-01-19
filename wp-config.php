<?php
/** 
 * A configuração de base do WordPress
 *
 * Este ficheiro define os seguintes parâmetros: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, e ABSPATH. Pode obter mais informação
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} no Codex. As definições de MySQL são-lhe fornecidas pelo seu serviço de alojamento.
 *
 * Este ficheiro é usado para criar o script  wp-config.php, durante
 * a instalação, mas não tem que usar essa funcionalidade se não quiser. 
 * Salve este ficheiro como "wp-config.php" e preencha os valores.
 *
 * @package WordPress
 */

define('WP_HOME','http://rose.cel.agh.edu.pl/peer_lang/peer_pt/');
define('WP_SITEURL','http://rose.cel.agh.edu.pl/peer_lang/peer_pt/');

define('DROPBOX_KEY','foe4f4tl7ar6yiv');
define('DROPBOX_SECRET', '0tqzoqum3b4oz2l');

// ** Definições de MySQL - obtenha estes dados do seu serviço de alojamento** //
/** O nome da base de dados do WordPress */
define('DB_NAME', 'peer_lang_pt');

/** O nome do utilizador de MySQL */
define('DB_USER', 'peerplatformen');

/** A password do utilizador de MySQL  */
define('DB_PASSWORD', 'AstRo4Vx32');

/** O nome do serviddor de  MySQL  */
define('DB_HOST', 'localhost');

/** O "Database Charset" a usar na criação das tabelas. */
define('DB_CHARSET', 'utf8');

/** O "Database Collate type". Se tem dúvidas não mude. */
define('DB_COLLATE', '');

/**#@+
 * Chaves Únicas de Autenticação.
 *
 * Mude para frases únicas e diferentes!
 * Pode gerar frases automáticamente em {@link https://api.wordpress.org/secret-key/1.1/salt/ Serviço de chaves secretas de WordPress.org}
 * Pode mudar estes valores em qualquer altura para invalidar todos os cookies existentes o que terá como resultado obrigar todos os utilizadores a voltarem a fazer login
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '+S@+@-SbG #y^=sFS{MDm6C$D*-|(vR)RvkO>$j4MycLm>@hNx|:odqy9l,:nv9;');
define('SECURE_AUTH_KEY',  'Uff{D8_)C@-T:Abu-a*{q#[BX!D:&M0,`BO$H:$^&x;3Aj4i]^11fMMJ8-!7ED5B');
define('LOGGED_IN_KEY',    '<v)t} :8EN1+na|+jf0PRUu31QQ32G&$$fLx+GSj2]n:E,h^rQ&EtVq|[mb)xRtI');
define('NONCE_KEY',        '{@-A{(T(o m1$NMN!O][Y%]a}ILO9 Yr!=6p*QY`L271f/+6HkDe=z2um<~H/MpX');
define('AUTH_SALT',        '!$/_X:3-Ut-p7$|MWn0(&+{<?#J_+8)/zey^,Mp%bHd-0?#V:K?I8p.5Y<uvgYd]');
define('SECURE_AUTH_SALT', 'TO/-Zv]ZZva+8V5*I]dZq{2tW-G51IspOLXf8-+ `FQC-^ZePn/<hWd<pBeXJ3F3');
define('LOGGED_IN_SALT',   '+MF44ldorV_{BWB<0n;B=xdzRm;{S)CkH3$@{mdoQWY^OOrVC#Wz-#X[BaWi8jdy');
define('NONCE_SALT',       'K=^K~?pLam{C.-@$PIotEp6@t.9aogOlrl&]-{rB?qi&Pc26m>>u75^$=lX.mbyA');

/**#@-*/

/**
 * Prefixo das tabelas de WordPress.
 *
 * Pode suportar múltiplas instalações numa só base de dados, ao dar a cada
 * instalação um prefixo único. Só algarismos, letras e underscores, por favor!
 */
$table_prefix  = 'wp_';

/**
 * Idioma de Localização do WordPress, Inglês por omissão.
 *
 * Mude isto para localizar o WordPress. Um ficheiro MO correspondendo ao idioma
 * escolhido deverá existir na directoria wp-content/languages. Instale por exemplo
 * pt_PT.mo em wp-content/languages e defina WPLANG como 'pt_PT' para activar o
 * suporte para a língua portuguesa.
 */
define('WPLANG', 'pt_PT');

/**
 * Para developers: WordPress em modo debugging.
 *
 * Mude isto para true para mostrar avisos enquanto estiver a testar.
 * É vivamente recomendado aos autores de temas e plugins usarem WP_DEBUG
 * no seu ambiente de desenvolvimento.
 */
define('WP_DEBUG', false);

/* E é tudo. Pare de editar! */

/** Caminho absoluto para a pasta do WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Define as variáveis do WordPress e ficheiros a incluir. */
require_once(ABSPATH . 'wp-settings.php');
require_once(ABSPATH . 'lang.php'); 	
