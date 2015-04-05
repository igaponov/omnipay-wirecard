<?php

namespace Omnipay\Wirecard\Message\TransactionBuilder;

use Omnipay\Common\CreditCard;
use Omnipay\Wirecard\Message\AbstractRequest;
use Wirecard\Element\Amount;
use Wirecard\Element\BillingAddress;
use Wirecard\Element\CreditCardData;
use Wirecard\Element\Transaction;
use Wirecard\Element\TrustCenterData;

class PurchaseTransactionBuilder implements TransactionBuilderInterface
{
    /**
     * @var AbstractRequest
     */
    private $request;

    /**
     * @param AbstractRequest $request
     */
    public function __construct(AbstractRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return Transaction
     */
    public function build()
    {
        /** @var CreditCard $creditCard */
        $creditCard = $this->request->getCard();
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
        $transaction->id = $this->request->getTransactionId();
        $transaction->amount = new Amount($this->request->getAmount());
        $transaction->currency = $this->request->getCurrency();
        $transaction->countryCode = $this->request->getCountryCode();
        $transaction->creditCardData = $creditCardData;
        $transaction->trustCenterData = $trustCenter;
    }
}
