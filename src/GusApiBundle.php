<?php

namespace MMierzynski\GusApi;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class GusApiBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->enumNode('env')
                ->values(['test', 'prod'])
                ->end()
            ->end()
        ->end();
    }


    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.xml');
        
        $config['env'] = isset($config['env']) ? $config['env'] : 'test';

        $container
            ->parameters()
            ->set('gus_api.env', $config['env']);
    }
}