<?php
/**
 * Bootstrap loads all necessary system classes, setup variables and prepare system to run.
 *
 * @package: Forpost3
 * @version: 3.2.0
 * @author: Dmitriy Yuriev <coolkid00@gmail.com>
 * @license: http://www.gnu.org/licenses/agpl.txt GNU Affero General Public License
 */


/* Protection against direct call */
if (!defined('FORPOST_VALID')) {
    header('HTTP/1.1 404 Not Found', 404);
    header('X-Powered-By: PHP', true);
    die("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL " . htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES) . " was not found on this server.</p>\n</body></html>");
}

define('SYS_BOOTSTRAP_START_TIME', microtime(true));

/* Turn off output buffering */
if (ob_get_level()) {
    ob_end_clean();
}

/* Load system constants */
require_once 'defines.php';

/* Checking PHP version */
if (version_compare(phpversion(), PHP_MIN_VERSION, '<')) {
    header('HTTP/1.1 500 Internal Server Error', true, 500);
    die("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n<html><head>\n<title>500 PHP Wrong Version Error</title>\n</head><body>\n<h1>PHP Wrong Version Error</h1>\n<p>Your version of PHP is <b>" . phpversion() . "</b>.<br>Forpost3 needs PHP version <b>" . PHP_MIN_VERSION . "</b> or higher to work properly.</p>\n</body></html>");
}

/* Checking for "short_open_tag" setting */
if (ini_get('short_open_tag') <> '1') {
    header('HTTP/1.1 500 Internal Server Error', true, 500);
    die("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n<html><head>\n<title>500 PHP Setting Error</title>\n</head><body>\n<h1>PHP Setting Error</h1>\n<p>Your PHP setting \"short_open_tag\" is off.<br>You must enable this setting to proceed.</p>\n</body></html>");
}

/* Checking for all needed extensions are loaded */
$needed_extensions = array(
    'PDO',
    'pdo_mysql',
    'pcre',
    'SPL',
    'Reflection',
    'session',
    'standard',
    'curl',
    'fileinfo',
    'gd',
    'json',
);

if ($diff = array_diff($needed_extensions, get_loaded_extensions())) {
    header('HTTP/1.1 510 Not Extended', true, 510);
    die("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n<html><head>\n<title>510 PHP Extension Error</title>\n</head><body>\n<h1>PHP Extension Error</h1>\n<p>One or more PHP extensions required for properly work are missing:<br><pre>\n" . implode("\n", $diff) . "</pre>Please install this extension(s) to proceed.</p>\n</body></html>");
}

/* Load system autoloader */
require_once 'autoload.php';

/* Bind system dependencies */
Container::bind('config', 'FConfig');
Container::bind('lang', 'FLang');
Container::bind('session', 'FSession', array('app.session'));
Container::bind('db', 'FDb');
Container::bind('event', 'FEvent');
Container::bind('input', 'FInput');
Container::bind('registry', 'FRegistry');
Container::bind('user', 'FUser');
Container::bind('router', 'FRouter');
Container::bind('output', 'FOutput');
Container::bind('benchmark', 'FBenchmark');
Container::bind('request', 'FRequest');
Container::bind('response', 'FResponse');
Container::bind('view', 'ForpostView');

/* Start benchmarking */
//Benchmark::startBench(START_TIME);
//Benchmark::checkPointBegin('system.load_vendor');
define('SYS_LOAD_VENDOR_START_TIME', microtime(true));

/* Load composer autoloader */
if (Lib::chkFile(LIB_DIR . '/autoload.php')) {
    require_once LIB_DIR . '/autoload.php';
} else {
    header('HTTP/1.1 510 Not Extended', true, 510);
    die("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n<html><head>\n<title>510 Application Error</title>\n</head><body>\n<h1>Application Error</h1>\n<p>File \"vendor/autoload.php\" doesn`t exists.<br>You must execute \"composer install\" command in application`s root directory.</p>\n</body></html>");
}

//Benchmark::checkPointEnd('system.load_vendor');
define('SYS_LOAD_VENDOR_END_TIME', microtime(true));

/* Load default settings from system`s config */
Config::load('system.default');

/** Setup PHP parameters */
ini_set('default_charset', Config::get('sys.default_charset'));
ini_set('session.gc_maxlifetime', Config::get('sys.session_max_lifetime'));
ini_set('session.cookie_lifetime', Config::get('sys.session_cookie_lifetime'));
session_name(Config::get('sys.session_name'));
session_cache_limiter(null);
session_start();

define('SYS_BOOTSTRAP_END_TIME', microtime(true));

/* Load application bootstrap file */
if (Lib::chkFile(APP_DIR . '/bootstrap.php')) {
    require_once APP_DIR . '/bootstrap.php';
}
