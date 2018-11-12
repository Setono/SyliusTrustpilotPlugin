@setono_sylius_trustpilot @orders_processor
Feature: Send trustpilot email only once for every eligible orders
    In order to prevent customers to receive Trustpilot emails everytime processor runs
    As a Developer
    I want emails to be sent only once for every eligible order

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "T-Shirt"

        And there is a customer "igor.mukhin@gmail.com" that placed an order "#00000001"
        And the customer bought a single "T-Shirt"
        And that order was completed 7 days ago

    Scenario: Trustpilot email should not be sent for customers
        When orders processor was executed
        Then processor should report that 1 orders was pre-fetched
        And processor should report that order "#00000001" is eligible

        When orders processor was executed one more time
        Then processor should report that 1 orders was pre-fetched
        And processor should not report that order "#00000001" is eligible
