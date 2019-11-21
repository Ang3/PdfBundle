<?php

namespace Ang3\Bundle\PdfBundle\DependencyInjection;

use Ang3\Component\Pdf\Command\GenerateCommand;
use Ang3\Component\Pdf\Command\MergeCommand;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
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
                    ->defaultValue(GenerateCommand::DEFAULT_BIN_PATH)
                ->end()
            ->end()
            ->children()
                ->scalarNode('pdfunite_path')
                    ->cannotBeEmpty()
                    ->defaultValue(MergeCommand::DEFAULT_BIN_PATH)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
