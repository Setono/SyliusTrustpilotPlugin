<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Workflow;

use Setono\SyliusTrustpilotPlugin\Model\InvitationInterface;
use Symfony\Component\Workflow\Transition;

final class InvitationWorkflow
{
    public const NAME = 'setono_sylius_trustpilot_invitation';

    public const STATE_FAILED = 'failed';

    public const STATE_INITIAL = 'initial';

    public const STATE_PENDING = 'pending';

    public const STATE_PROCESSING = 'processing';

    public const STATE_SENT = 'sent';

    public const TRANSITION_START = 'start';

    public const TRANSITION_PROCESS = 'process';

    public const TRANSITION_SEND = 'send';

    private function __construct()
    {
    }

    /**
     * @return list<string>
     */
    public static function getStates(): array
    {
        return [
            self::STATE_FAILED,
            self::STATE_INITIAL,
            self::STATE_PENDING,
            self::STATE_PROCESSING,
            self::STATE_SENT,
        ];
    }

    public static function getConfig(): array
    {
        $transitions = [];
        foreach (self::getTransitions() as $transition) {
            $transitions[$transition->getName()] = [
                'from' => $transition->getFroms(),
                'to' => $transition->getTos(),
            ];
        }

        return [
            self::NAME => [
                'type' => 'state_machine',
                'marking_store' => [
                    'type' => 'method',
                    'property' => 'state',
                ],
                'supports' => InvitationInterface::class,
                'initial_marking' => self::STATE_INITIAL,
                'places' => self::getStates(),
                'transitions' => $transitions,
            ],
        ];
    }

    /**
     * @return array<array-key, Transition>
     */
    public static function getTransitions(): array
    {
        return [
            new Transition(self::TRANSITION_START, self::STATE_INITIAL, self::STATE_PENDING),
            new Transition(self::TRANSITION_PROCESS, self::STATE_PENDING, self::STATE_PROCESSING),
            new Transition(self::TRANSITION_SEND, self::STATE_PROCESSING, self::STATE_SENT),
        ];
    }
}
