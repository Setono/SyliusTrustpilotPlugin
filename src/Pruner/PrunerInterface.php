<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Pruner;

interface PrunerInterface
{
    /**
     * Will prune invitations table
     */
    public function prune(): void;
}
