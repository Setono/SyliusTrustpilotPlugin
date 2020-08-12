<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    private const MINIMAL_DAYS_GAP = 2;

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('setono_sylius_trustpilot');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->addDefaultsIfNotSet()
            ->beforeNormalization()
                ->always(function ($values) {
                    if (!isset($values['send_in_days'])) {
                        // We should specify default value here
                        // as we need it before normalization
                        $values['send_in_days'] = 7;
                    } elseif ($values['send_in_days'] < 1) {
                        throw new \InvalidArgumentException("Parameter 'send_in_days' should not be less than 1");
                    }

                    if (empty($values['process_latest_days'])) {
                        $values['process_latest_days'] = $values['send_in_days'] + self::MINIMAL_DAYS_GAP;
                    } elseif ($values['process_latest_days'] - $values['send_in_days'] < self::MINIMAL_DAYS_GAP) {
                        throw new \InvalidArgumentException(sprintf(
                            "Parameter 'process_latest_days' (%s) should be greater than 'send_in_days' + " . self::MINIMAL_DAYS_GAP . '. Recommended value: %s',
                            $values['process_latest_days'],
                            $values['send_in_days'] + self::MINIMAL_DAYS_GAP
                        ));
                    }

                    return $values;
                })
            ->end()
        ;

        $rootNodeChildren = $rootNode->children();
        $rootNodeChildren->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM);
        $rootNodeChildren->scalarNode('trustpilot_email')->isRequired()->cannotBeEmpty();
        $rootNodeChildren->scalarNode('process_latest_days')->defaultValue(0);
        $rootNodeChildren->scalarNode('send_in_days');
        $rootNodeChildren->scalarNode('invites_limit')->defaultValue(0);

        return $treeBuilder;
    }
}
