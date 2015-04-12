<?php

namespace Omnipay\Wirecard\Message\TransactionBuilder;

class ReferencedTransactionBuilderTest extends TransactionBuilderTestCase
{
    public function testBuildMethodReturnsTransaction()
    {
        $request = $this->request;
        $builder = new ReferencedTransactionBuilder($request);
        $transaction = $builder->build();
        $this->assertInstanceOf('\Wirecard\Element\Transaction', $transaction);

        $this->assertSame(1234, $transaction->id);
        $this->assertSame('30FWE6HR325GB0', $transaction->guWid);

        $this->assertNull($transaction->secure);
        $this->assertNull($transaction->creditCardData);
        $this->assertNull($transaction->amount);
        $this->assertNull($transaction->trustCenterData);
        $this->assertNull($transaction->currency);
    }
}
