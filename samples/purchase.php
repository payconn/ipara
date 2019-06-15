<?php

require_once __DIR__.'/../vendor/autoload.php';

$token = new \Payconn\Ipara\Token('NRI769Q8RMLW0VB', 'VZXBBIRSVJSZTWYUP6O13G4A4');
$purchase = new \Payconn\Ipara\Model\Purchase();
$purchase->setTestMode(true);
$purchase->setAmount(100);
$purchase->setInstallment(1);
$purchase->setFirstName('Murat');
$purchase->setLastName('Sac');
$purchase->setEmail('muratsac@mail.com');
$purchase->setOrderId('Payconn'.time());
$purchase->addProduct((new \Payconn\Ipara\Product('001', 'Test', 100)));
$purchase->setCreditCard((new \Payconn\Common\CreditCard('4282209027132016', '2024', '12', '358'))->setHolderName('MuratSac'));
$response = (new \Payconn\Ipara\Ipara($token))->purchase($purchase);
print_r($response);
