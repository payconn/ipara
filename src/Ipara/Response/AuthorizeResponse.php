<?php

namespace Payconn\Ipara\Response;

use Payconn\Common\AbstractResponse;

class AuthorizeResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        if (0 == $this->getParameters()->get('result')) {
            return false;
        }

        return true;
    }

    public function getResponseMessage(): string
    {
        if (!$this->isSuccessful()) {
            return $this->getParameters()->get('errorMessage');
        }

        return 'Redirected';
    }

    public function getResponseCode(): string
    {
        if (!$this->isSuccessful()) {
            return $this->getParameters()->get('errorCode');
        }

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
