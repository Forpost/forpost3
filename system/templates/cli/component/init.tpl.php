&lt;?php

/* Autoload for component`s classes */
spl_autoload_register(function ($class) {
    $include_file = __DIR__ . '/classes/' . $class. '.class.php';

    if(Lib::chkFile($include_file)) {
        require_once $include_file;
    }

});

/* Load functions library if exists */
$functions_file = __DIR__ . '/includes/functions.inc.php';

if (Lib::chkFile($functions_file)) {
    require_once $functions_file;
}
