<?php

require_once __DIR__.'/../vendor/autoload.php';

$token = new \Payconn\Ipara\Token('YOUR_PUBLIC_KEY', 'YOUR_PRIVATE_KEY');
$complete = new \Payconn\Ipara\Model\Complete();
$complete->setTestMode(true);
$complete->setFirstName('Murat');
$complete->setLastName('Sac');
$complete->setEmail('muratsac@mail.com');
$complete->addProduct((new \Payconn\Ipara\Product('001', 'Test', 100)));
$complete->setReturnParams([
    'mode' => 'T',
    'amount' => '10000',
    'orderId' => 'ORD-1560882728',
    'threeDSecureCode' => '002Ddg+sMQOrdYxQdtsg/UNmH1mjHMCh/vq+pyljO6tID07sShj5B+1c9zH/6TSnCmwZlUfOmztiNcF1sLhRkdQDw==',
    'transactionDate' => '2019-06-18 21:28:50',
]);
$response = (new \Payconn\Ipara\Ipara($token))->complete($complete);
print_r([
    'isSuccessful' => (int) $response->isSuccessful(),
    'message' => $response->getResponseMessage(),
    'code' => $response->getResponseCode(),
]);
