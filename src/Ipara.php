<?php

namespace Payconn\Ipara;

use Payconn\Common\AbstractGateway;
use Payconn\Common\BaseUrl;
use Payconn\Common\Model\AuthorizeInterface;
use Payconn\Common\Model\CancelInterface;
use Payconn\Common\Model\CompleteInterface;
use Payconn\Common\Model\PurchaseInterface;
use Payconn\Common\Model\RefundInterface;
use Payconn\Common\ResponseInterface;
use Payconn\Ipara\Request\PurchaseRequest;

class Ipara extends AbstractGateway
{
    public function initialize(): void
    {
        $this->setBaseUrl((new BaseUrl())
            ->setProdUrls('https://api.ipara.com/rest/payment/auth', 'https://www.ipara.com/3dgate')
            ->setTestUrls('https://api.ipara.com/rest/payment/auth', 'https://www.ipara.com/3dgate'));
    }

    public function purchase(PurchaseInterface $purchase): ResponseInterface
    {
        return $this->createRequest(PurchaseRequest::class, $purchase);
    }

    public function authorize(AuthorizeInterface $model): ResponseInterface
    {
        // TODO: Implement authorize() method.
    }

    public function complete(CompleteInterface $model): ResponseInterface
    {
        // TODO: Implement complete() method.
    }

    public function refund(RefundInterface $model): ResponseInterface
    {
        // TODO: Implement refund() method.
    }

    public function cancel(CancelInterface $model): ResponseInterface
    {
        // TODO: Implement cancel() method.
    }
}
