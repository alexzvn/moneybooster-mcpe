<?php

namespace Alexzvn\MoneyBooster;

use Alexzvn\MoneyBooster\Contracts\BoosterCallbackContract;
use Alexzvn\MoneyBooster\Drivers\BoosterCallback;
use Alexzvn\MoneyBooster\Drivers\Driver;
use onebone\pointapi\PointAPI;
use pocketmine\utils\Config;
use Alexzvn\MoneyBooster\Web\Parser\Request;
use Alexzvn\MoneyBooster\Web\Parser\RequestParser;
use pocketmine\scheduler\Task;

class Callback extends Task
{
    protected Driver $driver;

    protected Config $config;

    protected MoneyBooster $plugin;

    public function __construct(MoneyBooster $plugin, Driver $driver, Config $config) {
        $this->driver = $driver;
        $this->config = $config;
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick): void
    {
        foreach (glob($this->plugin->getDataFolder() . '/pending/*.raw') as $file) {
            $request = file_get_contents($file);

            $parser = new RequestParser;

            $parser->addData($request);

            $this->__invoke(Request::create($parser->exportRequestState()));

            unlink($file);
        }
    }

    protected function handleTransaction(BoosterCallbackContract $request): void
    {
        if (! $request->success()) return;

        $pointAPI = PointAPI::getInstance();

        $points   = $this->makePoint($request->amount());

        $pointAPI->addPoint($request->player(), $points);
    }

    protected function makePoint(int $amount): int
    {
        return $this->convertRatio($this->convertBonus($amount));
    }

    private function convertRatio(int $amount): int
    {
        $ratio = (int) $this->config->get('ratio', 1);

        return (int) ($amount/$ratio);
    }

    private function convertBonus(int $amount): int
    {
        $bonus = (int) $this->config->get('bonus', 0);

        $amount += ($bonus/100) * $amount;

        return (int) $amount;
    }

    public function __invoke(Request $request): void
    {
        $this->handleTransaction($this->driver->callback($request));
    }
}
