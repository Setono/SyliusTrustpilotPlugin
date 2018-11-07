<?php

namespace spec\Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\ClientsTrustpilotEnabledOrderEligibilityChecker;
use PhpSpec\ObjectBehavior;

class ClientsTrustpilotEnabledOrderEligibilityCheckerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ClientsTrustpilotEnabledOrderEligibilityChecker::class);
    }

    public function it_returns_true_if_client_have_flag_enabled(OrderTrustpilotAwareInterface $order, CustomerTrustpilotAwareInterface $customer): void
    {
        $order->getCustomer()->willReturn($customer);
        $customer->isTrustpilotEnabled()->willReturn(true);

        $this->isEligible($order)->shouldReturn(true);
    }

    public function it_returns_false_if_client_have_flag_disabled(OrderTrustpilotAwareInterface $order, CustomerTrustpilotAwareInterface $customer): void
    {
        $order->getCustomer()->willReturn($customer);
        $customer->isTrustpilotEnabled()->willReturn(false);

        $this->isEligible($order)->shouldReturn(false);
    }
}
