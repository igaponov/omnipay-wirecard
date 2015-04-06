<?php

namespace Omnipay\Wirecard\Message;

use Guzzle\Http\ClientInterface;
use Omnipay\Common\CreditCard;
use Omnipay\Wirecard\Message\TransactionBuilder\EnrollmentTransactionBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Wirecard\Element\Action\Preauthorization;
use Wirecard\Element\CreditCardData;
use Wirecard\Element\Job;
use Wirecard\Element\Secure;
use Wirecard\Element\Transaction;

class PreauthorizationRequest extends AbstractRequest
{
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        parent::__construct(new EnrollmentTransactionBuilder($this), $httpClient, $httpRequest);
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    protected function buildData()
    {
        if ($this->getTransactionReference()) {
            $transaction = new Transaction();
            $transaction->id = $this->getTransactionId();
            $transaction->guWid = $this->getTransactionReference();
            $transaction->creditCardData = new CreditCardData();

            if ($this->getToken()) {
                $transaction->secure = Secure::createResponse($this->getToken());
            }
        } else {
            $transaction = $this->buildTransaction();
        }

        $preauthorization = new Preauthorization($transaction);
        $job = Job::createPreauthorizationJob($this->getSignature(), $preauthorization);

        /** @var CreditCard $creditCard */
        $creditCard = $this->getCard();
        $job->getTransaction()->creditCardData->secureCode = $creditCard->getCvv();

        return $job;
    }
}
