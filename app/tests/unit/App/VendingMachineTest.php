<?php
/**
 * Vending.
 *
 * @author     Chris Yallop <chrisyallopbsc@gmail.com>
 * @copyright  2016
 */

namespace AppTests;

use App\Domain\Model\VendingMachine;
use PHPUnit\Framework\TestCase;

/**
 * Responsible for testing vending things.
 *
 * @copyright  Copyright (c) 2016 Chris Yallop (http://chrisyallop.com)
 */
class VendingMachineTest extends TestCase
{
    public function testTheVendingMachineStartsByAcceptingASellingPriceInWholePennyAmounts()
    {
        $sellingPrice   = 100;
        $vendingMachine = VendingMachine::startWithSellingPrice($sellingPrice);

        $this->assertInternalType('int', $vendingMachine->getSellingPrice());
        $this->assertEquals($sellingPrice, $vendingMachine->getSellingPrice());

        return $vendingMachine;
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTheVendingMachineStartsByDecliningASellingPriceInFractionalAmounts()
    {
        $sellingPrice   = 50.50;
        $vendingMachine = VendingMachine::startWithSellingPrice($sellingPrice);
    }

    /**
     * @depends testTheVendingMachineStartsByAcceptingASellingPriceInWholePennyAmounts
     */
    public function testPurchasingAnItemAcceptsWholePennyAmounts(VendingMachine $vendingMachine)
    {
        $change = $vendingMachine->purchaseItem(100);

        $this->assertInternalType('int', $change->getAmount());
    }

    /**
     * @depends testTheVendingMachineStartsByAcceptingASellingPriceInWholePennyAmounts
     * @expectedException \InvalidArgumentException
     */
    public function testPurchasingAnItemWillNotAcceptFractionalAmounts(VendingMachine $vendingMachine)
    {
        $vendingMachine->purchaseItem(60.75);
    }
}
