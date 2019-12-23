<?php
declare(strict_types=1);

namespace Xamin\App;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Xamin\App\Controller\OrderController;
use Xamin\App\Controller\ProductController;
use Xamin\App\Entity\Order;
use Xamin\App\Entity\Product;
use Xamin\App\Repository\OrderRepository;
use Xamin\App\Repository\ProductRepository;
use Xamin\App\Service\OrderService;
use Xamin\App\Service\PaymentService;
use Xamin\App\Service\ProductService;

class App
{
    /**
     * @var
     */
    private $container;

    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $container = new ContainerBuilder();
        $this->initServices($container);
        $this->initKernel($container);

        $routes = new RouteCollection();
        $this->configureRoutes($routes);
        $container->setParameter('routes', $routes);
        $container->setParameter('charset', 'UTF-8');
        $this->container = $container;

    }

    protected function configureRoutes(RouteCollection $routes): void
    {
        $routes->add('hello', new Route('/hello', ['_controller' => OrderController::class.'::test']));
        $route1 = new Route(
            '/product/generate', [
                '_controller' => ProductController::class.'::generate',
            ]
        );
        $route1->setMethods(['POST']);
        $routes->add('product.generate', $route1);

        $route2 = new Route(
            '/order/create', [
                '_controller' => OrderController::class.'::createOrder'
            ]
        );
        $route2->setMethods(['POST']);
        $routes->add('order.create', $route2);

        $route3 = new Route(
            '/order/pay', [
                '_controller' => OrderController::class.'::payForOrder'
            ]
        );
        $route3->setMethods(['POST']);
        $routes->add('order.pay', $route3);
    }

    protected function initKernel(ContainerBuilder $container): void
    {
        $container->register('context', RequestContext::class);
        $container->register('matcher', UrlMatcher::class)
            ->setArguments(['%routes%', new Reference('context')]);
        $container->register('resolver', ContainerControllerResolver::class)->setArguments([$container]);
        $container->register('requestStack', RequestStack::class);
        $container->register('listener.router', RouterListener::class)
            ->setArguments([new Reference('matcher'), new Reference('requestStack')]);
        $container->register('listener.response', ResponseListener::class)
            ->setArguments(['%charset%']);
        $container->register('dispatcher', EventDispatcher::class)
            ->addMethodCall('addSubscriber', [new Reference('listener.router')])
            ->addMethodCall('addSubscriber', [new Reference('listener.response')]);
        $container->register('kernel', HttpKernel::class)
            ->setArguments([new Reference('dispatcher'), new Reference('resolver')]);
    }

    protected function initServices(ContainerBuilder $container): void
    {
        $container->register(EntityManager::class, EntityManager::class)
            ->setFactory(
                [static::class, 'createEntityManager']
            )
            ->setArguments([$this->config]);

        $container->register(PaymentService::class, PaymentService::class);

        $container->register(OrderRepository::class, OrderRepository::class)
            ->setFactory([new Reference(EntityManager::class), 'getRepository'])
            ->setArguments([Order::class]);

        $container->register(ProductRepository::class, ProductRepository::class)
            ->setFactory([new Reference(EntityManager::class), 'getRepository'])
            ->setArguments([Product::class]);

        $container->register(OrderService::class, OrderService::class)
            ->setArguments(
                [
                    new Reference(PaymentService::class),
                    new Reference(OrderRepository::class),
                    new Reference(ProductRepository::class),
                ]
            );
        $container->register(ProductService::class, ProductService::class)
            ->setArguments([new Reference(ProductRepository::class)]);
        $container->register(OrderController::class, OrderController::class)
            ->setArguments([new Reference(OrderService::class)]);
        $container->register(ProductController::class, ProductController::class)
            ->setArguments([new Reference(ProductService::class)]);
    }

    /**
     * @param array $dbConfig
     *
     * @return EntityManagerInterface
     * @throws ORMException
     */
    public static function createEntityManager(array $dbConfig): EntityManagerInterface
    {
        $config = Setup::createAnnotationMetadataConfiguration([$dbConfig['entitiesDir']], true, null, null, false);

        return EntityManager::create(
            $dbConfig['connection'],
            $config
        );
    }

    /**
     * @return HttpKernel
     * @throws Exception
     */
    public function getKernel(): HttpKernel
    {
        return $this->container->get('kernel');
    }

}