<?php

namespace EC\Behat\PoetryExtension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use EC\Behat\PoetryExtension\Context\Initializer\PoetryAwareInitializer;
use EC\Behat\PoetryExtension\Context\Services\PoetryMock;
use EC\Poetry\Poetry;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class PoetryExtension
 *
 * @package EC\Behat\PoetryExtension\ServiceContainer
 */
class PoetryExtension implements ExtensionInterface
{
    const POETRY_SERVICE = 'poetry';

    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'poetry';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
          ->children()
            ->arrayNode('mock')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('host')->defaultValue('localhost')->end()
                    ->scalarNode('port')->defaultValue('28080')->end()
                ->end()
            ->end()
            ->arrayNode('poetry')
                ->isRequired()
                ->children()
                    ->scalarNode('base_url')->isRequired()->end()
                    ->scalarNode('notification_endpoint')->defaultValue('/notification')->end()
                    ->scalarNode('notification_username')->defaultValue('username')->end()
                    ->scalarNode('notification_password')->defaultValue('password')->end()
                ->end()
            ->end()
        ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $container->setParameter('poetry.parameters', $config);
        $this->loadContextInitializer($container);
        $this->loadPoetry($container);
        $this->loadPoetryMock($container);
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
    }

    /**
     * Load service definition into container builder.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    private function loadContextInitializer(ContainerBuilder $container)
    {
        $definition = new Definition(PoetryAwareInitializer::class, [
            new Reference('poetry'),
            new Reference('poetry_mock'),
            '%poetry.parameters%',
        ]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, ['priority' => 0]);
        $container->setDefinition('poetry.context_initializer', $definition);
    }

    /**
     * Load service definition into container builder.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    private function loadPoetry(ContainerBuilder $container)
    {
        $container->setDefinition('poetry', new Definition(Poetry::class));
    }

    /**
     * Load service definition into container builder.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    private function loadPoetryMock(ContainerBuilder $container)
    {
        $container->setDefinition('poetry_mock', new Definition(PoetryMock::class, [
            new Reference('poetry'),
            '%poetry.parameters%',
        ]));
    }
}
