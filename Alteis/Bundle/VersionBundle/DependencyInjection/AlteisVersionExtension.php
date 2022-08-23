<?php

namespace Alteis\Bundle\VersionBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\Kernel;
use Alteis\Bundle\VersionBundle\AlteisVersionBundle;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AlteisVersionExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        if (!$config['enabled'])
            return;

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('version.xml');
        $loader->load('helper.xml');
        $loader->load('twig.xml');
        $loader->load('writer.xml');
        $loader->load('config.xml');

        if (version_compare(AlteisVersionBundle::getSymfonyVersion(Kernel::VERSION), '2.1.0', '<')) {
            $tagForOldSymfony = array (
                'data_collector' =>
                array (
                    0 =>
                    array (
                        'template'  => 'AlteisVersionBundle:Version:toolbar2.0',
                        'id'        => 'version',
                    ),
                ),
            );

            $container->getDefinition('alteis.version.data_collector')->clearTags();
            $container->getDefinition('alteis.version.data_collector')->setTags($tagForOldSymfony);
        }

        if ($config['version']){
            $container->getDefinition('alteis.version.data_collector')
                ->replaceArgument(1, $config['version']);
        }

        else
        {
            if ($config['file']){
                $container->getDefinition('alteis.version.data_collector')
                    ->replaceArgument(0, $config['file']);
            }

            if ($config['suffix']){
                $container->getDefinition('alteis.version.data_collector')
                    ->replaceArgument(1, $config['suffix']);
            }

            if ($config['file']){
                $container->getDefinition('alteis.version.writer')
                    ->replaceArgument(0, $config['file']);
            }
        }

        if (!$config['toolbar'])
            $container->getDefinition('alteis.version.data_collector')->setTags(array());

        if (isset($config['block']) && $config['block']['enabled'])
        {
            $loader->load('block.xml');
            $container->getDefinition('alteis.version.block')
                    ->replaceArgument(1, $config['block']['position'])
                    ->replaceArgument(2, $config['block']['prefix']);
        }
    }
}
