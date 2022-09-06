<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use DateInterval;
use DateTimeInterface;
use Sylius\Component\Channel\Model\ChannelAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface ChannelConfigurationInterface extends ResourceInterface, ChannelAwareInterface
{
    public function getId(): ?int;

    /**
     * This is the unique Trustpilot AFS service email address you get from Trustpilot
     * See https://support.trustpilot.com/hc/en-us/articles/213703667-Automatic-Feedback-Service-AFS-2-0-setup-guide
     */
    public function getAfsEmail(): ?string;

    public function setAfsEmail(string $afsEmail): void;

    /**
     * ISO 8601 formatted interval string. See https://www.php.net/manual/en/dateinterval.construct.php
     */
    public function getSendDelay(): DateInterval;

    public function setSendDelay(DateInterval $sendDelay): void;

    /**
     * Returns the hour and minutes for the preferred send time
     *
     * @return string formatted as HH:MM
     */
    public function getPreferredSendTime(): string;

    public function setPreferredSendTime(string $preferredSendTime): void;

    /**
     * This method will return the actual preferred send time taking the send delay into consideration
     */
    public function getPreferredSendTimeWithSendDelay(): DateTimeInterface;

    public function getTemplateId(): ?string;

    public function setTemplateId(?string $templateId): void;
}
