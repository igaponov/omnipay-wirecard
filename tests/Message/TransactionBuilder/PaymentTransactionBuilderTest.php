<?php

namespace Omnipay\Wirecard\Message\TransactionBuilder;

class PaymentTransactionBuilderTest extends TransactionBuilderTestCase
{
    public function testBuildMethodReturnsTransaction()
    {
        $request = $this->request;
        $builder = new PaymentTransactionBuilder($request);
        $transaction = $builder->build();
        $this->assertInstanceOf('\Wirecard\Element\Transaction', $transaction);
        $this->assertInstanceOf('\Wirecard\Element\CreditCardData', $transaction->creditCardData);
        $this->assertInstanceOf('\Wirecard\Element\Secure', $transaction->secure);

        $this->assertSame(1234, $transaction->id);
        $this->assertSame('30FWE6HR325GB0', $transaction->guWid);
        $this->assertSame('gqw5gwDWg$#%he&N4678iu3e56g1$5g', $transaction->secure->response);
        $this->assertNotNull($transaction->creditCardData->secureCode);

        $this->assertNull($transaction->creditCardData->creditCardNumber);
        $this->assertNull($transaction->amount);
        $this->assertNull($transaction->trustCenterData);
        $this->assertNull($transaction->currency);
    }
}
