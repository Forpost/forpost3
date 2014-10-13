<?php

/**
 * Created by JetBrains PhpStorm.
 * User: CoolKid
 * Date: 22.12.13
 * Time: 14:27
 * To change this template use File | Settings | File Templates.
 */
class FSession implements IStorage
{
    protected $reg_name = null;

    public function __construct($reg_name)
    {
        $this->reg_name = $reg_name;
        if (!array_key_exists($this->reg_name, $_SESSION)) {
            $_SESSION[$this->reg_name] = array();
        }
    }

    /** Return generated key for session storage operations */
    protected function makeKey($key)
    {
        return $key;
    }

    /** Is there a key in storage? */
    public function hasKey($key)
    {
        $_key = $this->makeKey($key);

        return array_key_exists($_key, $_SESSION[$this->reg_name]);
    }

    /** Reads data from session storage */
    public function get($key, $default_value = null)
    {
        $_key = $this->makeKey($key);

        return $this->hasKey($key) ? $_SESSION[$this->reg_name][$_key]['data'] : $default_value;
    }

    /** Writes data in session storage. */
    public function set($key, $data, $ttl = null)
    {
        $_key = $this->makeKey($key);
        $_SESSION[$this->reg_name][$_key]['time'] = time();
        $_SESSION[$this->reg_name][$_key]['data'] = $data;
    }

    /** Adds data in session storage if key not exists. */
    public function add($key, $data, $ttl = null)
    {
        if (!$this->hasKey($key)) {
            $this->set($key, $data, $ttl);

            return true;
        }

        return false;
    }

    /** Replaces data in session storage if key exists. */
    public function replace($key, $data, $ttl = null)
    {
        if ($this->hasKey($key)) {
            $this->set($key, $data, $ttl);

            return true;
        }

        return false;
    }

    /** Deletes data by key from session storage. */
    public function delete($key)
    {
        $_key = $this->makeKey($key);
        unset($_SESSION[$this->reg_name][$_key]);
    }

    /** Deletes all data from session storage. */
    public function flush()
    {
        unset($_SESSION[$this->reg_name]);
        $_SESSION[$this->reg_name] = array();
    }

    public function show()
    {
        return $_SESSION[$this->reg_name];
    }
}
