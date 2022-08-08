<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Behat\Page\Admin\Order;

use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Page\Admin\Order\ShowPage as BaseShowPage;

class ShowPage extends BaseShowPage
{
    public function clickCustomerTrustpilotEnabledToggle(): void
    {
        $this->getElement('customer_trustpilot_enabled_toggle')->click();
    }

    public function isCustomerTrustpilotEnabledToggleTurnedOn(): bool
    {
        return $this->getElement('customer_trustpilot_enabled_toggle_icon')
            ->hasClass('on')
            ;
    }

    public function isCustomerTrustpilotEnabledToggleTurnedOff(): bool
    {
        return $this->getElement('customer_trustpilot_enabled_toggle_icon')
            ->hasClass('off')
            ;
    }

    public function hasTrustpilotBox(): bool
    {
        try {
            $this->getElement('trustpilot');

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function getOrderEmailsSentCount(): int
    {
        return (int) $this->getElement('order_emails_sent')->getHtml();
    }

    public function getCustomerEmailsSentCount(): int
    {
        return (int) $this->getElement('customer_emails_sent')->getHtml();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'trustpilot' => '#setono-trustpilot',
            'order_emails_sent' => '#setono-trustpilot-order-emails-sent',
            'customer_emails_sent' => '#setono-trustpilot-customer-emails-sent',
            'customer_trustpilot_enabled_toggle' => '#setono-trustpilot-customer-enabled-toggle',
            'customer_trustpilot_enabled_toggle_icon' => '#setono-trustpilot-customer-enabled-toggle .icon',
        ]);
    }
}
