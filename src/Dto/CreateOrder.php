<?php
declare(strict_types=1);

namespace Xamin\App\Dto;

class CreateOrder
{
    /**
     * @var int[]
     */
    private $productIds;

    /**
     * @param int[] $productIds
     */
    public function __construct(array $productIds)
    {
        $this->productIds = $productIds;
    }

    /**
     * @return int[]
     */
    public function getProductIds(): array
    {
        return $this->productIds;
    }
}