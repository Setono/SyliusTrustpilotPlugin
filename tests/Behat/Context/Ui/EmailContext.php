<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Test\Services\EmailCheckerInterface;
use Webmozart\Assert\Assert;

final class EmailContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var EmailCheckerInterface */
    private $emailChecker;

    /** @var string */
    private $trustpilotEmail;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        EmailCheckerInterface $emailChecker,
        string $trustpilotEmail
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->emailChecker = $emailChecker;
        $this->trustpilotEmail = $trustpilotEmail;
    }

    /**
     * @Then trustpilot email should be sent for this order
     * @Then trustpilot email should be sent for order :order
     */
    public function trustpilotEmailShouldBeSentForOrder(?OrderInterface $order = null)
    {
        if (null == $order) {
            $order = $this->sharedStorage->get('order');
            Assert::notNull($order, 'Order not provided and not stored at shared storage');
        }

        Assert::true($this->emailChecker->hasRecipient($this->trustpilotEmail));
        $this->assertEmailContainsMessageTo((string) $order->getId(), $this->trustpilotEmail);
    }

    /**
     * @param string $message
     * @param string $recipient
     */
    private function assertEmailContainsMessageTo($message, $recipient)
    {
        Assert::true($this->emailChecker->hasMessageTo($message, $recipient));
    }
}
