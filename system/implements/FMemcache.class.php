<?php

/**
 * Created by PhpStorm.
 * User: CoolKid
 * Date: 19.01.14
 * Time: 1:58
 */
class FMemCache implements IStorage
{
    protected $instance = null;

    public function __construct(Memcache $instance)
    {
        $this->instance = $instance;
    }

    public function add($key, $data, $ttl = null)
    {
        $ttl = is_null($ttl) ? 0 : $ttl;

        return $this->instance->add($key, $data, null, $ttl);
    }

    public function hasKey($key)
    {
        if ($this->get($key) === false) {
            return false;
        } else {
            return true;
        }
    }

    public function delete($key)
    {
        return $this->instance->delete($key);
    }

    public function flush()
    {
        $this->instance->flush();
    }

    public function get($key)
    {
        return $this->instance->get($key);
    }

    public function replace($key, $data, $ttl = null)
    {
        $ttl = is_null($ttl) ? 0 : $ttl;
        $this->instance->replace($key, $data, null, $ttl);
    }

    public function set($key, $data, $ttl = null)
    {
        $ttl = is_null($ttl) ? 0 : $ttl;

        return $this->instance->set($key, $data, null, $ttl);
    }

    public function show()
    {
        return false;
    }

}
