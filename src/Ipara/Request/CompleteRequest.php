<?php

namespace Payconn\Ipara\Request;

use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Ipara\Model\Complete;
use Payconn\Ipara\Response\CompleteResponse;
use Payconn\Ipara\Token;

class CompleteRequest extends IparaRequest
{
    public function send(): ResponseInterface
    {
        /** @var Complete $model */
        $model = $this->getModel();
        /** @var Token $token */
        $token = $this->getToken();

        $body = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><auth></auth>');
        $body->addChild('threeD', 'true');
        $body->addChild('mode', $model->getReturnParams()->get('mode'));
        $body->addChild('orderId', $model->getReturnParams()->get('orderId'));
        $body->addChild('amount', $model->getReturnParams()->get('amount'));
        $body->addChild('threeDSecureCode', $model->getReturnParams()->get('threeDSecureCode'));

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
            $model->getReturnParams()->get('orderId').
            $model->getReturnParams()->get('amount').
            $model->getReturnParams()->get('mode').
            $model->getReturnParams()->get('threeDSecureCode').
            $model->getReturnParams()->get('transactionDate');

        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $model->getBaseUrl().'/rest/payment/auth', [
            'body' => $body->asXML(),
            'headers' => [
                'Accept' => 'application/xml',
                'Content-type' => 'application/xml',
                'version' => '1.0',
                'token' => $token->getPublicKey().':'.base64_encode(sha1($hash, true)),
                'transactionDate' => $model->getReturnParams()->get('transactionDate'),
            ],
        ]);

        return new CompleteResponse($this->getModel(), (array) @simplexml_load_string($response->getBody()->getContents()));
    }
}
