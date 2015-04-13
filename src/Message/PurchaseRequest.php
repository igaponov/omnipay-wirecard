<?php

namespace Omnipay\Wirecard\Message;

use Wirecard\Element\Action\Purchase;
use Wirecard\Element\Job;

class PurchaseRequest extends AbstractRequest
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
        $purchase = new Purchase($transaction);

        return Job::createPurchaseJob($this->getSignature(), $purchase);
    }
}
