<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class CompositeOrderEligibilityCheckerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container->getDefinition('setono_sylius_trustpilot.order_eligibility_checker')->setArguments([
            array_map(
                static function ($id): Reference {
                static function (string $id): Reference {
                    return new Reference($id);
                },
                array_keys($container->findTaggedServiceIds('setono_sylius_trustpilot.order_eligibility_checker'))
            ),
        ]);
    }
}
