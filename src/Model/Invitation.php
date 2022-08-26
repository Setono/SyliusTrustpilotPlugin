<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Model;

use DateTimeInterface;
use Setono\SyliusTrustpilotPlugin\Workflow\InvitationWorkflow;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

class Invitation implements InvitationInterface
{
    use TimestampableTrait;

    protected ?int $id = null;

    protected ?int $version = 1;

    protected string $state = InvitationWorkflow::STATE_INITIAL;

    /** @var list<string>|null */
    protected ?array $processingErrors = null;

    protected ?OrderInterface $order = null;

    protected ?DateTimeInterface $sentAt = null;

    protected ?DateTimeInterface $stateUpdatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?int $version): void
    {
        $this->version = $version;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getProcessingErrors(): array
    {
        return $this->processingErrors ?? [];
    }

    public function resetProcessingErrors(): void
    {
        $this->processingErrors = null;
    }

    public function addProcessingError(string $processingError): void
    {
        $this->processingErrors[] = $processingError;
    }

    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    public function setOrder(OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getSentAt(): ?DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(DateTimeInterface $sentAt): void
    {
        $this->sentAt = $sentAt;
    }

    public function getStateUpdatedAt(): ?DateTimeInterface
    {
        return $this->stateUpdatedAt;
    }

    public function setStateUpdatedAt(DateTimeInterface $stateUpdatedAt): void
    {
        $this->stateUpdatedAt = $stateUpdatedAt;
    }

    public function isPending(): bool
    {
        return $this->getState() === InvitationWorkflow::STATE_PENDING;
    }

    public function isDeletable(): bool
    {
        return $this->getState() !== InvitationWorkflow::STATE_SENT;
    }
}
