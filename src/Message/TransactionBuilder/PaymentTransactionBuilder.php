<?php

namespace Omnipay\Wirecard\Message\TransactionBuilder;

use Omnipay\Common\CreditCard;
use Wirecard\Element\CreditCardData;
use Wirecard\Element\Secure;
use Wirecard\Element\Transaction;

class PaymentTransactionBuilder extends EnrollmentTransactionBuilder
{
    public function build()
    {
        if ($this->request->getTransactionReference()) {
            $transaction = new Transaction();
            $transaction->id = $this->request->getTransactionId();
            $transaction->guWid = $this->request->getTransactionReference();
            $transaction->creditCardData = new CreditCardData();

            if ($this->request->getToken()) {
                $transaction->secure = Secure::createResponse($this->request->getToken());
            }
        } else {
            $transaction = parent::build();
        }

        /** @var CreditCard $creditCard */
        $creditCard = $this->request->getCard();
        $transaction->creditCardData->secureCode = $creditCard->getCvv();

        return $transaction;
    }
}
