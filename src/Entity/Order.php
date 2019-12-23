<?php
declare(strict_types=1);

namespace Xamin\App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Xamin\App\Enum\OrderStatuses;

/**
 * @ORM\Entity(repositoryClass="Xamin\App\Repository\OrderRepository")
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;
    /**
     * @var OrderItem[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="product", cascade={"persist"})
     */
    private $orderItems;
    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $status;
    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $totalSum;

    /**
     * @param Product[] $products
     * @param User $user
     */
    public function __construct(array $products)
    {
        $this->totalSum = 0.0;
        $this->orderItems = new ArrayCollection();
        foreach ($products as $product) {
            $this->addProduct($product);
        }
        $this->status = OrderStatuses::NEW;
    }

    public function addProduct(Product $product): void
    {
        if (!isset($this->orderItems[$product->getId()])) {
            $this->orderItems[$product->getId()] = new OrderItem($product, $this);
            $this->totalSum += $product->getPrice();
        }
    }

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Product[]
     */
    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    /**
     * @return float
     */
    public function getTotalSum(): float
    {
        return $this->totalSum;
    }

    public function isNew(): bool
    {
        return $this->status === OrderStatuses::NEW;
    }

    public function isPaid(): bool
    {
        return $this->status === OrderStatuses::PAID;
    }

    public function setPaid(): void
    {
        $this->status = OrderStatuses::PAID;
    }
}