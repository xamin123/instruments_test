<?php
declare(strict_types=1);

namespace Xamin\App\Service;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Throwable;
use Xamin\App\Dto\CreateOrder;
use Xamin\App\Dto\PayForOrder;
use Xamin\App\Dto\PaymentResult;
use Xamin\App\Entity\Order;
use Xamin\App\Entity\Product;
use Xamin\App\Entity\User;
use Xamin\App\Exception\OrderNotFountException;
use Xamin\App\Exception\PaymentException;
use Xamin\App\Exception\ProductsNotFoundException;
use Xamin\App\Repository\OrderRepository;
use Xamin\App\Repository\ProductRepository;
use function array_diff;
use function array_map;
use function array_unique;
use function count;
use function sprintf;

class OrderService
{
    /**
     * @var PaymentService
     */
    private $paymentService;
    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(
        PaymentService $paymentService,
        OrderRepository $orderRepository,
        ProductRepository $productRepository
    ) {
        $this->paymentService = $paymentService;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param CreateOrder $createOrder
     * @param User $user
     *
     * @return int
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ProductsNotFoundException
     */
    public function createOrder(CreateOrder $createOrder, User $user): int
    {
        $productIds = array_unique($createOrder->getProductIds());
        $products = $this->productRepository->findProducts($productIds);
        if (count($products) !== count($productIds)) {
            $foundProductIds = array_map(
                static function (Product $product): int {
                    return $product->getId();
                },
                $products
            );
            throw new ProductsNotFoundException(array_diff($productIds, $foundProductIds));
        }
        $order = new Order($products);
        $this->orderRepository->save($order);

        return $order->getId();
    }

    /**
     * @param PayForOrder $payForOrder
     *
     * @return PaymentResult
     * @throws OrderNotFountException
     * @throws PaymentException
     */
    public function payForOrder(PayForOrder $payForOrder): PaymentResult
    {
        $orderId = $payForOrder->getOrderId();
        if (($order = $this->orderRepository->find($orderId)) === null) {
            throw new OrderNotFountException($orderId);
        }
        if (!$order->isNew()) {
            return new PaymentResult(false, [sprintf('Order #%d not new', $orderId)]);
        }
        if ($order->getTotalSum() !== $payForOrder->getSum()) {
            return new PaymentResult(
                false,
                [sprintf('Order cost is %s, but given sum is %s', $order->getTotalSum(), $payForOrder->getSum())]
            );
        }

        try {
            $paymentResult = $this->paymentService->makePayment($payForOrder->getSum());
            if ($paymentResult->isSuccess()) {
                $order->setPaid();
                $this->orderRepository->save($order);
            }

            return $paymentResult;
        } catch (Throwable $e) {
            throw new PaymentException($e);
        }
    }
}