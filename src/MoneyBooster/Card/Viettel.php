<?php

namespace MoneyBooster\Card;

final class Viettel extends Card
{
    public function telecom(): int
    {
        return static::VIETTEL;
    }

    public function validate(): bool
    {
        [$serial, $code] = [$this->seri, $this->code];

        return (
            ($this->len($serial, 11) && $this->len($code, 13)) ||
            ($this->len($serial, 14) && $this->len($code, 15))
        );
    }
}
