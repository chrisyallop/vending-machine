<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use App\Domain\Model\VendingMachine;
use App\Domain\Model\Change;

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
     * @Then I should receive change to the amount of ":changeAmount"p in :denominationQuantity denomination of ":denomination"p coins
     */
    public function iShouldReceiveChangeToTheAmountOfPInDenominationOfPCoins($changeAmount, $denominationQuantity, $denomination)
    {
        $denominations = $this->change->getDenominations();

        PHPUnit_Framework_Assert::assertEquals($changeAmount, $this->change->getAmount());
        PHPUnit_Framework_Assert::assertInternalType('array', $denominations);
        PHPUnit_Framework_Assert::assertEquals($denominationQuantity, array_sum($denominations));
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination, $denominations);
        PHPUnit_Framework_Assert::assertEquals($denominationQuantity, $denominations[$denomination]);
    }

    /**
     * @Then I should receive change to the amount of ":changeAmount"p with :denominationQuantity_1 denomination at ":denomination_1"p and :denominationQuantity_2 denomination at ":denomination_2"p
     */
    public function iShouldReceiveChangeToTheAmountOfPWithDenominationAtPAndDenominationAtP(
        $changeAmount,
        $denominationQuantity_1,
        $denomination_1,
        $denominationQuantity_2,
        $denomination_2
    ) {
        PHPUnit_Framework_Assert::assertEquals($changeAmount, $this->change->getAmount());

        $denominations = $this->change->getDenominations();

        PHPUnit_Framework_Assert::assertInternalType('array', $denominations);

        $denominationsTotal = 0;
        foreach ($denominations as $denominationAmount => $denominationQuantity) {
            $denominationsTotal += $denominationQuantity * $denominationAmount;
        }

        PHPUnit_Framework_Assert::assertEquals($changeAmount, $denominationsTotal);

        PHPUnit_Framework_Assert::assertEquals(2, array_sum($denominations));
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_1, $denominations);
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_2, $denominations);
    }

    /**
     * @Then I should receive change of ":changeAmount"p
     */
    public function iShouldReceiveChangeOfP($changeAmount)
    {
        PHPUnit_Framework_Assert::assertEquals($changeAmount, $this->change->getAmount());
    }

    /**
     * @Then in the denominations of one ":denomination_1"p, one ":denomination_2"p and one ":denomination_3"p
     */
    public function inTheDenominationsOfOnePOnePAndOneP($denomination_1, $denomination_2, $denomination_3)
    {
        $denominations = $this->change->getDenominations();

        PHPUnit_Framework_Assert::assertInternalType('array', $denominations);

        $denominationsTotal = 0;
        foreach ($denominations as $denominationAmount => $denominationQuantity) {
            $denominationsTotal += $denominationQuantity * $denominationAmount;
        }

        PHPUnit_Framework_Assert::assertEquals($this->change->getAmount(), $denominationsTotal);

        PHPUnit_Framework_Assert::assertEquals(3, array_sum($denominations));
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_1, $denominations);
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_2, $denominations);
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_3, $denominations);
    }
}
