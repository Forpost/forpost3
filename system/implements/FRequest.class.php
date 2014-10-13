<?php

/**
 * Created by PhpStorm.
 * User: d.yuriev
 * Date: 22.07.14
 * Time: 18:04
 */
class FRequest
{
    protected $proxy = null;
    protected $proxy_auth = null;
    protected $user_agent = 'Forpost/3 (PHP5; cURL;)';
    protected $http_referer = 'http://localhost/';
    protected $response_http_code = null;
    protected $response_http_body = null;
    protected $response_http_headers = null;
    protected $response_cookies = array();
    protected $request_cookies = array();
    protected $response_raw_headers = null;
    
    protected function buildCookie(array $cookie_array)
    {
        foreach ($cookie_array as $key => $value) {
            $cookies .= "$key=$value; ";
        }   

        return rtrim(trim($cookies), ';');
    }

    public function getResponseCode()
    {
        $response_http_code = $this->response_http_code;
        unset($this->response_http_code);

        return $response_http_code;
    }
    
    public function setUserAgent($user_agent)
    {
        $this->user_agent = $user_agent;
        
        return $this;
    }
    
    public function setReferer($referer)
    {
        $this->http_referer= $referer;

        return $this;
    }
    
    public function setRequestCookies(array $request_cookies = array())
    {
        $this->request_cookies = $request_cookies;
        
        return $this;
    }

    public function getResponseBody()
    {
        $response_http_body = $this->response_http_body;
        unset($this->response_http_body);

        return $response_http_body;
    }
    
    public function getResponseHeaders()
    {
        $response_http_headers = $this->response_http_headers;
        unset($this->response_http_headers);

        return $response_http_headers;
    }

    public function getResponseRawHeaders()
    {
        $response_http_headers = $this->response_raw_headers;
        unset($this->response_raw_headers);

        return $response_http_headers;
    }
    
    public function getResponseCookies()
    {
        $response_cookies = $this->response_cookies;
        unset($this->response_cookies );

        return $response_cookies;
    }        
    
    protected function parseHeaders($headers_str)
    {
        $headers = array();

        foreach (explode("\n", $headers_str) as $line) {
            $line = trim($line);
            
            if(!empty($line)) {
                $key = trim($arr[0]);
                $value = trim($arr[1]);
                $arr = explode(':', $line);

                if (Lib::chkArrKey($key, $headers)) {
                    
                    if (Lib::chkArr($headers[$key])) {
                        $headers[$key][] = $value;
                    } else {
                        $headers[$key] = array($headers[$key], $value);
                    }

                } else {
                    $headers[$key] = $value;
                }
            }
        }
        
        unset($headers['']);
        
        return $headers;
    }

    public function get($url, array $curl_options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($ch, CURLOPT_REFERER, $this->http_referer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_ENCODING, 'deflate');
        
        if (Lib::chkArr($this->request_cookies))
        {
            curl_setopt($ch, CURLOPT_COOKIE, $this->buildCookie($this->request_cookies));
        }

        if (Lib::chkArr($curl_options)) {
            curl_setopt_array($ch, $curl_options);
        }

        if (!empty($this->proxy)) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
        }

        if (!is_null($this->proxy_auth)) {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth);
        }

        $response = curl_exec($ch);
        $this->response_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);
        $response_http_headers = substr($response, 0, $header_size);
        $this->response_raw_headers = $response_http_headers;
        $this->response_http_headers = $this->parseHeaders($response_http_headers);
        $this->response_http_body = substr($response, $header_size);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response_http_headers, $cookies_match);
        $this->response_cookies = array();
        
        foreach ($cookies_match[1] as $cookie_str) {
            $_cookie_arr = explode('=', $cookie_str);
            $this->response_cookies[$_cookie_arr[0]] = $_cookie_arr[1];
        }

        return $this;
    }

    public function post($url, array $post_parameters = array(), array $curl_options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($ch, CURLOPT_REFERER, $this->http_referer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_ENCODING, 'deflate');
        
        if (Lib::chkArr($this->request_cookies))
        {
            curl_setopt($ch, CURLOPT_COOKIE, $this->buildCookie($this->request_cookies));
        }

        if (Lib::chkArr($curl_options)) {
            curl_setopt_array($ch, $curl_options);
        }

        if (!empty($this->proxy)) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
        }

        if (!is_null($this->proxy_auth)) {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth);
        }

        if (Lib::chkArr($post_parameters)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_parameters);
        }

        $response = curl_exec($ch);
        $this->response_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);
        $response_http_headers = substr($response, 0, $header_size);
        $this->response_raw_headers = $response_http_headers;
        $this->response_http_headers = $this->parseHeaders($response_http_headers);
        $this->response_http_body = substr($response, $header_size);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response_http_headers, $cookies_match);
        $this->response_cookies = array();
        
        foreach ($cookies_match[1] as $cookie_str) {
            $_cookie_arr = explode('=', $cookie_str);
            $this->response_cookies[$_cookie_arr[0]] = $_cookie_arr[1];
        }

        return $this;
    }

    public function controller($request_method, $controller_path, $post_parameters = array(), $curl_options = array())
    {
        $url = Config::get('app.main_domain') . '/' . trim(strtolower($controller_path), '/') . '/';
        $request_method = strtolower($request_method);

        if ($request_method == 'get') {
            $this->get($url, $curl_options);
        } elseif ($request_method == 'post') {
            $this->post($url, $post_parameters, $curl_options);
        }

        return $this;
    }
}
