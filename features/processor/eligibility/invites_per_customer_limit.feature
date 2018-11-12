@setono_sylius_trustpilot @orders_processor
Feature: Process orders of customer with invites limit
    In order to prevent customers to receive too much Trustpilot emails
    As a Developer
    I want emails to be sent only configured times per customer

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "T-Shirt"

        And there is a customer "igor.mukhin@gmail.com" that placed an order "#00000001"
        And the customer bought a single "T-Shirt"
        And that order was completed 7 days ago

        And there is a customer "igor.mukhin@gmail.com" that placed an order "#00000002"
        And the customer bought a single "T-Shirt"
        And that order was completed 7 days ago

    Scenario: Trustpilot email should not be sent for customers
        When orders processor was executed
        Then processor should report that 2 orders was pre-fetched
        And processor should report that order "#00000001" is eligible
        And processor should not report that order "#00000002" is eligible
