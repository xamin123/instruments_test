<?php
declare(strict_types=1);

namespace Xamin\App\Service;

use GuzzleHttp\Client;
use Xamin\App\Dto\PaymentResult;
use function sprintf;

class PaymentService
{
    /**
     * @var Client
     */
    private $guzzleClient;

    public function __construct()
    {
        $this->guzzleClient = new Client();
    }

    public function makePayment(float $sum): PaymentResult
    {
        $res = $this->guzzleClient->request('GET', 'https://ya.ru');
        if ($res->getStatusCode() === 200) {
            return new PaymentResult(true, []);
        }

        return new PaymentResult(false, [sprintf('Payment client error code %d', $res->getStatusCode())]);
    }
}