<?php
return [
    'views.path' => dirname(__FILE__) . '/views',
    'db.host' => env('PHINX_DBHOST'),
    'db.user' => env('PHINX_DBUSER'),
    'db.pass' => env('PHINX_DBPASS'),
    'db.name' => env('PHINX_DBNAME'),
];