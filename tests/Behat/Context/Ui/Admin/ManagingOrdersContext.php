<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\Service\SharedStorageInterface;
use Tests\Setono\SyliusTrustpilotPlugin\Behat\Page\Admin\Order\ShowPage;
use Webmozart\Assert\Assert;

final class ManagingOrdersContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var ShowPage
     */
    private $showPage;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param ShowPage $showPage
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ShowPage $showPage
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->showPage = $showPage;
    }

    /**
     * @Then I should see trustpilot box
     */
    public function iShouldSeeTrustpilotBox()
    {
        Assert::true(
            $this->showPage->hasTrustpilotBox(),
            "Trustpilot box was not found at order details page"
        );
    }
}
