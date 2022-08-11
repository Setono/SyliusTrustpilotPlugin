<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Mailer;

interface EmailManagerInterface
{
    public function sendAfsEmail(AfsEmailDto $dto): void;
}
