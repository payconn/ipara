<?php

namespace Payconn\Ipara\Request;

use Payconn\Common\AbstractRequest;
use Payconn\Common\HttpClientInterface;
use Payconn\Common\ModelInterface;
use Payconn\Common\TokenInterface;
use Payconn\Ipara\Model\Authorize;
use Payconn\Ipara\Model\Purchase;
use Payconn\Ipara\Model\Refund;

abstract class IparaRequest extends AbstractRequest
{
    protected string $transactionDate;

    public function __construct(TokenInterface $token, HttpClientInterface $httpClient, ModelInterface $model)
    {
        parent::__construct($token, $httpClient, $model);
        $this->transactionDate = date('Y-m-d H:i:is');
    }

    public function getAmount(): ?float
    {
        $model = $this->getModel();
        if ($model instanceof Purchase
            || $model instanceof Authorize
            || $model instanceof Refund) {
            return $model->getAmount() * 100;
        }

        return null;
    }

    public function getMode(): string
    {
        return $this->getModel()->isTestMode() ? 'T' : 'P';
    }
}
