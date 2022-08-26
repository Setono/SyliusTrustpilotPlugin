<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Repository;

use Setono\SyliusTrustpilotPlugin\Model\BlacklistedCustomerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface BlacklistedCustomerRepositoryInterface extends RepositoryInterface
{
    public function findOneByEmail(string $email): ?BlacklistedCustomerInterface;

    public function isBlacklisted(string $email): bool;
}
