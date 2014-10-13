&lt;?php

/* Protection against direct call */
if (!defined('FORPOST_VALID')) {
    header('HTTP/1.1 404 Not Found', 404);
    header('X-Powered-By: PHP', true);
die("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL " . htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES) . " was not found on this server.</p>\n</body></html>");
}

class <?=ucfirst($model_name).'Model'?> extends AModel
{
    public function getSomeData()
    {
        return array();
    }
}
