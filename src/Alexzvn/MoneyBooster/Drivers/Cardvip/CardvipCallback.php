<?php

namespace Alexzvn\MoneyBooster\Drivers\Cardvip;

use Alexzvn\MoneyBooster\Drivers\BoosterCallback;
use Alexzvn\MoneyBooster\Web\Parser\Request;

class CardvipCallback extends BoosterCallback
{
    /**
     * Callback data
     *
     * @var \stdClass
     */
    protected $data;

    protected function boot(Request $request): void
    {
        $this->data = (object) $request->getGET();
    }

    public function player(): string
    {
        return $this->requestid;
    }

    public function status(): int
    {
        switch ($this->status) {
            case 200: return static::SUCCESS;
            case 201: return static::WRONG_AMOUNT;
            default: return static::CARD_INVALID;
        }
    }

    public function amount(): int
    {
        return $this->data->value_receive;
    }

    public function success(): bool
    {
        return in_array($this->status(), [200, 201], true);
    }

    public function verify(): bool
    {
        return !!$this->data;
    }

    /**
     * get attr data
     *
     * @param string $key
     * @return mixed|null
     */
    public function __get(string $key)
    {
        return $this->data->$key ?? null;
    }
}
