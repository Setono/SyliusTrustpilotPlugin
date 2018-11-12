<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Webmozart\Assert\Assert;

final class CustomerContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var ObjectManager
     */
    private $customerManager;

    /**
     * CustomerContext constructor.
     * @param SharedStorageInterface $sharedStorage
     * @param ObjectManager $customerManager
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ObjectManager $customerManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->customerManager = $customerManager;
    }

    /**
     * @Given trustpilot disabled for (this) customer
     * @Given trustpilot disabled for customer :customer
     */
    public function trustpilotDisabledForCustomer(?CustomerTrustpilotAwareInterface $customer = null)
    {
        if (null == $customer) {
            $customer = $this->sharedStorage->get('customer');
            Assert::notNull($customer, "Customer was not found at shared storage");
        }

        $customer->setTrustpilotEnabled(false);
        $this->customerManager->flush();
    }

    /**
     * @Given trustpilot enabled for (this) customer
     * @Given trustpilot enabled for customer :customer
     */
    public function trustpilotEnabledForCustomer(?CustomerTrustpilotAwareInterface $customer = null)
    {
        if (null == $customer) {
            $customer = $this->sharedStorage->get('customer');
            Assert::notNull($customer, "Customer was not found at shared storage");
        }

        $customer->setTrustpilotEnabled(true);
        $this->customerManager->flush();
    }
}
