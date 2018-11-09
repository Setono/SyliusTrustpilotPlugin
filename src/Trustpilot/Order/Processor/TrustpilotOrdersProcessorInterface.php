<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor;

use Psr\Log\LoggerAwareInterface;

interface TrustpilotOrdersProcessorInterface extends LoggerAwareInterface
{
    public function process(): void;
}
