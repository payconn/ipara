<?php

namespace Payconn\Ipara\Request;

use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Ipara\Model\Purchase;
use Payconn\Ipara\Response\PurchaseResponse;

class PurchaseRequest extends IparaRequest
{
    public function send(): ResponseInterface
    {
        /** @var Purchase $model */
        $model = $this->getModel();

        $body = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><auth></auth>');
        $body->addChild('threeD', 'false');
        $body->addChild('mode', $this->getMode());
        $body->addChild('cardOwnerName', $model->getCreditCard()->getHolderName());
        $body->addChild('cardNumber', $model->getCreditCard()->getNumber());
        $body->addChild('cardExpireMonth', $model->getCreditCard()->getExpireMonth()->format('m'));
        $body->addChild('cardExpireYear', $model->getCreditCard()->getExpireYear()->format('y'));
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
        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $model->getBaseUrl(), [
            'body' => $body->asXML(),
            'headers' => [
                'Accept' => 'application/xml',
                'Content-type' => 'application/xml',
                'version' => '1.0',
                'token' => $this->getTokenHash(),
                'transactionDate' => $this->transactionDate,
            ],
        ]);

        return new PurchaseResponse($this->getModel(), (array) @simplexml_load_string($response->getBody()->getContents()));
    }
}
