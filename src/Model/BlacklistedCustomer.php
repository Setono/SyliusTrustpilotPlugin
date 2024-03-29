<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;

class BlacklistedCustomer implements BlacklistedCustomerInterface
{
    use TimestampableTrait;

    protected ?int $id = null;

    protected ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
