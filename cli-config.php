<?php
declare(strict_types=1);

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Xamin\App\App;

require __DIR__.'/vendor/autoload.php';
$config = require __DIR__.'/config/config.php';
$entityManager = App::createEntityManager($config);
return ConsoleRunner::createHelperSet($entityManager);
