<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor;

use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerAwareTrait;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\OrderEligibilityCheckerInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EmailManager\EmailManagerInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Provider\PreQualifiedOrdersProviderInterface;
use Sylius\Component\Core\Model\CustomerInterface;

final class TrustpilotOrdersProcessor implements TrustpilotOrdersProcessorInterface
{
    use LoggerAwareTrait;

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
        /** @var OrderTrustpilotAwareInterface[] $preQualifiedOrders */
        $preQualifiedOrders = $this->preQualifiedOrdersProvider->getOrders();

        if (null !== $this->logger) {
            $this->logger->debug(sprintf(
                'Checking %s order(s)...',
                count($preQualifiedOrders)
            ));
        }

        foreach ($preQualifiedOrders as $order) {
            if ($this->orderEligibilityChecker->isEligible($order)) {
                if (null !== $this->logger) {
                    /** @var CustomerInterface $customer */
                    $customer = $order->getCustomer();
                    $this->logger->debug(sprintf(
                        'Order #%s is eligible. Sending email to Trustpilot for %s.',
                        $order->getNumber() ?: $order->getId(),
                        $customer->getEmail()
                    ));
                }

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
