<?php

namespace Alexzvn\MoneyBooster\Contracts;

interface BoosterDriverContract
{
    public function request(CardContract $card): BoosterResponseContract;
}
