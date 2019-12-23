<?php
declare(strict_types=1);

namespace Xamin\App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Xamin\App\Entity\Product;

class ProductRepository extends EntityRepository
{
    /**
     * @param array $ids
     *
     * @return Product[]
     */
    public function findProducts(array $ids): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.id IN (:ids)')
            ->setParameter('ids', $ids);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Product $product
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Product $product): void
    {
        if (!$this->_em->contains($product)) {
            $this->_em->persist($product);
        }
        $this->_em->flush();
    }

    /**
     * @param Product[] $products
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveProducts(array $products): void
    {
        foreach ($products as $product) {
            if (!$this->_em->contains($product)) {
                $this->_em->persist($product);
            }
        }
        $this->_em->flush();
    }
}