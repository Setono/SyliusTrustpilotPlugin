<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;

interface InvitationEligibilityCheckerInterface
{
    public function check(InvitationInterface $invitation): EligibilityCheck;
}
