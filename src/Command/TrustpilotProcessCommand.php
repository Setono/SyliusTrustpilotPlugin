<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Command;

use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor\TrustpilotOrdersProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

class TrustpilotProcessCommand extends Command
{
    protected static $defaultName = 'setono:trustpilot:process';

    protected TrustpilotOrdersProcessorInterface $trustpilotOrdersProcessor;

    public function __construct(
        TrustpilotOrdersProcessorInterface $trustpilotOrdersProcessor
    ) {
        $this->trustpilotOrdersProcessor = $trustpilotOrdersProcessor;

        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->trustpilotOrdersProcessor->setLogger(new ConsoleLogger($output));
        $this->trustpilotOrdersProcessor->process();

        return 0;
    }
}
