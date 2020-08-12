<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Webmozart\Assert\Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @mixin CustomerInterface
 */
trait CustomerTrait
{
    /**
     * @ORM\Column(name="trustpilot_enabled", type="boolean", options={"default": 1})
     *
     * @var bool
     */
    protected $trustpilotEnabled = true;

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
        \assert($this instanceof CustomerInterface);

        return (int) array_sum($this->getOrders()->map(static function (OrderInterface $order): int {
            Assert::isInstanceOf($order, OrderTrustpilotAwareInterface::class);

            return $order->getTrustpilotEmailsSent();
        })->toArray());
    }
}
