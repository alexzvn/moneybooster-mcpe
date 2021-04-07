<?php

namespace Alexzvn\MoneyBooster\Card;

final class Zing extends Card
{
    public function validateNumber(): bool
    {
        return $this->len($this->code, 9) &&
            preg_match('/^[A-Z0-9]{11}$/', $this->seri);
    }

    protected function getAcceptAmounts(): array
    {
        return [
            ...parent::getAcceptAmounts(),
            60_000,
            120_000
        ];
    }
}
