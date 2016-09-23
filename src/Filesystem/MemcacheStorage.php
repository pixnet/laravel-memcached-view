<?php

/**
 *  MemcacheStorage A filesystem with memcached base
 *
 *  We use cache.stores.memcached.servers for server configuration.
 *  Please make sure the configuration of memcached in config/cache.php is correct first.
 *
 */

namespace PIXNET\MemcachedView\Filesystem;

class MemcacheStorage extends \Illuminate\Filesystem\Filesystem
{

    protected $memcached;

    public function __construct()
    {
        $this->memcached = new \Memcached;
        $this->memcached->addServers(config('cache.stores.memcached.servers'));
    }

    public function exists($key)
    {
        return !empty($this->get($key));
    }

    public function get($key, $lock = false)
    {
        $value = $this->memcached->get($key);
        return $value ? $value['content'] : null;
    }

    public function put($key, $value, $lock = false)
    {
        return $this->memcached->set($key, ['content' => $value, 'modified' => time()]);
    }

    public function lastModified($key)
    {
        $value = $this->memcached->get($key);
        return $value ? $value['modified'] : null;
    }
}
