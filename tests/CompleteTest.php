<?php

namespace Payconn\Ipara\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Payconn\Common\HttpClient;
use Payconn\Ipara;
use Payconn\Ipara\Model\Complete;
use Payconn\Ipara\Token;
use PHPUnit\Framework\TestCase;

class CompleteTest extends TestCase
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

        // complete
        $token = new Token('PUBLIC_KEY', 'PRIVATE_KEY');
        $complete = new Complete();
        $complete->setTestMode(true);
        $complete->setReturnParams([
            'mode' => 'T',
            'amount' => '10000',
            'orderId' => 'ORD-1560882728',
            'threeDSecureCode' => '002Ddg+sMQOrdYxQdtsg/UNmH1mjHMCh/vq+pyljO6tID07sShj5B+1c9zH/6TSnCmwZlUfOmztiNcF1sLhRkdQDw==',
            'transactionDate' => '2019-06-18 21:28:50',
        ]);
        $complete->setFirstName('Murat');
        $complete->setLastName('Sac');
        $complete->setEmail('muratsac@mail.com');
        $complete->addProduct((new \Payconn\Ipara\Product('001', 'Test', 100)));
        $response = (new Ipara($token, $client))->complete($complete);
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

        // complete
        $token = new Token('PUBLIC_KEY', 'PRIVATE_KEY');
        $complete = new Complete();
        $complete->setTestMode(true);
        $complete->setReturnParams([
            'mode' => 'T',
            'amount' => '10000',
            'orderId' => 'ORD-1560882728',
            'threeDSecureCode' => '002Ddg+sMQOrdYxQdtsg/UNmH1mjHMCh/vq+pyljO6tID07sShj5B+1c9zH/6TSnCmwZlUfOmztiNcF1sLhRkdQDw==',
            'transactionDate' => '2019-06-18 21:28:50',
        ]);
        $complete->setFirstName('Murat');
        $complete->setLastName('Sac');
        $complete->setEmail('muratsac@mail.com');
        $complete->addProduct((new \Payconn\Ipara\Product('001', 'Test', 100)));
        $response = (new Ipara($token, $client))->complete($complete);
        $this->assertTrue($response->isSuccessful());
    }
}
