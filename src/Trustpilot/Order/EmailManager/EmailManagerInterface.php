<?php

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EmailManager;

use Setono\SyliusTrustpilotPlugin\Model\OrderInterface;

interface EmailManagerInterface
{
    /**
     * @param OrderInterface $order
     */
    public function sendTrustpilotEmail(OrderInterface $order): void;
}
