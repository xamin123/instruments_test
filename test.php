<?php

use GuzzleHttp\Client;

require __DIR__.'/vendor/autoload.php';
$guzzleClient = new Client();
$res = $guzzleClient->request('GET', 'https://ya.ru');
var_dump($res->getStatusCode());