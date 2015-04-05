<?php

namespace Omnipay\Wirecard\Message\TransactionBuilder;

use Omnipay\Wirecard\Message\AbstractRequest;
use Wirecard\Element\Transaction;

class ReferencedTransactionBuilder implements TransactionBuilderInterface
{
    /**
     * @var AbstractRequest
     */
    private $request;

    /**
     * @param AbstractRequest $request
     */
    public function __construct(AbstractRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return Transaction
     */
    public function build()
    {
        $transaction = new Transaction();
        $transaction->id = $this->request->getTransactionId();
        $transaction->guWid = $this->request->getTransactionReference();

        return $transaction;
    }
}
