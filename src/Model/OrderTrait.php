<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

trait OrderTrait
{
    /** @ORM\Column(name="trustpilot_emails_sent", type="smallint", nullable=false, options={"default": 0}) */
    protected int $trustpilotEmailsSent = 0;

    public function getTrustpilotEmailsSent(): int
    {
        return $this->trustpilotEmailsSent;
    }

    public function setTrustpilotEmailsSent(int $trustpilotEmailsSent): void
    {
        $this->trustpilotEmailsSent = $trustpilotEmailsSent;
    }
}
