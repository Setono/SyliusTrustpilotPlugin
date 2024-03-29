<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Processor;

use Doctrine\Persistence\ManagerRegistry;
use Setono\DoctrineObjectManagerTrait\ORM\ORMManagerTrait;
use Setono\SyliusTrustpilotPlugin\EligibilityChecker\InvitationEligibilityCheckerInterface;
use Setono\SyliusTrustpilotPlugin\Mailer\AfsEmailDto;
use Setono\SyliusTrustpilotPlugin\Mailer\EmailManagerInterface;
use Setono\SyliusTrustpilotPlugin\Model\ChannelConfigurationInterface;
use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Setono\SyliusTrustpilotPlugin\Repository\ChannelConfigurationRepositoryInterface;
use Setono\SyliusTrustpilotPlugin\Workflow\InvitationWorkflow;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\WorkflowInterface;
use Throwable;
use Webmozart\Assert\Assert;

final class InvitationProcessor implements InvitationProcessorInterface
{
    use ORMManagerTrait;

    private ?WorkflowInterface $workflow = null;

    private EmailManagerInterface $emailManager;

    private Registry $workflowRegistry;

    private ChannelConfigurationRepositoryInterface $channelConfigurationRepository;

    private InvitationEligibilityCheckerInterface $invitationEligibilityChecker;

    public function __construct(
        ManagerRegistry $managerRegistry,
        EmailManagerInterface $emailManager,
        Registry $workflowRegistry,
        ChannelConfigurationRepositoryInterface $channelConfigurationRepository,
        InvitationEligibilityCheckerInterface $invitationEligibilityChecker
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->emailManager = $emailManager;
        $this->workflowRegistry = $workflowRegistry;
        $this->channelConfigurationRepository = $channelConfigurationRepository;
        $this->invitationEligibilityChecker = $invitationEligibilityChecker;
    }

    public function process(InvitationInterface $invitation): void
    {
        try {
            $this->tryTransition($invitation, InvitationWorkflow::TRANSITION_PROCESS);

            $channelConfiguration = $this->getChannelConfiguration($invitation);

            $eligibilityCheck = $this->invitationEligibilityChecker->check($invitation);
            if (!$eligibilityCheck->eligible) {
                $invitation->addProcessingErrors($eligibilityCheck->reasons);

                $this->tryTransition($invitation, InvitationWorkflow::TRANSITION_FAIL_ELIGIBILITY_CHECK);

                return;
            }

            $this->tryTransition(
                $invitation,
                InvitationWorkflow::TRANSITION_SEND,
                function (InvitationInterface $invitation) use ($channelConfiguration) {
                    $order = $invitation->getOrder();
                    Assert::notNull($order);

                    $this->emailManager->sendAfsEmail($this->getEmailDto($invitation, $channelConfiguration, $order));
                }
            );
        } catch (Throwable $e) {
            $invitation->addProcessingError(sprintf(
                'An unexpected error occurred when processing invitation %d: %s',
                (int) $invitation->getId(),
                $e->getMessage()
            ));

            $this->tryTransition($invitation, InvitationWorkflow::TRANSITION_FAIL);

            throw $e;
        }
    }

    private function tryTransition(InvitationInterface $invitation, string $transition, callable $callable = null): void
    {
        $manager = $this->getManager($invitation);
        $workflow = $this->getWorkflow($invitation);

        if (null !== $callable) {
            if (!$workflow->can($invitation, $transition)) {
                $invitation->addProcessingError(sprintf(
                    'Could not take transition "%s". The state when trying to take the transition was: "%s"',
                    $transition,
                    $invitation->getState()
                ));

                $this->tryTransition($invitation, InvitationWorkflow::TRANSITION_FAIL);

                return;
            }

            $callable($invitation);
        }

        $workflow->apply($invitation, $transition);

        $manager->flush();
    }

    private function getWorkflow(InvitationInterface $invitation): WorkflowInterface
    {
        if (null === $this->workflow) {
            $this->workflow = $this->workflowRegistry->get($invitation, InvitationWorkflow::NAME);
        }

        return $this->workflow;
    }

    private function getEmailDto(
        InvitationInterface $invitation,
        ChannelConfigurationInterface $channelConfiguration,
        OrderInterface $order
    ): AfsEmailDto {
        $customer = $order->getCustomer();
        Assert::notNull($customer);

        return new AfsEmailDto(
            (string) $channelConfiguration->getAfsEmail(),
            (string) $order->getNumber(),
            (string) $invitation->getEmail(),
            (string) $customer->getFirstName(), // todo introduce InvitationInterface::getRecipientName() ?
            (string) $order->getLocaleCode(),
            $channelConfiguration->getPreferredSendTimeWithSendDelay(),
            $channelConfiguration->getTemplateId()
        );
    }

    private function getChannelConfiguration(InvitationInterface $invitation): ChannelConfigurationInterface
    {
        $order = $invitation->getOrder();
        Assert::notNull($order);

        $channel = $order->getChannel();
        Assert::notNull($channel);

        $channelConfiguration = $this->channelConfigurationRepository->findOneByChannel($channel);
        Assert::notNull(
            $channelConfiguration,
            sprintf('No channel configuration exists for channel %s', (string) $channel->getCode())
        );

        return $channelConfiguration;
    }
}
