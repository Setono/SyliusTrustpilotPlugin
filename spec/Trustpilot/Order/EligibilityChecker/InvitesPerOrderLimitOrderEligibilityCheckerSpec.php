<?php

namespace spec\Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\InvitesPerOrderLimitOrderEligibilityChecker;
use PhpSpec\ObjectBehavior;

class InvitesPerOrderLimitOrderEligibilityCheckerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(InvitesPerOrderLimitOrderEligibilityChecker::class);
    }

    public function it_returns_true_when_no_emails_was_sent(OrderTrustpilotAwareInterface $order): void
    {
        $order->getTrustpilotEmailsSent()->willReturn(0);
        $this->isEligible($order)->shouldReturn(true);
    }

    public function it_returns_false_when_at_least_one_email_was_sent_for_given_order(OrderTrustpilotAwareInterface $order): void
    {
        $order->getTrustpilotEmailsSent()->willReturn(1);
        $this->isEligible($order)->shouldReturn(false);
    }
}
