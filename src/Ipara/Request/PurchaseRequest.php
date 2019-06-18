<?php

namespace Payconn\Ipara\Request;

use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Ipara\Model\Purchase;
use Payconn\Ipara\Response\PurchaseResponse;
use Payconn\Ipara\Token;

class PurchaseRequest extends IparaRequest
{
    public function send(): ResponseInterface
    {
        /** @var Purchase $model */
        $model = $this->getModel();
        /** @var Token $token */
        $token = $this->getToken();

        $body = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><auth></auth>');
        $body->addChild('threeD', 'false');
        $body->addChild('mode', $this->getMode());
        $body->addChild('cardOwnerName', $model->getCreditCard()->getHolderName());
        $body->addChild('cardNumber', $model->getCreditCard()->getNumber());
        $body->addChild('cardExpireMonth', $model->getCreditCard()->getExpireMonth());
        $body->addChild('cardExpireYear', $model->getCreditCard()->getExpireYear());
        $body->addChild('cardCvc', $model->getCreditCard()->getCvv());
        $body->addChild('installment', (string) $model->getInstallment());
        $body->addChild('orderId', $model->getOrderId());
        $body->addChild('amount', (string) $this->getAmount());

        $purchaser = $body->addChild('purchaser');
        $purchaser->addChild('name', $model->getFirstName());
        $purchaser->addChild('surname', $model->getLastName());
        $purchaser->addChild('email', $model->getEmail());
        $purchaser->addChild('clientIp', $this->getIpAddress());

        $basketItems = $body->addChild('products');
        foreach ($model->getProducts() as $product) {
            $basketItem = $basketItems->addChild('product');
            $basketItem->addChild('productCode', $product->getCode());
            $basketItem->addChild('productName', $product->getName());
            $basketItem->addChild('quantity', (string) $product->getQuantity());
            $basketItem->addChild('price', (string) $product->getPrice());
        }

        $hash = $token->getPrivateKey().
            $model->getOrderId().
            $this->getAmount().
            $this->getMode().
            $model->getCreditCard()->getHolderName().
            $model->getCreditCard()->getNumber().
            $model->getCreditCard()->getExpireMonth().
            $model->getCreditCard()->getExpireYear().
            $model->getCreditCard()->getCvv().
            $model->getFirstName().
            $model->getLastName().
            $model->getEmail().
            $this->transactionDate;

        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $model->getBaseUrl().'/rest/payment/auth', [
            'body' => $body->asXML(),
            'headers' => [
                'Accept' => 'application/xml',
                'Content-type' => 'application/xml',
                'version' => '1.0',
                'transactionDate' => $this->transactionDate,
                'token' => $token->getPublicKey().':'.base64_encode(sha1($hash, true)),
            ],
        ]);

        return new PurchaseResponse($this->getModel(), (array) @simplexml_load_string($response->getBody()->getContents()));
    }
}
