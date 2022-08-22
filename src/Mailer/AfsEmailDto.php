<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Mailer;

use DateTimeInterface;
use JsonSerializable;

/**
 * This class represent the structured data you send to Trustpilot when using their AFS email service
 */
final class AfsEmailDto implements JsonSerializable
{
    /**
     * This is the unique AFS email you get from Trustpilot
     */
    public string $afsEmail;

    public string $referenceId;

    public string $recipientEmail;

    public string $recipientName;

    public string $locale;

    public DateTimeInterface $preferredSendTime;

    public ?string $templateId;

    public function __construct(
        string $afsEmail,
        string $referenceId,
        string $recipientEmail,
        string $recipientName,
        string $locale,
        DateTimeInterface $preferredSendTime,
        string $templateId = null
    ) {
        $this->afsEmail = $afsEmail;
        $this->referenceId = $referenceId;
        $this->recipientEmail = $recipientEmail;
        $this->recipientName = $recipientName;
        $this->locale = str_replace('_', '-', $locale);
        $this->preferredSendTime = $preferredSendTime;
        $this->templateId = $templateId;
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            'referenceId' => $this->referenceId,
            'recipientEmail' => $this->recipientEmail,
            'recipientName' => $this->recipientName,
            'locale' => $this->locale,
            'preferredSendTime' => $this->preferredSendTime->format('Y-m-d\TH:i:s'),
            'templateId' => $this->templateId,
        ]);
    }
}
