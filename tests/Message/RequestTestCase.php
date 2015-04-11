<?php

namespace Omnipay\Wirecard\Message;

use PHPUnit_Framework_TestCase;

class RequestTestCase extends PHPUnit_Framework_TestCase
{
    public function testGetDataBuildsProperData()
    {
        $class = str_replace('RequestTest', '', get_class($this));
        $method = substr(strrchr($class, '\\'), 1);
        /** @var \PHPUnit_Framework_MockObject_MockObject|AbstractRequest $request */
        $request = $this->getMockBuilder($class . 'Request')
            ->setMethods(['createRequest', 'buildTransaction', 'getSignature'])
            ->disableOriginalConstructor()
            ->getMock();

        $transaction = $this->getMock('\Wirecard\Element\Transaction');
        $request->expects($this->once())->method('buildTransaction')->will($this->returnValue($transaction));
        $request->expects($this->once())->method('getSignature')->will($this->returnValue('0000003164DF5F22'));
        $request->expects($this->once())->method('createRequest')
            ->with($this->attribute($this->isInstanceOf('Wirecard\Element\Action\\' . $method), lcfirst($method)));

        $request->getData();
    }
}
