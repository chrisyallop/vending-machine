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
    protected $coins = [];

    /** @var array */
    protected $availableDenominations = [];

    /**
     * Money constructor.
     */
    private function __construct()
    {
        $this->availableDenominations = [100,50,20,10,5,2,1];
    }

    /**
     * Create money from an amount.
     *
     * @param int $amount
     * @return Money
     */
    static public function fromAmount($amount)
    {
        self::assertWholeAmount($amount);

        $money = new self;
        $money->setAmount($amount);
        $money->setCoins($money->getOptimalDenominations());

        return $money;
    }

    /**
     * Create money from a collection of coins.
     *
     * @param array $coin
     * @return Money
     */
    static public function fromCoin($coin)
    {
        $coins = [$coin => 1];

        return self::fromCoins($coins);
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
     * Asserts a whole amount.
     *
     * @param int $amount
     * @throws \InvalidArgumentException
     */
    static public function assertWholeAmount($amount)
    {
        if (!is_int($amount)) {
            throw new \InvalidArgumentException(sprintf(
                'Only whole penny amounts are accepted. Given type %s with value %s',
                gettype($amount),
                (string) $amount
            ));
        }
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
    public function getOptimalDenominations()
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
     * Add an amount of money.
     *
     * @param Money $amount
     * @return Money
     */
    public function add(Money $amount)
    {
        $coins = $this->getCoins();
        foreach ($this->availableDenominations as $denomination) {
            if ($amount->hasCoin($denomination)) {
                if (array_key_exists($denomination, $coins)) {
                    $coins[$denomination] += $amount->getCoinQuantityByDenomination($denomination);
                } else {
                    $coins[$denomination] = $amount->getCoinQuantityByDenomination($denomination);
                }
            }
        }

        return self::fromCoins($coins);
    }

    /**
     * Deduct an amount of money.
     *
     * @param Money $amount
     * @return Money
     */
    public function deduct(Money $amount)
    {
        if ($amount->getAmount() == 0) {
            return [
                'newAmount'     => $this,
                'deductedCoins' => Money::fromAmount(0)
            ];
        }

        if ($this->getAmount() < $amount->getAmount()) {
            throw new CannotDeductLargerAmountFromSmallerAmountException(sprintf(
                'Cannot deduct a larger amount from a smaller amount. Tried deducting %d from %d.',
                $amount->getAmount(),
                $this->getAmount()
            ));
        }

        if (!$this->hasSufficientChange($amount->getAmount())) {
            throw new InsufficientChangeException('Insufficient change. Please use correct change.');
        }

        $changeCalculationResult    = $this->calculateChangeGiven($this->coins, $amount->getAmount());
        $remainingAmount            = $changeCalculationResult['remainingAmount'];
        $changedReturned            = $changeCalculationResult['changedReturned'];

        return [
            'newAmount'     => Money::fromCoins($remainingAmount),
            'deductedCoins' => self::fromCoins($changedReturned)
        ];
    }

    public function hasSufficientChange($changeAmountRequested)
    {
        $changeCalculationResult    = $this->calculateChangeGiven($this->coins, $changeAmountRequested);
        $changedReturned            = $changeCalculationResult['changedReturned'];

        return !empty($changedReturned) && $changeAmountRequested == self::fromCoins($changedReturned)->getAmount();
    }

    protected function calculateChangeGiven($startingAmount, $changeToDeduct)
    {
        $coins          = $startingAmount;
        $changeRequired = $changeToDeduct;
        $returnChange   = [];

        // loop available coins by denomination
        foreach ($coins as $coin => $quantity) {
            if ($quantity < 1) {
                continue;
            }

            // loop all quantities of an available coin
            for ($i=1; $i <= $quantity; $i++){
                if ($coin <= $changeRequired) {
                    if (array_key_exists($coin, $returnChange)) {
                        $returnChange[$coin] += 1;
                    } else {
                        $returnChange[$coin] = 1;
                    }

                    $coins[$coin]   -= 1;     // deduct quantity available
                    $changeRequired -= $coin; // deduct change amount required
                }
            }
        }

        return [
            'remainingAmount'   => $coins,
            'changedReturned'   => $returnChange,
        ];
    }

    public function hasCoin($coinDenomination)
    {
        return array_key_exists($coinDenomination, $this->coins);
    }

    public function getCoinQuantityByDenomination($coinDenomination)
    {
        if ($this->hasCoin($coinDenomination)) {
            return $this->coins[$coinDenomination];
        };

        return 0;
    }
}
