<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;

final class CustomersTrustpilotEnabledOrderEligibilityChecker implements OrderEligibilityCheckerInterface
{
    public function isEligible(OrderTrustpilotAwareInterface $order): bool
    {
        /** @var CustomerTrustpilotAwareInterface $customer */
        $customer = $order->getCustomer();

        return $customer->isTrustpilotEnabled();
    }
}
