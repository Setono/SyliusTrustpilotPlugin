<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Command;

use Setono\SyliusTrustpilotPlugin\Pruner\PrunerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PruneCommand extends Command
{
    protected static $defaultName = 'setono:sylius-trustpilot:prune';

    /** @var string|null */
    protected static $defaultDescription = 'Will prune the invitations table';

    private PrunerInterface $pruner;

    public function __construct(PrunerInterface $pruner)
    {
        parent::__construct();

        $this->pruner = $pruner;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->pruner->prune();

        return 0;
    }
}
