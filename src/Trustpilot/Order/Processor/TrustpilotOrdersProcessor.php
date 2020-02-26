<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\OrderEligibilityCheckerInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EmailManager\EmailManagerInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Provider\PreQualifiedOrdersProviderInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use function Safe\sprintf;

final class TrustpilotOrdersProcessor implements TrustpilotOrdersProcessorInterface
{
    use LoggerAwareTrait;

    /** @var PreQualifiedOrdersProviderInterface */
    private $preQualifiedOrdersProvider;

    /** @var OrderEligibilityCheckerInterface */
    private $orderEligibilityChecker;

    /** @var EmailManagerInterface */
    private $emailManager;

    /** @var EntityManager */
    private $orderManager;

    public function __construct(
        PreQualifiedOrdersProviderInterface $preQualifiedOrdersProvider,
        OrderEligibilityCheckerInterface $orderEligibilityChecker,
        EmailManagerInterface $emailManager,
        EntityManager $orderManager
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
            'Checking %s order(s)...',
            count($preQualifiedOrders)
        ));

        foreach ($preQualifiedOrders as $order) {
            if ($this->orderEligibilityChecker->isEligible($order)) {
                /** @var CustomerInterface $customer */
                $customer = $order->getCustomer();
                $this->logger->info(sprintf(
                    'Order #%s is eligible. Sending email to Trustpilot for %s.',
                    null !== $order->getNumber() ? $order->getNumber() : $order->getId(),
                    $customer->getEmail()
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
}
