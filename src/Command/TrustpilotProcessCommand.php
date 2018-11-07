<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Command;

use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor\TrustpilotOrdersProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TrustpilotProcessCommand extends Command
{
    /**
     * @var TrustpilotOrdersProcessorInterface
     */
    protected $trustpilotOrdersProcessor;

    /**
     * @param TrustpilotOrdersProcessorInterface $trustpilotOrdersProcessor
     */
    public function __construct(
        TrustpilotOrdersProcessorInterface $trustpilotOrdersProcessor
    ) {
        $this->trustpilotOrdersProcessor = $trustpilotOrdersProcessor;

        parent::__construct(null);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('setono:trustpilot:process')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->trustpilotOrdersProcessor->process();
    }
}
