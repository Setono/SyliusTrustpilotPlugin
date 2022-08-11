<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Factory;

use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface InvitationFactoryInterface extends FactoryInterface
{
    public function createNew(): InvitationInterface;

    public function createWithOrder(OrderInterface $order): InvitationInterface;
}
