<?php

namespace Payconn\Ipara\Request;

use Payconn\Common\AbstractRequest;
use Payconn\Common\HttpClientInterface;
use Payconn\Common\ModelInterface;
use Payconn\Common\TokenInterface;
use Payconn\Ipara\Model\Authorize;
use Payconn\Ipara\Model\Purchase;
use Payconn\Ipara\Model\Refund;
use Payconn\Ipara\Token;

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
        if ($this->getModel() instanceof Purchase
            || $this->getModel() instanceof Authorize
            || $this->getModel() instanceof Refund) {
            return $this->getModel()->getAmount() * 100;
        }

        return null;
    }

    public function getMode(): string
    {
        return $this->getModel()->isTestMode() ? 'T' : 'P';
    }

    public function getTokenHash(): string
    {
        /** @var Purchase|Authorize|Refund $model */
        $model = $this->getModel();
        /** @var Token $token */
        $token = $this->getToken();

        if ($this->getModel() instanceof Refund) {
            $hash = $token->getPrivateKey().
                $model->getOrderId().
                $this->getIpAddress().
                $model->getTransactionDate();
        } else {
            $hash = $token->getPrivateKey().
                $model->getOrderId().
                $this->getAmount().
                $this->getMode().
                $model->getCreditCard()->getHolderName().
                $model->getCreditCard()->getNumber().
                $model->getCreditCard()->getExpireMonth()->format('m').
                $model->getCreditCard()->getExpireYear()->format('y').
                $model->getCreditCard()->getCvv().
                $model->getFirstName().
                $model->getLastName().
                $model->getEmail().
                $this->transactionDate;
        }

        return $token->getPublicKey().':'.base64_encode(sha1($hash, true));
    }
}
