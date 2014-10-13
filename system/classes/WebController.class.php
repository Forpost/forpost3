<?php
/**
 * WebController processes all web requests.
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

class WebController extends AController
{
    protected $controller = null;
    protected $action = null;
    protected $params = array();
    protected $controller_postfix = 'Controller';
    protected $action_prefix = 'action';

    public function __construct()
    {
        parent::__construct();
        $this->loadFromRegistry();
        $this->checkAccessByIP();
    }

    protected function loadFromRegistry()
    {
        $this->controller = Registry::get('app.controller', 'index');
        $this->action = Registry::get('app.action', 'index');
        $this->params = Registry::get('app.params', array());
    }

    protected function checkAccessByIP()
    {
        if ($networks = Config::get('app.permitted_networks')) {

            if (!Lib::netCompare($networks, $this->client_ip)) {
                Output::show403();
            }
        }
    }

    public function run()
    {
        DebugBar::getCollector('time')->startMeasure('webcontroller', 'Web controller working');
        $this->findControllerActionParams();
        $response = $this->runController();
        DebugBar::getCollector('time')->stopMeasure('webcontroller');

        if (is_object($response) && $response instanceof FResponse) {
            DebugBar::getCollector('time')->startMeasure('response', 'Generating response');
            Output::addHeaders($response->getHeaders());
            Output::setContent($response->getContent());
            Output::setCharset($response->getCharset());
            Output::setContentType($response->getType());
            DebugBar::getCollector('time')->stopMeasure('response');
            Output::showPage();
        }
    }

    protected function findControllerActionParams()
    {
        $skip_url_part = (int) Config::get('app.skip_url_parts');

        if ($skip_url_part > 0) {
            $this->request_path = '/' . implode('/', array_slice(explode('/', $this->request_path), $skip_url_part + 1));
        }

        $dynamic_route = Router::checkDynamicRoute($this->request_path);

        if (Config::get('app.enable_static_routes')) {
            $static_route = Router::checkStaticRoute($this->request_path);
        }

        /* If static routes enabled and static route was found  */
        if (Config::get('app.enable_static_routes') && !empty($static_route)) {

            $this->controller = Config::get('app.static_controller');
            $this->action = Config::get('app.static_action');
            Registry::add('app.static_page_id', $static_route);

        } else {

            if (false !== $dynamic_route) {
                $path = Lib::explodePath($dynamic_route);
            } else {
                $path = Lib::explodePath($this->request_path);
            }

            if (Lib::chkArrKey(0, $path) && Lib::chkStr($path[0])) {
                $this->controller = strtolower($path[0]);
            }

            if (Lib::chkArrKey(1, $path) && Lib::chkStr($path[1])) {
                $this->action = strtolower($path[1]);
            }

            if (Lib::chkArr($path) && count($path) > 2) {
                $this->params = array_map(
                    function ($param) {

                        return urldecode($param);
                    },
                    array_slice($path, 2)
                );
            }
        }

        $this->save2Registry();
    }

    protected function save2Registry()
    {
        Registry::set('app.controller', $this->controller);
        Registry::set('app.action', $this->action);
        Registry::set('app.params', $this->params);
    }

    protected function runController()
    {
        $controller_name = ucfirst($this->controller) . $this->controller_postfix;
        $controller_method = $this->action_prefix . ucfirst($this->action);
        Event::fire('app.on_web_controller_loading', array($controller_name, $controller_method));

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

    protected function onControllerNotFound($controller_class)
    {
        if (Config::get('app.on_controller_not_found') == 'exception') {

            throw new FException(Lang::getMessage('system.core.controller_not_found', array($controller_class)));

        } elseif (Config::get('app.on_controller_not_found') == 'http_404') {

            Output::show404(Input::SERVER('REQUEST_URI'));

        } elseif (Config::get('app.on_controller_not_found') == 'http_403') {

            Output::show403(Input::SERVER('REQUEST_URI'));
        }
    }

    protected function onMethodNotFound($controller_class,$controller_method)
    {
        if (Config::get('app.on_method_not_found') == 'exception') {

            throw new FException(Lang::getMessage('system.core.method_not_found_in_controller', array($controller_method, $controller_class)));

        } elseif (Config::get('app.on_method_not_found') == 'http_404') {

            Output::show404(Input::SERVER('REQUEST_URI'));

        } elseif (Config::get('app.on_method_not_found') == 'http_403') {

            Output::show403(Input::SERVER('REQUEST_URI'));
        }
    }
}
