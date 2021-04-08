<?php

namespace Alexzvn\MoneyBooster\Commands;

use Alexzvn\MoneyBooster\Contracts\CardContract;
use Alexzvn\MoneyBooster\Drivers\Driver;
use Alexzvn\MoneyBooster\Exception\MoreThanOneCardFoundException;
use Alexzvn\MoneyBooster\Exception\NoCardFoundException;
use Alexzvn\MoneyBooster\MoneyBooster;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class NaptheCommand extends Command
{
    protected MoneyBooster $plugin;

    protected Driver $driver;

    public function __construct(MoneyBooster $plugin, Driver $driver) {
        $this->plugin = $plugin;
        $this->driver = $driver;
    }

    public function execute(CommandSender $sender, string $label, array $params): bool
    {
        try {
            $card = $this->getCard($params);

        } catch (NoCardFoundException $th) {
            return $this->notifyInvalidCard($sender);

        } catch (MoreThanOneCardFoundException $th) {
            $sender->sendMessage('Thiếu tên nhà mạng');
            return false;
        }

        if ($card->validate() === false) {
            return $this->notifyInvalidCard($sender);
        }

        $response = $this->driver->request($card, $sender);

        if ($response->isError()) {
            $sender->sendMessage('Thẻ không hợp lệ hoặc đã được nạp');
            return false;
        }

        $sender->sendMessage('Thẻ đang được xử lý.');

        return true;
    }

    protected function notifyInvalidCard(CommandSender $sender): bool
    {
        $sender->sendMessage('Thẻ không hợp lệ');

        return false;
    }

    protected function getCard(array $params): CardContract
    {
        @[$amount, $pin, $serial, $telecom] = $params;

        $amount  = (int) $amount;
        $pin     = (string) $pin;
        $serial  = (string) $serial;
        $telecom = (string) $telecom;

        if ($telecom) {
            return $this->driver->makeCard($pin, $serial, $amount, $telecom);
        }

        return $this->driver->guessCard($pin, $serial, $amount);
    }
}
