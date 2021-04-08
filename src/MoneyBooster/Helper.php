<?php

use Alexzvn\MoneyBooster\Contracts\BoosterDriverContract;
use Alexzvn\MoneyBooster\MoneyBooster;
use Illuminate\Container\Container;
use pocketmine\Player;

final class Helper
{
    public static function color(string $text): string
    {
        return str_replace('&', "\xc2\xa7", $text);
    }

    public static function player(string $name): ?Player
    {
        return static::plugin()->getServer()->getPlayer($name);
    }

    public static function plugin(): MoneyBooster
    {
        return Container::getInstance()->make(MoneyBooster::class);
    }

    public static function driver(): BoosterDriverContract
    {
        return Container::getInstance()->make(BoosterDriverContract::class);
    }
}
