@setono_sylius_trustpilot @orders_processor
Feature: Process orders of customer with trustpilot enabled/disabled
    In order to prevent some customers to receive Trustpilot emails
    As a Developer
    I want emails to be sent only to customers with trustpilot option enabled

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "T-Shirt"

        And there is a customer "igor.mukhin@gmail.com" that placed an order "#00000001"
        And the customer bought a single "T-Shirt"
        And that order was completed 7 days ago

    Scenario: Trustpilot email should not be sent for customers with disabled trustpilot option
        Given trustpilot disabled for customer "igor.mukhin@gmail.com"
        When orders processor was executed
        Then processor should report that 1 orders was pre-fetched
        And processor should not report that order "#00000001" is eligible

    Scenario: Trustpilot email should be sent for customers with enabled trustpilot option
        Given trustpilot enabled for customer "igor.mukhin@gmail.com"
        When orders processor was executed
        Then processor should report that 1 orders was pre-fetched
        And processor should report that order "#00000001" is eligible
        And trustpilot email should be sent for this order
