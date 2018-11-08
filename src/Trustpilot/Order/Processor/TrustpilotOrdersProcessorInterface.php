<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor;

use Symfony\Component\Console\Output\OutputInterface;

interface TrustpilotOrdersProcessorInterface
{
    public function process(?OutputInterface $output = null): void;
}
