<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;

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

    /**
     * {@inheritdoc}
     */
    public function getTrustpilotEmailsSent(): int
    {
        /** @var ArrayCollection|OrderTrustpilotAwareInterface[] $orders */
        $orders = $this->getOrders();

        return (int)array_sum($orders->map(function (OrderTrustpilotAwareInterface $order) {
            return $order->getTrustpilotEmailsSent();
        })->toArray());
    }
}
