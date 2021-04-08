<?php

namespace Alexzvn\MoneyBooster\Card;

final class Gate extends Card
{
    public function validateNumber(): bool
    {
        return $this->len($this->code, 11) &&
            preg_match('/^CB[0-9]{8}$/', $this->seri);
    }

    protected function getAcceptAmounts(): array
    {
        return [
            ...parent::getAcceptAmounts(),
            1_000_000,
            2_000_000,
            5_000_000
        ];
    }
}
