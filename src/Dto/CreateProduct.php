<?php
declare(strict_types=1);

namespace Xamin\App\Dto;

class CreateProduct
{
    /**
     * @var string
     */
    private $title;
    /**
     * @var float
     */
    private $price;

    public function __construct(string $title, float $price)
    {
        $this->title = $title;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }
}