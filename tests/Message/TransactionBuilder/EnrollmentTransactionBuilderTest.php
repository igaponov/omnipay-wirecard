<?php

namespace Omnipay\Wirecard\Message\TransactionBuilder;

class EnrollmentTransactionBuilderTest extends TransactionBuilderTestCase
{
    public function testBuildMethodReturnsTransaction()
    {
        $request = $this->request;
        $builder = new EnrollmentTransactionBuilder($request);
        $transaction = $builder->build();
        $this->assertInstanceOf('\Wirecard\Element\Transaction', $transaction);
        $this->assertInstanceOf('\Wirecard\Element\Amount', $transaction->amount);
        $this->assertInstanceOf('\Wirecard\Element\CreditCardData', $transaction->creditCardData);
        $this->assertInstanceOf('\Wirecard\Element\TrustCenterData', $transaction->trustCenterData);
        $this->assertInstanceOf('\Wirecard\Element\BillingAddress', $transaction->trustCenterData->billingAddress);

        $this->assertSame(1234, $transaction->id);
        $this->assertSame('EUR', $transaction->currency);
        $this->assertSame('DE', $transaction->countryCode);
        $this->assertSame(10000, $transaction->amount->value);
        $this->assertSame('4111111111111111', $transaction->creditCardData->creditCardNumber);
        $this->assertSame('Example User', $transaction->creditCardData->cardHolderName);
        $this->assertSame('123 Billing St', $transaction->trustCenterData->billingAddress->address1);
        $this->assertNull($transaction->guWid);
        $this->assertNull($transaction->secure);
        $this->assertNull($transaction->creditCardData->secureCode);
    }
}
