<?php
/**
 * CliController processes all cli requests.
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

class CliController extends AController
{
    protected $controller = null;
    protected $action = null;
    protected $params = array();
    protected $controller_postfix = 'ControllerCli';
    protected $action_prefix = 'action';

    public function __construct()
    {
        parent::__construct();
        $this->loadFromRegistry();
    }

    public function run()
    {
        $this->findControllerActionParams();
        $this->runController();
    }

    protected function runController()
    {
        $controller_name = ucfirst($this->controller) . $this->controller_postfix;
        $controller_method = $this->action_prefix . ucfirst($this->action);
        Event::fire('app.on_cli_controller_loading', array($controller_name, $controller_method));

        if (class_exists($controller_name) && !in_array($controller_name, array(
                'ApplicationController',
                'AController',
                'WebController',
                'CliController'
            ))) {

            if (method_exists($controller_name, $controller_method)) {

                if (method_exists($this->$controller_name, 'init')) {
                    $this->$controller_name->init();
                }

                return call_user_func_array(array($this->$controller_name, $controller_method), $this->params);

            } else {

                if (method_exists($controller_name, 'defaultAction')) {
                    Event::fire('app.on_default_method_found', array($controller_name,$controller_method));

                    return call_user_func_array(array($this->$controller_name, 'defaultAction'), $this->params);
                } else {
                    Event::fire('app.on_method_not_found', array($controller_name, $controller_method));
                    $this->onMethodNotFound($controller_name, $controller_method);
                }
            }
        } else {
            $this->onControllerNotFound($controller_name);
        }
    }

    protected function findControllerActionParams()
    {
        $argv = Registry::get('cli.params');

        if (Lib::chkArrKey(0, $argv) && Lib::chkStr($argv[0])) {
            $this->controller = strtolower($argv[0]);
        }

        if (Lib::chkArrKey(1, $argv) && Lib::chkStr($argv[1])) {
            $this->action = strtolower($argv[1]);
        }

        if (Lib::chkArr($argv) && count($argv) > 2) {

            $this->params = array_map(
                function ($param) {
                    return mb_strtolower($param);
                },
                array_slice($argv, 2)
            );
        }

        $this->save2Registry();
    }

    protected function save2Registry()
    {
        Registry::set('app.controller', $this->controller);
        Registry::set('app.action', $this->action);
        Registry::set('app.params', $this->params);
    }

    protected function loadFromRegistry()
    {
        $this->controller = Registry::get('app.controller', 'index');
        $this->action = Registry::get('app.action', 'index');
        $this->params = Registry::get('app.params', array());
    }

    protected function onMethodNotFound($controller_class, $controller_method)
    {
        throw new FException(Lang::getMessage('system.core.method_not_found_in_controller', array($controller_method, $controller_class)));
    }
}
