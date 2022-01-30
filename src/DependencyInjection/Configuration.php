<?php
namespace ICS\ToolsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder('tools');
        $treeBuilder->getRootNode()->children()
            ->enumNode('theme')
                ->values(['default','classic','bootstrap-5'])
                ->defaultValue('bootstrap-5')
            ->end()
        ;

        return $treeBuilder;
    }

}
