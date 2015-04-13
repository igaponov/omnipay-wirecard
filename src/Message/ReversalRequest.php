<?php

namespace Omnipay\Wirecard\Message;

use Wirecard\Element\Action\Reversal;
use Wirecard\Element\Job;

class ReversalRequest extends AbstractRequest
{
    /**
     * @return Job
     */
    protected function buildData()
    {
        $transaction = $this->buildTransaction();
        $reversal = new Reversal($transaction);

        return Job::createReversalJob($this->getSignature(), $reversal);
    }
}
