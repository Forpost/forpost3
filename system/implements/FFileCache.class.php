<?php

/**
 * Product: Forpost3
 * Author: Dmitriy Yuriev
 * Date: 21.08.13
 * License: MIT
 *
 * System registry class to store data in runtime memory.
 **/
class FFileCache implements IStorage
{
    protected $cache_dir = null;

    public function __construct($cache_dir = null)
    {
        $this->cache_dir = isset($cache_dir) ? $cache_dir : Lib::mkPath(array('application', 'cache', 'data'));

        if (!is_dir($this->cache_dir)) {
            if (false == mkdir($this->cache_dir, octdec(Config::get('sys.dir_chmod')), true)) {
                throw new FException('Can`t create cache directory ' . $this->cache_dir);
            }

            @chmod($this->cache_dir, octdec(Config::get('sys.dir_chmod')));
        }
    }

    protected function makeKey($key)
    {
        return sha1($key);
        //return $key;
    }

    protected function write($file, $data)
    {

        $_dir = dirname($file);
        //Lib::varDump($_dir); die();

        if (!Lib::chkDirWrite($_dir)) {
            if (false == mkdir($_dir, octdec(Config::get('sys.dir_chmod')), true)) {
                throw new Exception('Can`t create cache subdirectory. Check permissions on cache directory.');
            }
        }

        return file_put_contents($file, $data, LOCK_EX) ? true : false;
    }

    protected function read($file)
    {
        return file_get_contents($file);
    }

    protected function getCacheFilePath($key)
    {
        $_key = $this->makeKey($key);
        $_dir = substr($_key, 0, 3);

        return $this->cache_dir . DS . $_dir . DS . $_key;
    }

    /** Is there a key in storage? */
    public function hasKey($key)
    {
        return (Lib::chkFile($this->getCacheFilePath($key)) && $this->isValidTTL($key)) ? true : false;
    }

    /** Writes data in runtime storage. TTL parameter is not in use. */
    public function set($key, $data, $ttl = null)
    {
        if (is_null($ttl)) {
            $ttl = Config::get('cache.default_ttl');
        }
        $data = serialize(array(time() + $ttl, $data));

        return $this->write($this->getCacheFilePath($key), $data);
    }

    protected function isValidTTL($key)
    {
        return (file_get_contents($this->getCacheFilePath($key), false, null, 11, 10) - time()) > 0 ? true : false;
    }

    /** Adds data in runtime storage if key not exists. */
    public function add($key, $data, $ttl = null)
    {

        if (!$this->hasKey($key)) {
            $this->set($key, $data, $ttl);

            return true;
        }

        return false;
    }

    /** Replaces data in runtime storage if key exists. */
    public function replace($key, $data, $ttl = null)
    {
        if ($this->hasKey($key)) {
            $this->set($key, $data, $ttl);

            return true;
        }

        return false;
    }

    /** Reads data from runtime storage */
    public function get($key)
    {
        $data = null;

        if ($this->hasKey($key)) {
            $data = unserialize($this->read($this->getCacheFilePath($key)));
            if ($data !== false && Lib::chkArr($data)) {
                return $data[1];
            }
        }

        $this->delete($key);

        return false;
    }

    public function show()
    {
        return false;
    }

    public function delete($key)
    {
        @unlink($this->getCacheFilePath($key));
    }

    public function flush()
    {
        foreach (Lib::getFilesFromDir($this->cache_dir) as $file) {
            @unlink($file[0]);
        }
    }
}
