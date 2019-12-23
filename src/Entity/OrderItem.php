<?php
declare(strict_types=1);

namespace Xamin\App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table("order_item", uniqueConstraints={@ORM\UniqueConstraint(name="order_product", columns={"order_id", "product_id"})})
 */
class OrderItem
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="orderItems")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;


    public function __construct(Product $product, Order $order)
    {
        $this->product = $product;
        $this->order = $order;
    }
}