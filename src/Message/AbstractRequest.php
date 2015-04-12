<?php

namespace Omnipay\Wirecard\Message;

use Guzzle\Http\ClientInterface;
use JMS\Serializer\SerializerInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Wirecard\Message\TransactionBuilder\TransactionBuilderInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Wirecard\Element\Job;
use Wirecard\Element\Request;
use Wirecard\Element\Transaction;
use Wirecard\Element\WireCard;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * @var TransactionBuilderInterface
     */
    private $transactionBuilder;

    public function __construct(
        TransactionBuilderInterface $transactionBuilder,
        ClientInterface $httpClient,
        HttpRequest $httpRequest
    ) {
        $this->transactionBuilder = $transactionBuilder;
        parent::__construct($httpClient, $httpRequest);
    }

    /**
     * @return Job
     */
    abstract protected function buildData();

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->createRequest($this->buildData());
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer()
    {
        return $this->getParameter('serializer');
    }

    /**
     * @param SerializerInterface $serializer
     * @return $this
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        return $this->setParameter('serializer', $serializer);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ?
            'https://c3-test.wirecard.com/secure/ssl-gateway' :
            'https://c3.wirecard.com/secure/ssl-gateway';
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

    public function getContentType()
    {
        return $this->getParameter('contentType');
    }

    public function setContentType($value)
    {
        return $this->setParameter('contentType', $value);
    }

    public function getTransactionReference()
    {
        return $this->getParameter('transactionReference');
    }

    public function setTransactionReference($value)
    {
        return $this->setParameter('transactionReference', $value);
    }

    public function getCountryCode()
    {
        return $this->getParameter('countryCode');
    }

    public function setCountryCode($value)
    {
        return $this->setParameter('countryCode', $value);
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $response = $this->httpClient->post(
            $this->getEndpoint(),
            ['Content-Type' => $this->getContentType()],
            $data
        )
            ->setAuth($this->getUsername(), $this->getPassword())
            ->send();

        $wireCard = $this->getSerializer()->deserialize($response->getBody(true), 'Wirecard\Element\WireCard', 'xml');

        return $this->createResponse($wireCard->response);
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }

    protected function createRequest(Job $job)
    {
        $request = new Request($job);

        /** @var WireCard $wireCard */
        $wireCard = WireCard::createWithRequest($request);

        return $this->getSerializer()->serialize($wireCard, 'xml');
    }

    /**
     * @return Transaction
     */
    protected function buildTransaction()
    {
        return $this->transactionBuilder->build();
    }
}
