<?php

namespace Alexzvn\MoneyBooster\Contracts;

use pocketmine\Player;

interface BoosterDriverContract
{
    public function request(CardContract $card, Player $player): BoosterResponseContract;
}
