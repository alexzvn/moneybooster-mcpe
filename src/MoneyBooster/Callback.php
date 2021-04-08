<?php

namespace Alexzvn\MoneyBooster;

use Alexzvn\MoneyBooster\Drivers\BoosterCallback;
use Alexzvn\MoneyBooster\Drivers\Driver;
use onebone\pointapi\PointAPI;
use sekjun9878\RequestParser\Request;

class Callback
{
    protected MoneyBooster $plugin;

    protected Driver $driver;

    public function __construct(MoneyBooster $plugin, Driver $driver) {
        $this->plugin = $plugin;
        $this->driver = $driver;
    }

    protected function handleTransaction(BoosterCallback $request)
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
        $ratio = (int) $this->plugin->getConfig()->get('ratio', 1);

        return (int) ($amount/$ratio);
    }

    private function convertBonus(int $amount): int
    {
        $bonus = (int) $this->plugin->getConfig()->get('bonus', 0);

        $amount += ($bonus/100) * $amount;

        return (int) $amount;
    }

    public function __invoke(Request $request)
    {
        $this->handleTransaction($this->driver->callback($request));
    }
}
