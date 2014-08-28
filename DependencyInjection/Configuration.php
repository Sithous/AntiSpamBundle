<?php

namespace Sithous\AntiSpamBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration class.
 *
 * @author      William DURAND <william.durand1@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $builder->root('sithous_anti_spam')
            ->children()
                ->arrayNode('identifiers')
                    ->prototype('array')
                        ->children()
                            ->booleanNode('track_ip')
                                ->isRequired()
                            ->end()
                            ->booleanNode('track_user')
                                ->isRequired()
                            ->end()
                            ->integerNode('max_time')
                                ->min(1)
                                ->isRequired()
                            ->end()
                            ->integerNode('max_calls')
                                ->min(1)
                                ->isRequired()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
