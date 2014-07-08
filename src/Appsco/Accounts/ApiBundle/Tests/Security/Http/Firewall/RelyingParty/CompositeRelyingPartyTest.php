<?php

namespace Appsco\Accounts\ApiBundle\Tests\Security\Http\Firewall\RelyingParty;

use Appsco\Accounts\ApiBundle\Security\Http\Firewall\RelyingParty\CompositeRelyingParty;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompositeRelyingPartyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldAddReplyingParty()
    {
        $composite = new CompositeRelyingParty();
        $composite->add($this->getRelyingPartyMock());
    }

    /**
     * @test
     */
    public function shouldNotSupportIfNoChildAdded()
    {
        $composite = new CompositeRelyingParty();
        $request = new Request();
        $this->assertFalse($composite->supports($request));
    }

    /**
     * @test
     */
    public function shouldSupportIfAnyReplyingPartySupports()
    {
        $request = new Request();

        $rp1 = $this->getRelyingPartyMock();
        $rp1->expects($this->any())
            ->method('supports')
            ->with($request)
            ->will($this->returnValue(false));

        $rp2 = $this->getRelyingPartyMock();
        $rp2->expects($this->any())
            ->method('supports')
            ->with($request)
            ->will($this->returnValue(true));

        $rp3 = $this->getRelyingPartyMock();
        $rp3->expects($this->never())
            ->method('supports');

        $composite = new CompositeRelyingParty();
        $composite->add($rp1);
        $composite->add($rp2);
        $composite->add($rp3);

        $this->assertTrue($composite->supports($request));
    }

    /**
     * @test
     */
    public function shouldNotSupportIfNoReplyingPartySupport()
    {
        $request = new Request();

        $rp1 = $this->getRelyingPartyMock();
        $rp1->expects($this->any())
            ->method('supports')
            ->with($request)
            ->will($this->returnValue(false));

        $rp2 = $this->getRelyingPartyMock();
        $rp2->expects($this->any())
            ->method('supports')
            ->with($request)
            ->will($this->returnValue(false));

        $composite = new CompositeRelyingParty();
        $composite->add($rp1);
        $composite->add($rp2);

        $this->assertFalse($composite->supports($request));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unsupported request
     */
    public function shouldThrowOnManageUnsupportedRequest()
    {
        $composite = new CompositeRelyingParty();
        $request = new Request();
        $composite->manage($request);
    }

    /**
     * @test
     */
    public function shouldManage()
    {
        $request = new Request();

        $rp1 = $this->getRelyingPartyMock();
        $rp1->expects($this->any())
            ->method('supports')
            ->with($request)
            ->will($this->returnValue(false));
        $rp1->expects($this->never())
            ->method('manage');

        $rp2 = $this->getRelyingPartyMock();
        $rp2->expects($this->any())
            ->method('supports')
            ->with($request)
            ->will($this->returnValue(true));
        $rp2->expects($this->once())
            ->method('manage')
            ->with($request)
            ->will($this->returnValue($expectedResponse = new Response()));

        $rp3 = $this->getRelyingPartyMock();
        $rp3->expects($this->never())
            ->method('supports');
        $rp3->expects($this->never())
            ->method('manage');

        $composite = new CompositeRelyingParty();
        $composite->add($rp1);
        $composite->add($rp2);
        $composite->add($rp3);

        $response = $composite->manage($request);

        $this->assertSame($expectedResponse, $response);
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Appsco\Accounts\ApiBundle\Security\Http\Firewall\RelyingParty\CompositeRelyingParty
     */
    private function getRelyingPartyMock()
    {
        return $this->getMock('Appsco\Accounts\ApiBundle\Security\Http\Firewall\RelyingParty\CompositeRelyingParty');
    }

} 