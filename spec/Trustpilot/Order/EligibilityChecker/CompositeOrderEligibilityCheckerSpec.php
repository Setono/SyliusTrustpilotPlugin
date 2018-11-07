<?php

namespace spec\Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker;

use Setono\SyliusTrustpilotPlugin\Model\OrderInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\CompositeOrderEligibilityChecker;
use PhpSpec\ObjectBehavior;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\OrderEligibilityCheckerInterface;

class CompositeOrderEligibilityCheckerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CompositeOrderEligibilityChecker::class);
    }

    public function let(OrderEligibilityCheckerInterface $checker1, OrderEligibilityCheckerInterface $checker2): void
    {
        $this->beConstructedWith([$checker1, $checker2]);
    }

    public function it_returns_false_if_one_checker_returns_false(OrderInterface $order, OrderEligibilityCheckerInterface $checker1, OrderEligibilityCheckerInterface $checker2): void
    {
        $checker1->isEligible($order)->willReturn(true);
        $checker2->isEligible($order)->willReturn(false);

        $this->isEligible($order)->shouldReturn(false);
    }

    public function it_returns_true_if_all_checkers_returns_true(OrderInterface $order, OrderEligibilityCheckerInterface $checker1, OrderEligibilityCheckerInterface $checker2): void
    {
        $checker1->isEligible($order)->willReturn(true);
        $checker2->isEligible($order)->willReturn(true);

        $this->isEligible($order)->shouldReturn(true);
    }
}
