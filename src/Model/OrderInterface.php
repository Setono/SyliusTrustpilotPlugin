<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use Sylius\Component\Core\Model\OrderInterface as BaseOrderInterface;

interface OrderInterface extends BaseOrderInterface
{
    /**
     * @return int
     */
    public function getTrustpilotEmailsSent(): int;

    /**
     * @param int $trustpilotEmailsSent
     */
    public function setTrustpilotEmailsSent(int $trustpilotEmailsSent): void;
}
