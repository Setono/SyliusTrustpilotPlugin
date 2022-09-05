<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Repository;

use DateTimeInterface;
use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface InvitationRepositoryInterface extends RepositoryInterface
{
    /**
     * @return list<InvitationInterface>
     */
    public function findNew(string $orderState = OrderInterface::STATE_FULFILLED, int $limit = 100): array;

    /**
     * Will remove invitations older than the given threshold
     */
    public function removeOlderThan(DateTimeInterface $threshold): void;
}
