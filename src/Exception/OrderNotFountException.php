<?php
declare(strict_types=1);

namespace Xamin\App\Exception;

use Exception;
use function sprintf;

class OrderNotFountException extends Exception
{
    public function __construct(int $orderId)
    {
        parent::__construct(sprintf('Order #%d not found', $orderId));
    }
}