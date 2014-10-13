<?php

/**
 * Product: Forpost3
 * Author: Dmitriy Yuriev
 * Date: 21.08.13
 * License: MIT
 *
 * System registry class to store data in runtime memory.
 **/
class FRegistry implements IStorage
{
    protected $storage = array();

    protected function makeKey($key)
    {
        return $key;
    }

    /** Is there a key in storage? */
    public function hasKey($key)
    {
        $_key = $this->makeKey($key);

        return array_key_exists($_key, $this->storage);
    }

    /** Writes data in runtime storage. TTL parameter is not in use. */
    public function set($key, $data, $ttl = null)
    {
        $_key = $this->makeKey($key);
        $this->storage[$_key]['data'] = $data;
    }

    /** Adds data in runtime storage if key not exists. */
    public function add($key, $data, $ttl = null)
    {
        if (!$this->hasKey($key)) {
            $this->set($key, $data);

            return true;
        }

        return false;
    }

    /** Replaces data in runtime storage if key exists. */
    public function replace($key, $data, $ttl = null)
    {
        if ($this->hasKey($key)) {
            $this->set($key, $data);

            return true;
        }

        return false;
    }

    /** Reads data from runtime storage */
    public function get($key, $default_value = null)
    {
        $_key = $this->makeKey($key);

        return $this->hasKey($key) ? $this->storage[$_key]['data'] : $default_value;
    }

    public function show()
    {
        return $this->storage;
    }

    public function delete($key)
    {
        $_key = $this->makeKey($key);
        unset($this->storage[$_key]);
    }

    public function flush()
    {
        unset($this->storage);
        $this->storage = array();
    }
}
