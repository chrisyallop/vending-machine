<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use App\VendingMachine;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /** @var VendingMachine */
    protected $vendingMachine;

    /** @var integer */
    protected $vendoredChangeAmount;

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
        $this->vendoredChangeAmount = $this->vendingMachine->purchaseItem((int) $purchaseAmount);
    }

    /**
     * @Then I should receive change to the amount of ":expectedChangeAmount"p
     */
    public function iShouldReceiveChangeToTheAmountOfP($expectedChangeAmount)
    {
        PHPUnit_Framework_Assert::assertEquals($expectedChangeAmount, $this->vendoredChangeAmount);
    }
}
