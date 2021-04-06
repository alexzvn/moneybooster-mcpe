<?php

namespace MoneyBooster\Contracts;

interface BoosterResponseContract
{
    /**
     * Everything is fine
     */
    public const SUCCESS = 0;

    /**
     * Endpoint API are currently maintain
     */
    public const MAINTAIN = 1;

    /**
     * Card invalid
     */
    public const INVALID = 2;

    /**
     * Card already used
     */
    public const DUPLICATED = 3;

    /**
     * Is there are something wrong with account?
     */
    public const APIKEY_ERROR = 4;

    /**
     * Their server error
     */
    public const API_ERROR = 5;

    /**
     * Get status of response
     *
     * @return integer
     */
    public function status(): int;

    /**
     * Check if success or not
     *
     * @return boolean
     */
    public function success(): bool;

    /**
     * Check if there are any errors
     *
     * @return boolean
     */
    public function isError(): bool;

    /**
     * Get amount of card
     *
     * @return integer
     */
    public function amount(): int;
}
