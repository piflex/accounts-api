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

} 