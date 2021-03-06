<?php
/**
 * Vending.
 *
 * @author     Chris Yallop <chrisyallopbsc@gmail.com>
 * @copyright  2016
 */

namespace AppTests;

use App\Domain\Model\Money;
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

    public function testCreationOfMoneyFromRecognisedDenominations()
    {
        $startingMoney = [
            100 => 10,
             50 => 10,
             20 => 10,
             10 => 10,
              5 => 10,
              2 => 10,
              1 => 10,
        ];

        $money = Money::fromCoins($startingMoney);
        $this->assertEquals($startingMoney, $money->getCoins());
    }

    /**
     * @expectedException App\Domain\Model\UnrecognisedDenominationException
     */
    public function testCreationOfMoneyFromUnrecognisedDenominationFails()
    {
        Money::fromCoin(25);
    }

    public function testInsufficientChange()
    {
        $money = Money::fromCoin(20);

        $this->assertFalse($money->hasSufficientChange(50));
    }

    public function testThereIsSufficientChangeWhenNoChangeIsRequired()
    {
        $money = Money::fromCoin(10);
        $this->assertTrue($money->hasSufficientChange(0));
    }

    public function testSufficientChangeWithSingleCoin()
    {
        $money = Money::fromCoin(50);

        $this->assertTrue($money->hasSufficientChange(50));
    }

    public function testSufficientChangeWithTwoSingleDenominationCoins()
    {
        $money = Money::fromCoins([10 => 2]);

        $this->assertTrue($money->hasSufficientChange(20));
    }

    public function testAddingMoneyAsASingleSameDenominationCoin()
    {
        $money      = Money::fromCoin(100);
        $moMoney    = $money->add(Money::fromCoin(100));

        $this->assertEquals(200, $moMoney->getAmount());
    }

    public function testAddingMoneyAsASingleDifferentDenominationCoin()
    {
        $money      = Money::fromCoin(100);
        $moMoney    = $money->add(Money::fromCoin(50));

        $this->assertEquals(150, $moMoney->getAmount());
    }

    public function testTheCorrectChangeIsReturnedWhenOptimalChangeIsNotAvailable()
    {
        $money = Money::fromCoins([5 => 0, 2 => 4, 1 => 2]);

        $change = $money->deduct(Money::fromAmount(5));

        $this->assertEquals(5, $change['newAmount']->getAmount());
        $this->assertEquals(0, $change['deductedCoins']->getCoinQuantityByDenomination(5));
        $this->assertEquals([2 => 2, 1 => 1], $change['deductedCoins']->getCoins());
    }

    /**
     * @expectedException App\Domain\Model\CannotDeductLargerAmountFromSmallerAmountException
     */
    public function testDeductingALargerAmountFromASmallerAmountFails()
    {
        $money      = Money::fromCoin(10);
        $lessMoney  = $money->deduct(Money::fromCoin(20));
    }

    /**
     * @expectedException App\Domain\Model\InsufficientChangeException
     */
    public function testDeductingMoneyFromASingleCoinFails()
    {
        $money      = Money::fromCoin(100);
        $lessMoney  = $money->deduct(Money::fromCoin(50));
    }
}
