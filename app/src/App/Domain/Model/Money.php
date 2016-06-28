<?php
/**
 * Vending.
 *
 * @author     Chris Yallop <chrisyallopbsc@gmail.com>
 * @copyright  2016
 */

namespace App\Domain\Model;

/**
 * Responsible for representing money.
 *
 * @copyright  Copyright (c) 2016 Chris Yallop (http://chrisyallop.com)
 */
class Money
{
    /** @var int */
    protected $amount;

    /** @var array */
    protected $coins;

    /**
     * Change constructor.
     */
    private function __construct()
    {
    }

    /**
     * Create money from an amount.
     *
     * @param int $amount
     * @return Money
     */
    static public function fromAmount($amount)
    {
        $money = new self;
        $money->setAmount($amount);
        $money->setCoins($money->getDenominations());

        return $money;
    }

    /**
     * Create money from a collection of coins.
     *
     * @param array $coins
     * @return Money
     */
    static public function fromCoins(array $coins)
    {
        $money = new self;
        $money->setCoins($coins);
        $money->setAmount(Money::calculateAmountFromCoins($coins));

        return $money;
    }

    /**
     * Calculate the amount from coins.
     *
     * @param array $coins
     * @return int
     */
    static public function calculateAmountFromCoins(array $coins)
    {
        $amount = 0;
        foreach ($coins as $denominationAmount => $denominationQuantity) {
            $amount += $denominationQuantity * $denominationAmount;
        }

        return $amount;
    }

    /**
     * Set the amount.
     *
     * @param int $amount
     * @return $this
     */
    private function setAmount($amount)
    {
        $this->assertWholeAmount($amount);

        $this->amount = (int) $amount;

        return $this;
    }

    /**
     * Set the coins.
     *
     * @param array $coins
     * @return $this
     */
    private function setCoins(array $coins)
    {
        $this->coins = $coins;

        return $this;
    }

    /**
     * Get the coins.
     *
     * @return array
     */
    public function getCoins()
    {
        return $this->coins;
    }

    /**
     * Get the amount.
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get the denominations of coins.
     *
     * @return array
     */
    public function getDenominations()
    {
        $denominationAmounts    = [100,50,20,10,5,2,1];
        $denominations          = [];
        $amount                 = $this->amount;

        foreach ($denominationAmounts as $denominationAmount) {
            $denominationQuantity = floor($amount / $denominationAmount);
            if ($denominationQuantity) {
                $denominations[$denominationAmount] = $denominationQuantity;
                $amount -= $denominationAmount * $denominationQuantity;
            }
        }

        return $denominations;
    }

    /**
     * Deduct an amount of money.
     *
     * @param Money $amount
     * @return Money
     */
    public function deduct(Money $amount)
    {
        if ($this->getAmount() < $amount->getAmount()) {
            throw new NoChangeGivenException('No change given');
        }

        return Money::fromAmount($this->getAmount() - $amount->getAmount());
    }

    /**
     * Asserts a whole amount.
     *
     * @param int $amount
     * @throws \InvalidArgumentException
     */
    protected function assertWholeAmount($amount)
    {
        if (!is_int($amount)) {
            throw new \InvalidArgumentException(sprintf(
                'Only whole penny amounts are accepted. Given type %s with value %s',
                gettype($amount),
                (string) $amount
            ));
        }
    }
}
