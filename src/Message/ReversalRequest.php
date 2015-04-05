<?php

namespace Omnipay\Wirecard\Message;

use Guzzle\Http\ClientInterface;
use Omnipay\Wirecard\Message\TransactionBuilder\ReferencedTransactionBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Wirecard\Element\Action\Reversal;
use Wirecard\Element\Job;

class ReversalRequest extends AbstractRequest
{
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        parent::__construct(new ReferencedTransactionBuilder($this), $httpClient, $httpRequest);
    }

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
