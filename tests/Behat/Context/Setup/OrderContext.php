<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Persistence\ObjectManager;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Webmozart\Assert\Assert;

final class OrderContext implements Context
{
    private SharedStorageInterface $sharedStorage;

    private ObjectManager $objectManager;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        ObjectManager $objectManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->objectManager = $objectManager;
    }

    /**
     * @Given (that) order was not completed
     * @Given order :order was not completed
     */
    public function orderWasNotCompleted(?OrderInterface $order = null): void
    {
        if (null === $order) {
            $order = $this->sharedStorage->get('order');
        }

        $order->setCheckoutCompletedAt(null);
        $order->setState(OrderInterface::STATE_CART);

        $this->objectManager->flush();
        $this->sharedStorage->set('order', $order);
    }

    /**
     * @Given (that) order was completed
     * @Given order :order was completed
     * @Given (that) order was completed :days days ago
     * @Given order :order was completed :days days ago
     */
    public function orderWasCompletedXDaysAgo(int $days = 0, ?OrderInterface $order = null): void
    {
        if (null === $order) {
            $order = $this->sharedStorage->get('order');
            Assert::notNull($order, 'Order was not found at shared storage');
        }

        $order->setCheckoutCompletedAt(
            new \DateTime(sprintf('-%s day', $days))
        );
        $order->setState(OrderInterface::STATE_NEW);

        $this->objectManager->flush();
        $this->sharedStorage->set('order', $order);
    }

    /**
     * @Given (that) order have :emails emails sent
     * @Given order :order have :emails emails sent
     */
    public function orderHaveEmailsSent(int $emails, ?OrderTrustpilotAwareInterface $order = null): void
    {
        if (null === $order) {
            $order = $this->sharedStorage->get('order');
        }

        $order->setTrustpilotEmailsSent($emails);

        $this->objectManager->flush();
        $this->sharedStorage->set('order', $order);
    }
}
