<?php

namespace Alexzvn\MoneyBooster\Card;

final class Viettel extends Card
{
    public function validateNumber(): bool
    {
        [$serial, $code] = [$this->seri, $this->code];

        return (
            ($this->len($serial, 11) && $this->len($code, 13)) ||
            ($this->len($serial, 14) && $this->len($code, 15))
        );
    }
}
