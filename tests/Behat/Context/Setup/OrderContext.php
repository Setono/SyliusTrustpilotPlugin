<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Webmozart\Assert\Assert;

final class OrderContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * OrderContext constructor.
     * @param SharedStorageInterface $sharedStorage
     * @param ObjectManager $objectManager
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ObjectManager $objectManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->objectManager = $objectManager;
    }

    /**
     * @Given (that) order was completed
     * @Given (that) order was completed :days days ago
     * @Given order :order was completed :days days ago
     */
    public function orderWasCompletedXDaysAgo(int $days = 0, ?OrderInterface $order = null): void
    {
        if (null === $order) {
            $order = $this->sharedStorage->get('order');
        }

        Assert::greaterThanEq($days, 0, 'You should specify positive amount of days');
        if ($days > 0) {
            $order->setCheckoutCompletedAt(
                new \DateTime(sprintf('-%s day', $days))
            );
        }

        $order->setState(OrderInterface::STATE_NEW);

        $this->objectManager->flush();
        $this->sharedStorage->set('order', $order);
    }
}
