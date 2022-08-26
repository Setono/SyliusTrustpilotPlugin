<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\EventSubscriber\Workflow;

use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Setono\SyliusTrustpilotPlugin\Workflow\InvitationWorkflow;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Webmozart\Assert\Assert;

final class ResetProcessingErrorsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        $event = sprintf(
            'workflow.%s.transition.%s',
            InvitationWorkflow::NAME,
            InvitationWorkflow::TRANSITION_START
        );

        return [
            $event => 'reset',
        ];
    }

    public function reset(Event $event): void
    {
        /** @var object|InvitationInterface $invitation */
        $invitation = $event->getSubject();
        Assert::isInstanceOf($invitation, InvitationInterface::class);

        $invitation->resetProcessingErrors();
    }
}
