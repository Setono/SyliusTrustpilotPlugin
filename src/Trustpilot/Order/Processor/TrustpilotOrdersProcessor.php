<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor;

use Setono\SyliusTrustpilotPlugin\Model\OrderInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EligibilityChecker\OrderEligibilityCheckerInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EmailManager\EmailManagerInterface;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Provider\PreQualifiedOrdersProviderInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository;

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
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @param PreQualifiedOrdersProviderInterface $preQualifiedOrdersProvider
     * @param OrderEligibilityCheckerInterface $orderEligibilityChecker
     * @param EmailManagerInterface $emailManager
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        PreQualifiedOrdersProviderInterface $preQualifiedOrdersProvider,
        OrderEligibilityCheckerInterface $orderEligibilityChecker,
        EmailManagerInterface $emailManager,
        OrderRepository $orderRepository
    ) {
        $this->preQualifiedOrdersProvider = $preQualifiedOrdersProvider;
        $this->orderEligibilityChecker = $orderEligibilityChecker;
        $this->emailManager = $emailManager;
        $this->orderRepository = $orderRepository;
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
                $this->orderRepository->add($order);
            }
        }
    }
}
