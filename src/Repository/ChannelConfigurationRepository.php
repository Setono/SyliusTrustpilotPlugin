<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Repository;

use Setono\SyliusTrustpilotPlugin\Model\ChannelConfigurationInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;
use Webmozart\Assert\Assert;

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

    public function findOneByChannel(ChannelInterface $channel): ?ChannelConfigurationInterface
    {
        $obj = $this->createQueryBuilder('o')
            ->andWhere('o.channel = :channel')
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getResult()
        ;

        Assert::nullOrIsInstanceOf($obj, ChannelConfigurationInterface::class);

        return $obj;
    }
}
