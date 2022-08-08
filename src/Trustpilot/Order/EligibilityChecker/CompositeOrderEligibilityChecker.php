<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Webmozart\Assert\Assert;

final class CompositeOrderEligibilityChecker implements OrderEligibilityCheckerInterface
{
    /** @var OrderEligibilityCheckerInterface[] */
    private array $orderEligibilityCheckers;

    /**
     * @param OrderEligibilityCheckerInterface[] $orderEligibilityCheckers
     */
    public function __construct(array $orderEligibilityCheckers)
    {
        Assert::notEmpty($orderEligibilityCheckers);
        Assert::allIsInstanceOf($orderEligibilityCheckers, OrderEligibilityCheckerInterface::class);

        $this->orderEligibilityCheckers = $orderEligibilityCheckers;
    }

    public function isEligible(OrderTrustpilotAwareInterface $order): bool
    {
        foreach ($this->orderEligibilityCheckers as $orderEligibilityChecker) {
            if (!$orderEligibilityChecker->isEligible($order)) {
                return false;
            }
        }

        return true;
    }
}
