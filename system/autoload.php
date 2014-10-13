<?php
/**
 * System autoloader.
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

/* Autoload for system core classes */
spl_autoload_register(function ($class) {
    $include_file = CLASSES_DIR . '/' . $class . '.class.php';

    if (is_file($include_file) && is_readable($include_file)) {
        require_once $include_file;
    }
});

/* Autoload for system core controllers */
spl_autoload_register(function ($class) {
    $include_file = SYS_CONTROLLERS_DIR . '/' . $class . '.php';

    if (is_file($include_file) && is_readable($include_file)) {
        require_once $include_file;
    }
});

/* Autoload for system core models */
spl_autoload_register(function ($class) {
    $include_file = SYS_MODELS_DIR . '/' . $class . '.php';

    if (is_file($include_file) && is_readable($include_file)) {
        require_once $include_file;
    }
});

/* Autoload for system core views */
spl_autoload_register(function ($class) {
    $include_file = SYS_VIEWS_DIR . '/' . $class . '.php';

    if (is_file($include_file) && is_readable($include_file)) {
        require_once $include_file;
    }
});

/* Autoload for system core helpers */
spl_autoload_register(function ($class) {
    $include_file = HELPERS_DIR . '/' . $class . '.class.php';

    if (is_file($include_file) && is_readable($include_file)) {
        require_once $include_file;
    }
});

/* Autoload for system core abstracts */
spl_autoload_register(function ($class) {
    $include_file = ABSTRACTS_DIR . '/' . $class . '.class.php';

    if (is_file($include_file) && is_readable($include_file)) {
        require_once $include_file;
    }
});

/* Autoload for system core facades */
spl_autoload_register(function ($class) {
    $include_file = FACADES_DIR . '/' . $class . '.class.php';

    if (is_file($include_file) && is_readable($include_file)) {
        require_once $include_file;
    }
});

/* Autoload for system core implements */
spl_autoload_register(function ($class) {
    $include_file = IMPLEMENTS_DIR . '/' . $class . '.class.php';

    if (is_file($include_file) && is_readable($include_file)) {
        require_once $include_file;
    }
});

/* Autoload for system core interfaces */
spl_autoload_register(function ($class) {
    $include_file = INTERFACES_DIR . '/' . $class . '.class.php';

    if (is_file($include_file) && is_readable($include_file)) {
        require_once $include_file;
    }
});

/* Autoload for application controllers */
spl_autoload_register(function ($class) {
    $include_file = CONTROLLERS_DIR . '/' . $class . '.php';

    if (is_file($include_file) && is_readable($include_file)) {
        require_once $include_file;
    }
});

/* Autoload for application models */
spl_autoload_register(function ($class) {
    $include_file = MODELS_DIR . '/' . $class . '.php';

    if (is_file($include_file) && is_readable($include_file)) {
        require_once $include_file;
    }
});

/** Autoload for application views */
spl_autoload_register(function ($class) {
    $include_file = VIEWS_DIR . '/' . $class . '.php';

    if (is_file($include_file) && is_readable($include_file)) {
        require_once $include_file;
    }
});

/** Autoload for components classes */
spl_autoload_register(function ($component) {
    $init_file = COM_DIR . '/' . $component. '/' . 'init.php';
    $include_file = COM_DIR . '/' . $component . '/' . 'index.php';

    if (is_file($init_file) && is_readable($init_file)) {
        require_once $init_file;
    }

    if (is_file($include_file) && is_readable($include_file)) {
        require_once $include_file;
    }
});
