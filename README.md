<p align="center">
<a href="https://ipara.com.tr/"><img width="200" src="https://ipara.com.tr/assets/img/ipara-logo.svg"></a>
</p>

<h3 align="center">Payconn: iPara</h3>

<p align="center">iPara gateway for Payconn payment processing library</p>
<p align="center">
  <a href="https://travis-ci.com/payconn/ipara"><img src="https://travis-ci.com/payconn/ipara.svg?branch=master" /></a>
</p>
<hr>

<p align="center">
<b><a href="#installation">Installation</a></b>
|
<b><a href="#supported-methods">Supported Methods</a></b>
|
<b><a href="#basic-usages">Basic Usages</a></b>
</p>
<hr>
<br>

[Payconn](https://github.com/payconn/common) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements common classes required by Payconn.

## Installation

    $ composer require payconn/ipara

## Supported card families
* Bonus 
* World
* Axess
* Maximum
* Paraf
* CardFinans
* Sağlam Kart 
* Advantage

## Supported methods
* purchase
* authorize
* complete
* refund

## Basic Usage
```php
use Payconn\Ipara;
use Payconn\Ipara\Token;
use Payconn\Ipara\Product;
use Payconn\Ipara\Model\Purchase;
use Payconn\Common\CreditCard;

$token = new Token('YOUR_PUBLIC_KEY', 'YOUR_PRIVATE_KEY');
$purchase = new Purchase();
$purchase->setTestMode(true);
$purchase->setAmount(100);
$purchase->setInstallment(1);
$purchase->setFirstName('Murat');
$purchase->setLastName('Sac');
$purchase->setEmail('muratsac@mail.com');
$purchase->addProduct((new Product('001', 'Test', 100)));
$purchase->setCreditCard((new CreditCard('4282209027132016', '2024', '12', '358'))
    ->setHolderName('MuratSac'));
$purchase->generateOrderId();
$response = (new Ipara($token))->purchase($purchase);
if($response->isSuccessful()){
    // success!
}
```

## Change log

Please see [UPGRADE](UPGRADE.md) for more information on how to upgrade to the latest version.

## Support

If you are having general issues with Payconn, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/payconn/ipara/issues),
or better yet, fork the library and submit a pull request.


## Security

If you discover any security related issues, please email muratsac@mail.com instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
