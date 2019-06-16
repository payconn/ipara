<?php

namespace Payconn\Ipara\Request;

use Payconn\Common\AbstractRequest;
use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Ipara\Model\Purchase;
use Payconn\Ipara\Response\PurchaseResponse;
use Payconn\Ipara\Token;

class PurchaseRequest extends AbstractRequest
{
    public function send(): ResponseInterface
    {
        /** @var Purchase $model */
        $model = $this->getModel();
        /** @var Token $token */
        $token = $this->getToken();
        $transactionDate = date('Y-m-d H:i:s');
        $amount = $model->getAmount() * 100;

        $body = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><auth></auth>');
        $body->addChild('threeD', 'false');
        $body->addChild('mode', $model->isTestMode() ? 'T' : 'P');
        $body->addChild('cardOwnerName', $model->getCreditCard()->getHolderName());
        $body->addChild('cardNumber', $model->getCreditCard()->getNumber());
        $body->addChild('cardExpireMonth', $model->getCreditCard()->getExpireMonth()->format('m'));
        $body->addChild('cardExpireYear', $model->getCreditCard()->getExpireYear()->format('y'));
        $body->addChild('cardCvc', $model->getCreditCard()->getCvv());
        $body->addChild('installment', (string) $model->getInstallment());
        $body->addChild('orderId', $model->getOrderId());
        $body->addChild('amount', (string) $amount);
        $body->addChild('cardId', '');
        $body->addChild('userId', '');

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
            $amount.
            $model->isTestMode() ? 'T' : 'P'.
            $model->getCreditCard()->getHolderName().
            $model->getCreditCard()->getNumber().
            $model->getCreditCard()->getExpiryMonth()->format('m').
            $model->getCreditCard()->getExpiryMonth()->format('y').
            $model->getCreditCard()->getCvv().
            $model->getFirstName().
            $model->getLastName().
            $model->getEmail().
            $transactionDate;

        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $model->getBaseUrl(), [
            'body' => $body->asXML(),
            'headers' => [
                'Accept' => 'application/xml',
                'Content-type' => 'application/xml',
                'version' => '1.0',
                'token' => $token->getPublicKey().':'.base64_encode(sha1($hash, true)),
                'transactionDate' => $transactionDate,
            ],
        ]);

        return new PurchaseResponse($this->getModel(), (array) @simplexml_load_string($response->getBody()->getContents()));
    }
}
