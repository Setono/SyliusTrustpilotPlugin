<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Sylius\Component\Core\Model\ShopUserInterface;

final class ClientsTrustpilotEnabledOrderEligibilityChecker implements OrderEligibilityCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function isEligible(OrderTrustpilotAwareInterface $order): bool
    {
        /** @var ShopUserInterface $user */
        $user = $order->getUser();

        /** @var CustomerTrustpilotAwareInterface $customer */
        $customer = $user->getCustomer();

        return $customer->isTrustpilotEnabled();
    }
}
