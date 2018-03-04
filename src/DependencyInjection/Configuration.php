<?php

namespace Ang3\Bundle\PdfBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration du bundle.
 *
 * @author Joanis ROUANET
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('ang3_pdf');

        $rootNode
            ->isRequired()
            ->children()
                ->scalarNode('chrome_path')
                    ->cannotBeEmpty()
                    ->defaultValue('/usr/bin/google-chrome-stable')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
