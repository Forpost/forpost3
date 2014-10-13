<?php

/**
 * Created by JetBrains PhpStorm.
 * User: CoolKid
 * Date: 18.10.13
 * Time: 0:51
 * To change this template use File | Settings | File Templates.
 */
class FInput
{
    protected $get = null;
    protected $post = null;
    protected $cookie = null;
    protected $server = null;
    protected $files = null;
    protected $request = null;
    protected $raw_post = null;
    protected $args = array();

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $this->raw_post = $_POST;
        $this->cookie = $_COOKIE;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->request = $_REQUEST;

        if (Config::get('app.filter_vars')) {
            $this->filterVars();
        }

        if (Config::get('app.unset_global_vars')) {
            unset($_GET, $_POST, $_COOKIE, $_FILES, $_REQUEST);
        }
    }

    private function filterVars()
    {
        array_walk(
            $this->get,
            function (&$val, $key) {
                $val = Lib::html2text($val);
            }
        );
        array_walk(
            $this->post,
            function (&$val, $key) {
                $val = Lib::html2text($val);
            }
        );
        array_walk(
            $this->cookie,
            function (&$val, $key) {
                $val = Lib::html2text($val);
            }
        );
        array_walk(
            $this->request,
            function (&$val, $key) {
                $val = Lib::html2text($val);
            }
        );
    }

    public function GET($param = null)
    {
        if (is_null($param)) {
            return $this->get;
        } else {
            return isset($this->get[$param]) ? $this->get[$param] : null;
        }
    }

    public function POST($param = null)
    {
        if (is_null($param)) {
            return $this->post;
        } else {
            return isset($this->post[$param]) ? $this->post[$param] : null;
        }
    }

    public function RAW_POST($param = null)
    {
        if (is_null($param)) {
            return $this->raw_post;
        } else {
            return isset($this->raw_post[$param]) ? $this->raw_post[$param] : null;
        }
    }

    public function COOKIE($param = null)
    {
        if (is_null($param)) {
            return $this->cookie;
        } else {
            return isset($this->cookie[$param]) ? $this->cookie[$param] : null;
        }
    }

    public function REQUEST($param = null)
    {
        if (is_null($param)) {
            return $this->request;
        } else {
            return isset($this->request[$param]) ? $this->request[$param] : null;
        }
    }

    public function SERVER($param = null)
    {
        if (is_null($param)) {
            return $this->server;
        } else {
            return isset($this->server[$param]) ? $this->server[$param] : null;
        }
    }

    public function FILES($param = null)
    {
        if (is_null($param)) {
            return $this->files;
        } else {
            return isset($this->files[$param]) ? $this->files[$param] : null;
        }
    }

    public function isHTTPS()
    {
        $https = self::SERVER('HTTPS');

        return (!empty($https) && $https != 'off') ? true : false;
    }

    public function parseHttpRequest()
    {
        $request_uri = parse_url(self::SERVER('REQUEST_URI'));
        parse_str(self::SERVER('QUERY_STRING'), $get_params);
        Registry::add('http.request_uri', self::SERVER('REQUEST_URI'));
        Registry::add('http.query_string', self::SERVER('QUERY_STRING'));
        Registry::add('http.request_path', $request_uri['path']);
        Registry::add('http.get_params', $get_params);
        Registry::add('http.user_agent', self::SERVER('HTTP_USER_AGENT'));
        Registry::add('http.client_language', self::SERVER('HTTP_ACCEPT_LANGUAGE'));
        Registry::add('http.server_name', Lib::getHost());
        Registry::add('http.client_ip', self::SERVER('REMOTE_ADDR'));
        Registry::add('http.request_method', self::SERVER('REQUEST_METHOD'));
        Registry::add('http.referer', self::SERVER('HTTP_REFERER'));
        Registry::add('http.is_https', $this->isHTTPS());
    }

    public function parseCliRequest()
    {
        $argv = array_slice($this->server['argv'], 1);
        Registry::add('cli.script_name', $this->server['argv'][0]);
        Registry::add('cli.params', $argv);
    }
}
