@setono_sylius_trustpilot @ui
Feature: Order details page give ability to enable/disable trustpilot for Customer
    In order to enable/disable trustpilot for Customer
    As an Administrator
    I want order details page to have enable/disable toggle

    Background:
        Given the store operates on a single channel in "United States"
        And the store ships everywhere for free
        And the store allows paying with "Cash on Delivery"
        And the store has a product "T-Shirt"

        And there is a customer "igor.mukhin@gmail.com" that placed an order "#00000001"
        And the customer bought a single "T-Shirt"
        And the customer "Igor Mukhin" addressed it to "Seaside Fwy", "90802" "Los Angeles" in the "United States" with identical billing address
        And the customer chose "Free" shipping method with "Cash on Delivery" payment

        And I am logged in as an administrator

    Scenario: Clicking on toggle should revert its state
        Given I am viewing the summary of this order
        When I click customer trustpilot enabled toggle
        Then I should see customer trustpilot enabled toggle turned off

        When I click customer trustpilot enabled toggle again
        Then I should see customer trustpilot enabled toggle turned on
