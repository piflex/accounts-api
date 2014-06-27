<?php

namespace Appsco\Accounts\ApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class AppscoAccountsApiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('appsco_accounts_api.url.scheme', $config['scheme']);
        $container->setParameter('appsco_accounts_api.url.domain', $config['domain']);
        $container->setParameter('appsco_accounts_api.url.sufix', $config['sufix']);
        $container->setParameter('appsco_accounts_api.default_redirect_uri', $config['default_redirect_uri']);
        $container->setParameter('appsco_accounts_api.client_id', $config['client_id']);
        $container->setParameter('appsco_accounts_api.client_secret', $config['client_secret']);
        $container->setParameter('appsco_accounts_api.ca_path', $config['ca_path']);
        $container->setParameter('appsco_accounts_api.loose_ssl', $config['loose_ssl']);
    }

} 