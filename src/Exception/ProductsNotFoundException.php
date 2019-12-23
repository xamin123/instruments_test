<?php
declare(strict_types=1);

namespace Xamin\App\Exception;

use Exception;
use function implode;
use function sprintf;

class ProductsNotFoundException extends Exception
{
    public function __construct(array $missingProductIds)
    {
        parent::__construct(sprintf('Products %s not found', implode(', ', $missingProductIds)));
    }
}