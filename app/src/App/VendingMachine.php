<?php
/**
 * Vending.
 *
 * @author     Chris Yallop <chrisyallopbsc@gmail.com>
 * @copyright  2016
 */

namespace App;

/**
 * Responsible for vending things.
 *
 * @copyright  Copyright (c) 2016 Chris Yallop (http://chrisyallop.com)
 */
class VendingMachine
{
    /** @var int */
    protected $sellingPrice;

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
     * @return Change
     */
    public function purchaseItem($purchaseAmount)
    {
        $this->assertWholeAmount($purchaseAmount);

        return Change::giveAmount($purchaseAmount - $this->sellingPrice);
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
