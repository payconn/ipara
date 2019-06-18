<?php

namespace Payconn\Ipara\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Payconn\Common\CreditCard;
use Payconn\Common\HttpClient;
use Payconn\Ipara;
use Payconn\Ipara\Model\Purchase;
use Payconn\Ipara\Token;
use PHPUnit\Framework\TestCase;

class PurchaseTest extends TestCase
{
    public function testFailure()
    {
        $response = new Response(200, [], '<authResponse>
            <amount>2500</amount>
            <echo>Echo Bilgisi</echo>
            <hash>yO5ACnF9FpsiV2/WBRRsDEBDRt8=</hash>
            <mode>T</mode>
            <orderId>b3091d88-6320-4446-be6c-7a1f8e6e73c7</orderId>
            <publicKey>ABVQ03C77WPTODQN</publicKey>
            <result>0</result>
            <transactionDate>2014-01-03 21:08:51</transactionDate>
        </authResponse>');
        $mock = new MockHandler([
            $response,
        ]);
        $handler = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handler]);

        // purchase
        $token = new Token('PUBLIC_KEY', 'PRIVATE_KEY');
        $creditCard = new CreditCard('4355084355084358', '26', '12', '000');
        $creditCard->setHolderName('Murat SAC');
        $purchase = new Purchase();
        $purchase->setTestMode(true);
        $purchase->setCreditCard($creditCard);
        $purchase->setAmount(100);
        $purchase->setInstallment(1);
        $purchase->setFirstName('Murat');
        $purchase->setLastName('Sac');
        $purchase->setEmail('muratsac@mail.com');
        $purchase->addProduct((new \Payconn\Ipara\Product('001', 'Test', 100)));
        $purchase->generateOrderId();
        $response = (new Ipara($token, $client))->purchase($purchase);
        $this->assertFalse($response->isSuccessful());
    }

    public function testSuccessful()
    {
        $response = new Response(200, [], '<authResponse>
            <amount>2500</amount>
            <echo>Echo Bilgisi</echo>
            <hash>yO5ACnF9FpsiV2/WBRRsDEBDRt8=</hash>
            <mode>T</mode>
            <orderId>b3091d88-6320-4446-be6c-7a1f8e6e73c7</orderId>
            <publicKey>ABVQ03C77WPTODQN</publicKey>
            <result>1</result>
            <transactionDate>2014-01-03 21:08:51</transactionDate>
        </authResponse>');
        $mock = new MockHandler([
            $response,
        ]);
        $handler = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handler]);

        // purchase
        $token = new Token('PUBLIC_KEY', 'PRIVATE_KEY');
        $creditCard = new CreditCard('4355084355084358', '26', '12', '000');
        $creditCard->setHolderName('Murat SAC');
        $purchase = new Purchase();
        $purchase->setTestMode(true);
        $purchase->setCreditCard($creditCard);
        $purchase->setAmount(100);
        $purchase->setInstallment(1);
        $purchase->setFirstName('Murat');
        $purchase->setLastName('Sac');
        $purchase->setEmail('muratsac@mail.com');
        $purchase->addProduct((new \Payconn\Ipara\Product('001', 'Test', 100)));
        $purchase->generateOrderId();
        $response = (new \Payconn\Ipara($token, $client))->purchase($purchase);
        $this->assertTrue($response->isSuccessful());
    }
}
