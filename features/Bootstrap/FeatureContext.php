<?php

namespace Features\Bootstrap;

use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\MinkContext;
use Assert\Assertion;

class FeatureContext extends MinkContext implements SnippetAcceptingContext
{
    /** @var string $chosenGreeting */
    private $chosenGreeting;
    /** @var string $name */
    private $name;

    /**
     * @AfterScenario @javascript
     * @param AfterScenarioScope $scope
     */
    public function screenshotOnFailure(AfterScenarioScope $scope)
    {
        if ($scope->getTestResult()->isPassed() === false) {
            $imageData = $this->getSession()->getDriver()->getScreenshot();
            $imagePath = time() . '.png';
            file_put_contents($imagePath, $imageData);
        }
    }

    /**
     * @Given I visit :path
     */
    public function iVisit($path)
    {
        $this->visit($path);
    }

    /**
     * @When I enter :value in the :fieldName field
     */
    public function iEnterInTheField($value, $fieldName)
    {
        $this->fillField($fieldName, $value);
    }

    /**
     * @When I select a greeting from the :label field
     */
    public function iSelectAGreetingFromTheField($label)
    {
        $field = $this->getSession()->getPage()->findField($label);
        Assertion::notNull($field);
        $options = $field->findAll('css', 'option');
        Assertion::notNull($options);
        Assertion::isArray($options);
        Assertion::notEmpty($options);
        $greetings = [];
        foreach ($options AS $option) {
            $value = $option->getValue();
            //Exclude whatever is already selected, so we actually change it.
            if ($field->getValue() !== $value) {
                $greetings[] = $option->getValue();
            }
        }
        shuffle($greetings);
        $this->chosenGreeting = array_pop($greetings);
        $field->selectOption($this->chosenGreeting);
        $this->getSession()->wait(1000);
    }

    /**
     * @Then I should see the submit button updates to the new greeting
     */
    public function iShouldSeeTheSubmitButtonUpdatesToTheNewGreeting()
    {
        $expected = "Say {$this->chosenGreeting}!";
        $submitButton = $this->getSession()->getPage()->findButton('submit');
        Assertion::eq($submitButton->getText(), $expected);
    }

    /**
     * @Given I have entered a name in the :fieldLabel field
     */
    public function iHaveEnteredANameInTheField($fieldLabel)
    {
        $this->name = 'Bob';
        $this->fillField($fieldLabel, $this->name);
    }

    /**
     * @When I press the submit button
     */
    public function iPressTheSubmitButton()
    {
        $this->pressButton('submit');
    }

    /**
     * @Then I should see the greeting and my name
     */
    public function iShouldSeeTheGreetingAndMyName()
    {
        $greeting = $this->chosenGreeting . ' ' . $this->name . '!';
        $this->assertPageContainsText($greeting);
    }
}
