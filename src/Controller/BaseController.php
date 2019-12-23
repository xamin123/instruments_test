<?php
declare(strict_types=1);

namespace Xamin\App\Controller;

use Xamin\App\Entity\User;

class BaseController
{
    private $currentUser;
    protected function getCurrentUser(): User
    {
        if (null === $this->currentUser) {
            $this->currentUser = new User(1, 'admin');
        }

        return $this->currentUser;
    }
}