<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Sylius\Component\Channel\Model\ChannelInterface;

class ChannelConfiguration implements ChannelConfigurationInterface
{
    protected ?int $id = null;

    protected ?string $afsEmail = null;

    protected DateInterval $sendDelay;

    protected string $preferredSendTime = '09:00';

    protected ?string $templateId = null;

    protected ?ChannelInterface $channel = null;

    public function __construct()
    {
        $this->sendDelay = new DateInterval('P7DT0H0M'); // a week
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAfsEmail(): ?string
    {
        return $this->afsEmail;
    }

    public function setAfsEmail(string $afsEmail): void
    {
        $this->afsEmail = $afsEmail;
    }

    public function getSendDelay(): DateInterval
    {
        return $this->sendDelay;
    }

    public function setSendDelay(DateInterval $sendDelay): void
    {
        $this->sendDelay = $sendDelay;
    }

    public function getPreferredSendTime(): string
    {
        return $this->preferredSendTime;
    }

    public function setPreferredSendTime(string $preferredSendTime): void
    {
        $this->preferredSendTime = $preferredSendTime;
    }

    public function getPreferredSendTimeWithSendDelay(): DateTimeInterface
    {
        $preferredSendTimeOnDay = array_map(
            static function (string $part) { return (int) $part; },
            explode(':', $this->getPreferredSendTime())
        );

        return (new DateTimeImmutable())
            ->add($this->getSendDelay())
            ->setTime($preferredSendTimeOnDay[0], $preferredSendTimeOnDay[1])
        ;
    }

    public function getTemplateId(): ?string
    {
        return $this->templateId;
    }

    public function setTemplateId(?string $templateId): void
    {
        $this->templateId = $templateId;
    }

    public function getChannel(): ?ChannelInterface
    {
        return $this->channel;
    }

    public function setChannel(?ChannelInterface $channel): void
    {
        $this->channel = $channel;
    }
}
