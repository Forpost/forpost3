<?php
/**
 * System defines.
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

define('PHP_MIN_VERSION', '5.3.10');

define('APP_DIR', ROOT_DIR . '/application');
define('SYS_DIR', ROOT_DIR . '/system');
define('LIB_DIR', ROOT_DIR . '/vendor');
define('UPLOAD_DIR', ROOT_DIR . '/uploads');

define('SYS_CONTROLLERS_DIR', SYS_DIR . '/controllers');
define('SYS_MODELS_DIR', SYS_DIR . '/models');
define('SYS_VIEWS_DIR', SYS_DIR . '/views');
define('SYS_TPL_DIR', SYS_DIR . '/templates');
define('CLASSES_DIR', SYS_DIR . '/classes');

define('CONTROLLERS_DIR', APP_DIR . '/controllers');
define('MODELS_DIR', APP_DIR . '/models');
define('VIEWS_DIR', APP_DIR . '/views');

define('HELPERS_DIR', SYS_DIR . '/helpers');
define('ABSTRACTS_DIR', SYS_DIR . '/abstracts');
define('FACADES_DIR', SYS_DIR . '/facades');
define('IMPLEMENTS_DIR', SYS_DIR . '/implements');
define('INTERFACES_DIR', SYS_DIR . '/interfaces');
define('CACHE_DIR', APP_DIR . '/cache');
define('COM_DIR', APP_DIR . '/components');
define('LOGS_DIR', APP_DIR . '/logs');
define('TPL_DIR', APP_DIR . '/templates/forpost');
define('TPL_CACHE_DIR', CACHE_DIR . '/templates');
define('APP_ROUTES_FILE', APP_DIR . '/routes.php');
