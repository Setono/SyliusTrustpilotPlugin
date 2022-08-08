<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Tests\Setono\SyliusTrustpilotPlugin\Behat\Page\Admin\Order\ShowPage;
use Webmozart\Assert\Assert;

final class ManagingOrdersContext implements Context
{
    private ShowPage $showPage;

    public function __construct(ShowPage $showPage)
    {
        $this->showPage = $showPage;
    }

    /**
     * @When I click customer trustpilot enabled toggle (again)
     */
    public function iClickCustomerTrustpilotEnabledToggle(): void
    {
        $this->showPage->clickCustomerTrustpilotEnabledToggle();
    }

    /**
     * @Then I should see customer trustpilot enabled toggle turned on
     */
    public function iShouldSeeCustomerTrustpilotEnabledToggleTurnedOn(): void
    {
        Assert::true(
            $this->showPage->isCustomerTrustpilotEnabledToggleTurnedOn()
        );
    }

    /**
     * @Then I should see customer trustpilot enabled toggle turned off
     */
    public function iShouldSeeCustomerTrustpilotEnabledToggleTurnedOff(): void
    {
        Assert::true(
            $this->showPage->isCustomerTrustpilotEnabledToggleTurnedOff()
        );
    }

    /**
     * @Then I should see trustpilot box
     */
    public function iShouldSeeTrustpilotBox(): void
    {
        Assert::true(
            $this->showPage->hasTrustpilotBox(),
            'Trustpilot box was not found at order details page'
        );
    }

    /**
     * @Then I should see :count order emails sent
     */
    public function iShouldSeeOrderEmailsSentCount(int $count): void
    {
        Assert::eq($count, $this->showPage->getOrderEmailsSentCount());
    }

    /**
     * @Then I should see :count customer emails sent
     */
    public function iShouldSeeCustomerEmailsSentCount(int $count): void
    {
        Assert::eq($count, $this->showPage->getCustomerEmailsSentCount());
    }
}
