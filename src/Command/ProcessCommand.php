<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Command;

use Setono\SyliusTrustpilotPlugin\Dispatcher\InvitationDispatcherInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessCommand extends Command
{
    protected static $defaultName = 'setono:sylius-trustpilot:process';

    /** @var string|null */
    protected static $defaultDescription = 'Will start the processing of invitations';

    private InvitationDispatcherInterface $invitationDispatcher;

    public function __construct(
        InvitationDispatcherInterface $invitationDispatcher
    ) {
        parent::__construct();

        $this->invitationDispatcher = $invitationDispatcher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->invitationDispatcher->dispatch();

        return 0;
    }
}
