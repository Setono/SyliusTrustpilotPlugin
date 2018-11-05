<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Provider;

interface PreQualifiedOrdersProviderInterface
{
    /**
     * @return array
     */
    public function getOrders(): array;
}
