<?php
/*
* @author: Dmitriy Yuriev <coolkid00@gmail.com>
* @product: Forpost3
* @version: 3.1
* @release date: 04.06.2014
* @development started: 21.08.2013
* @license: GNU AGPLv3
*
* Config class loads and process configuration files.
*/

if (!defined('FORPOST_VALID')) {
    header('HTTP/1.1 403 Forbidden', 403);
    header('X-Powered-By: Apache 2.2.22');
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1>
    <p>You don\'t have permission to access ' . basename(__FILE__) . ' on this server.</p></body></html>');
}

class FConfig
{
    protected $config = array();
    protected $loaded_configs = array();

    /** Loads configuration file */
    public function load($config_file)
    {
        if (!in_array($config_file, $this->loaded_configs)) {

            $file_parts = explode('.', $config_file);
            $file = ROOT_DIR . '/' . $file_parts[0] . '/config/' . $file_parts[1] . '.conf.php';

            if (!$tmp_config = Lib::loadArrayFile($file)) {
                throw new FException(Lang::getMessage('system.core.cant_load_config_file', array($file)));
            }

            $this->config = array_merge($this->config, $tmp_config);
            $this->loaded_configs[] = $config_file;
        }
    }

    /** Get setting from array of settings. */
    public function get($setting = null, $default_value = null)
    {
        if (is_null($setting)) {
            return $this->config;
        } else {
            return isset($this->config[$setting]) ? $this->config[$setting] : $default_value;
        }
    }

    /** Adds setting to array of settings. */
    public function set($setting, $value)
    {
        if (!is_null($setting)) {
            $this->config[$setting] = $value;
        }
    }
}
