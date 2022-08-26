<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;

final class CompositeInvitationEligibilityChecker implements InvitationEligibilityCheckerInterface
{
    /** @var list<InvitationEligibilityCheckerInterface> */
    private array $checkers = [];

    public function add(InvitationEligibilityCheckerInterface $invitationEligibilityChecker): void
    {
        $this->checkers[] = $invitationEligibilityChecker;
    }

    public function check(InvitationInterface $invitation): EligibilityCheck
    {
        $eligible = true;
        $reasons = [];

        foreach ($this->checkers as $checker) {
            $check = $checker->check($invitation);
            if (!$check->eligible) {
                $eligible = false;
                $reasons[] = $check->reasons;
            }
        }

        return new EligibilityCheck($eligible, array_merge(...$reasons));
    }
}
