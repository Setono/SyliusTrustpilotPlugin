<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusTrustpilotExtension extends AbstractResourceExtension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(/** @scrutinizer ignore-type */$this->getConfiguration([], $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('setono_sylius_trustpilot.trustpilot_email', $config['trustpilot_email']);
        $container->setParameter('setono_sylius_trustpilot.process_latest_days', $config['process_latest_days']);
        $container->setParameter('setono_sylius_trustpilot.send_in_days', $config['send_in_days']);
        $container->setParameter('setono_sylius_trustpilot.invites_limit', $config['invites_limit']);

        $loader->load('services.xml');
    }
}
