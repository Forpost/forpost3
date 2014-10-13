<?php

/**
 * Created by PhpStorm.
 * User: coolkid
 * Date: 03.12.13
 * Time: 22:48
 */
class FOutput
{
    protected $args = array();
    protected $charset = 'utf-8';
    protected $content_type = 'text/html';
    protected $headers = array();
    protected $content = '';

    public function __construct()
    {
        $args = func_get_args();

        if (Lib::chkArr($args)) {
            $this->args = $args[0];
        }
    }

    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    protected function addNoCacheHeaders()
    {
        $this->addHeader('Cache-Control: no-cache, no-store, must-revalidate');
        $this->addHeader('Pragma: no-cache');
        $this->addHeader('Expires: 0');
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function addContent($content)
    {
        $this->content .= $content;

        return $this;
    }

    public function setContentType($content_type)
    {
        $this->content_type = $content_type;

        return $this;
    }

    public function sendHeaders()
    {
        if (Lib::chkArr($this->headers)) {
            foreach ($this->headers as $header) {
                header($header);
            }
        }

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    protected function show($add_headers, $show_debug_panel, $allowed_ips)
    {
        if ($add_headers) {
            $this->addNoCacheHeaders();
            $this->addHeader('Content-type: ' . $this->content_type . '; charset=' . $this->charset);
            $this->addHeader('X-Powered-By: Forpost3');
            $this->sendHeaders();
        }

        if ($show_debug_panel) {
            DebugBar::addCollector(new \DebugBar\DataCollector\ConfigCollector(Registry::show(), 'Registry'));
            DebugBar::addCollector(new \DebugBar\DataCollector\ConfigCollector(Session::show(), 'Session'));
            DebugBar::getCollector('time')->addMeasure('Total execution time', START_TIME, microtime(true));
            Lib::renderErrorsInfo();

            if ((!Lib::chkArr($allowed_ips)) || (Lib::chkArr($allowed_ips) && Lib::netCompare(
                        $allowed_ips,
                        Registry::get('http.client_ip')
                    ))
            ) {
                $this->addContent("\n\n" . Lib::getPHPDebugAssets() . "\n" . Lib::getPHPDebugPanel());
            }
        }

        if (Config::get('sys.global_buffer_enable')) {
            ob_end_clean();
        }

        $this->printContent();
        exit(0);
    }

    public function printContent()
    {
        echo $this->getContent();
        flush();
    }

    public function showContent($add_headers = false)
    {
        $this->show($add_headers, false, array());
    }

    public function showPage()
    {
        $this->show(true, Config::get('app.show_debug_panel'), Config::get('app.debug_panel_allowed_ips'));
    }

    public function println($content)
    {
        echo $content . "\n";
    }

    public function addHeader($header)
    {
        $this->headers[] = $header;

        return $this;
    }

    public function addHeaders(array $headers)
    {
        foreach ($headers as $header) {
            $this->addHeader($header);
        }

        return $this;
    }

    public function show404($uri_path)
    {
        $this->setContent(
            "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n<p>The requested URL " . $uri_path . " was not found on this server.</p>\n</body></html>"
        );
        $this->addHeader('HTTP/1.1 404 Not Found');
        $this->addHeader('X-Powered-By: Apache');
        $this->sendHeaders();
        $this->showContent();
    }

    public function show403($uri_path)
    {
        $this->setContent(
            "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n<html><head>\n<title>403 Forbidden</title>\n</head><body>\n<h1>Forbidden</h1>\n<p>You don't have permission to access " . $uri_path . " on this server.</p>\n</body></html>"
        );
        $this->addHeader('HTTP/1.1 403 Forbidden');
        $this->addHeader('X-Powered-By: Apache');
        $this->sendHeaders();
        $this->showContent();
    }

    public function show401()
    {
        $this->setContent(
            "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n<html><head>\n<title>401 Unauthorized</title>\n</head><body>\n<h1>Authorization Required</h1>\n</body></html>"
        );
        $this->addHeader('HTTP/1.1 401 Unauthorized');
        $this->addHeader('WWW-Authenticate: Basic realm="Protected zone. Username and password are required"');
        $this->addHeader('X-Powered-By: Apache');
        $this->sendHeaders();
        $this->showContent();
    }
}
