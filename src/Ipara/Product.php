<?php

namespace Payconn\Ipara;

class Product
{
    private $code;

    private $name;

    private $price;

    private $quantity;

    public function __construct(string $code, string $name, float $price, int $quantity = 1)
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
