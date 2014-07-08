<?php

namespace Appsco\Accounts\ApiBundle\Tests\Model;

use Appsco\Accounts\ApiBundle\Model\Certificate;
use Appsco\Accounts\ApiBundle\Model\CertificateList;

class CertificateListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldConstructWithNoArguments()
    {
        new CertificateList();
    }

    /**
     * @test
     */
    public function shouldConstructWithParameters()
    {
        new CertificateList('client_id', 123, array(new Certificate()));
    }

    /**
     * @test
     */
    public function shouldAddCertificate()
    {
        $list = new CertificateList();
        $this->assertCount(0, $list->getCertificates());
        $list->add($expectedCertificate = new Certificate());
        $this->assertCount(1, $list->getCertificates());
        $arr = $list->getCertificates();
        $this->assertSame($expectedCertificate, $arr[0]);
    }

    /**
     * @test
     */
    public function shouldSetClientId()
    {
        $list = new CertificateList();
        $list->setClientId($expectedValue = 'client id');
        $this->assertEquals($expectedValue, $list->getClientId());
    }

    /**
     * @test
     */
    public function shouldSetOwnerId()
    {
        $list = new CertificateList();
        $list->setOwnerId($expectedValue = 123);
        $this->assertEquals($expectedValue, $list->getOwnerId());
    }


    /**
     * @test
     */
    public function shouldSetCertificates()
    {
        $list = new CertificateList();
        $list->setCertificates(array($crt1 = new Certificate(), $crt2 = new Certificate()));
        $this->assertCount(2, $list->getCertificates());
        $arr = $list->getCertificates();
        $this->assertSame($crt1, $arr[0]);
        $this->assertSame($crt2, $arr[1]);
    }

}