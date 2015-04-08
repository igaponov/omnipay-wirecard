<?php

namespace Omnipay\Wirecard;

use JMS\Serializer\SerializerBuilder;
use Omnipay\Common\CreditCard;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    protected $paymentOptions;

    protected function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setSerializer($this->getSerializerMock());
        $this->paymentOptions = [
            'card' => new CreditCard([
                'number' => '4200000000000000',
                'expiryYear' => '2019',
                'expiryMonth' => '01',
                'name' => 'John Doe',
                'firstName' => 'John',
                'lastName' => 'Doe',
                'address1' => '550 South Winchester blvd.',
                'address2' => 'P.O. Box 850',
                'country' => 'US',
                'phone' => '+1(202)555-1234',
                'email' => 'John.Doe@email.com',
            ]),
            'amount' => '500.00',
            'currency' => 'EUR',
            'countryCode' => 'DE',
            'transactionId' => '9457892347623478',
        ];
    }

    protected function getSerializerMock()
    {
        $dir = __DIR__ . '/../vendor/igaponov/wirecard-php-api/src/Serializer/Metadata';

        return SerializerBuilder::create()
            ->addMetadataDir($dir, 'Wirecard\Element')
            ->build();
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

    public function testEnrollmentSuccess()
    {
        $this->setMockHttpResponse('EnrollmentSuccess.txt');
        $response = $this->gateway->enrollment($this->paymentOptions)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('C242720181323966504820', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testEnrollmentFailure()
    {
        $this->setMockHttpResponse('EnrollmentFailure.txt');
        $response = $this->gateway->enrollment($this->paymentOptions)->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Cardholder not participating.', $response->getMessage());
    }

    public function testPreauthorizationSuccess()
    {
        $this->setMockHttpResponse('PreauthorizationSuccess.txt');
        $response = $this->gateway->preauthorization($this->paymentOptions)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('C898756138191214481743', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testPreauthorizationFailure()
    {
        $this->setMockHttpResponse('PreauthorizationFailure.txt');
        $response = $this->gateway->preauthorization($this->paymentOptions)->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Authorization Declined.', $response->getMessage());
    }
}
