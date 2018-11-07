<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\CustomerInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderInterface;

final class InvitesLimitOrderEligibilityChecker implements OrderEligibilityCheckerInterface
{
    /**
     * @var int
     */
    private $limit;

    /**
     * @param int $limit
     */
    public function __construct(int $limit)
    {
        $this->limit = $limit;
    }

    /**
     * {@inheritdoc}
     */
    public function isEligible(OrderInterface $order): bool
    {
        if (!$this->limit) {
            return true;
        }

        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();

        return array_sum($customer->getOrders()->map(function (OrderInterface $order) {
            return $order->getTrustpilotEmailsSent();
        })->toArray()) < $this->limit;
    }
}
