<?php

namespace Payconn\Ipara\Request;

use Payconn\Common\HttpClient;
use Payconn\Common\ResponseInterface;
use Payconn\Ipara\Model\Authorize;
use Payconn\Ipara\Response\AuthorizeResponse;

class AuthorizeRequest extends IparaRequest
{
    public function send(): ResponseInterface
    {
        /** @var Authorize $model */
        $model = $this->getModel();

        /** @var HttpClient $httpClient */
        $httpClient = $this->getHttpClient();
        $response = $httpClient->request('POST', $model->getBaseUrl(), [
            'form_params' => [
                'version' => '1.0',
                'mode' => $this->getMode(),
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
                'amount' => $this->getAmount(),
                'token' => $this->getPurchasingTokenHash(),
                'transactionDate' => $this->transactionDate,
            ],
        ]);

        return new AuthorizeResponse($this->getModel(), [
            'content' => $response->getBody()->getContents(),
        ]);
    }
}
