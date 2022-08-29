<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Dispatcher;

use Doctrine\Persistence\ManagerRegistry;
use Setono\DoctrineObjectManagerTrait\ORM\ORMManagerTrait;
use Setono\SyliusTrustpilotPlugin\Message\Command\ProcessInvitation;
use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Setono\SyliusTrustpilotPlugin\Repository\InvitationRepositoryInterface;
use Setono\SyliusTrustpilotPlugin\Workflow\InvitationWorkflow;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\WorkflowInterface;

final class InvitationDispatcher implements InvitationDispatcherInterface
{
    use ORMManagerTrait;

    private ?WorkflowInterface $workflow = null;

    private MessageBusInterface $commandBus;

    private InvitationRepositoryInterface $invitationRepository;

    private Registry $workflowRegistry;

    private string $invitationOrderState;

    public function __construct(
        ManagerRegistry $managerRegistry,
        MessageBusInterface $commandBus,
        InvitationRepositoryInterface $invitationRepository,
        Registry $workflowRegistry,
        string $invitationOrderState
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->commandBus = $commandBus;
        $this->invitationRepository = $invitationRepository;
        $this->workflowRegistry = $workflowRegistry;
        $this->invitationOrderState = $invitationOrderState;
    }

    public function dispatch(): void
    {
        $invitations = $this->invitationRepository->findNew($this->invitationOrderState);

        foreach ($invitations as $invitation) {
            $workflow = $this->getWorkflow($invitation);
            if (!$workflow->can($invitation, InvitationWorkflow::TRANSITION_START)) {
                continue;
            }

            $workflow->apply($invitation, InvitationWorkflow::TRANSITION_START);

            $this->getManager($invitation)->flush();

            $this->commandBus->dispatch(new ProcessInvitation($invitation));
        }
    }

    private function getWorkflow(InvitationInterface $invitation): WorkflowInterface
    {
        if (null === $this->workflow) {
            $this->workflow = $this->workflowRegistry->get($invitation, InvitationWorkflow::NAME);
        }

        return $this->workflow;
    }
}
