<?php
/**
 * Application bootstrap loads all necessary classes, setup variables and prepare system to run.
 *
 * @package: Forpost3
 * @version: 3.2.0
 * @author: Dmitriy Yuriev <coolkid00@gmail.com>
 * @license: http://www.gnu.org/licenses/agpl.txt GNU Affero General Public License
 */


define('APP_BOOTSTRAP_START_TIME', microtime(true));

/* Protection against direct call */
if (!defined('FORPOST_VALID')) {
    header('HTTP/1.1 404 Not Found', 404);
    header('X-Powered-By: PHP', true);
    die("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL " . htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES) . " was not found on this server.</p>\n</body></html>");
}

/* Load application settings */
require_once APP_DIR.'/defines.php';
require_once APP_DIR . '/includes/functions.php';
Config::load('application.main');
Config::load('application.database');

/* Autoload for application classes*/
spl_autoload_register(
    function ($class) {
        $include_file = APP_DIR . '/classes/' . $class . '.class.php';

        if (is_file($include_file) && is_readable($include_file)) {
            require_once $include_file;
        }
    }
);

/* Setup locale */
Lang::setLanguage('ru');
setlocale(LC_ALL,'ru_RU.utf8');
date_default_timezone_set(Config::get('sys.date_timezone'));

/* Bind application dependencies */
Container::bind('pdo', 'PDO', array(
        Config::get('database.pdo_dsn'),
        Config::get('database.username'),
        Config::get('database.password')
    )
);

/* Setup error handling support */
DYuriev\ErrorHandler::configure(
    array(
        'lang' => 'ru',
        'show_debug' => true,
        'mail_subject' => 'Сообщение об ошибке'
    )
);

/** Enable Patchwork UTF-8 support */
\Patchwork\Utf8\Bootup::initAll();
\Patchwork\Utf8\Bootup::filterRequestUri();
\Patchwork\Utf8\Bootup::filterRequestInputs();

/** Enable Monolog Logger */
Container::bind('logger',function () {
    $formatter = new \Monolog\Formatter\LineFormatter("[%datetime%][%level_name%] %message% (%context%) \n", "d.m.Y H:i:s");
    $stream = new Monolog\Handler\StreamHandler(LOGS_DIR . '/application.log');
    $stream->setFormatter($formatter);
    $logger = new Monolog\Logger('default');
    $logger->pushHandler($stream, Monolog\Logger::DEBUG);

    return $logger;
});

/** Enable Twig templating */
Container::bind('twig', function ($twig_tpl_dir = TWIG_TPL_DIR, $twig_cache_dir = TWIG_TPL_CACHE_DIR) {

    return new Twig_Environment(new Twig_Loader_Filesystem($twig_tpl_dir), array(
        'cache' => $twig_cache_dir,
        'auto_reload' => true,
        'charset' => 'utf-8',
        'debug' => false,
        'strict_variables' => false,
    ));
});

Container::bind('twigview', 'TwigView');

/** Enable PhpThumb image manipulating library */
Container::bind('phpthumb',function ($image_path) {

    return PhpThumbFactory::create($image_path);
});

/** Initializing Memcache */
Container::bind('memcache',function () {
    Config::load('application.memcache');
    $memcache = new Memcache;
    $memcache->addserver(Config::get('memcache.host'), Config::get('memcache.port'));

    return new FMemCache($memcache);
});

/** Initializing Redis */
Container::bind('rediscache',function () {
    Config::load('application.redis');
    $redis = new Redis();
    $redis->connect(Config::get('redis.host'), Config::get('redis.port'));

    return new FRedisCache($redis);
});

/** Initializing bundled filecache */
Container::bind('filecache', function ($cache_store_path) {
    return new FFileCache($cache_store_path);
});

/** Setting up caching instances */
Container::bind('sqlcache', function () { //caching SQL queries

    return Container::make('memcache');
});

Container::bind('imgcache', function () { //caching image thumbnails

    return Container::makeNew('filecache', array(Lib::mkPath(array('application','cache','images'))));
});

Container::bind('pagecache', function () { //caching generated page data

     return Container::makeNew('filecache');
});

Container::bind('cache', function () { //caching generally data
    return Container::make('filecache');
});

Container::bind('file', 'FFile');

/* Load Eloquent ORM (Laravel 4) */
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;

$capsule->addConnection(array(
        'driver'    => 'mysql',
        'host'      => Config::get('database.host'),
        'database'  => Config::get('database.dbname'),
        'username'  => Config::get('database.username'),
        'password'  => Config::get('database.password'),
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => ''
    ));

$capsule->setAsGlobal();
$capsule->bootEloquent();
Container::bind('builder', $capsule::connection());

/** Enable PHP DebugBar */
$debug_bar = new \DebugBar\DebugBar;
$debug_bar->addCollector(new \DebugBar\DataCollector\TimeDataCollector(START_TIME));
$debug_bar->addCollector(new \DebugBar\DataCollector\MessagesCollector('Var dumps'));
$debug_bar->addCollector(new \DebugBar\DataCollector\MessagesCollector('Input'));
$debug_bar->addCollector(new \DebugBar\DataCollector\MessagesCollector('Errors'));
$debug_bar->addCollector(new \DebugBar\DataCollector\MemoryCollector());
$debug_bar->addCollector(new \DebugBar\DataCollector\ConfigCollector(Config::get()));

$debug_bar->getCollector('Input')->addMessage(Input::GET(), 'GET');
$debug_bar->getCollector('Input')->addMessage(Input::POST(), 'POST');
$debug_bar->getCollector('Input')->addMessage(Input::COOKIE(), 'COOKIE');
$debug_bar->getCollector('Input')->addMessage(Input::SERVER(), 'SERVER');
$debug_bar->getCollector('Input')->addMessage($_SESSION, 'SESSION');
Container::bind('debugbar', $debug_bar);

/** Initialize SwiftMailer */
Container::bind('swiftmailer', function () {
    return Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
});

Container::bind('swiftmessage',function ($title) {
    return Swift_Message::newInstance($title);
});

Container::bind('http_query', 'FHttpQuery');

/** Load application events file */
if (Lib::chkFile(APP_DIR . '/events.php')) {
    require_once APP_DIR . '/events.php';
}

/** Turn on global output buffering if it enabled in config */
if (Config::get('sys.global_buffer_enable')) {
    ob_start();
}

define('APP_BOOTSTRAP_END_TIME', microtime(true));


DebugBar::getCollector('time')->addMeasure('System bootstrap', SYS_BOOTSTRAP_START_TIME, SYS_BOOTSTRAP_END_TIME);
DebugBar::getCollector('time')->addMeasure('Application bootstrap', APP_BOOTSTRAP_START_TIME, APP_BOOTSTRAP_END_TIME);
