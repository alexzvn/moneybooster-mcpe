<?php

namespace Alexzvn\MoneyBooster;

use Alexzvn\MoneyBooster\Commands\NaptheCommand;
use Alexzvn\MoneyBooster\Drivers\Cardvip\CardvipDriver;
use Alexzvn\MoneyBooster\Drivers\Driver;
use Alexzvn\MoneyBooster\Web\AsyncServer;
use Alexzvn\MoneyBooster\Web\WebServer;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;


class MoneyBooster extends PluginBase {

    protected ?Driver $driver;

    protected $web;

    protected ?Config $config;

    public function onLoad() : void
    {
        $this->saveDefaultConfig();

        $this->config = new Config($this->getDataFolder() . '/config.yml');
    }

    public function onEnable() : void
    {
        $this->register();
    }

    public function onDisable() : void
    {
       
    }

    public function register(): void
    {
        $this->registerDriver();
        $this->registerCommands();
        $this->registerWebserver();
        $this->registerCallback();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $params): bool
    {
        $napthe = new NaptheCommand($this->driver, $this);

        if ($command->getName() === $napthe->getName()) {
            return $napthe->execute($sender, $label, $params);
        }

        return true;
    }

    public function registerCommands(): void
    {
        $mapper = $this->getServer()->getCommandMap();

        $mapper->register('napthe', new NaptheCommand($this->driver, $this));
    }

    public function registerDriver(): void
    {
        $driver = [
            'cardvip' => CardvipDriver::class

        ][$this->config->getNested('card.driver')];

        $this->driver = new $driver($this->config);
    }

    public function registerWebserver(): void
    {
        $web = new WebServer('0.0.0.0', $this->config->getNested('callback.port'));

        $folder = $this->getDataFolder();

        $this->web = new AsyncServer($web, function ($data) use ($folder) {

            if (! file_exists("$folder/pending")) {
                mkdir("$folder/pending");
            }

            file_put_contents("$folder/pending/". uniqid(). '.raw', $data);
        });

        $this->web->start();
    }

    public function registerCallback(): void
    {
        $this->getScheduler()->scheduleRepeatingTask(
            new Callback($this, $this->driver, $this->config),
            $this->config->getNested('callback.interval')
        );
    }
}
