<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Mailer;

use Sylius\Component\Mailer\Sender\SenderInterface;

class EmailManager implements EmailManagerInterface
{
    private SenderInterface $emailSender;

    public function __construct(SenderInterface $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    public function sendAfsEmail(AfsEmailDto $dto): void
    {
        $this->emailSender->send('trustpilot_email', [$dto->afsEmail], [
            'data' => $dto,
        ]);
    }
}
