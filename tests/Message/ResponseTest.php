<?php

namespace Omnipay\Wirecard\Message;

use PHPUnit_Framework_TestCase;

class ResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param $method
     * @return \PHPUnit_Framework_MockObject_MockObject|Response
     */
    protected function getResponseMock($method)
    {
        $data = $this->getMockBuilder('\Wirecard\Element\Response')
            ->setMethods([$method])
            ->disableOriginalConstructor()
            ->getMock();

        $data->expects($this->once())->method($method);

        $response = $this->getMockBuilder('\Omnipay\Wirecard\Message\Response')
            ->setMethods(['getData'])
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->any())->method('getData')->will($this->returnValue($data));

        return $response;
    }

    public function testIsRedirectCallsDataMethod()
    {
        $response = $this->getResponseMock('isRedirect');
        $response->isRedirect();
    }

    public function testGetMessageCallsDataMethod()
    {
        $response = $this->getResponseMock('getMessage');
        $response->getMessage();
    }

    public function testGetCodeCallsDataMethod()
    {
        $response = $this->getResponseMock('getCode');
        $response->getCode();
    }

    public function testGetTransactionReferenceCallsDataMethod()
    {
        $response = $this->getResponseMock('getProcessingGuWid');
        $response->getTransactionReference();
    }

    public function testGetRedirectUrlCallsDataMethod()
    {
        $response = $this->getResponseMock('getUrl');
        $response->getRedirectUrl();
    }

    public function testIsSuccessfulCallsDataMethod()
    {
        $response = $this->getResponseMock('isSuccessful');
        $response->isSuccessful();
    }

    public function testGetTokenCallsDataMethod()
    {
        $response = $this->getResponseMock('getToken');
        $response->getToken();
    }
}
