<?php

namespace Omnipay\Wirecard\Message;

use Guzzle\Http\ClientInterface;
use Omnipay\Wirecard\Message\TransactionBuilder\EnrollmentTransactionBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Wirecard\Element\Action\EnrollmentCheck;
use Wirecard\Element\Job;

class EnrollmentRequest extends AbstractRequest
{
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        parent::__construct(new EnrollmentTransactionBuilder($this), $httpClient, $httpRequest);
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
