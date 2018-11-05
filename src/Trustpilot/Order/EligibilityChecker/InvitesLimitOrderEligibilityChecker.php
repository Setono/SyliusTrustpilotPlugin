<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\CustomerInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;

/**
 * Class InvitesLimitOrderEligibilityChecker
 */
final class InvitesLimitOrderEligibilityChecker implements OrderEligibilityCheckerInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var int
     */
    protected $limit;

    /**
     * InvitesLimitOrderEligibilityChecker constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param int $limit
     */
    public function __construct(OrderRepositoryInterface $orderRepository, int $limit)
    {
        $this->orderRepository = $orderRepository;
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

        return array_sum($customer->getOrders()->map(function(OrderInterface $order){
            return $order->getTrustpilotEmailsSent();
        })->toArray()) < $this->limit;
    }
}
