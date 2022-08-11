<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\EventSubscriber;

use Doctrine\Persistence\ManagerRegistry;
use Setono\DoctrineObjectManagerTrait\ORM\ORMManagerTrait;
use Setono\SyliusTrustpilotPlugin\Factory\InvitationFactoryInterface;
use Setono\SyliusTrustpilotPlugin\Repository\ChannelConfigurationRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webmozart\Assert\Assert;

final class CreateInvitationOnOrderCompleteSubscriber implements EventSubscriberInterface
{
    use ORMManagerTrait;

    private InvitationFactoryInterface $invitationFactory;

    private ChannelConfigurationRepositoryInterface $channelConfigurationRepository;

    public function __construct(
        InvitationFactoryInterface $invitationFactory,
        ChannelConfigurationRepositoryInterface $channelConfigurationRepository,
        ManagerRegistry $managerRegistry
    ) {
        $this->invitationFactory = $invitationFactory;
        $this->channelConfigurationRepository = $channelConfigurationRepository;
        $this->managerRegistry = $managerRegistry;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.pre_complete' => 'create',
        ];
    }

    public function create(ResourceControllerEvent $event): void
    {
        /** @var mixed|OrderInterface $order */
        $order = $event->getSubject();
        Assert::isInstanceOf($order, OrderInterface::class);

        $channel = $order->getChannel();
        Assert::isInstanceOf($channel, ChannelInterface::class);

        if (!$this->channelConfigurationRepository->hasAny($channel)) {
            return;
        }

        $invitation = $this->invitationFactory->createWithOrder($order);

        // we only persist the invitation, this way the invitation is only
        // added to the database if the order is added to the database
        $this->getManager($invitation)->persist($invitation);
    }
}
