<?php

namespace Omnipay\Wirecard;

use JMS\Serializer\SerializerInterface;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Wirecard\Message\AbstractRequest;
use Omnipay\Wirecard\Message\TransactionBuilder\EnrollmentTransactionBuilder;
use Omnipay\Wirecard\Message\TransactionBuilder\PaymentTransactionBuilder;
use Omnipay\Wirecard\Message\TransactionBuilder\ReferencedTransactionBuilder;

/**
 * @method AbstractRequest createRequest(string $class, array $parameters)
 */
class Gateway extends AbstractGateway
{
    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'Wirecard';
    }

    public function getDefaultParameters()
    {
        return array(
            'username' => '',
            'password' => '',
            'signature' => '',
            'testMode' => false,
        );
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getSignature()
    {
        return $this->getParameter('signature');
    }

    public function setSignature($value)
    {
        return $this->setParameter('signature', $value);
    }

    public function getSerializer()
    {
        return $this->getParameter('serializer');
    }

    public function setSerializer(SerializerInterface $value)
    {
        return $this->setParameter('serializer', $value);
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function enrollment(array $parameters = array())
    {
        $request = $this->createRequest('\Omnipay\Wirecard\Message\EnrollmentCheckRequest', $parameters);
        $request->setTransactionBuilder(new EnrollmentTransactionBuilder($request));

        return $request;
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function preauthorization(array $parameters = array())
    {
        $request = $this->createRequest('\Omnipay\Wirecard\Message\PreauthorizationRequest', $parameters);
        $request->setTransactionBuilder(new PaymentTransactionBuilder($request));

        return $request;
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function capture(array $parameters = array())
    {
        $request = $this->createRequest('\Omnipay\Wirecard\Message\CaptureRequest', $parameters);
        $request->setTransactionBuilder(new ReferencedTransactionBuilder($request));

        return $request;
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function purchase(array $parameters = array())
    {
        $request = $this->createRequest('\Omnipay\Wirecard\Message\PurchaseRequest', $parameters);
        $request->setTransactionBuilder(new PaymentTransactionBuilder($request));

        return $request;
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function reversal(array $parameters = array())
    {
        $request = $this->createRequest('\Omnipay\Wirecard\Message\ReversalRequest', $parameters);
        $request->setTransactionBuilder(new ReferencedTransactionBuilder($request));

        return $request;
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function query(array $parameters = array())
    {
        $request = $this->createRequest('\Omnipay\Wirecard\Message\QueryRequest', $parameters);
        $request->setTransactionBuilder(new ReferencedTransactionBuilder($request));

        return $request;
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function bookBack(array $parameters = array())
    {
        $request = $this->createRequest('\Omnipay\Wirecard\Message\BookBackRequest', $parameters);
        $request->setTransactionBuilder(new ReferencedTransactionBuilder($request));

        return $request;
    }
}
