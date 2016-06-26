<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use App\VendingMachine;
use App\Change;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /** @var VendingMachine */
    protected $vendingMachine;

    /** @var Change */
    protected $change;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given a vending machine, dispensing items priced at ":sellingPrice"p
     */
    public function aVendingMachineDispensingItemsPricedAtP($sellingPrice)
    {
        $this->vendingMachine = VendingMachine::startWithSellingPrice((int) $sellingPrice);
    }

    /**
     * @When I purchase an item for ":purchaseAmount"p
     */
    public function iPurchaseAnItemForP($purchaseAmount)
    {
        $this->change = $this->vendingMachine->purchaseItem((int) $purchaseAmount);
    }

    /**
     * @Then I should receive change to the amount of ":changeAmount"p
     */
    public function iShouldReceiveChangeToTheAmountOfP($changeAmount)
    {
        PHPUnit_Framework_Assert::assertEquals($changeAmount, $this->change->getAmount());
    }

    /**
     * @Then I should receive change to the amount of ":changeAmount"p in :denominationQuantity ":denomination"p coins
     */
    public function iShouldReceiveChangeToTheAmountOfPInPCoins($changeAmount, $denominationQuantity, $denomination)
    {
        PHPUnit_Framework_Assert::assertEquals($changeAmount, $this->change->getAmount());
        PHPUnit_Framework_Assert::assertEquals($denominationQuantity, $this->change->getDenominationQuantity());
        PHPUnit_Framework_Assert::assertEquals($denomination, $this->change->getDenomination());
    }
}
