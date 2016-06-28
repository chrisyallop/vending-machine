<?php
/**
 * Vending.
 *
 * @author     Chris Yallop <chrisyallopbsc@gmail.com>
 * @copyright  2016
 */

namespace App\Domain\Model;

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
     * Get the denominations of coin change.
     *
     * @return array
     */
    public function getDenominations()
    {
        $denominationAmounts = [100,50,20,10,5,2,1];
        $denominations = [];
        $changeAmount = $this->amount;

        foreach ($denominationAmounts as $denominationAmount) {
            $denominationQuantity = floor($changeAmount / $denominationAmount);
            if ($denominationQuantity) {
                $denominations[$denominationAmount] = $denominationQuantity;
                $changeAmount -= $denominationAmount * $denominationQuantity;
            }
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
