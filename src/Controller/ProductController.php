<?php
declare(strict_types=1);

namespace Xamin\App\Controller;

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
            ['Утюг', 1000.0],
            ['Холодильник', 20000.50],
        ];

        $addProducts = [];
        foreach ($productsData as $productInfo)
        {
            $addProducts[] = new CreateProduct(...$productInfo);
        }
        $this->productService->createProducts($addProducts);

        return new Response();
    }
}