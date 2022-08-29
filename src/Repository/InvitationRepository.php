<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Repository;

use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Setono\SyliusTrustpilotPlugin\Workflow\InvitationWorkflow;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Order\Model\OrderInterface;

class InvitationRepository extends EntityRepository implements InvitationRepositoryInterface
{
    public function findNew(string $orderState = OrderInterface::STATE_FULFILLED, int $limit = 100): array
    {
        $qb = $this->createQueryBuilder('i')
            ->join('i.order', 'o')
            ->andWhere('i.state = :state')
            ->andWhere('o.state = :orderState')
            ->setParameter('state', InvitationWorkflow::STATE_INITIAL)
            ->setParameter('orderState', $orderState)
        ;

        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        /** @var list<InvitationInterface> $objs */
        $objs = $qb->getQuery()->getResult();

        return $objs;
    }
}
