<?php

namespace Payconn\Ipara\Model;

use Payconn\Common\AbstractModel;
use Payconn\Common\Model\AuthorizeInterface;
use Payconn\Common\Traits\Amount;
use Payconn\Common\Traits\CreditCard;
use Payconn\Common\Traits\Currency;
use Payconn\Common\Traits\Installment;
use Payconn\Common\Traits\OrderId;
use Payconn\Common\Traits\ReturnUrl;
use Payconn\Ipara\Product;

class Authorize extends AbstractModel implements AuthorizeInterface
{
    use CreditCard;
    use Amount;
    use Installment;
    use ReturnUrl;
    use OrderId;
    use Currency;

    protected string $firstName;

    protected string $lastName;

    protected string $email;

    protected ?array $products;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }

    /**
     * @return Product[]|null
     */
    public function getProducts(): ?array
    {
        return $this->products;
    }
}
