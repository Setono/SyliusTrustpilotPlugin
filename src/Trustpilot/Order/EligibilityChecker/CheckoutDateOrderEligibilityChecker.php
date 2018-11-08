<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;

final class CheckoutDateOrderEligibilityChecker implements OrderEligibilityCheckerInterface
{
    /**
     * @var int
     */
    private $sendInDays;

    /**
     * CheckoutDateOrderEligibilityChecker constructor.
     *
     * @param int $sendInDays
     */
    public function __construct(int $sendInDays)
    {
        $this->sendInDays = $sendInDays;
    }

    /**
     * Eligible only when order checkout completed given amount of days ago
     *
     * {@inheritdoc}
     */
    public function isEligible(OrderTrustpilotAwareInterface $order): bool
    {
        if (null === $order->getCheckoutCompletedAt()) {
            return false;
        }

        $pastDate = new \DateTime(sprintf('-%s day', $this->sendInDays));

        return $order->getCheckoutCompletedAt()->getTimestamp() <= $pastDate->getTimestamp();
    }
}
