<?php

namespace Payconn\Ipara\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Payconn\Common\CreditCard;
use Payconn\Common\HttpClient;
use Payconn\Ipara\Model\Authorize;
use Payconn\Ipara\Token;
use PHPUnit\Framework\TestCase;

class AuthorizeTest extends TestCase
{
    public function testSuccessful()
    {
        $response = new Response(200, [], 'TEST_CONTENT');
        $mock = new MockHandler([
            $response,
        ]);
        $handler = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handler]);

        // purchase
        $token = new Token('PUBLIC_KEY', 'PRIVATE_KEY');
        $creditCard = new CreditCard('4355084355084358', '26', '12', '000');
        $creditCard->setHolderName('MuratSac');
        $authorize = new Authorize();
        $authorize->setTestMode(true);
        $authorize->setAmount(100);
        $authorize->setInstallment(1);
        $authorize->setFirstName('Murat');
        $authorize->setLastName('Sac');
        $authorize->setEmail('muratsac@mail.com');
        $authorize->setSuccessfulUrl('http://127.0.0.1:8000/successful');
        $authorize->setFailureUrl('http://127.0.0.1:8000/failure');
        $authorize->addProduct((new \Payconn\Ipara\Product('001', 'Test', 100)));
        $authorize->setCreditCard($creditCard);
        $authorize->generateOrderId();
        $response = (new \Payconn\Ipara($token, $client))->authorize($authorize);
        $this->assertEquals('TEST_CONTENT', $response->getRedirectForm());
    }
}
