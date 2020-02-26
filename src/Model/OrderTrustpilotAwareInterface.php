<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use Sylius\Component\Core\Model\OrderInterface as BaseOrderInterface;

interface OrderTrustpilotAwareInterface extends BaseOrderInterface
{
    public function getTrustpilotEmailsSent(): int;

    public function setTrustpilotEmailsSent(int $trustpilotEmailsSent): void;
}
