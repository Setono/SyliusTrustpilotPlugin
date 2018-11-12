<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;

final class InvitesPerCustomerLimitOrderEligibilityChecker implements OrderEligibilityCheckerInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var int
     */
    private $limit;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param int $limit
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        int $limit
    ) {
        $this->orderRepository = $orderRepository;
        $this->limit = $limit;
    }

    /**
     * Eligible if there are no per customer limit
     * Eligible if customer received less emails than limit
     *
     * {@inheritdoc}
     */
    public function isEligible(OrderTrustpilotAwareInterface $order): bool
    {
        if (!$this->limit) {
            return true;
        }

        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();

        /** @var OrderTrustpilotAwareInterface[] $orders */
        $orders = $this->orderRepository->findByCustomer($customer);

        return array_sum(array_map(function (OrderTrustpilotAwareInterface $order) {
            return $order->getTrustpilotEmailsSent();
        }, $orders)) < $this->limit;
    }
}
