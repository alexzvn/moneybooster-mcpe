<?php

namespace MoneyBooster\Card;

final class Vcoin extends Card
{
    public function validateNumber(): bool
    {
        return $this->len($this->code, 12) &&
            preg_match('/^(PM|ID)[0-9]{10}$/', $this->seri);
    }

    public function getAcceptAmounts(): array
    {
        return [
            parent::getAcceptAmounts(),
            1_000_000
        ];
    }
}
