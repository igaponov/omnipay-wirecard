<?php

namespace Omnipay\Wirecard\Message;

use Guzzle\Http\ClientInterface;
use Omnipay\Wirecard\Message\TransactionBuilder\ReferencedTransactionBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Wirecard\Element\Action\BookBack;
use Wirecard\Element\Job;

class BookBackRequest extends AbstractRequest
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
        $bookBack = new BookBack($transaction);

        return Job::createBookBackJob($this->getSignature(), $bookBack);
    }
}
