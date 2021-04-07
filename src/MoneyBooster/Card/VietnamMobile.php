<?php

namespace MoneyBooster\Card;

final class VietnamMobile extends Card
{
    public function validateNumber(): bool
    {
        return $this->len($this->code, 12) &&
            $this->len($this->seri, 11);
    }
}
