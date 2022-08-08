<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\OrderEligibilityCheckerInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EmailManager\EmailManagerInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Provider\PreQualifiedOrdersProviderInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Webmozart\Assert\Assert;

final class TrustpilotOrdersProcessor implements TrustpilotOrdersProcessorInterface
{
    private LoggerInterface $logger;

    private PreQualifiedOrdersProviderInterface $preQualifiedOrdersProvider;

    private OrderEligibilityCheckerInterface $orderEligibilityChecker;

    private EmailManagerInterface $emailManager;

    private EntityManagerInterface $orderManager;

    public function __construct(
        PreQualifiedOrdersProviderInterface $preQualifiedOrdersProvider,
        OrderEligibilityCheckerInterface $orderEligibilityChecker,
        EmailManagerInterface $emailManager,
        EntityManagerInterface $orderManager
    ) {
        $this->preQualifiedOrdersProvider = $preQualifiedOrdersProvider;
        $this->orderEligibilityChecker = $orderEligibilityChecker;
        $this->emailManager = $emailManager;
        $this->orderManager = $orderManager;

        $this->logger = new NullLogger();
    }

    public function process(): void
    {
        /** @var OrderTrustpilotAwareInterface[] $preQualifiedOrders */
        $preQualifiedOrders = $this->preQualifiedOrdersProvider->getOrders();

        $this->logger->info(sprintf(
            'Checking %d order(s)...',
            count($preQualifiedOrders)
        ));

        foreach ($preQualifiedOrders as $order) {
            if ($this->orderEligibilityChecker->isEligible($order)) {
                /** @var CustomerInterface|null $customer */
                $customer = $order->getCustomer();
                Assert::notNull($customer);

                $this->logger->info(sprintf(
                    'Order #%s is eligible. Sending email to Trustpilot for %s.',
                    $order->getNumber() ?? (string) $order->getId(),
                    (string) $customer->getEmail()
                ));

                $this->emailManager->sendTrustpilotEmail($order);

                // Increment emails sent for this order
                $order->setTrustpilotEmailsSent(
                    $order->getTrustpilotEmailsSent() + 1
                );
                $this->orderManager->flush();
            }
        }
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
