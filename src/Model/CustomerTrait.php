<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

/**
 * Trait CustomerTrait
 */
trait CustomerTrait
{
    /**
     * @var bool
     */
    protected $trustpilotEnabled = true;

    /**
     * {@inheritdoc}
     */
    public function isTrustpilotEnabled(): bool
    {
        return $this->trustpilotEnabled;
    }

    /**
     * {@inheritdoc}
     */
    public function setTrustpilotEnabled(bool $trustpilotEnabled): void
    {
        $this->trustpilotEnabled = $trustpilotEnabled;
    }
}
