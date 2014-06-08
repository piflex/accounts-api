<?php

namespace Appsco\Accounts\ApiBundle\Model;

use JMS\Serializer\Annotation as JMS;

class Certificate
{
    /**
     * @var \DateTime
     * @JMS\Type("DateTime<'Y-m-d'>")
     */
    protected $validFrom;

    /**
     * @var \DateTime
     * @JMS\Type("DateTime<'Y-m-d'>")
     */
    protected $validTo;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $certificate;



    /**
     * @param \DateTime|null $validFrom
     * @param \DateTime|null $validTo
     * @param string|null $certificate
     */
    public function __construct(\DateTime $validFrom = null, \DateTime $validTo = null, $certificate = null)
    {
        $this->validFrom = $validFrom;
        $this->validTo = $validTo;
        $this->certificate = $certificate;
    }



    /**
     * @return string
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * @return \DateTime
     * @JMS\Type("DateTime<'Y-m-d'>")
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * @return \DateTime
     * @JMS\Type("DateTime<'Y-m-d'>")
     */
    public function getValidTo()
    {
        return $this->validTo;
    }

    /**
     * @param string $certificate
     * @return $this|Certificate
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;
        return $this;
    }

    /**
     * @param \DateTime $validFrom
     * @return $this|Certificate
     */
    public function setValidFrom($validFrom)
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    /**
     * @param \DateTime $validTo
     * @return $this|Certificate
     */
    public function setValidTo($validTo)
    {
        $this->validTo = $validTo;
        return $this;
    }

} 