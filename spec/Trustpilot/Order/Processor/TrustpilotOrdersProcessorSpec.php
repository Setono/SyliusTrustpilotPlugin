<?php

namespace spec\Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor;

use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Prophecy\Argument;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\OrderEligibilityCheckerInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EmailManager\EmailManagerInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor\TrustpilotOrdersProcessor;
use PhpSpec\ObjectBehavior;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Provider\PreQualifiedOrdersProviderInterface;
use Sylius\Component\Core\Model\CustomerInterface;

class TrustpilotOrdersProcessorSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(TrustpilotOrdersProcessor::class);
    }

    public function let(
        PreQualifiedOrdersProviderInterface $preQualifiedOrdersProvider,
        OrderEligibilityCheckerInterface $orderEligibilityChecker,
        EmailManagerInterface $emailManager,
        EntityManager $orderManager
    ): void {
        $this->beConstructedWith($preQualifiedOrdersProvider, $orderEligibilityChecker, $emailManager, $orderManager);
    }

    public function it_processes_orders(
        PreQualifiedOrdersProviderInterface $preQualifiedOrdersProvider,
        OrderEligibilityCheckerInterface $orderEligibilityChecker,
        EmailManagerInterface $emailManager,
        ObjectManager $orderManager,
        OrderTrustpilotAwareInterface $order,
        CustomerInterface $customer
    ): void {
        $orderEligibilityChecker->isEligible($order)->willReturn(true);
        $order->getNumber()->willReturn(Argument::any());
        $order->getCustomer()->willReturn($customer);
        $preQualifiedOrdersProvider->getOrders()->willReturn([$order]);
        $order->getTrustpilotEmailsSent()->willReturn(10);

        $emailManager->sendTrustpilotEmail($order)->shouldBeCalled();
        $orderManager->flush()->shouldBeCalled();
        $order->setTrustpilotEmailsSent(11)->shouldBeCalled();

        $this->process();
    }
}
