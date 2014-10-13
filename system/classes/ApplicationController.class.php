<?php
/**
 * Global application controller.
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

class ApplicationController
{
    protected $method = null;

    public function __construct()
    {
        Event::fire('app.on_starting');
        DebugBar::getCollector('time')->startMeasure('frontcontroller', 'Front controller working');

        if (Lib::chkStr(Input::SERVER('REQUEST_METHOD'))) {
            $this->method = Input::SERVER('REQUEST_METHOD');
        }

        if (php_sapi_name() == 'cli') {
            $this->method = 'CLI';
        }
    }

    public function getFrontController()
    {
        if (in_array($this->method,Config::get('app.allowed_methods'))) {
            Event::fire('app.on_front_loading', array($this->method));

            if ($this->method != 'CLI') {

                Input::parseHttpRequest();
                DebugBar::getCollector('time')->stopMeasure('frontcontroller');

                return new WebController();
            } else {
                Input::parseCliRequest();
                DebugBar::getCollector('time')->stopMeasure('frontcontroller');

                return new CliController();
            }

        } else {
            Event::fire('app.on_method_not_allowed', array($this->method));
            throw new FException(Lang::getMessage('system.core.method_not_allowed', array($this->method)));
        }
    }
}
