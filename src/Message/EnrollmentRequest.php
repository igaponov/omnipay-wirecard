<?php

namespace Omnipay\Wirecard\Message;

use Guzzle\Http\ClientInterface;
use Omnipay\Wirecard\Message\TransactionBuilder\PurchaseTransactionBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Wirecard\Element\Action\EnrollmentCheck;
use Wirecard\Element\Job;

class EnrollmentRequest extends AbstractRequest
{
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        parent::__construct(new PurchaseTransactionBuilder($this), $httpClient, $httpRequest);
    }

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
