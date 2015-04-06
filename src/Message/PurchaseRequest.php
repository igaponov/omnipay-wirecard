<?php

namespace Omnipay\Wirecard\Message;

use Guzzle\Http\ClientInterface;
use Omnipay\Wirecard\Message\TransactionBuilder\PaymentTransactionBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Wirecard\Element\Action\Purchase;
use Wirecard\Element\Job;

class PurchaseRequest extends AbstractRequest
{
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        parent::__construct(new PaymentTransactionBuilder($this), $httpClient, $httpRequest);
    }

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
