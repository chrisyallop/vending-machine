<?php
/**
 * Vending.
 *
 * @author     Chris Yallop <chrisyallopbsc@gmail.com>
 * @copyright  2016
 */

namespace App\Domain\Model;

/**
 * Responsible for vending things.
 *
 * @copyright  Copyright (c) 2016 Chris Yallop (http://chrisyallop.com)
 */
class VendingMachine
{
    /** @var int */
    protected $sellingPrice;

    /** @var Money */
    protected $inventory;

    /**
     * Start the vending machine with the given selling price.
     *
     * @param int $sellingPrice
     * @return VendingMachine
     */
    static public function startWithSellingPrice($sellingPrice)
    {
        $vendingMachine = new self;
        $vendingMachine->setSellingPrice($sellingPrice);

        return $vendingMachine;
    }

    /**
     * Set the selling price.
     *
     * @param int $sellingPrice
     * @return $this
     */
    private function setSellingPrice($sellingPrice)
    {
        $this->assertWholeAmount($sellingPrice);

        $this->sellingPrice = (int) $sellingPrice;

        return $this;
    }

    /**
     * Get the selling price.
     *
     * @return int
     */
    public function getSellingPrice()
    {
        return $this->sellingPrice;
    }

    /**
     * Purchase item.
     *
     * @param int $purchaseAmount
     * @return Money
     */
    public function purchaseItem($purchaseAmount)
    {
        $this->assertWholeAmount($purchaseAmount);

        $changeAmount = $purchaseAmount - $this->sellingPrice;

        if ($this->hasInventory()) {
            return $this->inventory->deduct(Money::fromAmount($changeAmount));
        }

        return Money::fromAmount($changeAmount);
    }

    /**
     * Check if there is an inventory.
     *
     * @return bool
     */
    public function hasInventory()
    {
        return $this->inventory instanceof Money;
    }

    /**
     * Set inventory.
     *
     * @param array $inventory
     * @return $this
     */
    public function setInventory(array $inventory)
    {
        $this->inventory = Money::fromCoins($inventory);

        return $this;
    }

    /**
     * Get inventory.
     *
     * @return array
     */
    public function getInventory()
    {
        return $this->inventory->getCoins();
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
