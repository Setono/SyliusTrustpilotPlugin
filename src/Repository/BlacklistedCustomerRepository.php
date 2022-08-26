<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Repository;

use Setono\SyliusTrustpilotPlugin\Model\BlacklistedCustomerInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Webmozart\Assert\Assert;

class BlacklistedCustomerRepository extends EntityRepository implements BlacklistedCustomerRepositoryInterface
{
    public function findOneByEmail(string $email): ?BlacklistedCustomerInterface
    {
        $obj = $this->createQueryBuilder('o')
            ->andWhere('o.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        Assert::nullOrIsInstanceOf($obj, BlacklistedCustomerInterface::class);

        return $obj;
    }

    public function isBlacklisted(string $email): bool
    {
        return $this->findOneByEmail($email) !== null;
    }
}
