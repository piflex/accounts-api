<?php

namespace Appsco\Accounts\ApiBundle\Tests\Security\Http\Firewall;

class AppscoAuthenticationListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldExtendAbstractAuthenticationListener()
    {
        $this->assertTrue(is_subclass_of(
            'Appsco\Accounts\ApiBundle\Security\Http\Firewall\AppscoAuthenticationListener',
            'Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener'
        ));
    }


} 