<?php

namespace MoneyBooster;

use MoneyBooster\Web\AsyncServer;
use MoneyBooster\Web\Response;
use MoneyBooster\Web\Server as Web;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;


class MoneyBooster extends PluginBase {

    protected ?AsyncServer $web;

    public function onLoad() : void
    {
        $this->saveDefaultConfig();

        $this->web = new AsyncServer(
            new Web('0.0.0.0', 1234),
            fn () => new Response('hello world')
        );
    }

    public function onEnable() : void
    {
        $this->web->start();
    }

    public function onDisable() : void
    {
        $this->web->quit();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool
    {
        return false;
    }
}
