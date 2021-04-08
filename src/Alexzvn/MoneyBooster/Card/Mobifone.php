<?php

namespace Alexzvn\MoneyBooster\Card;

final class Mobifone extends Card
{
    public function validateNumber(): bool
    {
        [$code, $seri] = [$this->code, $this->seri];

        return $this->len($code, 12) && $this->len($seri, 15);
    }
}
