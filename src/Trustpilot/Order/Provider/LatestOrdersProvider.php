<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Provider;

use Sylius\Component\Core\Repository\OrderRepositoryInterface;

class LatestOrdersProvider implements PreQualifiedOrdersProviderInterface
{
    protected OrderRepositoryInterface $orderRepository;

    protected int $latestDays;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        int $latestDays
    ) {
        $this->orderRepository = $orderRepository;
        $this->latestDays = $latestDays;
    }

    public function getOrders(): array
    {
        return $this->orderRepository->createListQueryBuilder()
            ->andWhere('o.checkoutCompletedAt > :date')
            ->setParameter('date', new \DateTime(sprintf('-%s day', $this->latestDays)))
            ->getQuery()
            ->getResult();
    }
}
