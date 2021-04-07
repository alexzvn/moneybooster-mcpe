<?php

namespace Alexzvn\MoneyBooster;

use Alexzvn\MoneyBooster\Contracts\BoosterDriverContract;
use Alexzvn\MoneyBooster\Drivers\Cardvip\CardvipDriver;
use Alexzvn\MoneyBooster\Web\AsyncServer;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class MoneyBooster extends PluginBase {

    protected ?ContainerContract $container;

    public function onLoad() : void
    {
        $this->saveDefaultConfig();

        $this->container = Container::getInstance();

        $this->register();
    }

    public function onEnable() : void
    {
        $this->register();
    }

    public function onDisable() : void
    {
        $this->unregister();
    }

    /**
     * Remove all abstract
     *
     * @return void
     */
    public function unregister(): void
    {
        $this->container->flush();
    }

    /**
     * Register all abstract to container
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->singleton(ContainerContract::class, $this->container);

        $this->container->bind(Config::class, $this->getConfig());

        $this->registerDriver();
    }

    public function registerDriver()
    {
        $driver = [
            'cardvip' => CardvipDriver::class

        ][$this->getConfig()->get('card.driver')];

        $this->container->bind(BoosterDriverContract::class, $driver);
    }
}
