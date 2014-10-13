<?php
/**
 * Abstract View class.
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

abstract class AView
{
    protected static $global_data = array();
    protected $data = array();
    protected $content = '';
    protected $layout = 'default';
    protected $ob_started = false;

    abstract public function render();

    public function assign($key, $value = null)
    {
        if (Lib::chkArr($key)) {

            foreach ($key as $arr_key => $arr_value) {
                $this->data[$arr_key] = $arr_value;
            }

        } elseif (Lib::chkStr($key)) {
            $this->data[$key] = $value;
        }

        return $this;
    }

    public function assignGlobal($key, $value = null)
    {
        if (Lib::chkArr($key)) {

            foreach ($key as $arr_key => $arr_value) {
                static::$global_data[$arr_key] = $arr_value;
            }

        } elseif (Lib::chkStr($key)) {
            static::$global_data[$key] = $value;
        }

        return $this;
    }

    public function bind($key, &$value)
    {
        $this->data[$key] =& $value;

        return $this;
    }

    public function bindGlobal($key, &$value)
    {
        static::$global_data[$key] =& $value;

        return $this;
    }

    public function clearAssigns()
    {
        $this->data = array();

        return $this;
    }

    public function clearGlobalAssigns()
    {
        static::$global_data = array();

        return $this;
    }

    protected function addContent($content = null)
    {
        if (!is_null($content)) {
            $this->content .= $content;
        }
    }

    public function getContent()
    {
        $content=$this->content;
        $this->content = '';
        ob_end_clean();
        $this->ob_started = false;
        $this->data = array();

        return $content;
    }

    protected function buildTplFileName($tpl_name)
    {
        return TPL_DIR . '/' . $this->layout . '/' . $tpl_name . '.tpl.php';
    }

    protected function includeTpl($tpl_name, $layout = null)
    {
        if (empty($tpl_name)) {
            throw new FException(Lang::getMessage('system.core.template_not_provided'));
        }

        if (empty($layout)) {
            $this->layout = 'default';

        } else {
            $this->layout = $layout;
        }

        $template_file = $this->buildTplFileName($tpl_name);

        if (Lib::chkFile($template_file)) {

            if (Lib::chkArr(static::$global_data)) {
                extract(static::$global_data, EXTR_OVERWRITE | EXTR_REFS);
            }

            if (Lib::chkArr($this->data)) {
                extract($this->data, EXTR_OVERWRITE);
            }

            if (!$this->ob_started) {
                ob_start();
                $this->ob_started = true;
            }

            include $template_file;

            $buffer_content = ob_get_contents();
            ob_clean();
            $this->addContent($buffer_content);

        } else {
            throw new FException(Lang::getMessage('system.core.template_not_found', array($template_file)));
        }
    }

    public function __get($alias)
    {
        if (!Container::isBindExists($alias)) {
            Container::bind($alias);
        }

        return Container::make($alias);
    }
}
