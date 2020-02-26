<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Provider;

use Safe\DateTime;
use function Safe\sprintf;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;

class LatestOrdersProvider implements PreQualifiedOrdersProviderInterface
{
    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var int */
    protected $latestDays;

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
            ->setParameter('date', new DateTime(sprintf('-%s day', $this->latestDays)))
            ->getQuery()
            ->getResult();
    }
}
