<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\OrderInterface;

/**
 * Interface OrderEligibilityCheckerInterface
 */
interface OrderEligibilityCheckerInterface
{
    public function isEligible(OrderInterface $order): bool;
}
