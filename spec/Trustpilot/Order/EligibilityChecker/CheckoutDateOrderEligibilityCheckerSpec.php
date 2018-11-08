<?php

namespace spec\Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\CheckoutDateOrderEligibilityChecker;
use PhpSpec\ObjectBehavior;

class CheckoutDateOrderEligibilityCheckerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CheckoutDateOrderEligibilityChecker::class);
    }

    public function let(): void
    {
        $this->beConstructedWith(7);
    }

    public function it_returns_false_when_order_not_completed(OrderTrustpilotAwareInterface $order): void
    {
        $order->getCheckoutCompletedAt()->willReturn(null);
        $this->isEligible($order)->shouldReturn(false);
    }

    public function it_returns_false_when_checked_out_less_than_configured_days_ago(OrderTrustpilotAwareInterface $order): void
    {
        $order->getCheckoutCompletedAt()->willReturn(new \DateTime('-6 day'));
        $this->isEligible($order)->shouldReturn(false);
    }

    public function it_returns_true_when_checked_out_configured_days_ago(OrderTrustpilotAwareInterface $order): void
    {
        $order->getCheckoutCompletedAt()->willReturn(new \DateTime('-7 day'));
        $this->isEligible($order)->shouldReturn(true);
    }

    public function it_returns_true_when_checked_out_more_than_configured_days_ago(OrderTrustpilotAwareInterface $order): void
    {
        $order->getCheckoutCompletedAt()->willReturn(new \DateTime('-8 day'));
        $this->isEligible($order)->shouldReturn(true);
    }
}
