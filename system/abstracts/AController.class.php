<?php
/**
 * Superclass for all controllers.
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

abstract class AController
{
    protected $request_uri = null;
    protected $query_string = null;
    protected $request_path = null;
    protected $get_params = null;
    protected $user_agent = null;
    protected $client_language = null;
    protected $server_name = null;
    protected $client_ip = null;
    protected $request_method = null;
    protected $referer = null;

    public function __construct()
    {
        $this->request_uri = Registry::get('http.request_uri');
        $this->query_string = Registry::get('http.query_string');
        $this->request_path = Registry::get('http.request_path');
        $this->get_params = Registry::get('http.get_params');
        $this->user_agent = Registry::get('http.user_agent');
        $this->client_language = Registry::get('http.client_language');
        $this->server_name = Registry::get('http.server_name');
        $this->client_ip = Registry::get('http.client_ip');
        $this->request_method = Registry::get('http.request_method');
        $this->referer = Registry::get('http.referer');
        Container::bind(get_class($this), $this);
    }

    public function __get($alias)
    {
        if (!Container::isBindExists($alias)) {
            Container::bind($alias);
        }

        return Container::make($alias);
    }

    abstract public function run();
}
