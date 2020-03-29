<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('coverd_money');
        /** @var ArrayNodeDefinition */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('currencies')
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('reference_currency')->isRequired()->cannotBeEmpty()->end()
                ->integerNode('decimals')->defaultValue(2)->min(0)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
