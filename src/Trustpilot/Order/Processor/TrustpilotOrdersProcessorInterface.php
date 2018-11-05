<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor;

interface TrustpilotOrdersProcessorInterface
{
    public function process(): void;
}
