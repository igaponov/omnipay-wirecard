<?php

namespace Omnipay\Wirecard\Message;

use Wirecard\Element\Action\Capture;
use Wirecard\Element\Job;

class CaptureRequest extends ReferencedRequest
{
    /**
     * @return Job
     */
    protected function buildData()
    {
        $transaction = $this->buildTransaction();
        $capture = new Capture($transaction);

        return Job::createCaptureJob($this->getSignature(), $capture);
    }
}
