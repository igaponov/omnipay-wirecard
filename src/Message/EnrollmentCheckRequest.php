<?php

namespace Omnipay\Wirecard\Message;

use Wirecard\Element\Action\EnrollmentCheck;
use Wirecard\Element\Job;

class EnrollmentCheckRequest extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    protected function buildData()
    {
        $transaction = $this->buildTransaction();
        $check = new EnrollmentCheck($transaction);

        return Job::createEnrollmentJob($this->getSignature(), $check);
    }
}
