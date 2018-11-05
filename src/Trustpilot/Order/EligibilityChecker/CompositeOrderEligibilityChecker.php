<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\OrderInterface;
use Webmozart\Assert\Assert;

/**
 * Class CompositeOrderEligibilityChecker
 */
final class CompositeOrderEligibilityChecker implements OrderEligibilityCheckerInterface
{
    /**
     * @var OrderEligibilityCheckerInterface[]
     */
    private $orderEligibilityCheckers;

    /**
     * @param OrderEligibilityCheckerInterface[] $orderEligibilityCheckers
     */
    public function __construct(array $orderEligibilityCheckers)
    {
        Assert::notEmpty($orderEligibilityCheckers);
        Assert::allIsInstanceOf($orderEligibilityCheckers, OrderEligibilityCheckerInterface::class);

        $this->orderEligibilityCheckers = $orderEligibilityCheckers;
    }

    /**
     * {@inheritdoc}
     */
    public function isEligible(OrderInterface $order): bool
    {
        foreach ($this->orderEligibilityCheckers as $orderEligibilityChecker) {
            if (!$orderEligibilityChecker->isEligible($order)) {
                return false;
            }
        }

        return true;
    }
}
