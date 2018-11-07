<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;

interface OrderEligibilityCheckerInterface
{
    public function isEligible(OrderTrustpilotAwareInterface $order): bool;
}
