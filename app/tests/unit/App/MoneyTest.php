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
class MoneyTest extends TestCase
{
    public function testMoneyCanBeCreatedInWholePennyAmounts()
    {
        $money = Money::fromAmount(100);

        $this->assertInstanceOf('App\Domain\Model\Money', $money);
        $this->assertEquals($money->getAmount(), $money->getAmount());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMoneyCanNotBeCreatedFromFractionalAmounts()
    {
        Money::fromAmount(5.50);
    }
}
