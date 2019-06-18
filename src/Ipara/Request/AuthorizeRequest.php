<?php

namespace Payconn\Ipara\Request;

use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Ipara\Model\Authorize;
use Payconn\Ipara\Response\AuthorizeResponse;
use Payconn\Ipara\Token;

class AuthorizeRequest extends IparaRequest
{
    public function send(): ResponseInterface
    {
        /** @var Authorize $model */
        $model = $this->getModel();
        /** @var Token $token */
        $token = $this->getToken();

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
        $response = $httpClient->request('POST', $model->getBaseUrl(), [
            'form_params' => [
                'version' => '1.0',
                'mode' => $this->getMode(),
                'orderId' => $model->getOrderId(),
                'cardOwnerName' => $model->getCreditCard()->getHolderName(),
                'cardNumber' => $model->getCreditCard()->getNumber(),
                'cardExpireMonth' => $model->getCreditCard()->getExpireMonth(),
                'cardExpireYear' => $model->getCreditCard()->getExpireYear(),
                'installment' => $model->getInstallment(),
                'cardCvc' => $model->getCreditCard()->getCvv(),
                'purchaserName' => $model->getFirstName(),
                'purchaserSurname' => $model->getLastName(),
                'purchaserEmail' => $model->getEmail(),
                'successUrl' => $model->getSuccessfulUrl(),
                'failureUrl' => $model->getFailureUrl(),
                'amount' => $this->getAmount(),
                'transactionDate' => $this->transactionDate,
                'token' => $token->getPublicKey().':'.base64_encode(sha1($hash, true)),
            ],
        ]);

        return new AuthorizeResponse($this->getModel(), [
            'content' => $response->getBody()->getContents(),
        ]);
    }
}
