Feature: Hello World with Custom Greeting
  As a user
  I want to see a greeting with my name
  So I know the page is working

  Scenario: User Enters Their Name
    Given I visit "greet.php"
    When I enter "Jane" in the "Name" field
    And I press "Say Hello"
    Then I should see "Hello Jane!"

  @javascript
  Scenario: Change Greeting Button
    Given I visit "custom-greet.php"
    When I select a greeting from the "Greeting" field
    Then I should see the submit button updates to the new greeting

  @javascript
  Scenario: Get Custom Greeting
    Given I visit "custom-greet.php"
    And I have entered a name in the "Name" field
    And I select a greeting from the "Greeting" field
    When I press the submit button
    Then I should see the greeting and my name
