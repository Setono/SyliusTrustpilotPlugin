<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Repository;

use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Setono\SyliusTrustpilotPlugin\Workflow\InvitationWorkflow;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class InvitationRepository extends EntityRepository implements InvitationRepositoryInterface
{
    public function findNew(int $limit = 100): array
    {
        $qb = $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->setParameter('state', InvitationWorkflow::STATE_INITIAL);

        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        /** @var list<InvitationInterface> $objs */
        $objs = $qb->getQuery()->getResult();

        return $objs;
    }
}
