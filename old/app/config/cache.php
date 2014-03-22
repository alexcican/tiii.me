<?php

return array(
    
    'default' => array(
        'driver' => 'dummy',
    ),
    
    'apc' => array(
        'driver' => 'apc',
    ),
    
    'memcached' => array(
        'driver' => 'memcached',
        'server' => array(
            array(
                'host' => 'localhost',
                'port' => 11211,
                'persistent' => false,
            ),
        )
    ),
    
    'memcache' => array(
        'driver' => 'memcache',
        'server' => array(
            array(
                'host' => 'localhost',
                'port' => 11211,
                'persistent' => false,
            ),
        )
    ),
    
    'redis' => array(
        'driver' => 'redis',
        'server' => array(
            array(
                'host' => 'localhost',
                'port' => 6379,
                'persistent' => false,
                'timeout' => 2.5
            ),
        )
    ),
);