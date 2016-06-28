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

    /** @var array */
    protected $inventory;

    /**
     * Change constructor.
     */
    private function __construct()
    {
    }

    /**
     * Set the amount of change by total value.
     *
     * @param int $amount
     * @return Change
     */
    static public function giveAmount($amount)
    {
        $change = new self;
        $change->setAmount($amount);
        $change->setInventory($change->getDenominations());

        return $change;
    }

    /**
     * Set the amount of change by inventory.
     *
     * @param array $inventory
     * @return Change
     */
    static public function giveAmountByInventory(array $inventory)
    {
        $change = new self;
        $change->setInventory($inventory);
        $change->setAmount(Change::calculateAmountFromInventory($inventory));

        return $change;
    }

    /**
     * Calculate the amount from inventory.
     *
     * @param array $inventory
     * @return int
     */
    static public function calculateAmountFromInventory(array $inventory)
    {
        $amount = 0;
        foreach ($inventory as $denominationAmount => $denominationQuantity) {
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
     * Set the inventory.
     *
     * @param array $inventory
     * @return $this
     */
    private function setInventory(array $inventory)
    {
        $this->inventory = $inventory;

        return $this;
    }

    /**
     * Get the inventory.
     *
     * @return array
     */
    public function getInventory()
    {
        return $this->inventory;
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
     * Deduct an amount of money.
     *
     * @param Change $amount
     * @return Change
     */
    public function deduct(Change $amount)
    {
        if ($this->getAmount() < $amount->getAmount()) {
            throw new NoChangeGivenException('No change given');
        }

        return Change::giveAmount($this->getAmount() - $amount->getAmount());
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
