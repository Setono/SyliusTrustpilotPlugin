<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Behat\Page\Admin\Order;

use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Page\Admin\Order\ShowPage as BaseShowPage;

class ShowPage extends BaseShowPage
{
    /**
     * @return bool
     */
    public function hasTrustpilotBox()
    {
        try {
            $this->getElement('trustpilot');
            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    /**
     * @return int
     */
    public function getOrderEmailsSentCount(): int
    {
        return (int)$this->getElement('order_emails_sent')->getHtml();
    }

    /**
     * @return int
     */
    public function getCustomerEmailsSentCount(): int
    {
        return (int)$this->getElement('customer_emails_sent')->getHtml();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'trustpilot' => '#setono-trustpilot',
        ]);
    }
}
