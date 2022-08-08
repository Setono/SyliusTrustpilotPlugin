<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EmailManager;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

class EmailManager implements EmailManagerInterface
{
    private SenderInterface $emailSender;

    private string $trustpilotEmail;

    private string $locale;

    public function __construct(
        SenderInterface $emailSender,
        string $trustpilotEmail,
        string $locale
    ) {
        $this->emailSender = $emailSender;
        $this->trustpilotEmail = $trustpilotEmail;
        $this->locale = str_replace('_', '-', $locale);
    }

    public function sendTrustpilotEmail(OrderInterface $order): void
    {
        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();

        $this->emailSender->send('trustpilot_email', [
            $this->trustpilotEmail,
        ], [
            'order' => $order,
            'customer' => $customer,
            'locale' => $this->locale,
        ]);
    }
}
