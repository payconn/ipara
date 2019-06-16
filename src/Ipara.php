<?php

namespace Payconn\Ipara;

use Payconn\Common\AbstractGateway;
use Payconn\Common\BaseUrl;
use Payconn\Common\Exception\NotSupportedMethodException;
use Payconn\Common\Model\AuthorizeInterface;
use Payconn\Common\Model\CancelInterface;
use Payconn\Common\Model\CompleteInterface;
use Payconn\Common\Model\PurchaseInterface;
use Payconn\Common\Model\RefundInterface;
use Payconn\Common\ResponseInterface;
use Payconn\Ipara\Request\AuthorizeRequest;
use Payconn\Ipara\Request\PurchaseRequest;
use Payconn\Ipara\Request\RefundRequest;

class Ipara extends AbstractGateway
{
    public function initialize(): void
    {
        $this->setBaseUrl((new BaseUrl())
            ->setProdUrls('https://api.ipara.com', 'https://www.ipara.com/3dgate')
            ->setTestUrls('https://api.ipara.com', 'https://www.ipara.com/3dgate'));
    }

    public function purchase(PurchaseInterface $purchase): ResponseInterface
    {
        return $this->createRequest(PurchaseRequest::class, $purchase);
    }

    public function authorize(AuthorizeInterface $authorize): ResponseInterface
    {
        return $this->createRequest(AuthorizeRequest::class, $authorize);
    }

    public function complete(CompleteInterface $model): ResponseInterface
    {
        // TODO: Implement complete() method.
    }

    public function refund(RefundInterface $refund): ResponseInterface
    {
        return $this->createRequest(RefundRequest::class, $refund);
    }

    public function cancel(CancelInterface $model): ResponseInterface
    {
        throw new NotSupportedMethodException('Method not supported. Review the refund method');
    }
}
