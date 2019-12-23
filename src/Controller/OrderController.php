<?php
declare(strict_types=1);

namespace Xamin\App\Controller;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Xamin\App\Dto\CreateOrder;
use Xamin\App\Dto\PayForOrder;
use Xamin\App\Exception\OrderNotFountException;
use Xamin\App\Exception\PaymentException;
use Xamin\App\Exception\ProductsNotFoundException;
use Xamin\App\Service\OrderService;
use function json_decode;

class OrderController extends BaseController
{
    /**
     * @var OrderService
     */
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function test(Request $request): Response
    {
        return new Response('Hello, world!');
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ProductsNotFoundException
     */
    public function createOrder(Request $request): Response
    {
        $user = $this->getCurrentUser();
        $createOrder = $this->getCreateOrder($request);

        $orderId = $this->orderService->createOrder($createOrder, $user);

        return new JsonResponse(['orderId' => $orderId]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws PaymentException
     */
    public function payForOrder(Request $request): Response
    {
        $payForOrder = $this->getPayForOrder($request);
        try {
            $paymentResult = $this->orderService->payForOrder($payForOrder);
            return new JsonResponse(['success' => $paymentResult->isSuccess(), 'errors' => $paymentResult->getErrors()]);

        } catch (OrderNotFountException $orderNotFountException) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

    private function getCreateOrder(Request $request): CreateOrder
    {
        $productIds = json_decode($request->getContent(), true);

        return new CreateOrder($productIds);
    }

    private function getPayForOrder(Request $request): PayForOrder
    {
        $data = json_decode($request->getContent(), true);

        return new PayForOrder($data['orderId'], (float)$data['sum']);
    }
}