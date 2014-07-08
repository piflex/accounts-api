<?php

namespace Appsco\Accounts\ApiBundle\Tests\DependencyInjection;

use Appsco\Accounts\ApiBundle\Client\AccountsClient;
use Appsco\Accounts\ApiBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldDefaultValuesOnEmptyConfig()
    {
        $configs = array();
        $config = $this->processConfiguration($configs);

        $this->assertArrayHasKey('scheme', $config);
        $this->assertArrayHasKey('domain', $config);
        $this->assertArrayHasKey('sufix', $config);
        $this->assertArrayHasKey('default_redirect_uri', $config);
        $this->assertArrayHasKey('client_id', $config);
        $this->assertArrayHasKey('client_secret', $config);
        $this->assertArrayHasKey('ca_path', $config);
        $this->assertArrayHasKey('loose_ssl', $config);
        $this->assertArrayHasKey('auth_type', $config);

        $this->assertEquals('https', $config['scheme']);
        $this->assertEquals('accounts.dev.appsco.com', $config['domain']);
        $this->assertEquals('', $config['sufix']);
        $this->assertEquals('', $config['default_redirect_uri']);
        $this->assertEquals('', $config['client_id']);
        $this->assertEquals('', $config['client_secret']);
        $this->assertEquals('/usr/lib/ssl/certs', $config['ca_path']);
        $this->assertEquals(false, $config['loose_ssl']);
        $this->assertEquals(AccountsClient::AUTH_TYPE_ACCESS_TOKEN, $config['auth_type']);
    }

    /**
     * @test
     */
    public function shouldAllowSchemeConfig()
    {
        $configs = array('appsco_accounts_api'=>array(
            'scheme' => 'http'
        ));
        $this->processConfiguration($configs);
    }

    /**
     * @test
     */
    public function shouldAllowDomainConfig()
    {
        $configs = array('appsco_accounts_api'=>array(
            'domain' => 'accounts.appsco.com'
        ));
        $this->processConfiguration($configs);
    }

    /**
     * @test
     */
    public function shouldAllowDefaultRedirectUriConfig()
    {
        $configs = array('appsco_accounts_api'=>array(
            'default_redirect_uri' => 'http://my-site.com'
        ));
        $this->processConfiguration($configs);
    }

    /**
     * @test
     */
    public function shouldAllowClientIdConfig()
    {
        $configs = array('appsco_accounts_api'=>array(
            'client_id' => '1234567890'
        ));
        $this->processConfiguration($configs);
    }

    /**
     * @test
     */
    public function shouldAllowClientSecretConfig()
    {
        $configs = array('appsco_accounts_api'=>array(
            'client_secret' => '1234567890123123123123123123'
        ));
        $this->processConfiguration($configs);
    }

    /**
     * @test
     */
    public function shouldAllowLooseSslConfig()
    {
        $configs = array('appsco_accounts_api'=>array(
            'loose_ssl' => true
        ));
        $this->processConfiguration($configs);
    }

    protected function processConfiguration(array $configs)
    {
        $configuration = new Configuration();
        $processor = new Processor();

        return $processor->processConfiguration($configuration, $configs);
    }

} 