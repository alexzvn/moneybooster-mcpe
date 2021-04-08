<?php

namespace Alexzvn\MoneyBooster\Drivers\Cardvip;

use Alexzvn\MoneyBooster\Contracts\BoosterCallbackContract;
use Alexzvn\MoneyBooster\Drivers\Driver;
use Alexzvn\MoneyBooster\Contracts\CardContract;
use Alexzvn\MoneyBooster\Contracts\BoosterResponseContract;
use pocketmine\Player;
use Alexzvn\MoneyBooster\Web\Parser\Request;

class CardvipDriver extends Driver
{
    public static array $cards = [
        \Alexzvn\MoneyBooster\Card\Viettel::class,
        \Alexzvn\MoneyBooster\Card\Mobifone::class,
        \Alexzvn\MoneyBooster\Card\Vinaphone::class,
        \Alexzvn\MoneyBooster\Card\Garena::class,
        \Alexzvn\MoneyBooster\Card\Zing::class,
        \Alexzvn\MoneyBooster\Card\Gate::class,
        \Alexzvn\MoneyBooster\Card\Vcoin::class,
    ];

    protected string $secret = '';

    /**
     * base url
     */
    protected string $callback = '';

    public function boot(): void
    {
        $address      = (object) $this->config->get('callback');
        $this->secret = $this->config->getNested('card.secret');

        $callback = ($address->ssl ?? false) ? 'https://' : 'http://';
        $callback .= "$address->ip:$address->port";

        $this->callback = $callback;
    }

    protected function api(): string
    {
        return 'https://partner.cardvip.vn/';
    }

    public function request(CardContract $card, Player $player): BoosterResponseContract
    {
        $response = $this->post('api/createExchange', [
            'APIKey'         => $this->secret,
            'NetworkCode'    => $card->telecom(),
            'PricesExchange' => $card->amount(),
            'NumberCard'     => $card->code(),
            'SeriCard'       => $card->seri(),
            'RequestId'      => $player->getLowerCaseName(),
            'UrlCallback'    => $this->callback
        ]);

        return new CardvipResponse($response);
    }

    public function callback(Request $request): BoosterCallbackContract
    {
        return new CardvipCallback($request);
    }
}
