<?php

namespace Alexzvn\MoneyBooster;

use Alexzvn\MoneyBooster\Commands\NaptheCommand;
use Alexzvn\MoneyBooster\Contracts\BoosterDriverContract;
use Alexzvn\MoneyBooster\Drivers\Cardvip\CardvipDriver;
use Alexzvn\MoneyBooster\Web\AsyncServer;
use Alexzvn\MoneyBooster\Web\Server;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use sekjun9878\RequestParser\Request;

class MoneyBooster extends PluginBase {

    protected ?ContainerContract $container;

    protected ?AsyncServer $web;

    public function onLoad() : void
    {
        $this->saveDefaultConfig();

        $this->container = Container::getInstance();
    }

    public function onEnable() : void
    {
        $this->register();
    }

    public function onDisable() : void
    {
       $this->container->flush();
       $this->web->quit();
    }

    public function register(): void
    {
        $this->container->bind(static::class, $this);
        $this->container->bind(Config::class, $this->getConfig());
        $this->container->singleton(ContainerContract::class, $this->container);

        $this->registerDriver();
        $this->registerCallback();
        $this->registerCommands();
        $this->registerCallback();
    }

    public function registerCommands()
    {
        $mapper = $this->getServer()->getCommandMap();

        $mapper->register('napthe', $this->container->make(NaptheCommand::class));
    }

    public function registerDriver()
    {
        $driver = [
            'cardvip' => CardvipDriver::class

        ][$this->getConfig()->get('card.driver')];

        $this->container->bind(BoosterDriverContract::class, $driver);
    }

    public function registerCallback()
    {
        $web = new Server('0.0.0.0', $this->getConfig()->get('callback.port'));

        $this->web = new AsyncServer($web, $this->container->make(Callback::class));

        $this->web->start();
    }
}
