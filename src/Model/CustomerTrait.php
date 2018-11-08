<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait CustomerTrait
{
    /**
     * @var bool
     */
    protected $trustpilotEnabled = true;

    /**
     * @return ArrayCollection|OrderTrustpilotAwareInterface[]
     */
    abstract public function getOrders(): Collection;

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

    /**
     * {@inheritdoc}
     */
    public function getTrustpilotEmailsSent(): int
    {
        return (int) array_sum($this->getOrders()->map(function (OrderTrustpilotAwareInterface $order) {
            return $order->getTrustpilotEmailsSent();
        })->toArray());
    }
}
