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
    /** @var Money */
    protected $sellingPrice;

    /** @var Money */
    protected $inventory;

    /**
     * Start the vending machine with the given selling price.
     *
     * @param Money $sellingPrice
     * @return VendingMachine
     */
    static public function startWithSellingPrice(Money $sellingPrice)
    {
        $vendingMachine = new self;
        $vendingMachine->setSellingPrice($sellingPrice);

        return $vendingMachine;
    }

    /**
     * Set the selling price.
     *
     * @param Money $sellingPrice
     * @return $this
     */
    private function setSellingPrice(Money $sellingPrice)
    {
        $this->sellingPrice = $sellingPrice;

        return $this;
    }

    /**
     * Get the selling price.
     *
     * @return Money
     */
    public function getSellingPrice()
    {
        return $this->sellingPrice;
    }

    /**
     * Purchase item.
     *
     * @param Money $purchaseAmount
     * @return Money
     */
    public function purchaseItem(Money $purchaseAmount)
    {
        $changeAmount = $purchaseAmount->deduct($this->sellingPrice);

        if ($this->hasInventory()) {
            return $this->inventory->deduct($changeAmount);
        }

        return $changeAmount;
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
}
