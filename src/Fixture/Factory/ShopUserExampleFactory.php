<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Fixture\Factory;

use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ShopUserExampleFactory as BaseShopUserExampleFactory;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopUserExampleFactory extends BaseShopUserExampleFactory
{
    public function create(array $options = []): ShopUserInterface
    {
        $user = parent::create($options);

        /** @var CustomerTrustpilotAwareInterface $customer */
        $customer = $user->getCustomer();
        $customer->setTrustpilotEnabled(
            $options['trustpilot_enabled']
        );

        return $user;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('trustpilot_enabled', true)
            ->setAllowedTypes('trustpilot_enabled', 'bool')
        ;
    }
}
