<?php
declare(strict_types=1);

namespace Xamin\App\Exception;

use Exception;
use function sprintf;

class UserNotFoundException extends Exception
{
    public function __construct(int $userId)
    {
        parent::__construct(sprintf('User %d not found', $userId));
    }
}