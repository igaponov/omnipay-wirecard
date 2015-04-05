<?php

namespace Omnipay\Wirecard\Message\TransactionBuilder;

use Omnipay\Wirecard\Message\AbstractRequest;
use Wirecard\Element\Transaction;

interface TransactionBuilderInterface
{
    /**
     * @param AbstractRequest $request
     */
    public function __construct(AbstractRequest $request);

    /**
     * @return Transaction
     */
    public function build();
}
