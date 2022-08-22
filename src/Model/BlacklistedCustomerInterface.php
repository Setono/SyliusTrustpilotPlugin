<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use Sylius\Component\Resource\Model\TimestampableInterface;

interface BlacklistedCustomerInterface extends TimestampableInterface
{
    public function getEmail(): ?string;

    public function setEmail(string $email): void;
}
