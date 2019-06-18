<?php

namespace Payconn\Ipara\Response;

use Payconn\Common\AbstractResponse;

class AuthorizeResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return true;
    }

    public function getResponseMessage(): string
    {
        return 'Redirected';
    }

    public function getResponseCode(): string
    {
        return '01';
    }

    public function getResponseBody(): array
    {
        return $this->getParameters()->all();
    }

    public function isRedirection(): bool
    {
        return true;
    }

    public function getRedirectForm(): ?string
    {
        return $this->getParameters()->get('content');
    }
}
