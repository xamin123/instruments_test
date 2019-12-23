<?php
declare(strict_types=1);

namespace Xamin\App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Xamin\App\Dto\CreateProduct;
use Xamin\App\Service\ProductService;

class ProductController
{
    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function generate(): Response
    {
        $productsData = [
            ['Утюг 1', 1000.0],
            ['Утюг 2', 1500.0],
            ['Холодильник 1', 20000.50],
            ['Холодильник 2', 24000.00],
            ['Телевизор', 32000.0],
            ['Тостер', 5200.0],
            ['Пылесос', 10000.0],
            ['Кондиционер', 20000.0],
            ['Ноутбук 1', 29000.50],
            ['Ноутбук 2', 50000.50],
        ];

        $addProducts = [];
        foreach ($productsData as $productInfo)
        {
            $addProducts[] = new CreateProduct(...$productInfo);
        }
        $count = $this->productService->createProducts($addProducts);

        return new JsonResponse(['count' => $count]);
    }
}