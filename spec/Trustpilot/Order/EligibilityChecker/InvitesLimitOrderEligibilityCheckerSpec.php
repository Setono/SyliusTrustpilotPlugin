<?php

namespace spec\Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Doctrine\Common\Collections\ArrayCollection;
use Setono\SyliusTrustpilotPlugin\Model\CustomerInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\InvitesLimitOrderEligibilityChecker;
use PhpSpec\ObjectBehavior;

class InvitesLimitOrderEligibilityCheckerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(InvitesLimitOrderEligibilityChecker::class);
    }

    public function let(): void
    {
        $this->beConstructedWith(0);
    }

    public function it_returns_true_when_there_is_no_limit(OrderInterface $order): void
    {
        $order->getCustomer()->shouldNotBeCalled();

        $this->isEligible($order)->shouldReturn(true);
    }

    public function it_returns_false_when_limit_is_reached(OrderInterface $order, CustomerInterface $customer): void
    {
        $order->getCustomer()->willReturn($customer);
        $order->getTrustpilotEmailsSent()->willReturn(3);
        $customer->getOrders()->willReturn(new ArrayCollection([$order->getWrappedObject()]));

        $this->beConstructedWith(3);
        $this->isEligible($order)->shouldReturn(false);
    }
}
