<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Fixture;

use Sylius\Bundle\CoreBundle\Fixture\ShopUserFixture as BaseShopUserFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ShopUserFixture extends BaseShopUserFixture
{
    public function getName(): string
    {
        return 'setono_sylius_trustpilot_shop_user';
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        parent::configureResourceNode($resourceNode);

        $resourceNode
            ->children()
                ->booleanNode('trustpilot_enabled')->end()
        ;
    }
}
