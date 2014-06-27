<?php

namespace Appsco\Accounts\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('appsco_accounts_api');

        $root->children()
            ->scalarNode('scheme')->defaultValue('https')->cannotBeEmpty()->end()
            ->scalarNode('domain')->defaultValue('https')->cannotBeEmpty()->end()
            ->scalarNode('sufix')->defaultValue('')->end()
            ->scalarNode('default_redirect_uri')->defaultValue('')->end()
            ->scalarNode('client_id')->defaultValue('')->end()
            ->scalarNode('client_secret')->defaultValue('')->end()
            ->scalarNode('ca_path')->defaultValue('/usr/lib/ssl/certs')->end()
        ->end();

        return $treeBuilder;
    }

} 