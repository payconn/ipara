<?php

namespace Payconn\Ipara\Response;

use Payconn\Common\AbstractResponse;

class RefundResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        if ('1' == $this->getParameters()->get('result')) {
            return true;
        }

        return false;
    }

    public function getResponseMessage(): string
    {
        if ($this->isSuccessful()) {
            return 'Approved';
        }

        return $this->getParameters()->get('errorMessage');
    }

    public function getResponseCode(): string
    {
        if ($this->isSuccessful()) {
            return '00';
        }

        return $this->getParameters()->get('errorCode');
    }

    public function getResponseBody(): array
    {
        return $this->getParameters()->all();
    }

    public function isRedirection(): bool
    {
        return false;
    }

    public function getRedirectForm(): ?string
    {
        return null;
    }
}
