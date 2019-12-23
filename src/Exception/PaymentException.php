<?php
declare(strict_types=1);

namespace Xamin\App\Exception;

use Exception;
use Throwable;

class PaymentException extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct('Payment error', 0, $previous);
    }
}