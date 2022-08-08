<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Behat\Context\Domain;

use Behat\Behat\Context\Context;
use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\Processor\TrustpilotOrdersProcessor;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;

final class OrdersProcessorContext implements Context
{
    private TrustpilotOrdersProcessor $trustpilotOrdersProcessor;

    private BufferedOutput $output;

    private ?string $lastOutputData = null;

    public function __construct(
        TrustpilotOrdersProcessor $trustpilotOrdersProcessor
    ) {
        $this->trustpilotOrdersProcessor = $trustpilotOrdersProcessor;
        $this->output = new BufferedOutput();
        $this->output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
        $this->trustpilotOrdersProcessor->setLogger(
            new ConsoleLogger($this->output)
        );
    }

    /**
     * @When orders processor was executed (one more time)
     */
    public function ordersProcessorWasExecuted(): void
    {
        $this->trustpilotOrdersProcessor->process();
        $this->lastOutputData = $this->output->fetch();
    }

    /**
     * @Then processor should report that :count orders was pre-fetched
     * @Then processor should report that :count order was pre-fetched
     */
    public function processorShouldReportCountOfPrefetchedOrders(int $count): void
    {
        Assert::notNull($this->lastOutputData);
        Assert::contains($this->lastOutputData, sprintf(
            'Checking %s order(s)...',
            $count
        ));
    }

    /**
     * @Then processor should report that order :order is eligible
     */
    public function processorShouldReportOrderEligible(OrderInterface $order): void
    {
        Assert::notNull($this->lastOutputData);
        Assert::contains($this->lastOutputData, sprintf(
            'Order #%s is eligible.',
            $order->getNumber() ?: (string) $order->getId()
        ));
    }

    /**
     * @Then processor should not report that order :order is eligible
     */
    public function processorShouldNotReportOrderEligible(OrderInterface $order): void
    {
        Assert::notNull($this->lastOutputData);
        Assert::notContains($this->lastOutputData, sprintf(
            'Order #%s is eligible.',
            $order->getNumber() ?: (string) $order->getId()
        ));
    }

    /**
     * @Then processor should not report that any order is eligible
     */
    public function processorShouldNotReportAnyOrderEligible(): void
    {
        Assert::notNull($this->lastOutputData);
        Assert::notContains($this->lastOutputData, 'is eligible.');
    }

    /**
     * @Then processor should report that trustpilot email was sent for (customer "([^"]+)")
     */
    public function processorShouldReportTrustpilotEmailSentForCustomer(CustomerInterface $customer): void
    {
        Assert::notNull($this->lastOutputData);
        Assert::contains($this->lastOutputData, sprintf(
            'Sending email to Trustpilot for %s.',
            (string) $customer->getEmail()
        ));
    }

    /**
     * @Then processor should not report that trustpilot email was sent for (customer "([^"]+)")
     */
    public function processorShouldNotReportTrustpilotEmailSentForCustomer(CustomerInterface $customer): void
    {
        Assert::notNull($this->lastOutputData);
        Assert::notContains($this->lastOutputData, sprintf(
            'Sending email to Trustpilot for %s.',
            (string) $customer->getEmail()
        ));
    }
}
