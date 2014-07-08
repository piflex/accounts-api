<?php

namespace Appsco\Accounts\ApiBundle\Tests\DependencyInjection\Security\Factory;

use Appsco\Accounts\ApiBundle\DependencyInjection\Security\Factory\AppscoFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

class AppscoFactoryConfiguration implements ConfigurationInterface
{
    /** @var AppscoFactory  */
    private $factory;

    /** @var  string */
    private $name;



    public function __construct(AppscoFactory $factory, $name)
    {
        $this->factory = $factory;
        $this->name = $name;
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root($this->name);
        $this->factory->addConfiguration($rootNode);
        return $treeBuilder;
    }


    /**
     * @param array $config
     * @return array
     */
    public function processConfiguration(array $config) {
        $processor = new Processor();
        $result = $processor->processConfiguration($this,
            array($this->name => $config)
        );
        return $result;
    }

} 