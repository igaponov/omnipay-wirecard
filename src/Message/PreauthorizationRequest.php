<?php

namespace Omnipay\Wirecard\Message;

use Wirecard\Element\Action\Preauthorization;
use Wirecard\Element\Job;

class PreauthorizationRequest extends AbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    protected function buildData()
    {
        $transaction = $this->buildTransaction();
        $preauthorization = new Preauthorization($transaction);

        return Job::createPreauthorizationJob($this->getSignature(), $preauthorization);
    }
}
