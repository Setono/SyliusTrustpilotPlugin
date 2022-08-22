<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Repository;

use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Setono\SyliusTrustpilotPlugin\Workflow\InvitationWorkflow;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Webmozart\Assert\Assert;

class InvitationRepository extends EntityRepository implements InvitationRepositoryInterface
{
    public function findNew(): array
    {
        $objs = $this->findBy([
            'state' => InvitationWorkflow::STATE_INITIAL,
        ]);

        Assert::allIsInstanceOf($objs, InvitationInterface::class);

        return $objs;
    }
}