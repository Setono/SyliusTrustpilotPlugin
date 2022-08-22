<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Repository;

use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface InvitationRepositoryInterface extends RepositoryInterface
{
    /**
     * @return list<InvitationInterface>
     */
    public function findPending(): array;
}
