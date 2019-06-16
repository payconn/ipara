<?php

namespace Payconn\Ipara\Request;

use Payconn\Common\AbstractRequest;
use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Ipara\Model\Authorize;
use Payconn\Ipara\Response\AuthorizeResponse;
use Payconn\Ipara\Token;

class AuthorizeRequest extends AbstractRequest
{
    public function send(): ResponseInterface
    {
        /** @var Authorize $model */
        $model = $this->getModel();
        /** @var Token $token */
        $token = $this->getToken();
        $transactionDate = date('Y-m-d H:i:s');
        $amount = $model->getAmount() * 100;

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
            'form_params' => [
                'version' => '1.0',
                'mode' => $model->isTestMode() ? 'T' : 'P',
                'orderId' => $model->getOrderId(),
                'cardOwnerName' => $model->getCreditCard()->getHolderName(),
                'cardNumber' => $model->getCreditCard()->getNumber(),
                'cardExpireMonth' => $model->getCreditCard()->getExpireMonth()->format('m'),
                'cardExpireYear' => $model->getCreditCard()->getExpireYear()->format('y'),
                'installment' => $model->getInstallment(),
                'cardCvc' => $model->getCreditCard()->getCvv(),
                'purchaserName' => $model->getFirstName(),
                'purchaserSurname' => $model->getLastName(),
                'purchaserEmail' => $model->getEmail(),
                'successUrl' => $model->getSuccessfulUrl(),
                'failureUrl' => $model->getFailureUrl(),
                'token' => $token->getPublicKey().':'.base64_encode(sha1($hash, true)),
                'transactionDate' => $transactionDate,
                'amount' => $amount,
            ],
        ]);

        return new AuthorizeResponse($this->getModel(), (array) @simplexml_load_string($response->getBody()->getContents()));
    }
}
