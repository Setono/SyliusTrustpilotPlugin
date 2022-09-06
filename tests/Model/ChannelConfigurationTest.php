<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Model;

use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Setono\SyliusTrustpilotPlugin\Model\ChannelConfiguration;

/**
 * @covers \Setono\SyliusTrustpilotPlugin\Model\ChannelConfiguration
 */
final class ChannelConfigurationTest extends TestCase
{
    /**
     * @test
     */
    public function it_calculates_the_preferred_send_time_with_send_delay_correctly(): void
    {
        $now = (new DateTimeImmutable())->setTime(9, 0);

        $channelConfiguration = new ChannelConfiguration();
        $channelConfiguration->setSendDelay(new DateInterval('P7DT0H0M'));
        $channelConfiguration->setPreferredSendTime('09:00');

        $interval = $now->diff($channelConfiguration->getPreferredSendTimeWithSendDelay());
        self::assertSame(0, $interval->y);
        self::assertSame(0, $interval->m);
        self::assertSame(7, $interval->d);
        self::assertSame(0, $interval->h);
        self::assertSame(0, $interval->i);
        self::assertSame(0, $interval->s);
    }
}
