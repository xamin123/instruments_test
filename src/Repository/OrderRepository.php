<?php
declare(strict_types=1);

namespace Xamin\App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Xamin\App\Entity\Order;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 */
class OrderRepository extends EntityRepository
{
    /**
     * @param Order $order
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Order $order): void
    {
        if (!$this->_em->contains($order)) {
            $this->_em->persist($order);
        }
        $this->_em->flush();
    }
}