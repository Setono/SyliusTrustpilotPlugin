<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use DateTimeInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface InvitationInterface extends ResourceInterface, TimestampableInterface
{
    public function getId(): ?int;

    public function getState(): string;

    /**
     * @param string $state Must be one of \Setono\SyliusTrustpilotPlugin\Workflow\InvitationWorkflow::getStates()
     */
    public function setState(string $state): void;

    public function getProcessingError(): ?string;

    public function setProcessingError(?string $processingError): void;

    public function getOrder(): ?OrderInterface;

    public function setOrder(OrderInterface $order): void;

    public function getSentAt(): ?DateTimeInterface;

    public function setSentAt(DateTimeInterface $sentAt): void;

    public function getStateUpdatedAt(): ?DateTimeInterface;

    public function setStateUpdatedAt(DateTimeInterface $stateUpdatedAt): void;

    /**
     * Returns true if the invitation is deletable
     */
    public function isDeletable(): bool;
}
