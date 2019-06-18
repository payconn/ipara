<?php

namespace Payconn\Ipara\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Payconn\Common\HttpClient;
use Payconn\Ipara\Model\Refund;
use Payconn\Ipara\Token;
use PHPUnit\Framework\TestCase;

class RefundTest extends TestCase
{
    public function testFailure()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"result": "0", "refundHash": "HASH"}'),
            new Response(200, [], '{"result": "0", "refundHash": "HASH"}'),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handler]);

        // purchase
        $token = new Token('PUBLIC_KEY', 'PRIVATE_KEY');
        $refund = new Refund();
        $refund->setTestMode(true);
        $refund->setAmount(100);
        $refund->setOrderId('Payconn1560711502');
        $refund->setTransactionDate('2019-06-16 21:55:01');
        $refund->setOrderHash('8h/3qkGToP6Q9QCGu4HWcMrIJRw=');
        $response = (new \Payconn\Ipara($token, $client))->refund($refund);
        $this->assertFalse($response->isSuccessful());
    }

    public function testSuccessful()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"result": "1", "refundHash": "HASH"}'),
            new Response(200, [], '{"result": "1", "refundHash": "HASH"}'),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handler]);

        // refund
        $token = new Token('PUBLIC_KEY', 'PRIVATE_KEY');
        $refund = new Refund();
        $refund->setTestMode(true);
        $refund->setAmount(100);
        $refund->setOrderId('Payconn1560711502');
        $refund->setTransactionDate('2019-06-16 21:55:01');
        $refund->setOrderHash('8h/3qkGToP6Q9QCGu4HWcMrIJRw=');
        $response = (new \Payconn\Ipara($token, $client))->refund($refund);
        $this->assertTrue($response->isSuccessful());
    }
}
