<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Dispatcher;

interface InvitationDispatcherInterface
{
    /**
     * Will dispatch invitations for processing
     */
    public function dispatch(): void;
}
