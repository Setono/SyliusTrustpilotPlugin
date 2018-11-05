<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    private const MINIMAL_DAYS_GAP = 2;

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('setono_sylius_trustpilot');
        $rootNode
            ->addDefaultsIfNotSet()
            ->beforeNormalization()
                ->always(function ($values) {
                    if ($values['send_in_days'] < 1) {
                        throw new \Exception("Parameter 'send_in_days' should not be less than 1");
                    }

                    if (empty($values['process_latest_days'])) {
                        $values['process_latest_days'] = $values['send_in_days'] + self::MINIMAL_DAYS_GAP;
                    } else {
                        if ($values['process_latest_days'] - $values['send_in_days'] < self::MINIMAL_DAYS_GAP) {
                            throw new \Exception(sprintf(
                                "Parameter 'process_latest_days' (%s) should be grater than 'send_in_days'. Recommended value: %s",
                                $values['process_latest_days'],
                                $values['send_in_days'] + self::MINIMAL_DAYS_GAP
                            ));
                        }
                    }

                    return $values;
                })
            ->end()
            ->children()
            ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->scalarNode('trustpilot_email')->cannotBeEmpty()->end()
            ->scalarNode('process_latest_days')->defaultValue(0)->end()
            ->scalarNode('send_in_days')->defaultValue(7)->end()
            ->scalarNode('invites_limit')->defaultValue(0)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
