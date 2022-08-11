<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Factory;

use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;

final class InvitationFactory implements InvitationFactoryInterface
{
    private FactoryInterface $decorated;

    public function __construct(FactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function createNew(): InvitationInterface
    {
        /** @var object|InvitationInterface $obj */
        $obj = $this->decorated->createNew();
        Assert::isInstanceOf($obj, InvitationInterface::class);

        return $obj;
    }

    public function createWithOrder(OrderInterface $order): InvitationInterface
    {
        $obj = $this->createNew();
        $obj->setOrder($order);

        return $obj;
    }
}
