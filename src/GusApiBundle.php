<?php

namespace MMierzynski\GusApi;

use MMierzynski\GusApi\Exception\BundleMisconfigurationException;
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
                ->arrayNode('regon')
                    ->children()
                        ->enumNode('env')
                            ->values(['test', 'prod'])
                            ->end()
                        ->scalarNode('api_key')
                            ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }


    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.xml');
        
        if (isset($config['regon'])) {
            if (empty($config['regon']['env']) || empty($config['regon']['api_key'])) {
                throw new BundleMisconfigurationException('Wrong configuration for REGON API. Required keys: \'env\' and \'api_key\' under \'regon\' key');
            }

            //$config['regon']['env'] = isset($config['regon']['env']) ? $config['regon']['env'] : null;
        }

        $container
            ->parameters()
            ->set('gus_api.regon.env', $config['regon']['env'])
            ->set('gus_api.regon.api_key', $config['regon']['api_key']);
    }
}