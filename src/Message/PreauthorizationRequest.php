<?php

namespace Omnipay\Wirecard\Message;

use Omnipay\Common\CreditCard;
use Wirecard\Element\Action\Preauthorization;
use Wirecard\Element\CreditCardData;
use Wirecard\Element\Job;
use Wirecard\Element\Secure;
use Wirecard\Element\Transaction;

class PreauthorizationRequest extends EnrollmentRequest
{
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

            $preauthorization = new Preauthorization($transaction);
            $job = Job::createPreauthorizationJob($this->getSignature(), $preauthorization);
        } else {
            $job = parent::buildData();
        }

        /** @var CreditCard $creditCard */
        $creditCard = $this->getCard();
        $job->getTransaction()->creditCardData->secureCode = $creditCard->getCvv();

        return $job;
    }
}
