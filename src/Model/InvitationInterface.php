<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use DateTimeInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\VersionedInterface;

interface InvitationInterface extends ResourceInterface, TimestampableInterface, VersionedInterface
{
    public function getId(): ?int;

    public function getState(): string;

    /**
     * @param string $state Must be one of \Setono\SyliusTrustpilotPlugin\Workflow\InvitationWorkflow::getStates()
     */
    public function setState(string $state): void;

    /**
     * @return list<string>
     */
    public function getProcessingErrors(): array;

    public function resetProcessingErrors(): void;

    public function addProcessingError(string $processingError): void;

    /**
     * @param list<string> $processingErrors
     */
    public function addProcessingErrors(array $processingErrors): void;

    public function getOrder(): ?OrderInterface;

    public function setOrder(OrderInterface $order): void;

    public function getSentAt(): ?DateTimeInterface;

    public function setSentAt(DateTimeInterface $sentAt): void;

    public function getStateUpdatedAt(): ?DateTimeInterface;

    public function setStateUpdatedAt(DateTimeInterface $stateUpdatedAt): void;

    /**
     * Returns true if the state of this invitation is pending
     */
    public function isPending(): bool;

    public function isFailed(): bool;

    public function isIneligible(): bool;

    /**
     * Returns true if the invitation is deletable
     */
    public function isDeletable(): bool;

    public function getEmail(): ?string;
}
