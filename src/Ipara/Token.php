<?php

namespace Payconn\Ipara;

use Payconn\Common\TokenInterface;

class Token implements TokenInterface
{
    private string $publicKey;

    private string $privateKey;

    public function __construct(string $publicKey, string $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }
}
