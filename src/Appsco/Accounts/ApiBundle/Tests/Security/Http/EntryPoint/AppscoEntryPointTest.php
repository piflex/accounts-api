<?php

namespace Appsco\Accounts\ApiBundle\Tests\Security\Http\EntryPoint;

use Appsco\Accounts\ApiBundle\Security\Http\EntryPoint\AppscoEntryPoint;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AppscoEntryPointTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldConstruct()
    {
        new AppscoEntryPoint($this->getHttpUtilsMock(), '/some/path');
    }

    /**
     * @test
     */
    public function shouldReturnRedirectResponse()
    {
        $request = new Request();
        $path = '/some/path';

        $httpUtilsMock = $this->getHttpUtilsMock();
        $httpUtilsMock->expects($this->once())
            ->method('createRedirectResponse')
            ->with($request, $path)
            ->will($this->returnValue($expectedRedirect = new RedirectResponse('/redirect/to')));

        $entryPoint = new AppscoEntryPoint($httpUtilsMock, $path);

        $response = $entryPoint->start($request);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertSame($expectedRedirect, $response);
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Security\Http\HttpUtils
     */
    private function getHttpUtilsMock()
    {
        return $this->getMock('Symfony\Component\Security\Http\HttpUtils');
    }


} 