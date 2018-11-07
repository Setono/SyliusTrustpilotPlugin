<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use Sylius\Component\Core\Model\CustomerInterface as BaseCustomerInterface;

interface CustomerTrustpilotAwareInterface extends BaseCustomerInterface
{
    /**
     * @return bool
     */
    public function isTrustpilotEnabled(): bool;

    /**
     * @param bool $trustpilotEnabled
     */
    public function setTrustpilotEnabled(bool $trustpilotEnabled): void;
}
