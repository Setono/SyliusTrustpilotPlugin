<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Processor;

use Doctrine\Persistence\ManagerRegistry;
use Setono\DoctrineObjectManagerTrait\ORM\ORMManagerTrait;
use Setono\SyliusTrustpilotPlugin\Mailer\AfsEmailDto;
use Setono\SyliusTrustpilotPlugin\Mailer\EmailManagerInterface;
use Setono\SyliusTrustpilotPlugin\Model\ChannelConfiguration;
use Setono\SyliusTrustpilotPlugin\Model\ChannelConfigurationInterface;
use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Setono\SyliusTrustpilotPlugin\Workflow\InvitationWorkflow;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Workflow\Registry;
use Webmozart\Assert\Assert;

final class InvitationProcessor implements InvitationProcessorInterface
{
    use ORMManagerTrait;

    private EmailManagerInterface $emailManager;

    private Registry $workflowRegistry;

    public function __construct(ManagerRegistry $managerRegistry, EmailManagerInterface $emailManager, Registry $workflowRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->emailManager = $emailManager;
        $this->workflowRegistry = $workflowRegistry;
    }

    public function process(InvitationInterface $invitation): void
    {
        // todo should we allow state=pending also to allow for calling the processor directly on an invitation?
        if ($invitation->getState() !== InvitationWorkflow::STATE_PROCESSING) {
            return;
        }

        $manager = $this->getManager($invitation);

        $channelConfiguration = $this->getChannelConfiguration($invitation);

        $workflow = $this->workflowRegistry->get($invitation, InvitationWorkflow::NAME);
        if (!$workflow->can($invitation, InvitationWorkflow::TRANSITION_SEND)) {
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

        // todo check if there's a channel configuration for the channel where the order was placed

        $channelConfiguration = new ChannelConfiguration();
        $channelConfiguration->setChannel($channel);
        $channelConfiguration->setAfsEmail('johndoe@example.com');

        return $channelConfiguration;
    }
}
