<?php

require_once __DIR__.'/../vendor/autoload.php';

$token = new \Payconn\Ipara\Token('YOUR_PUBLIC_KEY', 'YOUR_PRIVATE_KEY');
$authorize = new \Payconn\Ipara\Model\Authorize();
$authorize->setTestMode(true);
$authorize->setAmount(100);
$authorize->setInstallment(1);
$authorize->setFirstName('Murat');
$authorize->setLastName('Sac');
$authorize->setEmail('muratsac@mail.com');
$authorize->setOrderId('Payconn'.time());
$authorize->setSuccessfulUrl('http://127.0.0.1:8000/successful');
$authorize->setFailureUrl('http://127.0.0.1:8000/failure');
$authorize->addProduct((new \Payconn\Ipara\Product('001', 'Test', 100)));
$authorize->setCreditCard((new \Payconn\Common\CreditCard('4282209027132016', '2024', '12', '358'))->setHolderName('MuratSac'));
$response = (new \Payconn\Ipara\Ipara($token))->authorize($authorize);
print_r($response);
