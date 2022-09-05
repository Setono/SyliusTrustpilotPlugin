<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Pruner;

use DateTimeImmutable;
use Setono\SyliusTrustpilotPlugin\Repository\InvitationRepositoryInterface;

final class Pruner implements PrunerInterface
{
    private InvitationRepositoryInterface $invitationRepository;

    private int $pruneOlderThan;

    public function __construct(InvitationRepositoryInterface $invitationRepository, int $pruneOlderThan)
    {
        $this->invitationRepository = $invitationRepository;
        $this->pruneOlderThan = $pruneOlderThan;
    }

    public function prune(): void
    {
        $this->invitationRepository->removeOlderThan(
            new DateTimeImmutable(sprintf('-%d minutes', $this->pruneOlderThan))
        );
    }
}
