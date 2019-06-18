<?php

require_once __DIR__.'/../vendor/autoload.php';

$token = new \Payconn\Ipara\Token('YOUR_PUBLIC_KEY', 'YOUR_PRIVATE_KEY');
$purchase = new \Payconn\Ipara\Model\Purchase();
$purchase->setTestMode(true);
$purchase->setAmount(100);
$purchase->setInstallment(1);
$purchase->setFirstName('Murat');
$purchase->setLastName('Sac');
$purchase->setEmail('muratsac@mail.com');
$purchase->addProduct((new \Payconn\Ipara\Product('001', 'Test', 100)));
$purchase->setCreditCard((new \Payconn\Common\CreditCard('4282209027132016', '2024', '12', '358'))->setHolderName('MuratSac'));
$purchase->generateOrderId();
$response = (new \Payconn\Ipara($token))->purchase($purchase);
print_r([
    'isSuccessful' => $response->isSuccessful(),
    'message' => $response->getResponseMessage(),
    'code' => $response->getResponseCode(),
    'orderId' => $response->getOrderId(),
    'body' => $response->getResponseBody(),
]);
