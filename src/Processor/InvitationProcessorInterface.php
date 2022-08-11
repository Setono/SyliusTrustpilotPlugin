<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Processor;

use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;

interface InvitationProcessorInterface
{
    public function process(InvitationInterface $invitation): void;
}
