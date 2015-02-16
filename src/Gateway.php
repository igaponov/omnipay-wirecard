<?php

namespace Omnipay\Wirecard;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

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

    public function setSerializer($value)
    {
        return $this->setParameter('serializer', $value);
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function enrollment(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\EnrollmentRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function preauthorization(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\PreauthorizationRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\CaptureRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function reversal(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\ReversalRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function query(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\QueryRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function bookBack(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Wirecard\Message\BookBackRequest', $parameters);
    }
}
