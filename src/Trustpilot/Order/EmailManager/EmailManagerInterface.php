<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EmailManager;

use Sylius\Component\Core\Model\OrderInterface;

interface EmailManagerInterface
{
    /**
     * @param OrderInterface $order
     */
    public function sendTrustpilotEmail(OrderInterface $order): void;
}
