<?php

/**
 * Created by PhpStorm.
 * User: CoolKid
 * Date: 04.07.14
 * Time: 0:36
 */
class FRedisCache implements IStorage
{
    protected $instance = null;

    public function __construct(Redis $instance)
    {
        $this->instance = $instance;
    }

    public function add($key, $data, $ttl = null)
    {
        $ttl = is_null($ttl) ? 0 : $ttl;

        if (!$this->hasKey($key)) {
            return $this->set($key, $data, $ttl);
        }

        return false;
    }

    public function hasKey($key)
    {
        return $this->instance->exists($key);
    }

    public function delete($key)
    {
        return $this->instance->delete($key);
    }

    public function flush()
    {
        $this->instance->flushAll();
    }

    public function get($key)
    {
        return $this->instance->get($key);
    }

    public function replace($key, $data, $ttl = null)
    {
        $ttl = is_null($ttl) ? 0 : $ttl;

        if ($this->hasKey($key)) {
            return $this->set($key, $data, $ttl);
        }

        return false;
    }

    public function set($key, $data, $ttl = null)
    {
        $ttl = is_null($ttl) ? 0 : $ttl;

        return $this->instance->set($key, $data, $ttl);
    }

    public function show()
    {
        return false;
    }
}
