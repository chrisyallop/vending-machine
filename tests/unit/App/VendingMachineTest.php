<?php
/**
 * Vending.
 *
 * @author     Chris Yallop <chrisyallopbsc@gmail.com>
 * @copyright  2016
 */

namespace AppTests;

use App\Domain\Model\Money;
use App\Domain\Model\VendingMachine;
use PHPUnit\Framework\TestCase;

/**
 * Responsible for testing vending things.
 *
 * @copyright  Copyright (c) 2016 Chris Yallop (http://chrisyallop.com)
 */
class VendingMachineTest extends TestCase
{
    public function testVendingMachineStartsWithASellingPrice()
    {
        $startingAmount = 50;
        $vendingMachine = VendingMachine::startWithSellingPrice(Money::fromAmount($startingAmount));

        $this->assertInstanceOf('App\Domain\Model\VendingMachine', $vendingMachine);
        $this->assertEquals($startingAmount, $vendingMachine->getSellingPrice()->getAmount());

        return $vendingMachine;
    }

    /**
     * @depends testVendingMachineStartsWithASellingPrice
     */
    public function testVendingMachineStartsWithTheInventoryDisabled(VendingMachine $vendingMachine)
    {
        $this->assertFalse($vendingMachine->hasInventory());
        $this->assertEquals([], $vendingMachine->getInventory());

        return $vendingMachine;
    }

    /**
     * @depends clone testVendingMachineStartsWithTheInventoryDisabled
     * @expectedException App\Domain\Model\InsufficientPaymentAmountException
     */
    public function testVendingMachineDoesNotAllowAPurchaseWithInsufficientFunds(VendingMachine $vendingMachine)
    {
        $paymentAmount = Money::fromAmount(25);
        $vendingMachine->purchaseItem($paymentAmount);
    }

    /**
     * @depends clone testVendingMachineStartsWithTheInventoryDisabled
     */
    public function testVendingMachineAcceptsAPurchaseAmountMatchingTheSellingPrice(VendingMachine $vendingMachine)
    {
        $paymentAmount  = Money::fromAmount(50);
        $changeAmount   = $vendingMachine->purchaseItem($paymentAmount);

        $this->assertEquals(0, $changeAmount->getAmount());
    }

    /**
     * @depends clone testVendingMachineStartsWithTheInventoryDisabled
     */
    public function testVendingMachineReturnsTheOptimalChangeWhenNoInventoryIsSet(VendingMachine $vendingMachine)
    {
        $paymentAmount  = Money::fromAmount(88);
        $changeAmount   = $vendingMachine->purchaseItem($paymentAmount);

        $this->assertEquals(38, $changeAmount->getAmount());

        $optimalChange = [
            20 => 1,
            10 => 1,
             5 => 1,
             2 => 1,
             1 => 1,
        ];
        $this->assertEquals($optimalChange, $changeAmount->getCoins());
    }

    /**
     * @depends clone testVendingMachineStartsWithASellingPrice
     */
    public function testVendingMachineCanHaveTheInventorySet(VendingMachine $vendingMachine)
    {
        $inventory = [
            100 =>  11,
             50 =>  24,
             20 =>   0,
             10 =>  99,
              5 => 200,
              2 =>  11,
              1 =>  23,
        ];

        $vendingMachine->setInventory($inventory);

        $this->assertEquals($inventory, $vendingMachine->getInventory());

        return $vendingMachine;
    }

    /**
     * @depends testVendingMachineCanHaveTheInventorySet
     */
    public function testVendingMachinePurchaseDeductsFromTheInventoryWhenSet(VendingMachine $vendingMachine)
    {
        $paymentAmount  = Money::fromAmount(88);
        $changeAmount   = $vendingMachine->purchaseItem($paymentAmount);

        $this->assertEquals(38, $changeAmount->getAmount());

        $updatedInventory = [
            100 =>  11,
             50 =>  24,
             20 =>   0,
             10 =>  96,
              5 => 199,
              2 =>  10,
              1 =>  22,
        ];
        $this->assertEquals($updatedInventory, $vendingMachine->getInventory());
    }

    /**
     * @depends clone testVendingMachineStartsWithASellingPrice
     * @expectedException App\Domain\Model\InsufficientChangeException
     */
    public function testVendingMachineDetectsInsufficientChangeAvailable(VendingMachine $vendingMachine)
    {
        $inventory = [
             50 =>  1,
             20 =>  1,
             10 =>  1,
              5 =>  1,
              2 =>  1,
              1 =>  1,
        ];

        $vendingMachine->setInventory($inventory);

        $paymentAmount  = Money::fromAmount(90);
        $changeAmount   = $vendingMachine->purchaseItem($paymentAmount);
    }
}
