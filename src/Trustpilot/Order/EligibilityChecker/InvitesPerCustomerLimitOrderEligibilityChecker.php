<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;

final class InvitesPerCustomerLimitOrderEligibilityChecker implements OrderEligibilityCheckerInterface
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
    public function isEligible(OrderTrustpilotAwareInterface $order): bool
    {
        if (!$this->limit) {
            return true;
        }

        /** @var CustomerTrustpilotAwareInterface $customer */
        $customer = $order->getCustomer();

        return array_sum($customer->getOrders()->map(function (OrderTrustpilotAwareInterface $order) {
            return $order->getTrustpilotEmailsSent();
        })->toArray()) < $this->limit;
    }
}
