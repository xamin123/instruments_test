<?php
declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Xamin\App\App;

require __DIR__.'/../vendor/autoload.php';

$config = require __DIR__.'/../config.php';

$kernel = (new App($config))->getKernel();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
