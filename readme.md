# LaravelMemcachedView 

[![Packagist License][badge_license]](LICENSE.md) [![For Laravel 5][badge_laravel]][link-github-repo]
[![Github Issues][badge_issues]][link-github-issues]
[![Packagist][badge_package]][link-packagist]
[![Packagist Release][badge_release]][link-packagist]
[![Packagist Downloads][badge_downloads]][link-packagist]

*By [PIXNET &copy;](http://www.pixnet.net)*

Memcached base for blade cache instead of file base.

# install instructions

1. install by composer

        $ composer require pixnet/laravel-memcached-view
    
1. add service provider in config/app.php

        'providers' => [
			//...
            PIXNET\MemcachedView\Providers\ViewServiceProvider::class,
            //...
        ],
1. add alias in config/app.php

		'alias' => [
			//...
			'MemcacheStorage'      => PIXNET\MemcachedView\Filesystem\MemcacheStorage::class,
			//...
		]
1. set up your memcache setting in config/cache.php

		return [
			// ...
			'store' => [
				// ...
				'memcached' => [
					'driver' => 'memcached',
					'servers' => [
						[
							'host' => 'YOUR_MEMCACHED_SERVER_IP',
							'port' => 'MEMCACHED_PORT',
							'weight' => 100
						]
					]
				]
			]
		]
1. here you go