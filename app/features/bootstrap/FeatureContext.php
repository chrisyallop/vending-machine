<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use App\Domain\Model\Money;
use App\Domain\Model\NoChangeGivenException;
use App\Domain\Model\VendingMachine;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /** @var VendingMachine */
    protected $vendingMachine;

    /** @var Money */
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
     * @Transform /^(\d+)$/
     */
    public function castStringToNumber($string)
    {
        return intval($string);
    }

    /**
     * @Given a vending machine, dispensing items priced at ":sellingPrice"p
     */
    public function aVendingMachineDispensingItemsPricedAtP($sellingPrice)
    {
        $sellingPrice           = Money::fromAmount((int) $sellingPrice);
        $this->vendingMachine   = VendingMachine::startWithSellingPrice($sellingPrice);
    }

    /**
     * @When I purchase an item for ":purchaseAmount"p
     */
    public function iPurchaseAnItemForP($purchaseAmount)
    {
        try {
            $purchaseAmount = Money::fromAmount((int) $purchaseAmount);
            $this->change   = $this->vendingMachine->purchaseItem($purchaseAmount);
        } catch (NoChangeGivenException $exception) {
            $this->change   = $exception;
        }
    }

    /**
     * @Then I should receive change of ":changeAmount"p
     */
    public function iShouldReceiveChangeOfP($changeAmount)
    {
        PHPUnit_Framework_Assert::assertEquals($changeAmount, $this->change->getAmount());
    }

    /**
     * @Then in the denominations of one ":denomination"p
     */
    public function inTheDenominationsOfOneP($denomination)
    {
        $this->assertDenominationsTotalEqualsChangeAmount();

        PHPUnit_Framework_Assert::assertEquals(func_num_args(), array_sum($this->change->getDenominations()));
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination, $this->change->getDenominations());
    }

    /**
     * @Then in the denominations of one ":denomination_1"p and one ":denomination_2"p
     */
    public function inTheDenominationsOfOnePAndOneP($denomination_1, $denomination_2)
    {
        $this->assertDenominationsTotalEqualsChangeAmount();

        PHPUnit_Framework_Assert::assertEquals(func_num_args(), array_sum($this->change->getDenominations()));
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_1, $this->change->getDenominations());
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_2, $this->change->getDenominations());
    }

    /**
     * @Then in the denominations of one ":denomination_1"p, one ":denomination_2"p and one ":denomination_3"p
     */
    public function inTheDenominationsOfOnePOnePAndOneP($denomination_1, $denomination_2, $denomination_3)
    {
        $this->assertDenominationsTotalEqualsChangeAmount();

        PHPUnit_Framework_Assert::assertEquals(func_num_args(), array_sum($this->change->getDenominations()));
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_1, $this->change->getDenominations());
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_2, $this->change->getDenominations());
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_3, $this->change->getDenominations());
    }

    /**
     * @Then in the denominations of one ":arg1"p, one ":arg2"p, one ":arg3"p, one ":arg4"p, one ":arg5"p, one ":arg6"p and one ":arg7"p
     */
    public function inTheDenominationsOfOnePOnePOnePOnePOnePOnePAndOneP(
        $denomination_1,
        $denomination_2,
        $denomination_3,
        $denomination_4,
        $denomination_5,
        $denomination_6,
        $denomination_7
    ) {
        $this->assertDenominationsTotalEqualsChangeAmount();

        PHPUnit_Framework_Assert::assertEquals(func_num_args(), array_sum($this->change->getDenominations()));
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_1, $this->change->getDenominations());
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_2, $this->change->getDenominations());
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_3, $this->change->getDenominations());
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_4, $this->change->getDenominations());
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_5, $this->change->getDenominations());
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_6, $this->change->getDenominations());
        PHPUnit_Framework_Assert::assertArrayHasKey($denomination_7, $this->change->getDenominations());
    }

    protected function assertDenominationsTotalEqualsChangeAmount()
    {
        $denominations = $this->change->getDenominations();

        PHPUnit_Framework_Assert::assertInternalType('array', $denominations);

        $denominationsTotal = 0;
        foreach ($denominations as $denominationAmount => $denominationQuantity) {
            $denominationsTotal += $denominationQuantity * $denominationAmount;
        }

        PHPUnit_Framework_Assert::assertEquals($this->change->getAmount(), $denominationsTotal);
    }

    /**
     * @Given an inventory with the following coins:
     */
    public function anInventoryWithTheFollowingCoins(TableNode $inventory)
    {
        $startingInventory = [];
        foreach ($inventory as $inventoryLine) {
            $denomination   = $inventoryLine['denomination'];
            $quantity       = $inventoryLine['quantity'];

            $startingInventory[$denomination] = $quantity;
        }

        $this->vendingMachine->setInventory($startingInventory);
    }

    /**
     * @Then I should receive the message :arg1
     */
    public function iShouldReceiveTheMessage($arg1)
    {
        PHPUnit_Framework_Assert::assertInstanceOf('App\Domain\Model\NoChangeGivenException', $this->change);
        PHPUnit_Framework_Assert::assertEquals('No change given', $this->change->getMessage());
    }
}
