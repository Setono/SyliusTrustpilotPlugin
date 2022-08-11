<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\EventSubscriber\Workflow;

use DateTimeImmutable;
use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Setono\SyliusTrustpilotPlugin\Workflow\InvitationWorkflow;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Webmozart\Assert\Assert;

final class SetStateUpdatedAtSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        $event = sprintf('workflow.%s.completed', InvitationWorkflow::NAME);

        return [
            $event => 'set',
        ];
    }

    public function set(Event $event): void
    {
        /** @var object|InvitationInterface $invitation */
        $invitation = $event->getSubject();
        Assert::isInstanceOf($invitation, InvitationInterface::class);

        $invitation->setStateUpdatedAt(new DateTimeImmutable());
    }
}
