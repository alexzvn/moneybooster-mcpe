<?php

namespace MoneyBooster\Card;

final class Garena extends Card
{
    public function validateNumber(): bool
    {
        return $this->len($this->code, 16) &&
            $this->len($this->seri, 8);
    }
}
