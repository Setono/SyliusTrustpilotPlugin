<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Message\Command;

use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Webmozart\Assert\Assert;

final class ProcessInvitation implements CommandInterface
{
    /** @readonly */
    public int $invitationId;

    /**
     * @param int|InvitationInterface $invitation
     */
    public function __construct($invitation)
    {
        if ($invitation instanceof InvitationInterface) {
            $invitation = $invitation->getId();
        }

        Assert::integer($invitation);

        $this->invitationId = $invitation;
    }
}
