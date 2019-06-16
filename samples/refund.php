<?php

require_once __DIR__.'/../vendor/autoload.php';

$token = new \Payconn\Ipara\Token('YOUR_PUBLIC_KEY', 'YOUR_PRIVATE_KEY');
$refund = new \Payconn\Ipara\Model\Refund();
$refund->setTestMode(true);
$refund->setAmount(100);
$refund->setOrderId('Payconn1560711502');
$refund->setTransactionDate('2019-06-16 21:55:01');
$refund->setOrderHash('8h/3qkGToP6Q9QCGu4HWcMrIJRw=');
$response = (new \Payconn\Ipara\Ipara($token))->refund($refund);
print_r([
    'isSuccessful' => (int) $response->isSuccessful(),
    'message' => $response->getResponseMessage(),
    'code' => $response->getResponseCode(),
]);
