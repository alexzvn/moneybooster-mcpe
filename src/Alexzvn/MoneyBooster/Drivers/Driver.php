<?php

namespace Alexzvn\MoneyBooster\Drivers;

use Alexzvn\MoneyBooster\Contracts\BoosterCallbackContract;
use Alexzvn\MoneyBooster\Contracts\BoosterDriverContract;
use Alexzvn\MoneyBooster\Contracts\CardContract;
use Alexzvn\MoneyBooster\Exception\MoreThanOneCardFoundException;
use Alexzvn\MoneyBooster\Exception\NoCardFoundException;
use pocketmine\utils\Config;
use Alexzvn\MoneyBooster\Web\Parser\Request;
use pocketmine\utils\Internet;

abstract class Driver implements BoosterDriverContract
{
    protected Config $config;

    /**
     * @var string[]
     */
    public static array $cards = [
        // Card that available on driver
    ];

    public function __construct(Config $config) {
        $this->config = $config;

        $this->boot();
    }

    abstract protected function boot(): void;

    /**
     * Guess card via code & serial
     * 
     * @throws Alexzvn\MoneyBooster\Exception\MoreThanOneCardFoundException
     * @throws Alexzvn\MoneyBooster\Exception\NoCardFoundException
     *
     * @param string $code
     * @param string $seri
     * @param integer $amount
     * @return CardContract
     */
    public static function guessCard(string $code, string $seri, int $amount): CardContract
    {
        $cards = [];

        foreach (static::$cards as $class) {
            $card = new $class($code, $seri, $amount);

            if ($card->validate()) {
                $cards[] = $card;
            }
        }

        if (count($cards) > 1) {
            throw new MoreThanOneCardFoundException();
        }

        if (count($cards) ===0) {
            throw new NoCardFoundException();
        }

        return $cards[0];
    }

    public static function makeCard(string $code, string $serial, int $amount, string $telecom): CardContract
    {
        $shortest = -1;

        foreach (static::$cards as $card) {
            $name = explode('\\', $card);
            $name = array_pop($name);

            $level = levenshtein($telecom, $name);

            if ($level === 0) {
                return new $card($code, $serial, $amount);
            }

            if ($level <= $shortest || $shortest < 0) {
                $closest = $card;
                $shortest = $level;
            }
        }

        return new $closest($code, $serial, $amount);
    }

    abstract public function callback(Request $request): BoosterCallbackContract;

    /**
     * return endpoint API
     *
     * @return string base uri
     */
    abstract protected function api(): string;

    protected function post(string $uri, array $data = [])
    {
        $url = rtrim($this->api(), '/') . '/' . ltrim($uri, '/');

        $response = Internet::postURL($url, json_encode($data), 5, [
            'Content-Type: application/json'
        ]);

        return $response === false ? '' : $response;
    }
}
