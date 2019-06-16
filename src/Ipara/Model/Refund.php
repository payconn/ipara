<?php

namespace Payconn\Ipara\Model;

use Payconn\Common\AbstractModel;
use Payconn\Common\Model\RefundInterface;
use Payconn\Common\Traits\Amount;
use Payconn\Common\Traits\OrderId;

class Refund extends AbstractModel implements RefundInterface
{
    use Amount;
    use OrderId;

    protected $transactionDate;

    protected $orderHash;

    public function getTransactionDate(): string
    {
        return $this->transactionDate->format('Y-m-d H:i:s');
    }

    public function setTransactionDate(string $transactionDate): void
    {
        $this->transactionDate = new \DateTime($transactionDate);
    }

    public function getOrderHash(): string
    {
        return $this->orderHash;
    }

    public function setOrderHash(string $orderHash): void
    {
        $this->orderHash = $orderHash;
    }
}
