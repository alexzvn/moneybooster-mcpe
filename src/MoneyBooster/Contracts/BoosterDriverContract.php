<?php

namespace MoneyBooster\Contracts;

interface BoosterDriverContract
{
    public function request(CardContract $card): BoosterResponseContract;
}
