<?php

/**
 * Created by PhpStorm.
 * User: CoolKid
 * Date: 04.08.14
 * Time: 3:05
 */
class FResponse
{

    protected $content = '';
    protected $headers = array();
    protected $code = 200;
    protected $type = 'text/html';
    protected $charset = 'utf-8';

    public function __construct()
    {
        return $this;
    }

    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    public function headers(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    public function code($code)
    {
        $this->code = $code;

        return $this;
    }

    public function charset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    public function type($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function getType()
    {
        return $this->type;
    }

} 