<?php

return array(
    
    'default' => array(
        'expiration' => 7200,
        'name' => 'PAN_SID',
        'cookieExpire' => 0,
        'cookiePath' => '/',
        'cookieSecure' => false,
        'cookieDomain' => '',
        'driver' => 'native', /* The option is 'native', 'cookie', cache or 'database' */
        'driverConnection' => 'default',
        'storageName' => 'sessions',
        'isEncrypt' => false,
        'secretKey' => '123'
    ),
);