<?php

namespace Omnipay\Wirecard\Message;

use Wirecard\Element\Action\Query;
use Wirecard\Element\Job;

class QueryRequest extends ReferencedRequest
{
    /**
     * @return Job
     */
    protected function buildData()
    {
        $transaction = $this->buildTransaction();
        $query = new Query($transaction);

        return Job::createQueryJob($this->getSignature(), $query);
    }
}
