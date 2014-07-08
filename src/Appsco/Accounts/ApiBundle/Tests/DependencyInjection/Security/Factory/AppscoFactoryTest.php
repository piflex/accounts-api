<?php

namespace Appsco\Accounts\ApiBundle\Tests\DependencyInjection\Security\Factory;

use Appsco\Accounts\ApiBundle\DependencyInjection\Security\Factory\AppscoFactory;
use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class AppscoFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function couldBeConstructedWithoutAnyArguments()
    {
        new AppscoFactory();
    }

    /**
     * @test
     */
    public function shouldAllowGetKey()
    {
        $factory = new AppscoFactory();
        $this->assertEquals('appsco', $factory->getKey());
    }

    /**
     * @test
     */
    public function shouldAllowGetPosition()
    {
        $factory = new AppscoFactory();
        $this->assertEquals('form', $factory->getPosition());
    }


    /**
     * @test
     */
    public function shouldAddOAuthStartPathToConfigurationWithExpectedDefaultValue()
    {
        $factory = new AppscoFactory();
        $config = new AppscoFactoryConfiguration($factory, 'name');
        $treeBuilder = $config->getConfigTreeBuilder();

        /** @var $tree ArrayNode */
        $tree = $treeBuilder->buildTree();
        $children = $tree->getChildren();

        $this->assertArrayHasKey('oauth_start_path', $children);
        $this->assertEquals('/appsco/start', $children['oauth_start_path']->getDefaultValue());
    }

    /**
     * @test
     */
    public function shouldAddOAuthCallbackPathToConfigurationWithExpectedDefaultValue()
    {
        $factory = new AppscoFactory();
        $config = new AppscoFactoryConfiguration($factory, 'name');
        $treeBuilder = $config->getConfigTreeBuilder();

        /** @var $tree ArrayNode */
        $tree = $treeBuilder->buildTree();
        $children = $tree->getChildren();

        $this->assertArrayHasKey('oauth_callback_path', $children);
        $this->assertEquals('/appsco/callback', $children['oauth_callback_path']->getDefaultValue());
    }

    /**
     * @test
     */
    public function shouldReturnArrayOfStrings()
    {
        $factory = new AppscoFactory();
        $configProcessor = new AppscoFactoryConfiguration($factory, 'name');
        $config = $configProcessor->processConfiguration(array());
        $containerBuilder = new ContainerBuilder(new ParameterBag());

        $result = $factory->create($containerBuilder, 'main', $config, 'user.provider.id', null);

        $this->assertInternalType('array', $result);
        $this->assertCount(3, $result);
        $this->assertContainsOnly('string', $result);
    }

    /**
     * @test
     */
    public function shouldReturnAuthenticationProviderWithPostfixID()
    {
        $factory = new AppscoFactory();
        $configProcessor = new AppscoFactoryConfiguration($factory, 'name');
        $config = $configProcessor->processConfiguration(array());
        $containerBuilder = new ContainerBuilder(new ParameterBag());

        list($providerID) = $factory->create($containerBuilder, 'main', $config, 'user.provider.id', null);
        $this->assertStringStartsWith('security.authentication.provider.appsco.', $providerID);
        $this->assertStringEndsWith('.main', $providerID);
    }

    /**
     * @test
     */
    public function shouldReturnAuthenticationListenerWithPostfixID()
    {
        $factory = new AppscoFactory();
        $configProcessor = new AppscoFactoryConfiguration($factory, 'name');
        $config = $configProcessor->processConfiguration(array());
        $containerBuilder = new ContainerBuilder(new ParameterBag());

        list(,$listenerID) = $factory->create($containerBuilder, 'main', $config, 'user.provider.id', null);
        $this->assertStringStartsWith('security.authentication.listener.appsco.', $listenerID);
        $this->assertStringEndsWith('.main', $listenerID);
    }

    /**
     * @test
     */
    public function shouldReturnEntryPointWithPostfixId()
    {
        $factory = new AppscoFactory();
        $configProcessor = new AppscoFactoryConfiguration($factory, 'name');
        $config = $configProcessor->processConfiguration(array());
        $containerBuilder = new ContainerBuilder(new ParameterBag());

        list(,,$entryPointID) = $factory->create($containerBuilder, 'main', $config, 'user.provider.id', null);
        $this->assertStringStartsWith('security.authentication.appsco_entry_point.', $entryPointID);
        $this->assertStringEndsWith('.main', $entryPointID);
    }

    /**
     * @test
     */
    public function shouldCreateAuthenticationProvider()
    {
        $factory = new AppscoFactory();
        $configProcessor = new AppscoFactoryConfiguration($factory, 'name');
        $config = $configProcessor->processConfiguration(array());
        $containerBuilder = new ContainerBuilder(new ParameterBag());

        list($providerID) = $factory->create($containerBuilder, 'main', $config, 'user.provider.id', null);

        $this->assertTrue($containerBuilder->hasDefinition($providerID));
    }

    /**
     * @test
     */
    public function shouldCreateAuthenticationListener()
    {
        $factory = new AppscoFactory();
        $configProcessor = new AppscoFactoryConfiguration($factory, 'name');
        $config = $configProcessor->processConfiguration(array());
        $containerBuilder = new ContainerBuilder(new ParameterBag());

        list(,$listenerID) = $factory->create($containerBuilder, 'main', $config, 'user.provider.id', null);

        $this->assertTrue($containerBuilder->hasDefinition($listenerID));
    }

    /**
     * @test
     */
    public function shouldCreateEntryPoint()
    {
        $factory = new AppscoFactory();
        $configProcessor = new AppscoFactoryConfiguration($factory, 'name');
        $config = $configProcessor->processConfiguration(array());
        $containerBuilder = new ContainerBuilder(new ParameterBag());

        list(,,$entryPointID) = $factory->create($containerBuilder, 'main', $config, 'user.provider.id', null);

        $this->assertTrue($containerBuilder->hasDefinition($entryPointID));
    }

} 