<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;

class ChannelConfigurationRepository extends EntityRepository implements ChannelConfigurationRepositoryInterface
{
    public function hasAny(ChannelInterface $channel = null): bool
    {
        $qb = $this->createQueryBuilder('o')->select('COUNT(o)');

        if (null !== $channel) {
            $qb->andWhere('o.channel = :channel')->setParameter('channel', $channel);
        }

        return (int) $qb->getQuery()->getSingleScalarResult() > 0;
    }
}
