<?php

namespace Alexzvn\MoneyBooster\Commands;

use Alexzvn\MoneyBooster\Contracts\CardContract;
use Alexzvn\MoneyBooster\Drivers\Driver;
use Alexzvn\MoneyBooster\Exception\MoreThanOneCardFoundException;
use Alexzvn\MoneyBooster\Exception\NoCardFoundException;
use Alexzvn\MoneyBooster\MoneyBooster;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

class NaptheCommand extends Command
{
    protected Driver $driver;

    protected MoneyBooster $plugin;

    public function __construct(Driver $driver, MoneyBooster $moneyBooster) {
        parent::__construct('napthe');

        $this->driver = $driver;
        $this->plugin = $moneyBooster;
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

        if ($sender instanceof ConsoleCommandSender) {
            $sender->sendMessage("you're form console?");
            return false;
        }

        $player   = $sender->getServer()->getPlayer($sender->getName());

        $response = $this->driver->request($card, $player);

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
        $amount  = (int) array_shift($params);
        $pin     = (string) array_shift($params);
        $serial  = (string) array_shift($params);
        $telecom = (string) array_shift($params);

        if ($telecom !== '') {
            return $this->driver->makeCard($pin, $serial, $amount, $telecom);
        }

        return $this->driver->guessCard($pin, $serial, $amount);
    }
}
