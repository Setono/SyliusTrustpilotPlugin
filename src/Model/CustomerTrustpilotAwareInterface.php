<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use Sylius\Component\Core\Model\CustomerInterface as BaseCustomerInterface;

interface CustomerTrustpilotAwareInterface extends BaseCustomerInterface
{
    public function isTrustpilotEnabled(): bool;

    public function setTrustpilotEnabled(bool $trustpilotEnabled): void;

    public function getTrustpilotEmailsSent(): int;
}
