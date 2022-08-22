<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Message\Handler;

use Setono\SyliusTrustpilotPlugin\Message\Command\ProcessInvitation;
use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Setono\SyliusTrustpilotPlugin\Processor\InvitationProcessorInterface;
use Setono\SyliusTrustpilotPlugin\Repository\InvitationRepositoryInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;
use Webmozart\Assert\Assert;

final class ProcessInvitationHandler implements MessageHandlerInterface
{
    private InvitationRepositoryInterface $invitationRepository;

    private InvitationProcessorInterface $invitationProcessor;

    public function __construct(
        InvitationRepositoryInterface $invitationRepository,
        InvitationProcessorInterface $invitationProcessor
    ) {
        $this->invitationRepository = $invitationRepository;
        $this->invitationProcessor = $invitationProcessor;
    }

    public function __invoke(ProcessInvitation $message): void
    {
        /** @var InvitationInterface|object|null $invitation */
        $invitation = $this->invitationRepository->find($message->invitationId);
        Assert::nullOrIsInstanceOf($invitation, InvitationInterface::class);

        if (null === $invitation) {
            return;
        }

        try {
            $this->invitationProcessor->process($invitation);
        } catch (Throwable $e) {
            throw new UnrecoverableMessageHandlingException($e->getMessage(), 0, $e);
        }
    }
}
