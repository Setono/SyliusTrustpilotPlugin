<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Processor;

use Doctrine\Persistence\ManagerRegistry;
use Setono\DoctrineObjectManagerTrait\ORM\ORMManagerTrait;
use Setono\SyliusTrustpilotPlugin\Mailer\AfsEmailDto;
use Setono\SyliusTrustpilotPlugin\Mailer\EmailManagerInterface;
use Setono\SyliusTrustpilotPlugin\Model\ChannelConfigurationInterface;
use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Setono\SyliusTrustpilotPlugin\Repository\ChannelConfigurationRepositoryInterface;
use Setono\SyliusTrustpilotPlugin\Workflow\InvitationWorkflow;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Workflow\Registry;
use Webmozart\Assert\Assert;

final class InvitationProcessor implements InvitationProcessorInterface
{
    use ORMManagerTrait;

    private EmailManagerInterface $emailManager;

    private Registry $workflowRegistry;

    private ChannelConfigurationRepositoryInterface $channelConfigurationRepository;

    public function __construct(
        ManagerRegistry $managerRegistry,
        EmailManagerInterface $emailManager,
        Registry $workflowRegistry,
        ChannelConfigurationRepositoryInterface $channelConfigurationRepository
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->emailManager = $emailManager;
        $this->workflowRegistry = $workflowRegistry;
        $this->channelConfigurationRepository = $channelConfigurationRepository;
    }

    public function process(InvitationInterface $invitation): void
    {
        if (!$invitation->isPending()) {
            return;
        }

        $manager = $this->getManager($invitation);

        $workflow = $this->workflowRegistry->get($invitation, InvitationWorkflow::NAME);
        $workflow->apply($invitation, InvitationWorkflow::TRANSITION_PROCESS);
        $manager->flush();

        $channelConfiguration = $this->getChannelConfiguration($invitation);

        if (!$workflow->can($invitation, InvitationWorkflow::TRANSITION_SEND)) {
            // todo we are missing a failed transition here
            $invitation->setProcessingError(sprintf(
                'Could not take transition "%s". The state when trying to take the transition was: "%s"',
                InvitationWorkflow::TRANSITION_SEND,
                $invitation->getState()
            ));

            $manager->flush();

            return;
        }

        $order = $invitation->getOrder();
        Assert::notNull($order);

        $this->emailManager->sendAfsEmail($this->getEmailDto($channelConfiguration, $order));

        $workflow->apply($invitation, InvitationWorkflow::TRANSITION_SEND);

        $manager->flush();
    }

    private function getEmailDto(ChannelConfigurationInterface $channelConfiguration, OrderInterface $order): AfsEmailDto
    {
        $customer = $order->getCustomer();
        Assert::notNull($customer);

        $recipientEmail = $customer->getEmailCanonical();
        $recipientName = (string) $customer->getFirstName();
        Assert::notNull($recipientEmail);

        return new AfsEmailDto(
            (string) $channelConfiguration->getAfsEmail(),
            (string) $order->getNumber(),
            $recipientEmail,
            $recipientName,
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
