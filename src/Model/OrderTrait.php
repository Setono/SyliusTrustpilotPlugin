<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

/**
 * Trait OrderTrait
 */
trait OrderTrait
{
    /**
     * @var int
     */
    protected $trustpilotEmailsSent = 0;

    /**
     * {@inheritdoc}
     */
    public function getTrustpilotEmailsSent(): int
    {
        return $this->trustpilotEmailsSent;
    }

    /**
     * {@inheritdoc}
     */
    public function setTrustpilotEmailsSent(int $trustpilotEmailsSent): void
    {
        $this->trustpilotEmailsSent = $trustpilotEmailsSent;
    }
}
