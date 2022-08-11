<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Repository;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface ChannelConfigurationRepositoryInterface extends RepositoryInterface
{
    /**
     * If $channel is null this method returns true if one or more channel configurations exist
     * If $channel is not null it returns true if a channel configuration exists for the given channel
     */
    public function hasAny(ChannelInterface $channel = null): bool;
}
