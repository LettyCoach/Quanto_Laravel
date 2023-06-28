<?php

return [
    'mail' => [
        'host' => 'sv239.xbiz.ne.jp',
        'port' => 465,
        'username' => 'hosokawa@quanto.xbiz.jp',
        'password' => 'AutoQuote2021',
        'admin_email' => 'hosokawa@quanto.xbiz.jp',
        'admin_name' => 'hosokawa',
    ],
    'clientHost' => env('APP_URL') . "/front/",
    'cartHost' => env('APP_URL') . "/show/",
    'adminHost' => env('APP_URL'),
    'serverHost' => env('APP_URL') . "/",
];