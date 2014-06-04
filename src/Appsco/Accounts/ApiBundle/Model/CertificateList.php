<?php

namespace Appsco\Accounts\ApiBundle\Model;

use JMS\Serializer\Annotation as JMS;

class CertificateList 
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $clientId;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    protected $ownerId;

    /**
     * @var array|Certificate[]
     * @JMS\Type("array<Appsco\Accounts\ApiBundle\Model\Certificate>")
     */
    protected $certificates;


    /**
     * @return \Appsco\Accounts\ApiBundle\Model\Certificate[]
     */
    public function getCertificates()
    {
        return $this->certificates;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return int
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }



} 