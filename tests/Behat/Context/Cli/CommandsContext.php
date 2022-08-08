<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Behat\Context\Cli;

use Behat\Behat\Context\Context;
use Setono\SyliusTrustpilotPlugin\Command\TrustpilotProcessCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

final class CommandsContext implements Context
{
    private KernelInterface $kernel;

    private ?CommandTester $tester = null;

    private TrustpilotProcessCommand $trustpilotProcessCommand;

    public function __construct(
        KernelInterface $kernel,
        TrustpilotProcessCommand $trustpilotProcessCommand
    ) {
        $this->kernel = $kernel;
        $this->trustpilotProcessCommand = $trustpilotProcessCommand;
    }

    /**
     * @When I run trustpilot process CLI command (again)
     */
    public function iRunTrustpilotProcessCommand(): void
    {
        $application = new Application($this->kernel);
        $application->add($this->trustpilotProcessCommand);
        $this->tester = new CommandTester($this->trustpilotProcessCommand);
        $this->tester->execute([
            'command' => $this->trustpilotProcessCommand->getName(),
        ], [
            'verbosity' => OutputInterface::VERBOSITY_DEBUG,
        ]);
    }

    /**
     * @Then the command should finish successfully
     */
    public function commandSuccess(): void
    {
        Assert::notNull($this->tester);
        Assert::same($this->tester->getStatusCode(), 0);
    }

    /**
     * @Then echo command output
     */
    public function echoCommandOutput(): void
    {
        Assert::notNull($this->tester);
        echo $this->tester->getDisplay();
    }

    /**
     * @Then I should see output :text
     */
    public function iShouldSeeOutput(string $text): void
    {
        Assert::notNull($this->tester);
        Assert::contains($this->tester->getDisplay(), $text);
    }

    /**
     * @Then Output shouldn't contain :text
     */
    public function iShouldNotSeeOutput(string $text): void
    {
        Assert::notNull($this->tester);
        Assert::notContains($this->tester->getDisplay(), $text);
    }
}
