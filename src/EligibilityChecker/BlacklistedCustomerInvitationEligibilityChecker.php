<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Setono\SyliusTrustpilotPlugin\Repository\BlacklistedCustomerRepositoryInterface;

final class BlacklistedCustomerInvitationEligibilityChecker implements InvitationEligibilityCheckerInterface
{
    private BlacklistedCustomerRepositoryInterface $blacklistedCustomerRepository;

    public function __construct(BlacklistedCustomerRepositoryInterface $blacklistedCustomerRepository)
    {
        $this->blacklistedCustomerRepository = $blacklistedCustomerRepository;
    }

    public function check(InvitationInterface $invitation): EligibilityCheck
    {
        $email = $invitation->getEmail();

        return null !== $email && $this->blacklistedCustomerRepository->isBlacklisted($email) ?
            new EligibilityCheck(false, sprintf('The email %s is blacklisted', $email)) : new EligibilityCheck(true);
    }
}
