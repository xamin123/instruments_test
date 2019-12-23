<?php

return [
    'connection' => [
        'driver' => 'pdo_pgsql',
        'user' => 'postgres',
        'password' => '142857',
        'host' => 'localhost',
        'dbname' => 'instruments_test',
    ],
    'entitiesDir' => __DIR__.'/src/Entity'
];