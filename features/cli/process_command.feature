@setono_sylius_trustpilot @cli
Feature: Process orders via CLI command
    In order to send emails to Trustpilot
    As a Developer
    I want latest orders to be processed via CLI command

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "T-Shirt"
        And there is a customer "igor.mukhin@gmail.com" that placed an order
        And the customer bought a single "T-Shirt"

    Scenario: Trustpilot email should not be sent immediately
        Given that order was completed
        When I run trustpilot process CLI command
        Then the command should finish successfully
        And I should see output "Checking 1 order(s)..."
        And Output shouldn't contain "Sending email to Trustpilot for igor.mukhin@gmail.com."

    Scenario: Trustpilot email should not be sent for orders completed before configured day
        Given that order was completed 6 days ago
        When I run trustpilot process CLI command
        Then the command should finish successfully
        And I should see output "Checking 1 order(s)..."
        And Output shouldn't contain "Sending email to Trustpilot for igor.mukhin@gmail.com."

    Scenario: Trustpilot email should be sent for orders completed after configured day
        Given that order was completed 7 days ago
        When I run trustpilot process CLI command
        Then the command should finish successfully
        And I should see output "Checking 1 order(s)..."
        And I should see output "Sending email to Trustpilot for igor.mukhin@gmail.com."
        And trustpilot email should be sent for this order
