<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait CustomerTrait
{
    /**
     * @ORM\Column(name="trustpilot_enabled", type="boolean", options={"default": 1})
     *
     * @var bool
     */
    protected $trustpilotEnabled = true;

    /**
     * @return ArrayCollection|OrderTrustpilotAwareInterface[]
     */
    abstract public function getOrders(): Collection;

    public function isTrustpilotEnabled(): bool
    {
        return $this->trustpilotEnabled;
    }

    public function setTrustpilotEnabled(bool $trustpilotEnabled): void
    {
        $this->trustpilotEnabled = $trustpilotEnabled;
    }

    public function getTrustpilotEmailsSent(): int
    {
        return (int) array_sum($this->getOrders()->map(function (OrderTrustpilotAwareInterface $order) {
            return $order->getTrustpilotEmailsSent();
        })->toArray());
    }
}
