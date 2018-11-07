<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\CustomerInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;

final class ClientsTrustpilotEnabledOrderEligibilityChecker implements OrderEligibilityCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function isEligible(OrderInterface $order): bool
    {
        /** @var ShopUserInterface $user */
        $user = $order->getUser();

        /** @var CustomerInterface $customer */
        $customer = $user->getCustomer();

        return $customer->isTrustpilotEnabled();
    }
}
