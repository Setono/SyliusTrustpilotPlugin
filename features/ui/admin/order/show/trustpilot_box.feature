@setono_sylius_trustpilot @ui
Feature: Order details page shows Trustpilot box
    In order to see trustpilot-related data at order details page
    As an Administrator
    I want orders details page have trustpilot box

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

    Scenario: An order details contains trustpilot box
        When I am viewing the summary of this order
        Then I should see trustpilot box
        And I should see 0 order emails sent
        And I should see 0 customer emails sent

    Scenario: Trustpilot box represents actual amount of sent emails
        Given order "#00000001" have 1 emails sent
        When I am viewing the summary of this order
        Then I should see trustpilot box
        And I should see 1 order emails sent
        And I should see 1 customer emails sent
