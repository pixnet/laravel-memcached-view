<?php

/**
 *  BladeCompiler
 *
 *  Rewrite constructor for receiving additional variable $local_storage to load blade file from `path`
 *  rather than use $files to load even after we replacing the Filesystem with memcached
 */

namespace PIXNET\MemcachedView\Compilers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler as IlluminateBladeCompiler;

class BladeCompiler extends IlluminateBladeCompiler
{

    /**
     * Filesystem $local_storage filesystem for loading blade file
     * 
     * @var mixed
     */
    public $local_storeage;

    public function __construct(Filesystem $files, Filesystem $local_storeage, $cache_path)
    {
        $this->files = $files;
        $this->local_storage = $local_storeage;
        $this->cachePath = $cache_path;
    }

    /**
     * Compile the view at the given path.
     *
     * @param  string  $path
     * @return void
     */
    public function compile($path = null)
    {
        if ($path) {
            $this->setPath($path);
        }

        if (! is_null($this->cachePath)) {
            $contents = $this->compileString($this->local_storage->get($this->getPath()));

            $this->files->put($this->getCompiledPath($this->getPath()), $contents);
        }
    }
}
