<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Command;

use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor\TrustpilotOrdersProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TrustpilotProcessCommand
 */
class TrustpilotProcessCommand extends Command
{
    /**
     * @var TrustpilotOrdersProcessorInterface
     */
    protected $trustpilotOrdersProcessor;

    /**
     * TrustpilotProcessCommand constructor.
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
    protected function configure()
    {
        $this
            ->setName('setono:sylius-trustpilot:process')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->trustpilotOrdersProcessor->process();

        $output->writeln('Done');
    }
}
