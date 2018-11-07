<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EmailManager;

use Setono\SyliusTrustpilotPlugin\Model\OrderInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

class EmailManager implements EmailManagerInterface
{
    /**
     * @var SenderInterface
     */
    private $emailSender;

    /**
     * @var string
     */
    private $trustpilotEmail;

    /**
     * @var string
     */
    private $locale;

    /**
     * @param SenderInterface $emailSender
     * @param string $trustpilotEmail
     * @param string $locale
     */
    public function __construct(
        SenderInterface $emailSender,
        string $trustpilotEmail,
        string $locale
    ) {
        $this->emailSender = $emailSender;
        $this->trustpilotEmail = $trustpilotEmail;
        $this->locale = $locale;
    }

    /**
     * {@inheritdoc}
     */
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
