<?php
declare(strict_types=1);

namespace Xamin\App\Dto;

class PayForOrder
{
    /**
     * @var int
     */
    private $orderId;
    /**
     * @var float
     */
    private $sum;

    public function __construct(int $orderId, float $sum)
    {
        $this->orderId = $orderId;
        $this->sum = $sum;
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @return mixed
     */
    public function getSum(): float
    {
        return $this->sum;
    }
}