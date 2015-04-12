<?php

namespace Omnipay\Wirecard\Message\TransactionBuilder;

use Omnipay\Tests\TestCase;
use Omnipay\Wirecard\Message\AbstractRequest;

class TransactionBuilderTestCase extends TestCase
{
    /**
     * @var AbstractRequest
     */
    protected $request;

    protected function setUp()
    {
        parent::setUp();

        /** @var AbstractRequest $request */
        $this->request = $this->getMockBuilder('\Omnipay\Wirecard\Message\AbstractRequest')
            ->setMethods(['buildData'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->request->initialize([
            'card' => $this->getValidCard(),
            'transactionId' => 1234,
            'transactionReference' => '30FWE6HR325GB0',
            'amount' => 100.00,
            'currency' => 'EUR',
            'countryCode' => 'DE',
            'token' => 'gqw5gwDWg$#%he&N4678iu3e56g1$5g'
        ]);
    }
}
