@setono_sylius_trustpilot @orders_processor
Feature: Send trustpilot emails only after confgured amount of days after checkout
    In order to prevent customers to receive Trustpilot emails immediately
    As a Developer
    I want emails to be sent only after configured amount of days after checkout date

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "T-Shirt"

        And there is a customer "igor.mukhin@gmail.com" that placed an order "#00000001"
        And the customer bought a single "T-Shirt"

    Scenario: Trustpilot email should not be sent immediately
        Given that order was completed
        When orders processor was executed
        Then processor should report that 1 order was pre-fetched
        And processor should not report that any order is eligible

    Scenario: Trustpilot email should not be sent for orders completed before configured day
        Given that order was completed 6 days ago
        When orders processor was executed
        Then processor should report that 1 order was pre-fetched
        And processor should not report that any order is eligible

    Scenario: Trustpilot email should be sent for orders completed after configured day
        Given that order was completed 7 days ago
        When orders processor was executed
        Then processor should report that 1 order was pre-fetched
        And processor should report that order "#00000001" is eligible
        And trustpilot email should be sent for this order
