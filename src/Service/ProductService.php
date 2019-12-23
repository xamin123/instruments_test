<?php
declare(strict_types=1);

namespace Xamin\App\Service;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Xamin\App\Dto\CreateProduct;
use Xamin\App\Entity\Product;
use Xamin\App\Repository\ProductRepository;
use function array_map;
use function count;

class ProductService
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param CreateProduct[] $createProducts
     *
     * @return int
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createProducts(array $createProducts): int
    {
        $products = array_map(function (CreateProduct $createProduct): Product {
            return new Product($createProduct->getTitle(), $createProduct->getPrice());
        }, $createProducts);

        $this->productRepository->saveProducts($products);

        return count($products);
    }
}