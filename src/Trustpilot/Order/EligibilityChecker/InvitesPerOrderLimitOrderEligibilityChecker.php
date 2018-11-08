<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;

final class InvitesPerOrderLimitOrderEligibilityChecker implements OrderEligibilityCheckerInterface
{
    /**
     * Eligible only when no emails was sent for this order
     *
     * {@inheritdoc}
     */
    public function isEligible(OrderTrustpilotAwareInterface $order): bool
    {
        return $order->getTrustpilotEmailsSent() === 0;
    }
}
