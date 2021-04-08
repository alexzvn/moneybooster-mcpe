<?php

namespace Alexzvn\MoneyBooster\Contracts;

interface BoosterCallbackContract
{
    public const SUCCESS = 1;

    public const WRONG_AMOUNT = 2;

    public const CARD_INVALID = 3;

    /**
     * Get player name
     *
     * @return string
     */ 
    public function player(): string;

    /**
     * Get status from callback
     *
     * @return integer Callback enum
     */
    public function status(): int;

    /**
     * Get amount of card
     *
     * @return integer
     */
    public function amount(): int;

    /**
     * Determine if card was successfully deposit
     *
     * @return boolean
     */
    public function success(): bool;
}
