<?php

namespace Omnipay\Wirecard\Message;

use JMS\Serializer\SerializerInterface;
use Omnipay\Tests\TestCase;
use Omnipay\Wirecard\Message\TransactionBuilder\TransactionBuilderInterface;

class AbstractRequestTest extends TestCase
{
    /**
     * @param array $methods
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractRequest
     */
    protected function getRequestMock(array $methods = [])
    {
        $methods[] = 'buildData';
        /** @var AbstractRequest $request */
        $request = $this->getMockBuilder('\Omnipay\Wirecard\Message\AbstractRequest')
            ->setMethods($methods)
            ->disableOriginalConstructor()
            ->getMock();
        $request->initialize();

        return $request;
    }

    public function testGetDataMethodCreatesRequestFromDataBuilder()
    {
        $job = $this->getMockBuilder('\Wirecard\Element\Job')->disableOriginalConstructor()->getMock();
        $request = $this->getRequestMock(['createRequest']);
        $request->expects($this->once())->method('buildData')->will($this->returnValue($job));
        $request->expects($this->once())->method('createRequest')->with($job);
        $request->getData();
    }

    public function testSerializer()
    {
        $request = $this->getRequestMock();
        /** @var SerializerInterface $serializer */
        $serializer = $this->getMock('\JMS\Serializer\SerializerInterface');
        $this->assertSame($request, $request->setSerializer($serializer));
        $this->assertSame($serializer, $request->getSerializer());
    }

    public function testTransactionBuilder()
    {
        $request = $this->getRequestMock();
        /** @var TransactionBuilderInterface $builder */
        $builder = $this->getMock('\Omnipay\Wirecard\Message\TransactionBuilder\TransactionBuilderInterface');
        $this->assertSame($request, $request->setTransactionBuilder($builder));
        $this->assertSame($builder, $request->getTransactionBuilder());
    }

    public function testEndpointInTestMode()
    {
        $request = $this->getRequestMock();
        $request->setTestMode(true);
        $this->assertSame('https://c3-test.wirecard.com/secure/ssl-gateway', $request->getEndpoint());
    }

    public function testEndpointInNonTestMode()
    {
        $request = $this->getRequestMock();
        $request->setTestMode(false);
        $this->assertSame('https://c3.wirecard.com/secure/ssl-gateway', $request->getEndpoint());
    }

    public function testUsername()
    {
        $request = $this->getRequestMock();
        $this->assertSame($request, $request->setUsername('user1'));
        $this->assertSame('user1', $request->getUsername());
    }

    public function testPassword()
    {
        $request = $this->getRequestMock();
        $this->assertSame($request, $request->setPassword('pwd'));
        $this->assertSame('pwd', $request->getPassword());
    }

    public function testSignature()
    {
        $request = $this->getRequestMock();
        $this->assertSame($request, $request->setSignature('sign'));
        $this->assertSame('sign', $request->getSignature());
    }

    public function testContentType()
    {
        $request = $this->getRequestMock();
        $this->assertSame($request, $request->setContentType('application/xml'));
        $this->assertSame('application/xml', $request->getContentType());
    }

    public function testTransactionReference()
    {
        $request = $this->getRequestMock();
        $this->assertSame($request, $request->setTransactionReference('0241085'));
        $this->assertSame('0241085', $request->getTransactionReference());
    }

    public function testCountryCode()
    {
        $request = $this->getRequestMock();
        $this->assertSame($request, $request->setCountryCode('DE'));
        $this->assertSame('DE', $request->getCountryCode());
    }

    public function testBuildTransactionMethodCallsTransactionBuilder()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|TransactionBuilderInterface $builder */
        $builder = $this->getMock('Omnipay\Wirecard\Message\TransactionBuilder\TransactionBuilderInterface', ['build']);
        $builder->expects($this->once())->method('build');

        $request = $this->getRequestMock();
        $request->setTransactionBuilder($builder);

        $class = new \ReflectionClass('\Omnipay\Wirecard\Message\AbstractRequest');
        $method = $class->getMethod('buildTransaction');
        $method->setAccessible(true);
        $method->invoke($request);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testBuildingWithoutBuilderThrowsException()
    {
        $request = $this->getRequestMock();
        $class = new \ReflectionClass('\Omnipay\Wirecard\Message\AbstractRequest');
        $method = $class->getMethod('buildTransaction');
        $method->setAccessible(true);
        $method->invoke($request);
    }

    public function testSendDataMethodCreatesAndSendsHttpRequest()
    {
        $httpResponse = $this->getMock('\Guzzle\Http\Message\Response', ['getBody'], [200]);
        $httpResponse->expects($this->once())->method('getBody')->with(true)->will($this->returnValue('resp_data'));

        $httpRequest = $this->getMock('\Guzzle\Http\Message\RequestInterface');
        $httpRequest->expects($this->once())->method('setAuth')
            ->with('usr', 'pwd')
            ->will($this->returnValue($httpRequest));
        $httpRequest->expects($this->once())->method('send')->will($this->returnValue($httpResponse));

        $httpClient = $this->getMock('\Guzzle\Http\ClientInterface');
        $httpClient->expects($this->once())->method('post')
            ->with('https://c3-test.wirecard.com/secure/ssl-gateway', ['Content-Type' => 'application/xml'], 'data')
            ->will($this->returnValue($httpRequest));

        /** @var \PHPUnit_Framework_MockObject_MockObject|SerializerInterface $serializer */
        $serializer = $this->getMock('\JMS\Serializer\SerializerInterface', ['serialize', 'deserialize']);
        $serializer->expects($this->once())->method('deserialize')
            ->with('resp_data')
            ->will($this->returnValue((object)['response' => 'resp']));

        /** @var \PHPUnit_Framework_MockObject_MockObject|AbstractRequest $request */
        $request = $this->getMock(
            '\Omnipay\Wirecard\Message\AbstractRequest',
            ['buildData'],
            [$httpClient, $this->getMock('\Symfony\Component\HttpFoundation\Request')]
        );
        $request->setTestMode(true);
        $request->setContentType('application/xml');
        $request->setUsername('usr');
        $request->setPassword('pwd');
        $request->setSerializer($serializer);

        $response = $request->sendData('data');
        $this->assertSame($request, $response->getRequest());
        $this->assertSame('resp', $response->getData());
    }

    public function testGetDataMethodCreatesRequestData()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|SerializerInterface $serializer */
        $serializer = $this->getMock('\JMS\Serializer\SerializerInterface', ['serialize', 'deserialize']);
        $serializer->expects($this->once())->method('serialize')
            ->with($this->isInstanceOf('\Wirecard\Element\WireCard'))
            ->will($this->returnValue('request_data'));

        $job = $this->getMockBuilder('\Wirecard\Element\Job')->disableOriginalConstructor()->getMock();

        /** @var \PHPUnit_Framework_MockObject_MockObject|AbstractRequest $request */
        $request = $this->getMockBuilder('\Omnipay\Wirecard\Message\AbstractRequest')
            ->setMethods(['buildData'])
            ->disableOriginalConstructor()
            ->getMock();
        $request->initialize(['serializer' => $serializer]);
        $request->expects($this->once())->method('buildData')->will($this->returnValue($job));

        $this->assertSame('request_data', $request->getData());
    }
}
