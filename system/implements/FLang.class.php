<?php
/*
* @author: Dmitriy Yuriev <coolkid00@gmail.com>
* @product: Forpost3
* @version: 3.1
* @release date: 04.06.2014
* @development started: 21.08.2013
* @license: GNU AGPLv3
*
* Facade for Lang core class.
*/

if (!defined('FORPOST_VALID')) {
    header('HTTP/1.1 403 Forbidden', 403);
    header('X-Powered-By: Apache 2.2.22');
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this URI on this server.</p></body></html>');
}

class FLang
{
    protected $args = array();
    protected $lang = null;
    protected $messages = array();

    public function __construct()
    {
        $args = func_get_args();

        if (Lib::chkArr($args)) {
            $this->args = $args[0];
        }

        $this->lang = isset($this->args[0]) ? $this->args[0] : Config::get('app.default_language');
    }

    public function getLanguage()
    {
        return $this->lang;
    }

    public function setLanguage($lang)
    {
        $this->lang = $lang;
    }

    public function getMessage($msg_code, $replaces = array())
    {

        $lang_file_path = explode('.', $msg_code);
        $lang_file = ROOT_DIR . '/' . $lang_file_path[0] . '/lang/' . $this->lang . '/' . $lang_file_path[1] . '.lang.php';

        if (!Lib::chkArr($this->messages[$lang_file_path[0]][$this->lang][$lang_file_path[1]])) {

            if (Lib::chkFile($lang_file)) {
                $this->messages[$lang_file_path[0]][$this->lang][$lang_file_path[1]] = Lib::loadArrayFile($lang_file);
            }
        }

        $message_arr = $this->messages[$lang_file_path[0]][$this->lang][$lang_file_path[1]][$lang_file_path[2]];

        if (Lib::chkArr($replaces)) {
            $message = str_replace($message_arr[1], $replaces, $message_arr[0]);
        } else {
            $message = $message_arr[0];
        }

        return $message;
    }
}
