<?php

declare(strict_types=1);

namespace AppBundle\Model;

use Setono\SyliusTrustpilotPlugin\Model\OrderInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

/**
 * Class Order
 */
class Order extends BaseOrder implements OrderInterface
{
    use OrderTrait;
}
