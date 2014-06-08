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
    protected $certificates = array();


    /**
     * @param string|null $clientId
     * @param string|null $ownerId
     * @param array $certificates
     */
    public function __construct($clientId = null, $ownerId = null, array $certificates = array())
    {
        $this->certificates = $certificates;
        $this->clientId = $clientId;
        $this->ownerId = $ownerId;
    }


    /**
     * @param Certificate $certificate
     * @return $this|CertificateList
     */
    public function add(Certificate $certificate)
    {
        $this->certificates[] = $certificate;

        return $this;
    }


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

    /**
     * @param \Appsco\Accounts\ApiBundle\Model\Certificate[]|array $certificates
     * @return $this|CertificateList
     */
    public function setCertificates($certificates)
    {
        $this->certificates = $certificates;
        return $this;
    }

    /**
     * @param string $clientId
     * @return $this|CertificateList
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @param int $ownerId
     * @return $this|CertificateList
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
        return $this;
    }

} 