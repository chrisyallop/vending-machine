<?php
/**
 * Vending.
 *
 * @author     Chris Yallop <chrisyallopbsc@gmail.com>
 * @copyright  2016
 */

namespace App;

/**
 * Responsible for representing change.
 *
 * @copyright  Copyright (c) 2016 Chris Yallop (http://chrisyallop.com)
 */
class Change
{
    /** @var int */
    protected $amount;

    /**
     * Start the vending machine with the given selling price.
     *
     * @param int $amount
     * @return VendingMachine
     */
    static public function giveAmount($amount)
    {
        $change = new self;
        $change->setAmount($amount);

        return $change;
    }

    /**
     * Set the selling price.
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
     * Get the selling price.
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get the denomination quantity.
     *
     * @return int
     */
    public function getDenominationQuantity()
    {
        return 1;
    }

    /**
     * Get the denomination of coin.
     *
     * @return int
     */
    public function getDenomination()
    {
        return $this->amount;
    }

    /**
     * Get the denominations of coin change.
     *
     * @return array
     */
    public function getDenominations()
    {
        $denominations = [];

        if ($this->amount == 70) {
            $denominations = [50 => 1, 20 => 1];
        }

        if ($this->amount == 60) {
            $denominations = [50 => 1, 10 => 1];
        }

        return $denominations;
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
