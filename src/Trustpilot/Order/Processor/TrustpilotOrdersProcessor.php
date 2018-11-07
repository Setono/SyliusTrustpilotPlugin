<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor;

use Doctrine\Common\Persistence\ObjectManager;
use Setono\SyliusTrustpilotPlugin\Model\OrderInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\OrderEligibilityCheckerInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EmailManager\EmailManagerInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Provider\PreQualifiedOrdersProviderInterface;

final class TrustpilotOrdersProcessor implements TrustpilotOrdersProcessorInterface
{
    /**
     * @var PreQualifiedOrdersProviderInterface
     */
    private $preQualifiedOrdersProvider;

    /**
     * @var OrderEligibilityCheckerInterface
     */
    private $orderEligibilityChecker;

    /**
     * @var EmailManagerInterface
     */
    private $emailManager;

    /**
     * @var ObjectManager
     */
    private $orderManager;

    /**
     * @param PreQualifiedOrdersProviderInterface $preQualifiedOrdersProvider
     * @param OrderEligibilityCheckerInterface $orderEligibilityChecker
     * @param EmailManagerInterface $emailManager
     * @param ObjectManager $orderManager
     */
    public function __construct(
        PreQualifiedOrdersProviderInterface $preQualifiedOrdersProvider,
        OrderEligibilityCheckerInterface $orderEligibilityChecker,
        EmailManagerInterface $emailManager,
        ObjectManager $orderManager
    ) {
        $this->preQualifiedOrdersProvider = $preQualifiedOrdersProvider;
        $this->orderEligibilityChecker = $orderEligibilityChecker;
        $this->emailManager = $emailManager;
        $this->orderManager = $orderManager;
    }

    /**
     * {@inheritdoc}
     */
    public function process(): void
    {
        /** @var OrderInterface[] $preQualifiedOrders */
        $preQualifiedOrders = $this->preQualifiedOrdersProvider->getOrders();
        foreach ($preQualifiedOrders as $order) {
            if ($this->orderEligibilityChecker->isEligible($order)) {
                $this->emailManager->sendTrustpilotEmail($order);

                // Increment emails sent for this order
                $order->setTrustpilotEmailsSent(
                    $order->getTrustpilotEmailsSent() + 1
                );
                $this->orderManager->flush();
            }
        }
    }
}
