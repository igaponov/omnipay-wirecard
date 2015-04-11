<?php

namespace Omnipay\Wirecard\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * @method \Wirecard\Element\Response getData()
 */
class Response extends AbstractResponse
{
    public function isRedirect()
    {
        return $this->getData()->isRedirect();
    }

    public function getMessage()
    {
        return $this->getData()->getMessage();
    }

    public function getCode()
    {
        return $this->getData()->getCode();
    }

    public function getTransactionReference()
    {
        return $this->getData()->getProcessingGuWid();
    }

    public function getRedirectUrl()
    {
        return $this->getData()->getUrl();
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->getData()->isSuccessful();
    }

    public function getToken()
    {
        return $this->getData()->getToken();
    }
}
