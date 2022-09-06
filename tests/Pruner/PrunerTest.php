<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Pruner;

use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\SyliusTrustpilotPlugin\Pruner\Pruner;
use Setono\SyliusTrustpilotPlugin\Repository\InvitationRepositoryInterface;

/**
 * @covers \Setono\SyliusTrustpilotPlugin\Pruner\Pruner
 */
final class PrunerTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function it_prunes(): void
    {
        $threshold = 43_200; // 30 days
        $thresholdDateTime = (new DateTimeImmutable())->sub(new DateInterval(sprintf('PT%dM', $threshold)));
        self::assertNotFalse($thresholdDateTime);

        $repository = $this->prophesize(InvitationRepositoryInterface::class);
        $repository->removeOlderThan(
            Argument::that(static function (DateTimeImmutable $argument) use ($thresholdDateTime) {
                // this makes the test non-flaky since we allow a delay of 10 seconds between instantiating $thresholdDateTime
                // and calling $pruner->prune() which should be more than enough
                $diff = abs($argument->getTimestamp() - $thresholdDateTime->getTimestamp());

                return $diff <= 10;
            })
        )->shouldBeCalled();

        $pruner = new Pruner($repository->reveal(), $threshold);
        $pruner->prune();
    }
}
