<?php

/**
 *  ViewServiceProvider
 *
 *  The major concept of this service provider is to replace CompilerEngine, PhpEngine and BladeCompiler
 *  We need these replacement to fix some I/O issue
 *
 */

namespace PIXNET\MemcachedView\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\ViewServiceProvider as LaravelViewProvider;
use PIXNET\MemcachedView\Engines\CompilerEngine;
use PIXNET\MemcachedView\Engines\PhpEngine;
use PIXNET\MemcachedView\Compilers\BladeCompiler;

class ViewServiceProvider extends LaravelViewProvider
{
    /**
     * registerBladeEngine
     *
     * Replace filesystem with MemcacheStorage for BladeCompiler
     *
     * @return void
     */
    public function registerBladeEngine($resolver)
    {
        $app = $this->app;
        $storage = $app->make('MemcacheStorage');
        $app->singleton('blade.compiler', function ($app) use ($storage) {

            $cache = $app['config']['view.compiled'];

            return new BladeCompiler($storage, $app['files'], $cache);
        });

        $resolver->register('blade', function () use ($app, $storage) {
            return new CompilerEngine($app['blade.compiler'], $storage);
        });

    }

    /**
     * Register the PHP engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerPhpEngine($resolver)
    {
        $resolver->register('php', function () {
            return new PhpEngine;
        });
    }
}
