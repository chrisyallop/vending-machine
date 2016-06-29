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
        $this->checkForSufficientPaymentAmount($purchaseAmount);

        if ($this->hasInventory()) {
            $this->checkForSufficientChangeAmount($purchaseAmount);
            $this->inventory    = $this->inventory->add($purchaseAmount);

            $deductionResult    = $this->inventory->deduct(Money::fromAmount($this->getChangeAmount($purchaseAmount)));
            $this->inventory    = $deductionResult['newAmount'];
            $changeAmount       = $deductionResult['deductedCoins'];
        } else {
            $changeAmount       = Money::fromAmount($this->getChangeAmount($purchaseAmount));
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

    /**
     * Checks for sufficient payment.
     *
     * @param Money $purchaseAmount
     * @throws InsufficientPaymentAmount
     */
    protected function checkForSufficientPaymentAmount(Money $purchaseAmount)
    {
        if ($purchaseAmount < $this->sellingPrice) {
            throw new InsufficientPaymentAmount(sprintf(
                'Insufficient payment amount. The selling price is %d. You have given %d.',
                $this->sellingPrice,
                $purchaseAmount
            ));
        }
    }

    protected function checkForSufficientChangeAmount(Money $purchaseAmount)
    {
        if ($this->isExactPurchaseAmount($purchaseAmount)) {
            return true;
        }

        if (!$this->inventory->hasSufficientChange($this->getChangeAmount($purchaseAmount))) {
            throw new InsufficientChangeException('Insufficient change. Please use correct change.');
        }
    }

    protected function isExactPurchaseAmount(Money $purchaseAmount)
    {
        return 0 == $this->getChangeAmount($purchaseAmount);
    }

    protected function getChangeAmount(Money $purchaseAmount)
    {
        return $purchaseAmount->getAmount() - $this->sellingPrice->getAmount();
    }
}
