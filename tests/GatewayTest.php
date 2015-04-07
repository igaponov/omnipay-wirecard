<?php

namespace Omnipay\Wirecard;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    protected function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testSupportsEnrollment()
    {
        $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->enrollment());
    }

    public function testSupportsPreauthorization()
    {
        $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->preauthorization());
    }

    public function testSupportsReversal()
    {
        $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->reversal());
    }

    public function testSupportsQuery()
    {
        $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->query());
    }

    public function testSupportsBookBack()
    {
        $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->bookBack());
    }
}
