<?php

namespace Omnipay\Wirecard\Message;

use Omnipay\Common\CreditCard;
use Wirecard\Element\Action\EnrollmentCheck;
use Wirecard\Element\Amount;
use Wirecard\Element\BillingAddress;
use Wirecard\Element\CreditCardData;
use Wirecard\Element\Job;
use Wirecard\Element\Transaction;
use Wirecard\Element\TrustCenterData;

class EnrollmentRequest extends AbstractRequest
{
    public function getCountryCode()
    {
        return $this->getParameter('countryCode');
    }

    public function setCountryCode($value)
    {
        return $this->setParameter('countryCode', $value);
    }

    /**
     * @inheritdoc
     */
    protected function buildData()
    {
        /** @var CreditCard $creditCard */
        $creditCard = $this->getCard();
        $creditCardData = new CreditCardData();
        $creditCardData->creditCardNumber = $creditCard->getNumber();
        $creditCardData->setExpirationDate($creditCard->getExpiryDate('Y-m'));
        $creditCardData->cardHolderName = $creditCard->getName();

        $address = new BillingAddress(
            $creditCard->getFirstName(),
            $creditCard->getLastName(),
            $creditCard->getAddress1(),
            $creditCard->getAddress2(),
            $creditCard->getCity(),
            $creditCard->getPostcode(),
            $creditCard->getState(),
            $creditCard->getCountry(),
            $creditCard->getPhone(),
            $creditCard->getEmail()
        );
        $trustCenter = new TrustCenterData($address);

        $transaction = new Transaction();
        $transaction->id = $this->getTransactionId();
        $transaction->amount = new Amount($this->getAmount());
        $transaction->currency = $this->getCurrency();
        $transaction->countryCode = $this->getCountryCode();
        $transaction->creditCardData = $creditCardData;
        $transaction->trustCenterData = $trustCenter;

        $check = new EnrollmentCheck($transaction);
        $job = Job::createEnrollmentJob($this->getSignature(), $check);

        return $job;
    }
}
