@setono_sylius_trustpilot @cli @current
Feature: Process orders via CLI command
    In order to process orders
    As a Developer
    I want CLI command runs processor and transfer its output to console

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "T-Shirt"

        And there is a customer "igor.mukhin@gmail.com" that placed an order "#00000001"
        And the customer bought a single "T-Shirt"
        And that order was completed

        And there is a customer "igor.mukhin@gmail.com" that placed an order "#00000002"
        And the customer bought a single "T-Shirt"
        And that order was completed

    Scenario: Console should contain processor's output
        When I run trustpilot process CLI command
        Then the command should finish successfully
        And I should see output "Checking 2 order(s)..."
