<?php

namespace Appsco\Accounts\ApiBundle\Tests\DependencyInjection;

use Appsco\Accounts\ApiBundle\DependencyInjection\AppscoAccountsApiExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class AppscoAccountsApiExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldLoadWithEmptyConfiguration()
    {
        $configs = array();
        $extension = new AppscoAccountsApiExtension();
        $containerBuilder = new ContainerBuilder(new ParameterBag());
        $extension->load($configs, $containerBuilder);

        $this->assertTrue($containerBuilder->hasDefinition('appsco_accounts_api.client'));
        $this->assertTrue($containerBuilder->getDefinition('appsco_accounts_api.client')->isPublic());
        $this->assertTrue($containerBuilder->hasDefinition('appsco_accounts_api.oauth'));
        $this->assertTrue($containerBuilder->getDefinition('appsco_accounts_api.oauth')->isPublic());
    }

    /**
     * @test
     */
    public function shouldLoadPublicServices()
    {
        $configs = array();
        $extension = new AppscoAccountsApiExtension();
        $containerBuilder = new ContainerBuilder(new ParameterBag());
        $extension->load($configs, $containerBuilder);

        $this->assertTrue($containerBuilder->hasDefinition('appsco_accounts_api.client'));
        $this->assertTrue($containerBuilder->getDefinition('appsco_accounts_api.client')->isPublic());
        $this->assertTrue($containerBuilder->hasDefinition('appsco_accounts_api.oauth'));
        $this->assertTrue($containerBuilder->getDefinition('appsco_accounts_api.oauth')->isPublic());
    }


}