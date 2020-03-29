<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CoverdMoneyExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('form_types.xml');

        if (\in_array('twig', $config['templating']['engines'])) {
            $loader->load('twig_extension.xml');
        }

        if (\in_array('php', $config['templating']['engines'])) {
            $loader->load('templating_helper.xml');
        }

        $this->remapParameters($config, $container, [
            'currencies' => 'coverd_money.currencies',
            'reference_currency' => 'coverd_money.reference_currency',
            'decimals' => 'coverd_money.decimals',
        ]);
    }

    /**
     * @return void
     */
    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (isset($config[$name])) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }
}
