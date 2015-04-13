<?php

namespace Omnipay\Wirecard\Message;

use Wirecard\Element\Action\BookBack;
use Wirecard\Element\Job;

class BookBackRequest extends AbstractRequest
{
    /**
     * @return Job
     */
    protected function buildData()
    {
        $transaction = $this->buildTransaction();
        $bookBack = new BookBack($transaction);

        return Job::createBookBackJob($this->getSignature(), $bookBack);
    }
}
