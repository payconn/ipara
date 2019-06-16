<?php

namespace Payconn\Ipara\Request;

use Payconn\Common\AbstractRequest;
use Payconn\Common\HttpClientInterface;
use Payconn\Common\ModelInterface;
use Payconn\Common\TokenInterface;

abstract class IparaRequest extends AbstractRequest
{
    protected $transactionDate;

    public function __construct(TokenInterface $token, HttpClientInterface $httpClient, ModelInterface $model)
    {
        parent::__construct($token, $httpClient, $model);
        $this->transactionDate = date('Y-m-d H:i:is');
    }

    public function getAmount(): ?float
    {
        return $this->getModel()->getAmount() * 100;
    }

    public function getMode(): string
    {
        return $this->getModel()->isTestMode() ? 'T' : 'P';
    }

    public function getTokenHash(): string
    {
        $hash = $this->getToken()->getPrivateKey().
            $this->getModel()->getOrderId().
            $this->getAmount().
            $this->getMode().
            $this->getModel()->getCreditCard()->getHolderName().
            $this->getModel()->getCreditCard()->getNumber().
            $this->getModel()->getCreditCard()->getExpireMonth()->format('m').
            $this->getModel()->getCreditCard()->getExpireYear()->format('y').
            $this->getModel()->getCreditCard()->getCvv().
            $this->getModel()->getFirstName().
            $this->getModel()->getLastName().
            $this->getModel()->getEmail().
            $this->transactionDate;

        return $this->getToken()->getPublicKey().':'.base64_encode(sha1($hash, true));
    }
}
