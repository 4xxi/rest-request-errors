<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('rest_request_error');
        $root = $treeBuilder->getRootNode();

        $root
            ->children()
                ->booleanNode('use_exception_listener')->defaultFalse()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}