<?php

namespace Omnipay\Wirecard\Message;

use Wirecard\Element\Transaction;

abstract class ReferencedRequest extends AbstractRequest
{
    /**
     * @return Transaction
     */
    protected function buildTransaction()
    {
        $transaction = new Transaction();
        $transaction->id = $this->getTransactionId();
        $transaction->guWid = $this->getTransactionReference();

        return $transaction;
    }
}
