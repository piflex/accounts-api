<?php

namespace Appsco\Accounts\ApiBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class AppscoFactory extends AbstractFactory
{
    public function __construct()
    {
        // otherwise it will end up with
        // throw new SessionUnavailableException('Your session has timed out, or you have disabled cookies.');
        // on each new session
        $this->addOption('require_previous_session', false);
    }


    public function addConfiguration(NodeDefinition $node)
    {
        /** @var ArrayNodeDefinition $node */
        parent::addConfiguration($node);

        $node
            ->treatTrueLike(array())
            ->children()
                ->scalarNode('oauth_start_path')->defaultValue('/appsco/start')->cannotBeEmpty()->end()
                ->scalarNode('oauth_callback_path')->defaultValue('/appsco/callback')->cannotBeEmpty()->end()
            ->end();
        ;
    }

    protected function createListener($container, $id, $config, $userProvider)
    {
        $this->addOption('oauth_start_path', $config['oauth_start_path']);
        $this->addOption('oauth_callback_path', $config['oauth_callback_path']);

        return parent::createListener($container, $id, $config, $userProvider);
    }

    /**
     * Subclasses must return the id of a service which implements the
     * AuthenticationProviderInterface.
     *
     * @param ContainerBuilder $container
     * @param string $id The unique id of the firewall
     * @param array $config The options array for this listener
     * @param string $userProviderId The id of the user provider
     *
     * @return string never null, the id of the authentication provider
     */
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $providerId = 'security.authentication.provider.appsco.'.$id;
        $provider = new DefinitionDecorator('security.authentication.provider.appsco');
        $provider->replaceArgument(0, new Reference($userProviderId));
        $container->setDefinition(
            $providerId,
            $provider
        );

        return $providerId;
    }

    /**
     * Subclasses must return the id of the listener template.
     *
     * Listener definitions should inherit from the AbstractAuthenticationListener
     * like this:
     *
     *    <service id="my.listener.id"
     *             class="My\Concrete\Classname"
     *             parent="security.authentication.listener.abstract"
     *             abstract="true" />
     *
     * In the above case, this method would return "my.listener.id".
     *
     * @return string
     */
    protected function getListenerId()
    {
        return 'security.authentication.listener.appsco';
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'appsco';
    }

}