<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Factory;

use Setono\SyliusTrustpilotPlugin\Model\BlacklistedCustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface BlacklistedCustomerFactoryInterface extends FactoryInterface
{
    public function createNew(): BlacklistedCustomerInterface;

    public function createWithEmail(string $email): BlacklistedCustomerInterface;
}
