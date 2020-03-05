<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrait;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Sylius\Component\Core\Model\Order as BaseOrder;

/**
 * @ORM\Table(name="sylius_order")
 * @ORM\Entity()
 */
class Order extends BaseOrder implements OrderTrustpilotAwareInterface
{
    use OrderTrait;
}
