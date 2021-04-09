<?php

namespace Alexzvn\MoneyBooster\Card;

final class Vinaphone extends Card
{
    public function validateNumber(): bool
    {
        list($code, $seri) = [$this->code, $this->seri];

        return $this->len($seri, 14) && (
            $this->len($code, 12) || $this->len($code, 14)
        );
    }
}
