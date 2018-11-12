@setono_sylius_trustpilot @orders_processor
Feature: Fetch only completed orders to process
    In order to prevent processing carts
    As a Developer
    I want processor to fetch only completed orders

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "T-Shirt"

        And there is a customer "igor.mukhin@gmail.com" that placed an order "#00000001"
        And the customer bought a single "T-Shirt"
        And that order was not completed

    Scenario: Not completed orders should not be fetched
        When orders processor was executed
        Then processor should report that 0 orders was pre-fetched

    Scenario: Completed orders should be fetched
        Given that order was completed
        When orders processor was executed
        Then processor should report that 1 orders was pre-fetched
