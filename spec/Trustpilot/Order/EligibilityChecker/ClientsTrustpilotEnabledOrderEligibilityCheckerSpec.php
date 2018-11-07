<?php

namespace spec\Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\CustomerInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\ClientsTrustpilotEnabledOrderEligibilityChecker;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ShopUserInterface;

class ClientsTrustpilotEnabledOrderEligibilityCheckerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ClientsTrustpilotEnabledOrderEligibilityChecker::class);
    }

    public function it_is_eligible(OrderInterface $order, CustomerInterface $customer, ShopUserInterface $shopUser): void
    {
        $order->getUser()->willReturn($shopUser);
        $shopUser->getCustomer()->willReturn($customer);
        $customer->isTrustpilotEnabled()->willReturn(true);

        $this->isEligible($order)->shouldReturn(true);
    }
}
