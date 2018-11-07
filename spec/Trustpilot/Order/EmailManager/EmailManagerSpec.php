<?php

namespace spec\Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EmailManager;

use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EmailManager\EmailManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

class EmailManagerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(EmailManager::class);
    }

    public function let(SenderInterface $emailSender): void
    {
        $this->beConstructedWith($emailSender, 'email@example.com', 'da_DK');
    }

    public function it_sends_an_email(OrderInterface $order, CustomerInterface $customer, SenderInterface $emailSender): void
    {
        $order->getCustomer()->willReturn($customer);

        $emailSender->send('trustpilot_email', ['email@example.com'], [
            'order' => $order,
            'customer' => $customer,
            'locale' => 'da_DK',
        ])->shouldBeCalled();

        $this->sendTrustpilotEmail($order);
    }
}
