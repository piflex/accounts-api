<?php

namespace Appsco\Accounts\ApiBundle\Tests\Model;

use Appsco\Accounts\ApiBundle\Model\Certificate;

class CertificateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldConstructWithNoArguments()
    {
        new Certificate();
    }

    /**
     * @test
     */
    public function shouldConstructWithParameters()
    {
        new Certificate(new \DateTime('now'), new \DateTime('now +1 year'), 'certificate data');
    }

    /**
     * @test
     */
    public function shouldSetValidFrom()
    {
        $certificate = new Certificate();
        $certificate->setValidFrom($expectedValue = new \DateTime());
        $this->assertEquals($expectedValue, $certificate->getValidFrom());
    }

    /**
     * @test
     */
    public function shouldSetValidTo()
    {
        $certificate = new Certificate();
        $certificate->setValidTo($expectedValue = new \DateTime());
        $this->assertEquals($expectedValue, $certificate->getValidTo());
    }

    /**
     * @test
     */
    public function shouldSetCertificate()
    {
        $certificate = new Certificate();
        $certificate->setCertificate($expectedValue = 'certificate data');
        $this->assertEquals($expectedValue, $certificate->getCertificate());
    }

} 