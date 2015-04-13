<?php

namespace Omnipay\Wirecard\Message\TransactionBuilder;

use Wirecard\Element\Transaction;

interface TransactionBuilderInterface
{
    /**
     * @return Transaction
     */
    public function build();
}
