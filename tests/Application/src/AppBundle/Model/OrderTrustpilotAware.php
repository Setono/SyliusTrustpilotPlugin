<?php

declare(strict_types=1);

namespace AppBundle\Model;

use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

/**
 * Class Order
 */
class OrderTrustpilotAware extends BaseOrder implements OrderTrustpilotAwareInterface
{
    use OrderTrait;
}
