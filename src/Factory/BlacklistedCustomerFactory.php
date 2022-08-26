<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Factory;

use Setono\SyliusTrustpilotPlugin\Model\BlacklistedCustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;

final class BlacklistedCustomerFactory implements BlacklistedCustomerFactoryInterface
{
    private FactoryInterface $decorated;

    public function __construct(FactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function createNew(): BlacklistedCustomerInterface
    {
        /** @var object|BlacklistedCustomerInterface $obj */
        $obj = $this->decorated->createNew();
        Assert::isInstanceOf($obj, BlacklistedCustomerInterface::class);

        return $obj;
    }

    public function createWithEmail(string $email): BlacklistedCustomerInterface
    {
        $obj = $this->createNew();
        $obj->setEmail($email);

        return $obj;
    }
}
